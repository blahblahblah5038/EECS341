<%@page language="java" import="java.sql.*"%>
<% 
String driver = "com.mysql.jdbc.Driver";
String server = "jdbc:mysql://127.0.0.1/hwdb?user=root&password=jkjpbskjja";
Class.forName(driver);
Connection con= DriverManager.getConnection(server);
Statement StatementRecordset1 = con.createStatement();
String query=request.getParameter("my_sql_injection");
ResultSet Recordset1 = StatementRecordset1.executeQuery(query);
boolean Recordset1_isEmpty = !Recordset1.next();
boolean Recordset1_hasData = !Recordset1_isEmpty;
int Recordset1_numRows = 0;
ResultSetMetaData meta_data = Recordset1.getMetaData();
int cols = meta_data.getColumnCount();
%>
<% int Repeat1__numRows = 10 ;
int Repeat1__index = 0 ;
Recordset1_numRows += Repeat1__numRows;
%>
<table>
<% while ((Recordset1_hasData)&&(Repeat1__numRows-- != 0))
{
%>
<tr>
<% for(int i = 1; i<=cols; i++){%>
<td><%= Recordset1.getObject(i) %></td>
<%}%>
</tr>
<%Repeat1__index++;
Recordset1_hasData = Recordset1.next();
}
%>
</table>
<%
Recordset1.close();
con.close();
%>

