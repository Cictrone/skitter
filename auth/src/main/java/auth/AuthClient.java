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

  public AuthClient(String username, String password, Boolean checkLocalDB) throws Exception{
    this.username = username;
    this.password = password;

    if(checkLocalDB){
      this.loginSuccess = this.isUserInDB(this.username);
      if(!this.loginSuccess){
        return;
      }
    }
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

  public AuthClient(String sessionID) throws Exception{
    this.sessionID = sessionID;
    String query = "select username from User where sessionID=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.sessionID);
    ResultSet rs = preparedStmt.executeQuery();
    this.username = null;
    while (rs.next()) {
				this.username = rs.getString("username");
		}
    conn.close();
    if(this.username == null){
      this.loginSuccess = false;
      System.out.println("User: "+this.username+" has unsuccessfully authenticated with a sessionID...");
    }else{
      System.out.println("User: "+this.username+" has successfully authenticated with a sessionID...");
      this.loginSuccess = true;
    }
  }

  private Boolean isUserInDB(String username) throws Exception{
    String query = "select email from User where username=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    ResultSet rs = preparedStmt.executeQuery();
    String test = null;
    while (rs.next()) {
        test = rs.getString("email");
    }
    conn.close();
    if(test == null){
      return false;
    }
    return true;
  }

  private Boolean isSessionIDValid(String sessionID) throws Exception{
    String query = "select sessionID from User where username=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    ResultSet rs = preparedStmt.executeQuery();
    String test = null;
    while (rs.next()) {
        test = rs.getString("sessionID");
    }
    conn.close();
    if(test == null){
      return false;
    }
    return test.equals(sessionID);
  }

  public String[] GetUserData() throws Exception{
    String query = "select email, displayName, profileImage from User where username=?";
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    ResultSet rs = preparedStmt.executeQuery();
    String email = null;
    String name = null;
    String profileImage = null;
    String[] data = new String[4];
    while (rs.next()) {
      email = rs.getString("email");
      name = rs.getString("displayName");
      profileImage = rs.getString("profileImage");
    }
    conn.close();
    if(email == null){
      return null;
    }
    data[0] = this.username;
    data[1] = email;
    data[2] = name;
    data[3] = profileImage;
    return data;
  }

  public Boolean RegisterUser(String displayName, String email) throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      System.out.println("User: "+this.username+" has failed to be registered...");
      return false;
    }
    if(this.isUserInDB(this.username)){
      System.out.println("User: "+this.username+" has failed to be registered twice...");
      return false;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "insert into User (username, displayName, email, profileImage) values(?,?,?,?)";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    preparedStmt.setString(2, displayName);
    preparedStmt.setString(3, email);
    preparedStmt.setString(4, "http://laoblogger.com/images/default-profile-picture-5.jpg");
    preparedStmt.execute();
    conn.close();
    System.out.println("User: "+this.username+" has successfully been registered...");
    return true;
  }

  public Boolean UpdateUser(String sessionID, String displayName, String email, String profileImage) throws Exception{
    // Should never happen
    if(!this.loginSuccess || !this.isSessionIDValid(sessionID)){
      System.out.println("User: "+this.username+" has failed to be updated in...");
      return false;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "update User set displayName=?, email=?, profileImage=? where username=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, displayName);
    preparedStmt.setString(2, email);
    preparedStmt.setString(3, profileImage);
    preparedStmt.setString(4, this.username);
    preparedStmt.execute();
    conn.close();
    System.out.println("User: "+this.username+" has successfully been logged in...");
    return true;
  }

  public Boolean DeleteUser() throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      System.out.println("User: "+this.username+" has failed to be deleted...");
      return false;
    }
    if(!this.isUserInDB(this.username)){
      System.out.println("User: "+this.username+" has failed to be deleted twice...");
      return false;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "delete from User where username=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.username);
    preparedStmt.execute();
    conn.close();
    System.out.println("User: "+this.username+" has successfully been deleted...");
    return true;
  }

  public String Login() throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      System.out.println("User: "+this.username+" has failed to be logged in...");
      return null;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "update User set sessionID=? where username=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    this.sessionID = UUID.randomUUID().toString().replace("-", "");
    preparedStmt.setString(1, this.sessionID);
    preparedStmt.setString(2, this.username);
    preparedStmt.execute();
    conn.close();
    System.out.println("User: "+this.username+" has successfully been logged in...");
    return this.sessionID;
  }


  public Boolean Logout() throws Exception{
    // Should never happen
    if(!this.loginSuccess){
      System.out.println("User: "+this.username+" has failed to be logged out...");
      return false;
    }
    Class.forName("com.mysql.jdbc.Driver");
    Connection conn = DriverManager.getConnection(this.authDB, "root", "skitter_auth_dbpass");
    String query = "update User set sessionID=NULL where sessionID=?";
    PreparedStatement preparedStmt = conn.prepareStatement(query);
    preparedStmt.setString(1, this.sessionID);
    preparedStmt.execute();
    conn.close();
    System.out.println("User: "+this.username+" has successfully been logged out...");
    return true;
  }



}
