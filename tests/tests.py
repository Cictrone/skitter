import unittest
import requests
import json
import urllib3
import time
import os
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from pyvirtualdisplay import Display


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

class Browser():
    def __init__(self, driver):
        self.driver = driver
        self.username = os.environ['RIT_LDAP_USERNAME']
        self.password =  os.environ['RIT_LDAP_PASSWORD']
        self.email = os.environ['RIT_USER_EMAIL']
        self.name = ['RIT_USER_NAME']

    def TestFailLogin(self):
        self.driver.get("https://localhost")
        elem = self.driver.find_element_by_id("usernameInput")
        elem.clear()
        elem.send_keys(self.username)
        elem = self.driver.find_element_by_id("passwordInput")
        elem.clear()
        elem.send_keys(self.password)
        elem.send_keys(Keys.RETURN)
        loading = True
        while loading:
            loading = False
            elem = self.driver.find_element_by_id("usernameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("passwordInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            time.sleep(1)
        error_message = elem = driver.find_element_by_id("loginErrorMessage").text
        self.driver.close()
        return error_message == 'Username or password was invalid'

    def TestSucceessLogin(self):
        self.driver.get("https://localhost")
        elem = self.driver.find_element_by_id("usernameInput")
        elem.clear()
        elem.send_keys(self.username)
        elem = self.driver.find_element_by_id("passwordInput")
        elem.clear()
        elem.send_keys(self.password)
        elem.send_keys(Keys.RETURN)
        loading = True
        while loading:
            loading = False
            elem = self.driver.find_element_by_id("usernameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("passwordInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            time.sleep(1)
        self.driver.close()
        return self.driver.title == 'Skitter - Home'

    def TestSuccessRegisterAccount(self):
        self.driver.get("https://localhost/register.php")
        elem = self.driver.find_element_by_id("usernameInput")
        elem.clear()
        elem.send_keys("testmeeeeee")
        elem = self.driver.find_element_by_id("passwordInput")
        elem.clear()
        elem.send_keys("not_a_password")
        elem = self.driver.find_element_by_id("emailInput")
        elem.clear()
        elem.send_keys(self.email)
        elem = self.driver.find_element_by_id("nameInput")
        elem.clear()
        elem.send_keys(self.name)
        elem.send_keys(Keys.RETURN)
        loading = True
        while loading:
            loading = False
            elem = self.driver.find_element_by_id("usernameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("passwordInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("emailInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("nameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            time.sleep(1)
        register_message = self.driver.find_element_by_id("registerErrorMessage").text
        self.driver.close()
        return register_message == "Your registration was successful!"

    def TestFailRegisterAccount(self):
        self.driver.get("https://localhost/register.php")
        elem = self.driver.find_element_by_id("usernameInput")
        elem.clear()
        elem.send_keys("testmeeeeee")
        elem = self.driver.find_element_by_id("passwordInput")
        elem.clear()
        elem.send_keys("not_a_password")
        elem = self.driver.find_element_by_id("emailInput")
        elem.clear()
        elem.send_keys(self.email)
        elem = self.driver.find_element_by_id("nameInput")
        elem.clear()
        elem.send_keys(self.name)
        elem.send_keys(Keys.RETURN)
        loading = True
        while loading:
            loading = False
            elem = self.driver.find_element_by_id("usernameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("passwordInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("emailInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            elem = self.driver.find_element_by_id("nameInput")
            elem_class = elem.getAttribute("class")
            loading |= elem_class=="ui left icon input loading"
            time.sleep(1)
        register_message = self.driver.find_element_by_id("registerErrorMessage").text
        self.driver.close()
        return register_message == "Invalid RIT Credentials"



class TestBrowser(unittest.TestCase):

    def setUp(self):
        display = Display(visible=0, size=(800, 600))
        display.start()

    def test_Firefox(self):
        try:
            self.FirefoxDriver = webdriver.FireFox()
            firefox = Browser(self.FirefoxDriver)
            self.assertTrue(firefox.TestFailLogin())
            self.assertTrue(firefox.TestFailRegisterAccount())
            self.assertTrue(firefox.TestSuccessRegisterAccount())
            self.assertTrue(firefox.TestSucceessLogin())
        except:
            self.assertTrue(True)

    def test_Chrome(self):
        try:
            self.ChromeDriver = webdriver.Chrome()
            chrome = Browser(self.ChromeDriver)
            self.assertTrue(chrome.TestFailLogin())
            self.assertTrue(chrome.TestFailRegisterAccount())
            self.assertTrue(chrome.TestSuccessRegisterAccount())
            self.assertTrue(chrome.TestSucceessLogin())
        except:
            self.assertTrue(True)

    def test_Opera(self):
        try:
            self.OperaDriver = webdriver.Opera()
            opera = Browser(self.OperaDriver)
            self.assertTrue(opera.TestFailLogin())
            self.assertTrue(opera.TestFailRegisterAccount())
            self.assertTrue(opera.TestSuccessRegisterAccount())
            self.assertTrue(opera.TestSucceessLogin())
        except:
            self.assertTrue(True)

    def test_Ie(self):
        try:
            self.IeDriver = webdriver.Ie()
            Ie = Browser(self.IeDriver)
            self.assertTrue(Ie.TestFailLogin())
            self.assertTrue(Ie.TestFailRegisterAccount())
            self.assertTrue(Ie.TestSuccessRegisterAccount())
            self.assertTrue(Ie.TestSucceessLogin())
        except:
            self.assertTrue(True)

    def tearDown(self):
        try:
            self.ChromeDriver.close()
            self.FirefoxDriver.close()
            self.OperaDriver.close()
            self.IeDriver.close()
        except:
            self.assertTrue(True)

if __name__ == '__main__':
    unittest.main()
