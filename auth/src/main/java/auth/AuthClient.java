package auth;

import com.unboundid.ldap.sdk.LDAPConnection;
import com.unboundid.ldap.sdk.BindResult;
import com.unboundid.ldap.sdk.LDAPException;


import com.unboundid.util.ssl.SSLUtil;
import javax.net.ssl.SSLSocketFactory;
import com.unboundid.util.ssl.TrustAllTrustManager;
import java.security.GeneralSecurityException;
import com.unboundid.ldap.sdk.LDAPBindException;

public class AuthClient{

  private String username;
  private String password;
  private static final String domain = "rit.edu";
  private static final String server = "ldap.rit.edu";
  private static final String ou_and_dcs = ",ou=people,dc=rit,dc=edu";
  private BindResult bind;
  protected Boolean loginSuccess = false;

  public AuthClient(String username, String password) throws LDAPException,GeneralSecurityException {
    this.username = username;
    this.password = password;
    SSLUtil sslUtil = new SSLUtil(new TrustAllTrustManager());
    SSLSocketFactory sslSocketFactory = sslUtil.createSSLSocketFactory();
    LDAPConnection c = new LDAPConnection(sslSocketFactory);
    c.connect(this.server, 636); // 636 is LDAPSSL
    String bindDN = "uid="+username+this.ou_and_dcs;
    try{
      this.bind = c.bind(bindDN, password);
      this.loginSuccess = true;
    }catch(LDAPBindException e){
      System.err.println("Login Failed for "+bindDN);
    }
  }
}
