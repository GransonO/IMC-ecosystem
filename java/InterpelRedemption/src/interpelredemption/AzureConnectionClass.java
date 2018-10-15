/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interpelredemption;

import java.awt.HeadlessException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import javax.swing.JOptionPane;

/**
 *
 * @author Granson
 */
public class AzureConnectionClass {
    
     public static Connection newConnection() {

        //Connection string to the azure db
        String connectionString
                = "jdbc:sqlserver://interpeltransactor.database."
                + "windows.net:1433;database=InterpelDb;"
                + "user=indulgenceAdmin@interpeltransactor;password={granson@3942};encrypt=true;"
                + "trustServerCertificate=false;hostNameInCertificate=*.database.windows.net;"
                + "loginTimeout=30;";

        // Declare the JDBC objects.
        Connection connection = null;

        try {
            connection = DriverManager.getConnection(connectionString);
           // JOptionPane.showMessageDialog(null, "Success");
            return connection;
        } catch (SQLException | HeadlessException e) {
            JOptionPane.showMessageDialog(null, e +"\n DB connection error");
            return null;
        }

    }
}
