from python:3.6-alpine3.7

COPY requirements.txt /requirements.txt
COPY entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh
RUN apk add --update curl
COPY server.py server.py
EXPOSE 80
RUN pip install -r /requirements.txt
ENTRYPOINT ["/entrypoint.sh"]
