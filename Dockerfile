FROM lsiobase/ubuntu:xenial
LABEL maintainer="Sam Burney <sam@burney.io>"

# Disable npm notifications
ENV DISABLE_NOTIFIER true

# Copy scripts
ADD ./docker/bin /usr/local/bin
RUN chmod +x /usr/local/bin/*

# Install packages
RUN \
	install_packages apache2 libapache2-mod-php \
	php php-mysql php-mbstring php-xml php-zip \
	git composer \
	npm nodejs-legacy \
	freeradius freeradius-mysql \
	mysql-client \
	cron

RUN npm install gulp-cli -g

# Install Radium app
RUN git clone https://github.com/samburney/Radium.git /var/www/radium
ADD ./docker/radium/* /var/www/radium/

RUN cd /var/www/radium && \
	composer install && \
	npm install gulp -D && \
	npm install laravel-elixir -D && \
	gulp && \
	chown -R www-data:www-data /var/www/radium && \
	chmod -R g+rw /var/www/radium

# Set up freeradius
RUN rm /etc/freeradius/sites-enabled/inner-tunnel
ADD ./docker/freeradius/*.conf* /etc/freeradius/
ADD ./docker/freeradius/default /etc/freeradius/sites-available/

# Set up Apache
RUN	a2enmod rewrite

# Copy helper scripts
RUN rm /etc/cont-init.d/10-adduser
COPY ./docker/root/ /

# Web and RADIUS Auth/Acct
EXPOSE 80/tcp 1812/udp 1813/udp

ENTRYPOINT ["/init"]