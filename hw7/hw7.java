import java.sql.*;

public class  hw7
{
  private static String driver = "com.mysql.jdbc.Driver";
  private static String server = "jdbc:mysql://127.0.0.1/hwdb?user=root&password=jkjpbskjja";
  private static Connection con = null;

  //doQuery is a function that takes a query and runs it. it expects conection to be set up correctly
  //it also expects a number of columns to return.
  public static void doQuery(String query, int col) throws Exception
  {
        System.out.println(query+"\n");
        Statement instruction = con.createStatement();
        //Execute query
        ResultSet resultat = instruction.executeQuery(query);
        //Output the first columns of returned records through a while loop.
        while(resultat.next()){
            for(int i = 1; i<= col; i++)
            {
            System.out.print(resultat.getString(i)+",\t");
            }
            System.out.print("\n");
        }//end loop
        System.out.print("\n\n\n");
  }

  public static void main(String[] args)
  {
    try{
        Class.forName(driver);
        con= DriverManager.getConnection(server);
        System.out.println("Q.a:");
        doQuery("SELECT p.pname FROM PRODUCTS p, ORDERS o, AGENTS a WHERE o.aid=a.aid AND o.pid=p.pid AND a.aname='Jenny Doe'", 1);
        System.out.println("Q4:");
        doQuery("SELECT aname FROM AGENTS a WHERE NOT EXISTS (SELECT o.aid FROM ORDERS o, CUSTOMERS c WHERE o.aid = a.aid AND o.cid = c.cid AND a.city != c.city)", 1);        
        System.out.println("Q.b:");
        doQuery("SELECT p1.city, p1.pname FROM PRODUCTS p1 WHERE NOT EXISTS (SELECT * FROM PRODUCTS p2 WHERE p1.pid!=p2.pid AND p1.price>p2.price AND p2.city=p1.city)", 2);        
        System.out.println("Q.c:");
        doQuery("SELECT DISTINCT o1.month, p1.pname FROM PRODUCTS p1, ORDERS o1 WHERE p1.pid = o1.pid AND NOT EXISTS (SELECT * FROM PRODUCTS p2, ORDERS o2 WHERE p1.pid!=p2.pid AND p2.pid = o2.pid AND p1.price<p2.price AND o1.month = o2.month)", 2);
        con.close();
        } catch(Exception e){
            System.out.println(e.toString());
        }finally{
        }
    }
} 
