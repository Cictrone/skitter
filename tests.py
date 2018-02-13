import unittest
import requests


class TestMethods(unittest.TestCase):
    def test_IsAuthenticated_Fail(self):
        headers = {"Host": "skitter", "sessionID": "deadbeef"}
        resp = requests.post("http://skitter/isAuthenticated", headers=headers)
        # test assumes sessionID will never be deadbeef
        self.assertTrue(resp.status_code == 401)
        

if __name__ == '__main__':
    unittest.main()
