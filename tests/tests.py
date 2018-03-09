import unittest
import requests
import json


class TestMethods(unittest.TestCase):
    def test_IsAuthenticated_Fail(self):
        headers = {"Host": "localhost", "Cookie": "sessionID=deadbeef"}
        try:
            resp = requests.post("https://localhost/isAuthenticated", headers=headers, verify=False)
        except requests.exceptions.ConnectionError as e:
            # if server is not up
            self.fail("Failed with {}".format(e))
        # test assumes sessionID will never be deadbeef
        resp_parsed = resp.json()
        self.assertTrue(resp.status_code == 401 and resp_parsed['response'] == "false")

    def test_Login_Fail(self):
        headers = {"Host": "localhost", "Authorization": "Basic dXNlcm5hbWU6cGFzcw=="}
        try:
            resp = requests.post("https://localhost/Login", headers=headers, verify=False)
        except requests.exceptions.ConnectionError as e:
            # if server is not up
            self.fail("Failed with {}".format(e))
        resp_parsed = resp.json()
        self.assertTrue(resp.status_code == 401 and resp_parsed['response'] == "false")

    def test_Logout_Fail(self):
        headers = {"Host": "localhost", "Cookie": "sessionID=deadbeef"}
        try:
            resp = requests.post("https://localhost/Logout", headers=headers, verify=False)
        except requests.exceptions.ConnectionError as e:
            # if server is not up
            self.fail("Failed with {}".format(e))
        # test assumes sessionID will never be deadbeef
        resp_parsed = resp.json()
        self.assertTrue(resp.status_code == 401 and resp_parsed['response'] == "false")

if __name__ == '__main__':
    unittest.main()
