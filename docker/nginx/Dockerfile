FROM nginx:1.21-alpine AS nginx

RUN rm /etc/nginx/conf.d/default.conf;

COPY sshelter.conf /etc/nginx/conf.d/
COPY sshelter.localhost+1-key.pem /etc/nginx/
COPY sshelter.localhost+1.pem /etc/nginx/

WORKDIR /app

VOLUME /app/var/storage

EXPOSE 80
