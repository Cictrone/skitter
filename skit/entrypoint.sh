#!/bin/sh

# wait till elasticsearch is up
while ! (curl -k --silent --head skitter-skit-db:9200); do sleep 3; done

# setup elastic search skit index
curl -X PUT  skitter-skit-db:9200/skits


#run the flask api
python -u server.py
