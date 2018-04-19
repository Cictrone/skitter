FROM nginx:alpine

COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /opt/entrypoint.sh
RUN apk add --update python curl
RUN mkdir -p /etc/nginx/certs
RUN chmod 755 /opt/entrypoint.sh
ENTRYPOINT ["/opt/entrypoint.sh"]
