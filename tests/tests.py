import unittest
import requests
import json
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


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

if __name__ == '__main__':
    unittest.main()
