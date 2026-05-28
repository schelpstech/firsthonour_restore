# First Honour Schools website + portal + admission app.
# Pure PHP 8.1 + Apache, mirrors the cPanel environment the app was
# built against (EasyApache4, ea-php81). MySQL is a separate Coolify
# service; the app reads DB_HOST / DB_USER / DB_PASS / DB_NAME from
# the environment.
FROM php:8.1-apache

# MySQL drivers (the app uses both mysqli and PDO) + Apache rewrite
# module so the portal's legacy-domain redirect rules keep working.
RUN docker-php-ext-install mysqli pdo pdo_mysql \
 && a2enmod rewrite

# Runtime limits — matches what cPanel had configured in the original
# .user.ini. Keeps the school's existing flows (large form posts,
# admission file uploads) working without per-directory overrides.
RUN { \
      echo 'display_errors = Off'; \
      echo 'memory_limit = 988M'; \
      echo 'upload_max_filesize = 260M'; \
      echo 'post_max_size = 968M'; \
      echo 'max_execution_time = 10000'; \
      echo 'max_input_time = 10000'; \
      echo 'max_input_vars = 10000'; \
      echo 'zlib.output_compression = Off'; \
    } > /usr/local/etc/php/conf.d/firsthonour.ini

# AllowOverride All on /var/www/html so the per-directory .htaccess
# files (portal/.htaccess legacy-domain redirect, future rewrites)
# are honored. The stock php:8.1-apache image leaves AllowOverride
# at "None" which silently disables .htaccess.
RUN sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Behind a reverse proxy: trust the Host header verbatim, never inject
# the container's listening port into self-referenced URLs. Without
# these, Apache's trailing-slash redirect (e.g. /portal -> /portal/)
# leaks the internal port through to the customer's browser. Also set
# a generic ServerName so Apache stops warning at startup.
RUN { \
      echo 'ServerName localhost'; \
      echo 'UseCanonicalName Off'; \
      echo 'UseCanonicalPhysicalPort Off'; \
    } >> /etc/apache2/apache2.conf

COPY --chown=www-data:www-data . /var/www/html/

EXPOSE 80
