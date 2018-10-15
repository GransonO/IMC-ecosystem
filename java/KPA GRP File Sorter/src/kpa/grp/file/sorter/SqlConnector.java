/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kpa.grp.file.sorter;

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

	public static Connection newConnection() {
           
            //String DatabaseFile = File;
		try {                    
			Class.forName("org.h2.Driver");
			 Connection connection = DriverManager.getConnection("jdbc:h2:mem:interpel_data_sorter;DB_CLOSE_DELAY=-1", "INDULGENCEIT", "Power3942");
                         JOptionPane.showMessageDialog(null, "Connection Success !!!");
			return connection;
		} catch (ClassNotFoundException | SQLException | HeadlessException e) {
			JOptionPane.showMessageDialog(null, e);
			return null;
		}
            
	}
}
