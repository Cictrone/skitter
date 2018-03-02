package auth;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.bind.annotation.RequestMethod;

import java.util.Collections;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@RestController
public class AuthAPI{

  @RequestMapping(path="/isAuthenticated", method=RequestMethod.POST)
  public Map isAuthenticated(HttpServletRequest request, HttpServletResponse response) {
    // TODO: add logic
    return Collections.singletonMap("response", true);
  }

}
