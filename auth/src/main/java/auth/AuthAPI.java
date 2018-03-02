package auth;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.bind.annotation.RequestMethod;

import org.springframework.http.ResponseEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.Collections;
import java.util.Map;

@RestController
public class AuthAPI{

  @RequestMapping(path="/isAuthenticated", method=RequestMethod.POST)
  public ResponseEntity<Map> isAuthenticated(HttpServletRequest request, HttpServletResponse response) throws URISyntaxException {
    // TODO: add logic
    String location = request.getHeader("Host")+request.getContextPath();
    HttpHeaders headers = new HttpHeaders();
    headers.setLocation(new URI(location));
    Map json_resp = Collections.singletonMap("response", false);
    return new ResponseEntity<Map>(json_resp, headers, HttpStatus.UNAUTHORIZED);
  }

}
