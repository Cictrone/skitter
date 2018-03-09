from maven:alpine

RUN mkdir -p /opt/Auth
WORKDIR /opt/Auth
RUN mkdir -p /opt/Auth/src/main/java/auth
RUN mkdir -p /opt/Auth/src/main/resources
COPY src/main/java/auth/AuthAPI.java /opt/Auth/src/main/java/auth/AuthAPI.java
COPY src/main/java/auth/AuthRun.java /opt/Auth/src/main/java/auth/AuthRun.java
COPY src/main/java/auth/AuthClient.java /opt/Auth/src/main/java/auth/AuthClient.java
COPY src/main/resources/application.properties /opt/Auth/src/main/resources/application.properties
COPY pom.xml /opt/Auth/pom.xml
COPY setupDB.sh /opt/Auth/setupDB.sh
RUN chmod 755 /opt/Auth/setupDB.sh


RUN mvn package
EXPOSE 80

ENTRYPOINT ["/opt/Auth/setupDB.sh"]
