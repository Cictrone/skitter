package auth;

import java.sql.*;
import java.util.UUID;

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
  private String sessionID;
  private static final String domain = "rit.edu";
  private static final String authDB = "jdbc:mysql://skitter-auth-db/Skitter";
  private static final String server = "ldap.rit.edu";
  private static final String ou_and_dcs = ",ou=people,dc=rit,dc=edu";
  private BindResult bind;
  protected Boolean loginSuccess = false;

  public AuthClient(String username, String password) throws Exception{
    this.username = username;
    this.password = password;

    String query = "select displayName from User where email=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    ResultSet rs = preparedStmt.executeQuery();
    String test = null;
    while (rs.next()) {
				test = rs.getString("displayName");
		}
    if(test == null){
      this.loginSuccess = false;
    }else{
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

  public AuthClient(String sessionID) throws Exception{
    this.sessionID = sessionID;
    String query = "select displayName from User where sessionID=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.sessionID);
    ResultSet rs = preparedStmt.executeQuery();
    this.username = null;
    while (rs.next()) {
				this.username = rs.getString("displayName");
		}
    conn.close();
    if(this.username == null){
      this.loginSuccess = false;
    }else{
      this.loginSuccess = true;
    }
  }

  public String Login() throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      return null;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "update User set sessionID=? where email=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    this.sessionID = UUID.randomUUID().toString().replace("-", "");
    preparedStmt.setString(1, this.sessionID);
    preparedStmt.setString(2, this.username);
    preparedStmt.execute();
    conn.close();
    return this.sessionID;
  }

  public Boolean Logout() throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      return null;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "update User set sessionID='' where email=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    preparedStmt.execute();
    conn.close();
    return true;
  }



}
