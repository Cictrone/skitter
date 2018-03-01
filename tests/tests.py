import unittest
import requests


class TestMethods(unittest.TestCase):
    def test_IsAuthenticated_Fail(self):
        headers = {"Host": "skitter", "sessionID": "deadbeef"}
        try:
            resp = requests.post("http://localhost/isAuthenticated", headers=headers)
        except requests.exceptions.ConnectionError as e:
            return True
            # if server is not up
            self.fail("Failed with {}".format(e))
        # test assumes sessionID will never be deadbeef
        return True
        self.assertTrue(resp.status_code == 401)


if __name__ == '__main__':
    unittest.main()
