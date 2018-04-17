from flask import Flask, jsonify
from flask import request as request_obj
import requests
import datetime
import json

app = Flask(__name__)

@app.route('/AddSkit/<skit_message>', methods=['POST'])
def AddSkit(skit_message):
    success = False
    status = 401
    skit_id = None
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    print("SessionID: {}".format(request_obj.cookies['sessionID']))
    if 'username' in resp.json():
        username = resp.json()['username']
        data = {"user": username, "message": skit_message, "timestamp":datetime.datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")}
        data_s = json.dumps(data)
        header = {"Content-Type": "application/json"}
        resp = requests.post("http://skitter-skit-db:9200/skits/_doc/", headers=header, data=data_s)
        print("2: ", resp.json())
        try:
            success = (resp.json()['_shards']['failed'] == 0)
        except:
            success = False
        skit_id = resp.json()['_id']
        if success:
            status = 201
    resp = jsonify({"success": success, "_id": skit_id})
    resp.status_code = status
    return resp

@app.route('/GetSkits', methods=['POST'])
def GetSkit():
    success = False
    hits = []
    status = 401
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    print("1: ", resp.json())
    if 'username' in resp.json():
        username = resp.json()['username']
        data = {"query": { "constant_score":{"filter":{"term":{"user": username}}}}}
        data_s = json.dumps(data)
        header = {"Content-Type": "application/json"}
        resp = requests.post("http://skitter-skit-db:9200/skits/_search/", headers=header, data=data_s)
        print("2: ", resp.json())
        try:
            success = (resp.json()['_shards']['failed'] == 0)
            hits = resp.json()['hits']['hits']
        except:
            success = False
        if success:
            status = 200
    resp = jsonify({"success": success, "skits": hits})
    resp.status_code = status
    return resp

@app.route('/RemoveSkit/<skit_id>', methods=['POST'])
def RemoveSkit(skit_id):
    success = False
    hits = []
    status = 401
    resp = requests.post("http://skitter-auth/GetUserData", cookies=request_obj.cookies)
    if 'username' in resp.json():
        username = resp.json()['username']
        data = {"query": { "constant_score":{"filter":{"term":{"user": username}}}}}
        data_s = json.dumps(data)
        header = {"Content-Type": "application/json"}
        resp = requests.post("http://skitter-skit-db:9200/skits/_search/", headers=header, data=data_s)
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
            data = {"query": { "match": { "_id": skit_id}}}
            data_s = json.dumps(data)
            header = {"Content-Type": "application/json"}
            resp = requests.post("http://skitter-skit-db:9200/skits/_delete_by_query", headers=header, data=data_s)
            success = (resp.json()['total'] == 1)
            if success:
                status = 201
    resp = jsonify({"success": success})
    resp.status_code = status
    return resp

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)
