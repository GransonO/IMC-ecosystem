/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.granson.corum_integrated_sytems;

import java.awt.HeadlessException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import javax.swing.JOptionPane;

/**
 *
 * @author Granson
 */
public class CorumAzureDbConector {

     public static Connection newConnection() {

        //Connection string to the azure db
        String connectionString
                = "jdbc:sqlserver://corumtransactorservice.database.windows.net:1433;database=CorumTransactor;user=GransonDeveloper@corumtransactorservice;password={Corum3942*};encrypt=true;trustServerCertificate=false;hostNameInCertificate=*.database.windows.net;loginTimeout=30;";

        // Declare the JDBC objects.
        Connection connection = null;

        try {
            connection = DriverManager.getConnection(connectionString);
           // JOptionPane.showMessageDialog(null, "Success");
            return connection;
        } catch (SQLException | HeadlessException e) {
            JOptionPane.showMessageDialog(null, e +" DB connection error");
            return null;
        }

    }
    
}
