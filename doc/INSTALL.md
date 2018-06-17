## ENV
export DISABLE_NOTIFIER=true


## OS setup
apt update
apt install -y mysql-server apache2 libapache2-mod-php php php-mysql php-mbstring php-xml php-zip composer npm nodejs-legacy freeradius freeradius-mysql
npm install gulp-cli -g


## MySQL Setup
# Set up DB schema
mysqladmin create -uroot radium
mysql -uroot radium < /etc/freeradius/sql/mysql/nas.sql
mysql -uroot radium < /etc/freeradius/sql/mysql/schema.sql

echo "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';" | mysql -uroot


## Radium Install
git clone https://github.com/samburney/Radium.git /var/www/radium

cd /var/www/radium

cp .env.example .env
echo "

BROADCAST_DRIVER=log
" >> .env

# Handle environment variables
# ** TODO **

composer install
php artisan key:generate

php artisan migrate
php artisan db:seed --class=OperatorAccountSeeder
php artisan db:seed --class=DictionarySeeder

# Gulp
npm install gulp -D
npm install laravel-elixir -D
gulp

# Fix permissions
chown -R www-data:www-data /var/www/radium
chmod -R g+rw /var/www/radium


## Apache setup
sed -e "s/\/var\/www\/html/\/var\/www\/radium\/public/g" /etc/apache2/sites-available/000-default.conf > /etc/apache2/sites-available/000-default.conf.new && mv /etc/apache2/sites-available/000-default.conf.new /etc/apache2/sites-available/000-default.conf

echo "<directory /var/www/radium/public>
  AllowOverride all
</directory>" > /etc/apache2/sites-available/radium.conf

a2enmod rewrite
a2ensite radium

systemctl restart apache2


## FreeRADIUS setup
rm sites-enabled/inner-tunnel

# radiusd.conf
- Comment $INCLUDE eap.conf
- Uncomment $INCLUDE sql.conf

# sql.conf
- Update login
- Update password
- Update radius_db
- Uncomment readclients = yes

# sites-enabled/default
- Comment all EAP related
- Comment files
- Uncomment SQL

