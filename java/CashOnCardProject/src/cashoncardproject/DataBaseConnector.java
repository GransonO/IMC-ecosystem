/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package cashoncardproject;

import java.awt.HeadlessException;
import java.sql.*;

import javax.swing.*;

/**
 *
 * @author Granson
 */
public class DataBaseConnector {
    	Connection connection = null;
	
	public static Connection newConnection() {
		try {
			Class.forName("org.sqlite.JDBC");
			 Connection connection = DriverManager.getConnection("jdbc:sqlite:C:\\Users\\Granson\\Documents\\NetBeansProjects\\CashOnCardProject\\DataBase\\CashOnCardDataBase.sqlite");
                         //JOptionPane.showMessageDialog(null, "Connection Success !!!");
			return connection;
		} catch (ClassNotFoundException | SQLException | HeadlessException e) {
			JOptionPane.showMessageDialog(null, e);
			return null;
		}

	}
}
