from flask import Flask, json
from flask import request as request_obj
import requests

app = Flask(__name__)

@app.route('/AddSkit/<skit_message>', methods=['POST'])
def AddSkit(skit_message):
    success = False
    status = 401
    skit_id = None
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    if 'username' in resp.json():
        username = resp.json()['username']
        resp = requests.post("http://skitter-skit-db:9200/skits/_doc/", data={"user": username, "message": skit_message})
        try:
            success = (resp.json()['_shards']['failed'] == 0)
        except:
            success = False
        skit_id = resp.json()['_id']
        if success:
            status = 201
    resp = app.response_class( response={"success": success, "_id": skit_id}, status=status, mimetype='application/json')
    return resp

@app.route('/GetSkits', methods=['POST'])
def GetSkit():
    success = False
    hits = []
    status = 401
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    if 'username' in resp.json():
        username = resp.json()['username']
        resp = requests.post("http://skitter-skit-db:9200/skits/_search/", data={"query": { "constant_score":{"filter":{"term":{"user": username}}}}})
        try:
            success = (resp.json()['_shards']['failed'] == 0)
            hits = resp.json()['hits']['hits']
        except:
            success = False
        if success:
            status = 201
    resp = app.response_class( response={"success": success, "skits": hits}, status=status, mimetype='application/json')
    return resp

@app.route('/RemoveSkit/<skit_id>', methods=['POST'])
def RemoveSkit(skit_id):
    success = False
    hits = []
    status = 401
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    if 'username' in resp.json():
        username = resp.json()['username']
        resp = requests.post("http://skitter-skit-db:9200/skits/_search/", data={"query": { "constant_score":{"filter":{"term":{"user": username}}}}})
        try:
            success = (resp.json()['_shards']['failed'] == 0)
            hits = resp.json()['hits']['hits']
        except:
            success = False
        if success:
            skit_found = None
            for skit in hits:
                if skit['_id'] == skit_id:
                    skit_found = skit
                    break
            if skit_found is None:
                return {"success": False}, 400
            resp = requests.post("http://skitter-skit-db:9200/skits/_delete_by_query", data={"query": { "match": { "_id": skit_id}}})
            success = (resp.json()['total'] == 1)
            if success:
                status = 201
    resp = app.response_class( response={"success": success}, status=status, mimetype='application/json')
    return resp

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)
