from maven:alpine

RUN mkdir -p /opt/Auth
WORKDIR /opt/Auth
RUN mkdir -p /opt/Auth/src/main/java/auth
RUN mkdir -p /opt/Auth/src/main/resources
COPY src/main/java/auth/AuthAPI.java /opt/Auth/src/main/java/auth/AuthAPI.java
COPY src/main/java/auth/AuthRun.java /opt/Auth/src/main/java/auth/AuthRun.java
COPY src/main/resources/application.properties /opt/Auth/src/main/resources/application.properties
COPY pom.xml /opt/Auth/pom.xml

RUN mvn package
ENTRYPOINT ["/usr/bin/java"]
CMD ["-jar", "/opt/Auth/target/gs-rest-service-0.1.0.jar"]
EXPOSE 80
