FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!' /etc/apache2/apache2.conf \
    && sed -ri 's!^ServerTokens .*!ServerTokens Prod!; s!^ServerSignature .*!ServerSignature Off!' /etc/apache2/conf-available/security.conf \
    && a2enmod rewrite headers expires \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html
COPY . .
RUN mkdir -p data && chown -R www-data:www-data data

HEALTHCHECK --interval=30s --timeout=3s CMD php -r 'exit(@file_get_contents("http://127.0.0.1/health.php") === "ok" ? 0 : 1);'

CMD ["sh", "-c", "php setup.php && chown -R www-data:www-data data && exec apache2-foreground"]
