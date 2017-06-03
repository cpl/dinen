FROM ubuntu:latest
MAINTAINER Alexandru-Paul Copil (thee-engineer) <alexandru.p.copil@gmail.com>

# Install Apache & Update Ubuntu
RUN DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get -y upgrade
RUN apt-get install -y apache2 php7.0 php7.0-mysql libapache2-mod-php7.0 curl lynx-cur

# Enable php and ssl mods
RUN a2enmod php7.0
RUN a2enmod rewrite
RUN a2enmod ssl

# Silence PHP
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.0/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php/7.0/apache2/php.ini

# Manualy setting Apache Varaibles
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Open ports to the host machine
EXPOSE 80 443 3306

# Add website content
ADD web /srv/dinen

# Prepare SSL
RUN mkdir /etc/apache2/ssl
ADD env/certs/privkey.pem /etc/letsencrypt/live/dinen.ddns.net/privkey.pem
ADD env/certs/fullchain.pem /etc/letsencrypt/live/dinen.ddns.net/fullchain.pem
RUN a2ensite default-ssl.conf

# Add Apache configs
ADD env/apache/apache2.conf /etc/apache2/apache2.conf
ADD env/apache/ports.conf /etc/apache2/ports.conf
ADD env/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD env/apache/security.conf /etc/apache2/conf-available/security.conf
ADD env/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
ADD env/apache/ssl-params.conf /etc/apache2/conf-available/ssl-params.conf

# Setup permissions
RUN chown www-data:www-data -R /srv/

# Prepare MySQL
RUN apt-get update
RUN echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
RUN echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
RUN apt-get install -y mysql-server mysql-client

# Copy configuration
ADD env/database/my.cnf /etc/mysql/my.cnf
RUN chown -R mysql:mysql /var/lib/mysql

# Start MySQL
RUN /etc/init.d/mysql start

# Create teamdinen user in MySQL database
RUN mysql -u root -p=root << "CREATE USER 'teamdinen'@'localhost' IDENTIFIED BY 'dinenX3'; GRANT ALL PRIVILEGES ON * . * TO 'teamdinen'@'localhost'; FLUSH PRIVILEGES;"

# Migrate DB
ADD env/database/dinen.sql /var/dinen.sql
RUN mysql -u teamdinen -p=dinenX3 dinen < /var/dinen.sql

# Clean apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Start Apache
CMD /usr/sbin/apache2ctl -D FOREGROUND
