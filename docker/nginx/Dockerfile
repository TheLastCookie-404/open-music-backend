FROM thelastcookie404/openmusic-app:latest AS app

FROM nginx:stable-alpine
COPY --from=app /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf