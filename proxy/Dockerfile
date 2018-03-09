FROM nginx:alpine

COPY nginx.conf /etc/nginx/nginx.conf
RUN apk add --update openssl openssl-dev
RUN mkdir -p /etc/nginx/certs
RUN openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
    -subj "/C=US/ST=NY/L=Rochester/O=Student/CN=skitter" \
    -keyout /etc/nginx/certs/skitter.key  -out /etc/nginx/certs/skitter.cert
