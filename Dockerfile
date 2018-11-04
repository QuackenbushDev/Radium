# Requires Ubuntu 16.04
FROM lsiobase/ubuntu:xenial
LABEL maintainer "Sam Burney <sam@burney.io>"

# Disable npm notifications
ENV DISABLE_NOTIFIER true

# Fix 'Pusher' error on composer install 
ENV BROADCAST_DRIVER=log

# Disable dpkg frontend to avoid erro rmessages
ENV DEBIAN_FRONTEND=noninteractive

# Install packages
RUN \
	rm /etc/cont-init.d/10-adduser && \
	apt update && \
	apt -yq install apache2 libapache2-mod-php \
	php php-mysql php-mbstring php-xml php-zip \
	git composer \
	npm nodejs-legacy \
	freeradius freeradius-mysql \
	mysql-client \
	cron && \
	apt clean && \
	a2enmod rewrite && \
	npm install gulp-cli -g && \
	rm /etc/freeradius/sites-enabled/inner-tunnel

# Install Radium app
RUN git clone https://github.com/samburney/Radium.git /var/www/radium && \
	cd /var/www/radium && \
	composer install && \
	npm install gulp -D && \
	npm install laravel-elixir -D && \
	gulp && \
	chown -R www-data:www-data /var/www/radium && \
	chmod -R g+rw /var/www/radium && \
	composer clear-cache && \
	rm -r node_modules

# Copy helper scripts
COPY ./docker/root/ /

# Web and RADIUS Auth/Acct
EXPOSE 80/tcp 1812/udp 1813/udp

# Redirect Apache logs to stdout/stderr
RUN ln -sf /proc/self/fd/1 /var/log/apache2/access.log && \
	ln -sf /proc/self/fd/1 /var/log/apache2/error.log

ENTRYPOINT ["/init"]