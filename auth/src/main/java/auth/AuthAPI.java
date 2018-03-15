package auth;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.util.WebUtils;

import org.springframework.http.ResponseEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;

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


  @RequestMapping(path="/RegisterUser", method=RequestMethod.POST)
  public ResponseEntity<Map> RegisterUser(HttpServletRequest request, HttpServletResponse response) throws Exception{
    final String username = request.getParameter("username");
    final String password = request.getParameter("password");
    final String displayName = request.getParameter("displayName");
    final String email = request.getParameter("email");
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    Boolean registerSuccess = false;
    if (username != null && password != null && displayName != null && email != null) {
        AuthClient client = new AuthClient(username, password, false);
        loginResponse = client.loginSuccess;
        if(loginResponse){
          registerSuccess = client.RegisterUser(displayName, email);
          // add more logic for skits
          if(registerSuccess){
            responseStatus = HttpStatus.CREATED;
          }else{
            // if authenticated BUT account already exists
            responseStatus = HttpStatus.CONFLICT;
          }
        }
    }

    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    headers.setContentType(MediaType.APPLICATION_JSON_UTF8);
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(registerSuccess));
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }

  @RequestMapping(path="/DeleteUser", method=RequestMethod.POST)
  public ResponseEntity<Map> DeleteUser(HttpServletRequest request, HttpServletResponse response) throws Exception{
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    Boolean deleteResponse = false;
    String sessionID = WebUtils.getCookie(request, "sessionID").getValue();
    if(sessionID != null){
      AuthClient client = new AuthClient(sessionID);
      loginResponse = client.loginSuccess;
      if(loginResponse){
        deleteResponse = client.DeleteUser();
        // add more logic for skits
        if(deleteResponse){
          responseStatus = HttpStatus.OK;
        }
      }
    }
    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    headers.setContentType(MediaType.APPLICATION_JSON_UTF8);
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(deleteResponse));
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }









  @RequestMapping(path="/isAuthenticated", method=RequestMethod.POST)
  public ResponseEntity<Map> isAuthenticated(HttpServletRequest request, HttpServletResponse response) throws Exception {
    HttpStatus responseStatus = HttpStatus.UNAUTHORIZED;
    Boolean loginResponse = false;
    String sessionID = WebUtils.getCookie(request, "sessionID").getValue();
    if(sessionID != null){
      AuthClient client = new AuthClient(sessionID);
      loginResponse = client.loginSuccess;
      if(loginResponse){
        responseStatus = HttpStatus.OK;
      }
    }
    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    headers.setContentType(MediaType.APPLICATION_JSON_UTF8);
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
        AuthClient client = new AuthClient(username, password, true);
        loginResponse = client.loginSuccess;
        if(loginResponse){
          responseStatus = HttpStatus.OK;
          sessionID = client.Login();
        }
    }

    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    headers.setContentType(MediaType.APPLICATION_JSON_UTF8);
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(loginResponse));
    if(loginResponse){
      headers.add("Set-Cookie", "sessionID="+sessionID+"; HttpOnly; Secure");
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
      loginResponse = client.Logout();
      if(loginResponse){
        responseStatus = HttpStatus.OK;
      }
    }
    final String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    headers.setContentType(MediaType.APPLICATION_JSON_UTF8);
    Map<String, String> json_resp = new HashMap<>();
    json_resp.put("response", String.valueOf(loginResponse));
    return new ResponseEntity<Map>(json_resp, headers, responseStatus);
  }

}
