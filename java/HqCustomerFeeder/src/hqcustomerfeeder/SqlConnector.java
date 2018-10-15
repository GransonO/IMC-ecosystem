/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package hqcustomerfeeder;

import com.microsoft.sqlserver.jdbc.SQLServerDataSource;
import java.awt.HeadlessException;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.PrintWriter;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;

/**
 *
 * @author Granson
 */
public class SqlConnector {

	public static Connection newConnection() throws ClassNotFoundException, InstantiationException, IllegalAccessException {
                // Declare the JDBC objects.
            Connection conn=null;
            
                //------------------------- GET DB NAME ----------------------------------------------------------------
   
            String DB_NAME = "nothing";
                try {
                    DB_NAME = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\DATABASE_NAME.txt")));

                } catch (IOException ex) {
                   DB_NAME = "nothing";
                }
                //------------------------- END OF GET DB NAME ----------------------------------------------------------------
            if(DB_NAME.equals("nothing")){
                JOptionPane.showMessageDialog(null, "The DATABASE name is not indicated");
            }else{
                
                    try {

                        String url = "jdbc:sqlserver://localhost:1433;user=sa;password=jade@123;databaseName="+DB_NAME+";";
                        //String url = "jdbc:sqlserver://localhost:1433;databaseName="+DB_NAME+";integratedSecurity=true;";

                        //TO USE THE INTEGRATED SECURITY... ENSURE THE SQLJDBC.DLL FILE IS ADDED TO C:\\WINDOWS\SYSTEM32 FOLDER

                        Class.forName("com.microsoft.sqlserver.jdbc.SQLServerDriver");
                        conn = DriverManager.getConnection(url);

                        } catch (SQLException e) {
                        JOptionPane.showMessageDialog(null, "Error" + e);
                        PrintWriter writer;
                        try {
                            writer = new PrintWriter("C:\\INDULGENCE_DATA\\UPLOAD_LOG_FILE(DO NOT EDIT).txt");
                            writer.print("");
                            writer.close();
                        } catch (FileNotFoundException ex) {
                            Logger.getLogger(SqlConnector.class.getName()).log(Level.SEVERE, null, ex);
                        }
                    }
            
            }

            return conn;
    }
}
