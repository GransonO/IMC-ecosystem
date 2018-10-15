/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package jade.bridge;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import static java.lang.Thread.sleep;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import net.proteanit.sql.DbUtils;

/**
 *
 * @author Granson
 */
public final class JadeBridge extends javax.swing.JFrame {

    /**
     * Creates new form JadeBridge
     */
    int dayDate;
    String stated_time ,date,previuos_day,next_day,Total_Points,ip = "1.1.1.1",DATA_UPLOAD_STATE,NETWORK_STATE_LOG;
    Connection conn = null;
    Rest_Requester requester = new Rest_Requester();
    Data_Log log_status = new Data_Log();  
    String New_Time = "nothing";
    
    public JadeBridge() throws ClassNotFoundException, InstantiationException, IllegalAccessException, InterruptedException, IOException {
        initComponents();
        
        this.setIconImage(Toolkit.getDefaultToolkit().getImage(getClass().getResource("circle.png")));
        log_status.makeUsefulFiles();
        
         String DB_NAME = "NOTHING";
            try {
                DB_NAME = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\DATABASE_NAME.txt")));
               
            } catch (IOException ex) {
               DB_NAME = "nothing";
                JOptionPane.showMessageDialog(null, "This is the first error point.\n" + ex);
            }
            
            if(!DB_NAME.equals("NOTHING")){
                starting_actions();  
            }else{
                String DB_Name = JOptionPane.showInputDialog("Please enter the DATABASE name...");
                Files.write(Paths.get("C:\\INDULGENCE_DATA\\DATABASE_NAME.txt"), DB_Name.getBytes(), StandardOpenOption.CREATE);
                
                String store_id = JOptionPane.showInputDialog("Please enter the Store Name...");
                Files.write(Paths.get("C:\\INDULGENCE_DATA\\STORE_ID.txt"), store_id.getBytes(), StandardOpenOption.CREATE);
                starting_actions();  
            }
    }
    
    public void appendTotxt(String word){    
        log_area.append(word + "\n");
    }
    
    public void starting_actions() throws InterruptedException{
        log_status.makeUsefulFiles();
        
        clockWork();
        
        sleep(1000);
        log_area.append("> Checking connection...\n");
        //Checks for network availability and the users ip
        
        sleep(1000);
        if (netIsAvailable() == true) {
            connectPanel.setBackground(Color.GREEN);
            connectionTxt.setText("Connected");
            GetConnectionStatus();
        } else {
            connectPanel.setBackground(Color.RED);
            connectionTxt.setText("Disonnected");
            log_area.append(">> Connection failure\n>> Check your connectivity and refresh...\n");
            GetConnectionStatus();
        }
        
        add_Customer_Data();
        add_Transactions_Data();
        //add_items_trans();
    } 
    
    public void createConnection(){
            try {
            // TODO add your handling code here:
            //add_General_Data();
            conn = SqlConnector.newConnection();
                    
            if(conn == null){
                log_area.append("\n>" + date + " " + stated_time + " >> Could not connect to the server");
                //log_status.writeStatusLog("\n>" + date + " " + stated_time + ">> Could not connect to the server");
            }else{
                 log_area.append("\n>" + date + " " + stated_time + " Local SQL Connection success...");
                 //log_status.writeStatusLog("\n>" + date + " " + stated_time + "Local SQL Connection success...");
            }
        } catch (ClassNotFoundException | InstantiationException | IllegalAccessException ex) {
             log_area.append("\n>" + date + " " + stated_time + "Local SQL Connection failed..." + ex);
        }
    }
    
    public void closeConnection(){
        if(conn != null){
            try {
                conn.close();
                log_area.append("\n" + date + " " + stated_time + " >> SQL connection is closed\n");
                log_status.writeStatusLog("\n" + date + " " + stated_time + " <*> SQL is NOT Connected");
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(null, "SQL is NOT Connected " + ex);
            } catch (IOException ex) {
                log_area.append("Could not write to file");
            }
            }else{
            
                 log_area.append("\n >" + date + " " + stated_time + " Local SQL Connection success...");
            }
    }
    
    public final void GetConnectionStatus() {
        try {
            URL whatismyip = new URL("http://checkip.amazonaws.com");
            BufferedReader in = new BufferedReader(new InputStreamReader(
                    whatismyip.openStream()));

            ip = in.readLine(); //you get the IP as a String
            log_area.append("> Connected under IP: " + ip + "\n");
        } catch (MalformedURLException ex) {
            log_area.append(">>> Connection aborted...");
        } catch (IOException ex) {
            log_area.append("***Please check your internet***");
        }
    }
                  
    //Add data to the table
    private void add_Transactions_Data() {
        createConnection();
        String Sql = "SELECT * FROM dbo.[Transaction];";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                general_table.setModel(DbUtils.resultSetToTableModel(rs));
                log_area.append("\n> Transaction data Added ");
                
            }
            rs.close();
        } catch (SQLException ex) {
            log_area.append("\n> Local SQL error > " + ex);
        }finally{
            closeConnection();
        }

    }
            
    //Add data to the table
    private void add_Customer_Data() {
        createConnection();
        String Sql = "SELECT * FROM Customer";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                customers_tbl.setModel(DbUtils.resultSetToTableModel(rs));
                log_area.append("\n> Customer data Added ");
                
            }
            rs.close();
        } catch (SQLException ex) {
             log_area.append("\n> Local SQL error > " + ex);
        }finally{
            closeConnection();
        }

    }            
    
    //Add Transaction Entry to the table
    private void add_items_trans() throws SQLException {
        createConnection();
        String Sql = "SELECT * FROM TransactionEntry";

            ResultSet rs = null;
        try {
                log_area.append("\n" + conn.toString());
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                log_area.append("\n" + rs.toString());
                item_trans_table.setModel(DbUtils.resultSetToTableModel(rs));
                log_area.append("\n> ItemsEntries data Added ");
                
            }
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(null, ex);
             log_area.append("\n> Local SQL error > " + ex);
        }finally{
            rs.close();
            closeConnection();
        }

    }
                
    //Upload data to the Indulgence server
    private void RegisterMembersOnline(){
        Thread thread = new Thread(){
            @Override
            public void run() {
                    String Sql,account_id,customer_id,name,phone,email,entry_date,last_visit;
                    Sql = "SELECT * FROM Customer WHERE LastVisit >= ' "+ date +"' AND LastVisit < '" + next_day + "'" ;
                    appendTotxt("\n>> " + Sql);
                    
                        try {

                            PreparedStatement ps = conn.prepareStatement(Sql);
                            ResultSet Rs = ps.executeQuery();
                            while (Rs.next()) {

                                account_id = Rs.getString("AccountNumber");
                                name = Rs.getString("FirstName") +" "+ Rs.getString("LastName");
                                phone = Rs.getString("AccountNumber");
                                email = Rs.getString("EmailAddress");
                                customer_id = Rs.getString("ID");
                                entry_date = Rs.getString("AccountOpened");
                                last_visit = Rs.getString("LastVisit");

                                log_area.append("\n> Sending Data: " + account_id + " " + name + " " + phone + " " + email + " " + entry_date + " " + last_visit + " " + customer_id);
                                String responce = requester.RegisterProtocol(account_id,name,phone,email, entry_date, last_visit,customer_id);
                                log_area.append("\n> " + responce);
                            }

                        } catch (SQLException ex) {
                            ex.printStackTrace();
                            log_area.append("\n> SQLException Data entry error occured.Check Logs...\n" + ex);
                        }        
            }
            
        };
        thread.start();
        
    }
 
    //Upload data to the Indulgence server
    private String GetPhoneNumber(String CUSTOMER_ID){
        
        String Sql,phone = "No Phone";
        Sql = "SELECT * FROM Customer WHERE ID = ' "+ CUSTOMER_ID +"'";
        appendTotxt("\n>> " + Sql);
        
            try {
                
                PreparedStatement ps = conn.prepareStatement(Sql);
                ResultSet Rs = ps.executeQuery();
                if(Rs.next()){
                    
                 phone = Rs.getString("AccountNumber");
                }
            return phone;
            } catch (SQLException ex) {
                ex.printStackTrace();
                log_area.append("\n> SQLException Error Logs...\n" + ex);
            return phone;
            }
    }         
     
    //Upload data to the Indulgence server
    private ArrayList<String> GetPurchasedItem(String TRANSACTION_NUMBER){
        
        String Sql,ItemID,SalesRepID,Quantity;
        ArrayList<String> results = new ArrayList<>();
        results.clear();
        Sql = "SELECT * FROM TransactionEntry WHERE TransactionNumber = ' "+ TRANSACTION_NUMBER +"'";
        appendTotxt("\n>> " + Sql);
        
            try {
                
                PreparedStatement ps = conn.prepareStatement(Sql);
                ResultSet Rs = ps.executeQuery();
                if(Rs.next()){
                    
                 ItemID = Rs.getString("ItemID");
                 SalesRepID = Rs.getString("SalesRepID");
                 Quantity = Rs.getString("Quantity");
                 results.add(ItemID);
                 results.add(SalesRepID);
                 results.add(Quantity);
                }
            return results;
            } catch (SQLException ex) {
                ex.printStackTrace();
                log_area.append("\n> SQLException Error Logs...\n" + ex);
            return null;
            }
    }         
    
    //Upload data to the Indulgence server
    private void PostDataOnline(){
        
        String Sql,store_id ,customer_phone,transaction_number,transaction_time,customer_id,total_spend,cashier_id,ItemID,SalesRepID,Quantity;
        ArrayList<String> result = new ArrayList<>();        
        Sql = "SELECT * FROM dbo.[Transaction] WHERE Time >= ' "+ date +"' AND Time < '" + next_day + "'" ;
        
        appendTotxt("\n>> " + Sql);
            try {
                
                PreparedStatement ps = conn.prepareStatement(Sql);
                ResultSet Rs = ps.executeQuery();
                while (Rs.next()) {
                    
                    store_id = Rs.getString("StoreID");
                    transaction_number = Rs.getString("TransactionNumber");
                    transaction_time = Rs.getString("Time");
                    customer_id = Rs.getString("CustomerID");
                    customer_phone = GetPhoneNumber(customer_id);
                    total_spend = Rs.getString("Total");
                    cashier_id = Rs.getString("CashierID");
                    result = GetPurchasedItem(transaction_number);
                    //ITEM DETAILS
                    if( result == null){
                        ItemID = null;
                        SalesRepID = null;
                        Quantity = null;
                    }else{
                        
                        ItemID = result.get(0);
                        SalesRepID = result.get(1);
                        Quantity = result.get(2);
                    }
                    result.clear();
                    
                    log_area.append("\n> Output from Server ...");
                    log_area.append("\n> The Query : store_id : " + store_id + " transaction_number : " + transaction_number + " transaction_time : " + transaction_time + " customer_id : " + customer_id + " total_spend : " + total_spend + " cashier_id : " + cashier_id + " customer_phone : " + customer_phone);
                    String responce = requester.RestProtocol(store_id,transaction_number,transaction_time,customer_id, total_spend, cashier_id,customer_phone,ItemID,SalesRepID,Quantity);
                    log_area.append("\n> " + responce + "...");
                    //log_area.append("\n Test For the pushed data \n ~ " + store_id + " " + transaction_number + " " + transaction_time + " " + customer_id + " " + total_spend + " " + cashier_id + " " + customer_phone + " " + ItemID + " " + SalesRepID + " " + Quantity);
                }

            } catch (SQLException ex) {
                ex.printStackTrace();
                log_area.append("\n> SQLException Data entry error occured.Check Logs...\n" + ex);
            }
    }        

    private static boolean netIsAvailable() {
        try {
            final URL url = new URL("http://www.google.com");
            final URLConnection conn = url.openConnection();
            conn.connect();
            return true;
        } catch (MalformedURLException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {

            return false;
        }
    }
    
    private void checkInternetConnectivity() throws IOException{
    
        if (netIsAvailable() == true) {
            //IF INTERNET CONNECTION IS AVAILABLE
            GetConnectionStatus();  
            connectPanel.setBackground(Color.GREEN);
            connectionTxt.setText("Connected");  
          } else {
            appendTotxt("\n>> The Internet connection is lost.\n Please check you connection properties");
            connectPanel.setBackground(Color.RED);
            connectionTxt.setText("Disonnected");
            
             NETWORK_STATE_LOG = log_status.checkStatusLog();
            if(NETWORK_STATE_LOG == null){                                                
                    log_status.writeStatusLog(date + " " + stated_time + " <*> Internet Interruption. Connection lost. Check your connection proprerties");
            }else{
                    log_status.writeStatusLog(DATA_UPLOAD_STATE +"\n " + date + " " + stated_time + " : Internet Interruption. Connection lost. Check your connection proprertie");
            }
        }
                        
    }    
    
    private void checkFormerUploadState() throws IOException, InterruptedException{
    
        if (netIsAvailable() == true) {
            //IF INTERNET CONNECTION IS AVAILABLE
                uploadpreviousData();
                
          } else {

            
        }
                        
    }
    
    private void uploadPreviousCustomers(){
            appendTotxt(date + ": Uploading " + previuos_day + " data");
            Thread thread = new Thread(){

                 @Override
                 public void run() {
                     createConnection();
                        String Sql,account_id,customer_id,name,phone,email,entry_date,last_visit;

                        Sql = "SELECT * FROM Customer WHERE LastVisit >= ' "+ previuos_day +"' AND LastVisit < '" + date + "'" ;


                        appendTotxt("\n>> " + Sql);
                            try {

                                PreparedStatement ps = conn.prepareStatement(Sql);
                                ResultSet Rs = ps.executeQuery();
                                while (Rs.next()) {


                                    account_id = Rs.getString("AccountTypeID");
                                    name = Rs.getString("FirstName") + " " + Rs.getString("LastName");
                                    phone = Rs.getString("PhoneNumber");
                                    email = Rs.getString("EmailAddress");
                                    customer_id = Rs.getString("ID");
                                    entry_date = Rs.getString("AccountOpened");
                                    last_visit = Rs.getString("LastVisit");

                                    log_area.append("\n> Output from Server ...");
                                    String responce = requester.RegisterProtocol(account_id,name,phone,email, entry_date, last_visit,customer_id);
                                    log_area.append("\n> " + responce + "...");
                                }
                                appendTotxt("\n>>> Clearing data from previous date file");
                            
                                PrintWriter writer = new PrintWriter("C:\\INDULGENCE_DATA\\CUSTOMER_NOT_UPLOAD_DATE(DO NOT EDIT).txt");
                                writer.print("");
                                writer.close();

                                appendTotxt(">>> Cleared...");

                            } catch (SQLException ex) {
                                ex.printStackTrace();
                                log_area.append("\n> SQLException Data entry error occured.Check Logs...\n" + ex);
                            } catch (FileNotFoundException ex) {
                                appendTotxt("File Not Found Error " + ex);
                     }finally{
                            closeConnection();
                            }
                   
                  }
             
            };
             thread.start();
           
    }
    
    private void uploadpreviousData(){
            appendTotxt(date + ": Uploading " + previuos_day + " data");
            Thread thread = new Thread(){

                 @Override
                 public void run() {
                    createConnection();
                    String Sql,customer_phone,store_id ,transaction_number,transaction_time,customer_id,total_spend,cashier_id;  
                    Sql = "SELECT * FROM dbo.[Transaction] WHERE Time >= ' "+ previuos_day +"' AND Time < '" + date + "'" ;
                    appendTotxt("\n>> " + Sql);
        
                    try {

                            PreparedStatement ps = conn.prepareStatement(Sql);
                            ResultSet Rs = ps.executeQuery();
                            while (Rs.next()) {

                                store_id = Rs.getString("StoreID");
                                transaction_number = Rs.getString("TransactionNumber");
                                transaction_time = Rs.getString("Time");
                                customer_id = Rs.getString("CustomerID");
                                customer_phone = GetPhoneNumber(customer_id);
                                total_spend = Rs.getString("Total");
                                cashier_id = Rs.getString("CashierID");

                                log_area.append("\n> Output from Server ...");
                                //String responce = requester.RestProtocol(store_id,transaction_number,transaction_time,customer_id, total_spend, cashier_id,customer_phone);
                                //log_area.append("\n> " + responce + "...");
                            }

                            appendTotxt("\n>>> Clearing data from previous date file");
                            
                            PrintWriter writer = new PrintWriter("C:\\INDULGENCE_DATA\\NOT_UPLOAD_DATE(DO NOT EDIT).txt");
                            writer.print("");
                            writer.close();
                            
                            appendTotxt(">>> Cleared...");

                        } catch (SQLException ex) {
                            ex.printStackTrace();
                            log_area.append("\n> SQLException Data entry error occured.Check Logs...\n" + ex);
                        } catch (IOException ex) {
                        appendTotxt("Input Out put error " + ex);
                    } finally{
                            closeConnection();
                            } 
                   
                  }
             
            };
             thread.start();
           
    }
    
    //Upload data to the Indulgence server
    private void PingServer(){
        Thread thread = new Thread(){
            @Override
            public void run() {
                    
                String id = "xx";
                try {
                id = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\STORE_ID.txt")));
                    appendTotxt("\n>> Pinging Server alert");
                    log_area.append("\n> Checking Output from Server ...");
                    String responce = requester.PingServer(id);
                    log_area.append("\n> " + responce + "...");  
                } catch (IOException ex) {
                    log_area.append(">> Error fetching store id");
                }
                          
            }
            
        };
        thread.start();
        
    } 
    
    //Clock Activity
    private void clockWork() {

        Thread thread = new Thread() {
            public void run() {
                try {
                    for (;;) {
                        String hr, min, sec;
                        Calendar cal = new GregorianCalendar();
                        int day = cal.get(Calendar.DAY_OF_MONTH);
                        int month = cal.get(Calendar.MONTH);
                        int year = cal.get(Calendar.YEAR);
                        dayDate = cal.get(Calendar.DAY_OF_WEEK);
                        //getDay();

                        int hour = cal.get(Calendar.HOUR_OF_DAY);
                        int minute = cal.get(Calendar.MINUTE);
                        int seconds = cal.get(Calendar.SECOND);

                        if (hour < 10) {
                            hr = "0";
                        } else {
                            hr = "";
                        }
                        if (minute < 10) {
                            min = "0";
                        } else {
                            min = "";
                        }
                        if (seconds < 10) {
                            sec = "0";
                        } else {
                            sec = "";
                        }
                        stated_time = " " + hr + hour + ": " + min + minute
                                + ": " + sec + seconds;
                        timeLabel.setText(stated_time);
                        //CHECK DATABASE CONNECTIVITY
                                                
                        //CHECK NETWORK CONNECTION ON EVERY 55TH MINUTE
                        if(seconds == 55){
                            appendTotxt("\n>>> System check for connection state");
                            checkInternetConnectivity();
                        }
                        
                        //START UPLOADING TRANSACTIONS PROCESS AT  10:00 PM
                        if(stated_time.equals(" 22: 00: 30")){
                        //if(stated_time.equals(New_Time)){
                            createConnection();
                            Thread thread = new Thread(){

                                @Override
                                public void run() {
                                    try {
                                        log_area.append("\n> Upload time " + stated_time + "\n> Initializing upload to Indulgence Server Loyalty 001");
                                        sleep(1000);
                                        log_area.append("\n> Calling transaction Post Data");

                                        
                                        if (netIsAvailable() == true) {
                                            //IF INTERNET CONNECTION IS AVAILABLE
                                              PostDataOnline();
                                              
                                        } else {
                                            
                                                DATA_UPLOAD_STATE = log_status.checkUploadLog();
                                                log_status.writeNotUploadDate(date);
                                                if(DATA_UPLOAD_STATE == null){                                                
                                                        log_status.writeUploadLog(date + " " + stated_time + " <*> Internet Interruption. Data not posted to Indulgence Server");
                                                }else{
                                                        log_status.writeUploadLog(DATA_UPLOAD_STATE +"\n " + date + " " + stated_time + " : Internet Interruption. Data not posted to Indulgence Server");
                                                }
                                        }
                                    } catch (InterruptedException ex) {
                                        log_area.append("\n> Interruption error on posting data");
                                        
                                    } catch (IOException ex) {
                                        log_area.append("Exception caught" + ex);
                                    }finally{
                                        closeConnection();
                                    }
                                }       
                            };
                            thread.start();
                        }
                        
                        
                  
                        //PING SERVER
                        if(stated_time.equals(" 08: 00: 20")){
                            appendTotxt("\n>>> System check for connection state");
                            PingServer(); 
                        }
                  
                        //PING SERVER
                        if(stated_time.equals(" 12: 00: 20")){
                            appendTotxt("\n>>> System check for connection state");
                            PingServer(); 
                        }
                  
                        //PING SERVER
                        if(stated_time.equals(" 18: 00: 20")){
                            appendTotxt("\n>>> System check for connection state");
                            PingServer(); 
                        }
                  
                        //CHECK UPLOAD STATE FOR PAST DAY 
                        if(stated_time.equals(" 09: 00: 20")){
                            appendTotxt("\n>>> System check for connection state");
                                                                                                    
                            if(((log_status.checkNotUploadDate()).trim()).equals(previuos_day.trim())){
                                appendTotxt("\n>>> Processing previous data");
                                checkFormerUploadState();                                
                            }else{
                                appendTotxt("\n>> No previous data to be uploaded.");
                            }
                            
                            if(((log_status.checkCustomerNotUploadDate()).trim()).equals(previuos_day.trim())){
                                appendTotxt("\n>>> Prosessing previous customers");
                                uploadPreviousCustomers();                                
                            }else{
                                appendTotxt("\n>> No previous cutomer data to be uploaded.");
                            }
                        }
                  
                        //CHECK UPLOAD STATE FOR PAST DAY
                        if(stated_time.equals(" 09: 30: 20")){
                            appendTotxt("\n>>> System check for connection state");
                            
                            if(((log_status.checkNotUploadDate()).trim()).equals(previuos_day.trim())){
                               
                                appendTotxt("\n>>> Prosessing previous data");
                                checkFormerUploadState();                                
                            }else{
                                appendTotxt("\n>> No previous data to be uploaded.");
                            }
                            if(((log_status.checkCustomerNotUploadDate()).trim()).equals(previuos_day.trim())){
                                
                                appendTotxt("\n>>> Prosessing previous customers");
                                uploadPreviousCustomers();                                
                            }
                        }
                        
                        //START CUSTOMERS UPLOAD PROCESS AT  9:00 PM
                        if(stated_time.equals(" 21: 00: 20")){

                            createConnection();
                            Thread thread = new Thread(){
                                @Override
                                public void run() {
                                    try {
                                        log_area.append("\n> Customer contacts Upload time " + stated_time + "\n> Initializing upload to Indulgence Server Loyalty 002");
                                        sleep(1000);
                                        log_area.append("\n> Calling customer registration function");

                                        if (netIsAvailable() == true) {
                                            //IF INTERNET CONNECTION IS AVAILABLE
                                            RegisterMembersOnline();
                                              
                                        } else {
                                            
                                                DATA_UPLOAD_STATE = log_status.checkCustomerNotUploadDate();
                                                log_status.writeCustomerNotUploadDate(date);
                                                if(DATA_UPLOAD_STATE == null){                                                
                                                        log_status.writeCustomerNotUploadLog(date + " " + stated_time + " <*> Internet Interruption. Customers data not posted to Indulgence Server");
                                                }else{
                                                        log_status.writeCustomerNotUploadLog(DATA_UPLOAD_STATE +"\n " + date + " " + stated_time + " : Internet Interruption.Customers data not posted to Indulgence Server");
                                                }
                                        }
                                    } catch (InterruptedException ex) {
                                        log_area.append("\n> Interruption error on posting data");
                                        
                                    } catch (IOException ex) {
                                        appendTotxt("IOException " + ex);
                                    }
                                }       
                            };
                            thread.start();
                        }
                        
                        int myMonth = month + 1;
                        if (myMonth == 13) {
                            myMonth = 1;

                            dateLabel.setText(" " + day + "/" + myMonth + "/"
                                    + year);

                            //Transaction time
                            date =  year + myMonth + day + "";
                           
                            sleep(1000);
                        } else {
                            dateLabel.setText(" " + day + " / " + myMonth + " / "
                                    + year+" ");

                            //Transaction time                            
                            date =  year +"-"+ myMonth +"-"+ day;
                            previuos_day =  year +"-"+ myMonth +"-"+ (day - 1);
                            next_day = year +"-"+ myMonth +"-"+ (day + 1);
                            sleep(1000);
                        }

                    }
                } catch (InterruptedException e) {
                    // TODO Auto-generated catch block
                    log_area.append("<<*>> " + "Timer upset....reset");
                } catch (IOException ex) {
                    log_area.append("Exception :: " + ex);
                }
            }
        };
        thread.start();

    }
    
    public void time_setter(){
        //String hrs = hrs_field.getText().toString();
        //String min = min_field.getText().toString();
        //String sec = sec_field.getText().toString();
        
        //New_Time = " " + hrs + ": " + min +": " + sec;
        //upload_time_txt.setText(New_Time);
       
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel1 =  new JPanel() {
            BufferedImage img;
            {
                try {
                    img = ImageIO.read(getClass().getResource("water.jpg"));
                } catch (IOException ex) {  ex.printStackTrace(); }
            }

            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                g.drawImage(img, 0, 0, getWidth(), getHeight(), this);

            }
        };
        jPanel2 = new javax.swing.JPanel();
        jLabel7 = new javax.swing.JLabel();
        jLabel8 = new javax.swing.JLabel();
        logo = new javax.swing.JLabel();
        jButton1 = new javax.swing.JButton();
        upload_time_txt = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        connectPanel = new javax.swing.JPanel();
        connectionTxt = new javax.swing.JLabel();
        jScrollPane1 = new javax.swing.JScrollPane();
        log_area = new javax.swing.JTextArea();
        dateLabel = new javax.swing.JLabel();
        timeLabel = new javax.swing.JLabel();
        jTabbedPane2 = new javax.swing.JTabbedPane();
        jScrollPane2 = new javax.swing.JScrollPane();
        general_table = new javax.swing.JTable();
        jScrollPane3 = new javax.swing.JScrollPane();
        customers_tbl = new javax.swing.JTable();
        jScrollPane4 = new javax.swing.JScrollPane();
        item_trans_table = new javax.swing.JTable();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("Jade Collection Loyalty Bridge");
        setBackground(new java.awt.Color(255, 255, 255));
        setForeground(java.awt.Color.white);

        jPanel1.setBackground(new java.awt.Color(0, 0, 0));

        jPanel2.setBackground(new java.awt.Color(255, 255, 255));
        jPanel2.setOpaque(false);

        jLabel7.setFont(new java.awt.Font("Gotham Rounded Bold", 0, 20)); // NOI18N
        jLabel7.setForeground(new java.awt.Color(0, 153, 0));
        jLabel7.setText("Jade Collection");

        jLabel8.setFont(new java.awt.Font("Yu Gothic UI Semibold", 2, 11)); // NOI18N
        jLabel8.setForeground(new java.awt.Color(255, 0, 0));
        jLabel8.setText("by Indulgence Marketing");

        logo.setHorizontalAlignment(javax.swing.SwingConstants.TRAILING);
        logo.setIcon(new javax.swing.ImageIcon(getClass().getResource("/jade/bridge/jade.png"))); // NOI18N
        logo.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                logoMouseClicked(evt);
            }
        });

        jButton1.setBackground(new java.awt.Color(255, 255, 255));
        jButton1.setFont(new java.awt.Font("Trajan Pro 3", 1, 12)); // NOI18N
        jButton1.setText("Manual Input");
        jButton1.setOpaque(false);
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        upload_time_txt.setFont(new java.awt.Font("Trajan Pro 3", 1, 14)); // NOI18N
        upload_time_txt.setForeground(new java.awt.Color(32, 0, 255));
        upload_time_txt.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(upload_time_txt, javax.swing.GroupLayout.PREFERRED_SIZE, 212, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel7)
                            .addComponent(jLabel8, javax.swing.GroupLayout.PREFERRED_SIZE, 126, javax.swing.GroupLayout.PREFERRED_SIZE))
                        .addGap(18, 18, 18)
                        .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                .addGap(8, 8, 8)
                .addComponent(logo, javax.swing.GroupLayout.PREFERRED_SIZE, 0, Short.MAX_VALUE))
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(logo, javax.swing.GroupLayout.PREFERRED_SIZE, 103, javax.swing.GroupLayout.PREFERRED_SIZE)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addComponent(jLabel7)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jLabel8))
                    .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addGap(33, 33, 33)
                .addComponent(upload_time_txt, javax.swing.GroupLayout.DEFAULT_SIZE, 0, Short.MAX_VALUE)
                .addContainerGap())
        );

        jLabel4.setFont(new java.awt.Font("Trajan Pro 3", 1, 14)); // NOI18N
        jLabel4.setForeground(new java.awt.Color(255, 255, 255));
        jLabel4.setText("Status Log : ");

        connectPanel.setBackground(new java.awt.Color(255, 0, 0));

        connectionTxt.setFont(new java.awt.Font("Arial", 1, 11)); // NOI18N
        connectionTxt.setForeground(new java.awt.Color(255, 255, 255));
        connectionTxt.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        connectionTxt.setText("Connection Status");

        javax.swing.GroupLayout connectPanelLayout = new javax.swing.GroupLayout(connectPanel);
        connectPanel.setLayout(connectPanelLayout);
        connectPanelLayout.setHorizontalGroup(
            connectPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, connectPanelLayout.createSequentialGroup()
                .addGap(59, 59, 59)
                .addComponent(connectionTxt, javax.swing.GroupLayout.DEFAULT_SIZE, 511, Short.MAX_VALUE)
                .addGap(60, 60, 60))
        );
        connectPanelLayout.setVerticalGroup(
            connectPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(connectionTxt, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 24, Short.MAX_VALUE)
        );

        log_area.setEditable(false);
        log_area.setColumns(20);
        log_area.setRows(5);
        log_area.setOpaque(false);
        jScrollPane1.setViewportView(log_area);

        dateLabel.setFont(new java.awt.Font("Trajan Pro 3", 1, 12)); // NOI18N
        dateLabel.setForeground(new java.awt.Color(255, 255, 255));
        dateLabel.setHorizontalAlignment(javax.swing.SwingConstants.RIGHT);
        dateLabel.setText("Date");

        timeLabel.setFont(new java.awt.Font("Trajan Pro 3", 1, 14)); // NOI18N
        timeLabel.setForeground(new java.awt.Color(255, 255, 255));
        timeLabel.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        timeLabel.setText("time");

        jTabbedPane2.setBackground(new java.awt.Color(255, 255, 255));
        jTabbedPane2.setFont(new java.awt.Font("Trajan Pro", 1, 11)); // NOI18N

        jScrollPane2.setBackground(new java.awt.Color(255, 255, 255));
        jScrollPane2.setOpaque(false);

        general_table.setFont(new java.awt.Font("Tahoma", 0, 10)); // NOI18N
        general_table.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null}
            },
            new String [] {
                "Title 1", "Title 2", "Title 3", "Title 4"
            }
        ));
        general_table.setEnabled(false);
        general_table.setOpaque(false);
        jScrollPane2.setViewportView(general_table);

        jTabbedPane2.addTab("Transactions Data Table", jScrollPane2);

        jScrollPane3.setBackground(new java.awt.Color(255, 255, 255));

        customers_tbl.setFont(new java.awt.Font("Tahoma", 0, 10)); // NOI18N
        customers_tbl.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null}
            },
            new String [] {
                "Title 1", "Title 2", "Title 3", "Title 4"
            }
        ));
        customers_tbl.setGridColor(new java.awt.Color(239, 239, 239));
        jScrollPane3.setViewportView(customers_tbl);

        jTabbedPane2.addTab("Customers Data Table", jScrollPane3);

        item_trans_table.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null}
            },
            new String [] {
                "Title 1", "Title 2", "Title 3", "Title 4"
            }
        ));
        jScrollPane4.setViewportView(item_trans_table);

        jTabbedPane2.addTab("Item Transaction Data", jScrollPane4);

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel4)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(timeLabel, javax.swing.GroupLayout.PREFERRED_SIZE, 116, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(110, 110, 110)
                        .addComponent(dateLabel, javax.swing.GroupLayout.PREFERRED_SIZE, 129, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(56, 56, 56))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addComponent(jTabbedPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 620, Short.MAX_VALUE)
                                .addGap(10, 10, 10))
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 628, Short.MAX_VALUE)
                                .addGap(2, 2, 2)))
                        .addContainerGap())))
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(connectPanel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(11, 11, 11)))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addComponent(jPanel2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel4)
                    .addComponent(dateLabel)
                    .addComponent(timeLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 170, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(connectPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jTabbedPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 170, Short.MAX_VALUE)
                .addGap(3, 3, 3))
        );

        jPanel2.getAccessibleContext().setAccessibleName("");

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(0, 0, 0))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );

        setBounds(0, 0, 656, 548);
    }// </editor-fold>//GEN-END:initComponents

    private void jButton1ActionPerformed(ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        Thread thread = new Thread(){
            @Override
            public void run() {
                 Force_Push_Data s = new Force_Push_Data();
                 s.setVisible(true);
            }            
        };
        thread.start();        
    }//GEN-LAST:event_jButton1ActionPerformed

    private void logoMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_logoMouseClicked
        // TODO add your handling code here:
          
            try {
                log_area.append("\n> Adding Item transaction data;");
               add_items_trans();
            } catch (Exception x) {
                log_area.append("\n> Interruption error on Collecting data \n Error:>>" + x);

            }finally{
             JOptionPane.showMessageDialog(null, "Process successful.");
            }
        
    }//GEN-LAST:event_logoMouseClicked

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(JadeBridge.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(JadeBridge.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(JadeBridge.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(JadeBridge.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                try {
                        new JadeBridge().setVisible(true);
                  
                } catch (ClassNotFoundException | InstantiationException | IllegalAccessException |InterruptedException ex) {
                    Logger.getLogger(JadeBridge.class.getName()).log(Level.SEVERE, null, ex);
                } catch (IOException ex) {
                    Logger.getLogger(JadeBridge.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel connectPanel;
    private javax.swing.JLabel connectionTxt;
    private javax.swing.JTable customers_tbl;
    private javax.swing.JLabel dateLabel;
    private javax.swing.JTable general_table;
    private javax.swing.JTable item_trans_table;
    private javax.swing.JButton jButton1;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JScrollPane jScrollPane4;
    private javax.swing.JTabbedPane jTabbedPane2;
    private javax.swing.JTextArea log_area;
    private javax.swing.JLabel logo;
    private javax.swing.JLabel timeLabel;
    private javax.swing.JLabel upload_time_txt;
    // End of variables declaration//GEN-END:variables


}
