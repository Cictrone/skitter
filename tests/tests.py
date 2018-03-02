import unittest
import requests
import json


class TestMethods(unittest.TestCase):
    def test_IsAuthenticated_Fail(self):
        headers = {"Host": "localhost", "sessionID": "deadbeef"}
        try:
            resp = requests.post("http://localhost/isAuthenticated", headers=headers)
        except requests.exceptions.ConnectionError as e:
            # if server is not up
            self.fail("Failed with {}".format(e))
        # test assumes sessionID will never be deadbeef
        self.assertTrue(resp.status_code == 401)
        
    def test_IsAuthenticated_Fail_Reps(self):
        headers = {"Host": "localhost", "sessionID": "deadbeef"}
        try:
            resp = requests.post("http://localhost/isAuthenticated", headers=headers)
        except requests.exceptions.ConnectionError as e:
            # if server is not up
            self.fail("Failed with {}".format(e))
        # test assumes sessionID will never be deadbeef
        resp_parsed = resp.json()
        self.assertTrue(resp_parsed['response'] == False)

if __name__ == '__main__':
    unittest.main()
