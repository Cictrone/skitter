FROM cfssl/cfssl:latest

COPY ca.pem ca.pem
COPY ca-key.pem ca-key.pem

EXPOSE 8888

ENTRYPOINT ["cfssl"]

CMD ["serve","-ca=ca.pem","-ca-key=ca-key.pem","-address=0.0.0.0"]
