/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package jade.bridge;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author Granson
 * Add logs to local log text file
 */
public class Data_Log {
    
        String LOCAL_LOG_FILE = "C:\\INDULGENCE_DATA\\LOCAL_LOG_FILE(DO NOT EDIT).txt";
        String UPLOAD_LOG_FILE = "C:\\INDULGENCE_DATA\\UPLOAD_LOG_FILE(DO NOT EDIT).txt"; 
        String three = "C:\\INDULGENCE_DATA\\NOT_UPLOAD_DATE(DO NOT EDIT).txt";   
        String four = "C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_DATE(DO NOT EDIT).txt";   
        String five = "C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_LOG(DO NOT EDIT).txt";  
        String DB_NAME = "C:\\INDULGENCE_DATA\\DATABASE_NAME.txt";  
        String STORE_ID = "C:\\INDULGENCE_DATA\\STORE_ID.txt";
        
    public void makeUsefulFiles() {

        File file = new File("C:\\INDULGENCE_DATA");
        if (!file.exists()) {
            if (file.mkdir()) {
                //System.out.println("Directory is created!");
                makeFilesAvailable();
            } else {
                //System.out.println("Failed to create directory!");
            }
        }
    }
    
    public void makeFilesAvailable(){

     	FileWriter f1 = null,f2 = null,f3 = null,f4 = null,f5 = null,db = null,st=null;

		try {

			
			f1 = new FileWriter(LOCAL_LOG_FILE);
			f2 = new FileWriter(UPLOAD_LOG_FILE);
			f3 = new FileWriter(three);
			f4 = new FileWriter(four);
			f5 = new FileWriter(five);
                        db = new FileWriter(DB_NAME);
                        st = new FileWriter(STORE_ID);
                        
			writeDBName();
                        
		} catch (IOException e) {

			e.printStackTrace();

		} finally {

			try {
				
				if (f1 != null)
					f1.close();				
				if (f2 != null)
					f2.close();				
				if (f3 != null)
					f3.close();				
				if (f4 != null)
					f4.close();				
				if (f5 != null)
					f5.close();				
				if (db != null)
					db.close();				
				if (st != null)
					st.close();

			} catch (IOException ex) {

				ex.printStackTrace();

			}

		}

    }
   
    public void writeDBName() throws IOException{
        makeUsefulFiles();
        String status = "NOTHING";
        Files.write(Paths.get(DB_NAME), status.getBytes(), StandardOpenOption.CREATE);
    }  
    
    //------------------------- THE NETWORK STATUS LOG ----------------------------------------------------------------
    public void writeStatusLog(String Status) throws IOException{
        makeUsefulFiles();
        String status = Status;
        Files.write(Paths.get("C:\\INDULGENCE_DATA\\LOCAL_LOG_FILE(DO NOT EDIT).txt"), status.getBytes(), StandardOpenOption.CREATE);
    }
    
    public String checkStatusLog(){
        makeUsefulFiles();
        String status = null;
        try {
            status = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\LOCAL_LOG_FILE(DO NOT EDIT).txt")));
           
        } catch (IOException ex) {
           status = null;
        }
         return status;
    } 
    //------------------------- END OF THE NETWORK STATUS LOG ----------------------------------------------------------------
    
    
    //------------------------- THE DATA UPLOAD STATUS LOG ----------------------------------------------------------------
    public void writeUploadLog(String Status) throws IOException{
        makeUsefulFiles();
        String status = Status;
        Files.write(Paths.get("C:\\INDULGENCE_DATA\\UPLOAD_LOG_FILE(DO NOT EDIT).txt"), status.getBytes(), StandardOpenOption.CREATE);
    }
    
    public String checkUploadLog(){
        makeUsefulFiles();
        String status = null;
        try {
            status = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\UPLOAD_LOG_FILE(DO NOT EDIT).txt")));
           
        } catch (IOException ex) {
           status = null;
        }
         return status;
    } 
    //------------------------- END OF THE DATA UPLOAD STATUS LOG ----------------------------------------------------------------
    
    
    //------------------------- THE DATE NOT UPLOAD STATE ----------------------------------------------------------------
    public void writeNotUploadDate(String Status) throws IOException{
        makeUsefulFiles();
        String status = Status;
        Files.write(Paths.get("C:\\INDULGENCE_DATA\\NOT_UPLOAD_DATE(DO NOT EDIT).txt"), status.getBytes(), StandardOpenOption.CREATE);
    }
    
    public String checkNotUploadDate(){
        makeUsefulFiles();
        String status = null;
        try {
            status = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\NOT_UPLOAD_DATE(DO NOT EDIT).txt")));
           
        } catch (IOException ex) {
           status = null;
        }
         return status;
    } 
    //------------------------- END OF THE DATE NOT UPLOAD STATE ----------------------------------------------------------------
    
    
    //------------------------- THE CUSTOMER NOT UPLOADED STATE ----------------------------------------------------------------
    public void writeCustomerNotUploadDate(String Status) throws IOException{
        makeUsefulFiles();
        String status = Status;
        Files.write(Paths.get("C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_DATE(DO NOT EDIT).txt"), status.getBytes(), StandardOpenOption.CREATE);
    }
    
    public String checkCustomerNotUploadDate(){
        makeUsefulFiles();
        String status = null;
        try {
            status = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_DATE(DO NOT EDIT).txt")));
           
        } catch (IOException ex) {
           status = null;
        }
         return status;
    } 
    //------------------------- END OF THE DATE NOT UPLOAD STATE ----------------------------------------------------------------
      
    
    //------------------------- THE CUSTOMER NOT UPLOADED STATUS LOG ----------------------------------------------------------------
    public void writeCustomerNotUploadLog(String Status) throws IOException{
        makeUsefulFiles();
        String status = Status;
        Files.write(Paths.get("C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_LOG(DO NOT EDIT).txt"), status.getBytes(), StandardOpenOption.CREATE);
    }
    
    public String checkCustomerNotUploadLog(){
        makeUsefulFiles();
        String status = null;
        try {
            status = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_LOG(DO NOT EDIT).txt")));
           
        } catch (IOException ex) {
           status = null;
        }
         return status;
    } 
    //------------------------- END OF THE DATE NOT STATUS LOG ----------------------------------------------------------------
      
}
