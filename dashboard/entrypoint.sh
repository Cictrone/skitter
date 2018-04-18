#!/bin/sh

while ! (curl -k --silent --head http://skitter-skit-db:9200/.kibana); do sleep 3; done

curl -XPUT http://skitter-skit-db:9200/.kibana/index-pattern/skits -d '{"title" : "skits",  "timeFieldName": "timestamp"}'

