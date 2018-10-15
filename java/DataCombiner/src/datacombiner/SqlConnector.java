/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package datacombiner;

import java.awt.HeadlessException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import javax.swing.JOptionPane;

/**
 *
 * @author Granson
 */
public class SqlConnector {

	public static Connection newConnection(String File) {
           
            String DatabaseFile = File;
		try {                    
			Class.forName("org.sqlite.JDBC");
			 Connection connection = DriverManager.getConnection("jdbc:sqlite:"+DatabaseFile);
                         JOptionPane.showMessageDialog(null, "Connection Success !!!");
			return connection;
		} catch (ClassNotFoundException | SQLException | HeadlessException e) {
			JOptionPane.showMessageDialog(null, e);
			return null;
		}

	}
}
