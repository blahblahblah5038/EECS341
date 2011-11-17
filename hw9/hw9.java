//Wesley Gould
//EECS341 HW9

import java.sql.*;

public class  hw9
{
  private static String driver = "com.mysql.jdbc.Driver";
  private static String server = "jdbc:mysql://127.0.0.1/hwdb?user=root&password=jkjpbskjja";
  private static Connection con = null;

  static String[][] toCall = {{"stupidsville", "1"},{"bobsville", "1000"}, {"townThatIsntInTheDatabase", "1"},{"stupidsville","this is not a number"}};

  public static void main(String[] args)
  {
    try{
        Class.forName(driver);
        con= DriverManager.getConnection(server);
        String command="{call HW9PROC(?,?)}";
        CallableStatement cs = con.prepareCall(command);
      
        for(int i = 0; i<toCall.length; i++)
        {
        try{
           cs.setString(1,toCall[i][0]);
           cs.setString(2,toCall[i][1]);
           cs.executeQuery();         
        }
        catch(Exception e)
        {
            System.out.println(e.toString());
        }
        }
        cs.close();
        con.close();
        } catch(Exception e){
            System.out.println(e.toString());
        }finally{
        }
    }
} 
