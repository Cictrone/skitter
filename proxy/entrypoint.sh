#!/bin/sh
echo "Requesting certificate from CA..."
curl -d '{ "request": {"CN": "localhost","hosts":["localhost"], "key": { "algo": "rsa","size": 4096 }, "names": [{"C":"US","ST":"New York", "L":"Rochester","O":"localhost"}]}}' http://skitter-ca:8888/api/v1/cfssl/newcert > resp.json
echo "Succeeded: Requesting certificate from CA..."
echo "Grepping response from CA for private_key..."
echo -e "$(cat resp.json | python -m json.tool | grep private_key | cut -f4 -d '"')" > /etc/nginx/certs/skitter.key
echo "Succeeded: Grepping response from CA for private_key..."
echo "Grepping response from CA for public certificate..."
echo -e "$(cat resp.json | python -m json.tool | grep certificate | cut -f4 -d '"')" > /etc/nginx/certs/skitter.cert
echo "Succeeded: Grepping response from CA for public certificate..."
echo "Starting nginx..."
nginx -g "daemon off;"
