package auth;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.util.WebUtils;

import org.springframework.http.ResponseEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;

import com.unboundid.ldap.sdk.LDAPException;
import java.security.GeneralSecurityException;


import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.Collections;
import java.util.Map;
import java.util.HashMap;
import java.util.Base64;
import java.nio.charset.Charset;

@RestController
public class AuthAPI {


  @RequestMapping(path="/isAuthenticated", method=RequestMethod.POST)
  public ResponseEntity<Map> isAuthenticated(HttpServletRequest request, HttpServletResponse response) throws Exception {
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    String sessionID = WebUtils.getCookie(request, "sessionID").getValue();
    if(sessionID != null){
      AuthClient client = new AuthClient(sessionID);
      loginResponse = client.loginSuccess;
      if(loginResponse){
        responseStatus = HttpStatus.ACCEPTED;
      }
    }
    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(loginResponse));
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }

  @RequestMapping(path="/Login", method=RequestMethod.POST)
  public ResponseEntity<Map> Login(HttpServletRequest request, HttpServletResponse response) throws Exception{
    final String authorization = request.getHeader("Authorization");
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    String sessionID = "";
    if (authorization != null && authorization.startsWith("Basic")) {
        String base64Credentials = authorization.substring("Basic".length()).trim();
        String credentials = new String(Base64.getDecoder().decode(base64Credentials), Charset.forName("UTF-8"));
        final String[] values = credentials.split(":",2);
        final String username = values[0];
        final String password = values[1];
        AuthClient client = new AuthClient(username, password);
        loginResponse = client.loginSuccess;
        if(loginResponse){
          responseStatus = HttpStatus.ACCEPTED;
          sessionID = client.Login();
        }
    }

    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(loginResponse));
    if(loginResponse){
      json_resp.put("sessionID", sessionID);
    }
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }

  @RequestMapping(path="/Logout", method=RequestMethod.POST)
  public ResponseEntity<Map> Logout(HttpServletRequest request, HttpServletResponse response) throws Exception {
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    String sessionID = WebUtils.getCookie(request, "sessionID").getValue();
    if(sessionID != null){
      AuthClient client = new AuthClient(sessionID);
      client.Logout();
      loginResponse = true;
      responseStatus = HttpStatus.ACCEPTED;
    }
    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(loginResponse));
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }

}
