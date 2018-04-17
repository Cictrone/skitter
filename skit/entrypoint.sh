#!/bin/sh

# wait till elasticsearch is up
while ! (curl -k --silent --head skitter-skit-db:9200); do sleep 3; done

# setup elastic search skit index
curl -H "Content-Type: application/json" -X PUT -d '{"mappings": {"_doc": {"properties":{"timestamp": {"type": "date"}, "message":{"type":"text"}, "username":{"type":"text"}}}}}' http://skitter-skit-db:9200/skits
curl -H "Content-Type: application/json" -X PUT -d '{"persistent": {"cluster.routing.allocation.disk.threshold_enabled": false}}' http://skitter-skit-db:9200/_cluster/settings
curl -H "Content-Type: application/json" -X PUT -d '{"index.blocks.read_only_allow_delete": null}' http://skitter-skit-db:9200/_all/_settings


#run the flask api
python -u server.py
