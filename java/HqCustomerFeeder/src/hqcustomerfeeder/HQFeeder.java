/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package hqcustomerfeeder;

import java.awt.Color;
import java.awt.Toolkit;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
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
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;
import net.proteanit.sql.DbUtils;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

/**
 *
 * @author Granson
 */
public class HQFeeder extends javax.swing.JFrame {

    /**
     * Creates new form HQFeeder
     */
    int dayDate;
    String stated_time ,date,previuos_day,next_day,Total_Points,ip = "1.1.1.1",DATA_UPLOAD_STATE,NETWORK_STATE_LOG;
    Connection conn = null;
    Rest_Requester requester = new Rest_Requester();
    Data_Log log_status = new Data_Log();  
    String New_Time = "nothing";
    SQL_INSERT process_insertion;
    String Store_Name = null;
    
    public HQFeeder() throws InterruptedException, IOException {
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
            
            try {
                Store_Name = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\STORE_ID.txt")));

             } catch (IOException ex) {
                DB_NAME = "nothing";
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
                        if(minute == 55){
                            appendTotxt("\n>>> System check for connection state");
                            checkInternetConnectivity();
                        }
                        
                        //PING SERVER
                        if(stated_time.equals(" 12: 00: 20")){
                            appendTotxt("\n>>> System check for connection state");
                            PingServer(); 
                        }
                  
                        //PING SERVER
                        if(stated_time.equals(" 18: 00: 30")){
                            appendTotxt("\n>>> System check for connection state");
                            PingServer(); 
                        }
                        
                        //Updated table every 60 sec
                        if(seconds == 1200){ 
                            //NEVER RUN THIS QUERY
                            createConnection();
                            Thread thread = new Thread(){
                                @Override
                                public void run() {
                                    try {
                                        log_area.append("\n> Customer contacts Download time " + stated_time + "\n\n> Initializing download from Indulgence Server Loyalty 002 For Jade Collections");
                                        sleep(200);
                                        log_area.append("\n> Calling customer registration function");

                                        if (netIsAvailable() == true) {
                                            //IF INTERNET CONNECTION IS AVAILABLE
                                              
                                            String Results = requester.pullCustomer(1);//CHANGE 1 TO Store_Name
                                            log_area.append("\n >> " + Results);
                                            
                                            JSONParser parser = new JSONParser(); 
                                            JSONObject json = (JSONObject) parser.parse(Results);
                                            
                                            String results_status = (json.get("status")).toString();
                                            String results_code = (json.get("status_code")).toString();
                                            String results_result = (json.get("result")).toString();
                                            
                                            log_area.append("\n >> " + results_status);
                                            log_area.append("\n >> " + results_code);
                                            
                                            results_result = (results_result.replace("[", "")).replace("]", "");
                                            log_area.append("\n >> " + results_result);
                                            
                                            String[] results_array = results_result.split("\",\"");
                                            log_area.append("\n >> Array Length : " + results_array.length);
                                            
                                            if(results_array.length < 1){
                                            
                                            log_area.append("\n\n >> No entry from server to post...");
                                            }else{
                                                
                                                int x = 0;    
                                                while( x < results_array.length ) {

                                                log_area.append("\n\n >> The result : " + results_array[x].replace("\"", ""));
                                                String my_string = results_array[x].replace("\"", "");
                                                String[] processed_array = my_string.split(",");

                                                log_area.append("\n >> Processed Array Length : " + processed_array.length);

                                                    String results_name = processed_array[0];
                                                    String results_email = processed_array[1];
                                                    String results_phone = processed_array[2];
                                                    String results_date = processed_array[3];
                                                    String results_num = processed_array[4];

                                                    log_area.append("\n >> Name : " + results_name);
                                                    log_area.append("\n >> Email : " + results_email);
                                                    log_area.append("\n >> Phone : " + results_phone);
                                                    log_area.append("\n >> Date : " + results_date);
                                                    log_area.append("\n >> Number : " + results_num);


                                                    process_insertion = new SQL_INSERT(results_name, results_email, results_phone, results_date, results_num);
                                                    String Posting_Data = process_insertion.InsertData();
                                                    log_area.append("\n\n >> " + Posting_Data);
                                                    
                                                    add_Customer_Data();
                                                    x++;

                                                }
                                                log_area.append("\n >> Data processing complete.");
                                            } 
                                            
                                            
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
                                        appendTotxt("\n> IOException " + ex);
                                    } catch (ParseException ex) {
                                       appendTotxt("\n> ParseException " + ex);
                                    } catch (SQLException ex) {
                                        Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
                                    } catch (ClassNotFoundException ex) {
                                        Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
                                    } catch (InstantiationException ex) {
                                        Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
                                    } catch (IllegalAccessException ex) {
                                        Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
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
    
    public void call_all_customers(){
                    
        Thread T = new Thread(){
            @Override
            public void run() {
                
                //IF INTERNET CONNECTION IS AVAILABLE
                for(int count = 0;count <=550;count++){
                    
                    int number = count + 1;
                        log_area.append("\n >> A : " + number);
                        
                        
                                String Results = requester.pullCustomer(number);
                                log_area.append("\n >> " + Results);

                                JSONParser parser = new JSONParser();
                                JSONObject json = null;
                                    try {
                                        json = (JSONObject) parser.parse(Results);
                                    } catch (ParseException ex) {
                                        log_area.append("\n >> " + ex);                                       
                                    }

                                String results_status = (json.get("status")).toString();
                                String results_code = (json.get("status_code")).toString();
                                String results_result = (json.get("result")).toString();

                                log_area.append("\n >> " + results_status);
                                log_area.append("\n >> " + results_code);

                                results_result = (results_result.replace("[", "")).replace("]", "");
                                log_area.append("\n >> " + results_result);

                                String[] results_array = results_result.split("\",\"");
                                log_area.append("\n >> Array Length : " + results_array.length);

                                if(results_array.length < 1){

                                    log_area.append("\n\n >> No entry from server to post...");
                                }else{
                                try {
                                    log_area.append("\n\n >> The result : " + results_array[0].replace("\"", ""));
                                    String my_string = results_array[0].replace("\"", "");
                                    String[] processed_array = my_string.split(",");

                                    log_area.append("\n >> Processed Array Length : " + processed_array.length);

                                    String results_name = processed_array[0];
                                    String results_email = processed_array[1];
                                    String results_phone = processed_array[2];
                                    String results_date = processed_array[3];
                                    String results_num = processed_array[4];

                                    log_area.append("\n >> Name : " + results_name);
                                    log_area.append("\n >> Email : " + results_email);
                                    log_area.append("\n >> Phone : " + results_phone);
                                    log_area.append("\n >> Date : " + results_date);
                                    log_area.append("\n >> Number : " + results_num);

                                    process_insertion = new SQL_INSERT(results_name, results_email, results_phone, results_date, results_num);
                                    //String insertion = "Name : " + results_name + " Email : " +  results_email + " Email : " +  results_phone + " Email : " +  results_date + " Email : " +  results_num;
                                    String Posting_Data = process_insertion.InsertData();
                                    log_area.append("\n\n >> " + count + ". " + Posting_Data);

                                } catch (IOException ex) {
                                    log_area.append("\n\n >>Error :" + ex);
                                } catch (SQLException ex) {
                                    log_area.append("\n\n >>Error :" + ex);
                                } catch (ClassNotFoundException ex) {
                                    log_area.append("\n\n >>Error :" + ex);
                                } catch (InstantiationException ex) {
                                    log_area.append("\n\n >>Error :" + ex);
                                } catch (IllegalAccessException ex) {
                                    log_area.append("\n\n >>Error :" + ex);
                                }

                                
                        }
                }
            }
            
        };
        T.start();
        
    }
    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel1 = new javax.swing.JPanel();
        jPanel3 = new javax.swing.JPanel();
        jLabel9 = new javax.swing.JLabel();
        upload_time_txt1 = new javax.swing.JLabel();
        connectPanel = new javax.swing.JPanel();
        connectionTxt = new javax.swing.JLabel();
        jScrollPane1 = new javax.swing.JScrollPane();
        log_area = new javax.swing.JTextArea();
        dateLabel = new javax.swing.JLabel();
        timeLabel = new javax.swing.JLabel();
        jButton2 = new javax.swing.JButton();
        jLabel10 = new javax.swing.JLabel();
        jScrollPane5 = new javax.swing.JScrollPane();
        jScrollPane3 = new javax.swing.JScrollPane();
        customers_tbl = new javax.swing.JTable();
        jDateChooser1 = new com.toedter.calendar.JDateChooser();
        jDateChooser2 = new com.toedter.calendar.JDateChooser();
        jLabel1 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("HQ Customer Data Feeder");

        jPanel1.setBackground(new java.awt.Color(0, 0, 0));

        jPanel3.setBackground(new java.awt.Color(255, 255, 255));
        jPanel3.setOpaque(false);

        jLabel9.setFont(new java.awt.Font("Gotham Rounded Bold", 0, 20)); // NOI18N
        jLabel9.setForeground(new java.awt.Color(0, 153, 0));
        jLabel9.setText("Jade Collection");

        upload_time_txt1.setFont(new java.awt.Font("Trajan Pro 3", 1, 14)); // NOI18N
        upload_time_txt1.setForeground(new java.awt.Color(32, 0, 255));
        upload_time_txt1.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(upload_time_txt1, javax.swing.GroupLayout.PREFERRED_SIZE, 212, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel9))
                .addContainerGap(46, Short.MAX_VALUE))
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addComponent(jLabel9, javax.swing.GroupLayout.PREFERRED_SIZE, 21, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(70, 70, 70)
                .addComponent(upload_time_txt1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );

        connectPanel.setBackground(new java.awt.Color(255, 0, 0));

        connectionTxt.setFont(new java.awt.Font("Gotham Rounded Bold", 0, 11)); // NOI18N
        connectionTxt.setForeground(new java.awt.Color(255, 255, 255));
        connectionTxt.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        connectionTxt.setText("Connection Status");

        javax.swing.GroupLayout connectPanelLayout = new javax.swing.GroupLayout(connectPanel);
        connectPanel.setLayout(connectPanelLayout);
        connectPanelLayout.setHorizontalGroup(
            connectPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, connectPanelLayout.createSequentialGroup()
                .addGap(59, 59, 59)
                .addComponent(connectionTxt, javax.swing.GroupLayout.DEFAULT_SIZE, 112, Short.MAX_VALUE)
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

        jButton2.setBackground(new java.awt.Color(255, 255, 255));
        jButton2.setFont(new java.awt.Font("Tahoma", 1, 12)); // NOI18N
        jButton2.setText("Get Customer Data");
        jButton2.setOpaque(false);
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        jLabel10.setFont(new java.awt.Font("Yu Gothic UI Semibold", 2, 11)); // NOI18N
        jLabel10.setForeground(new java.awt.Color(255, 0, 0));
        jLabel10.setText("by Indulgence Marketing");

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

        jScrollPane5.setViewportView(jScrollPane3);

        jLabel1.setFont(new java.awt.Font("Gotham Rounded Bold", 0, 14)); // NOI18N
        jLabel1.setForeground(new java.awt.Color(255, 255, 255));
        jLabel1.setText("Date From :");

        jLabel2.setFont(new java.awt.Font("Gotham Rounded Bold", 0, 14)); // NOI18N
        jLabel2.setForeground(new java.awt.Color(255, 255, 255));
        jLabel2.setText("Date To :");

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1)
            .addComponent(jScrollPane5)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(timeLabel, javax.swing.GroupLayout.PREFERRED_SIZE, 116, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(dateLabel, javax.swing.GroupLayout.PREFERRED_SIZE, 129, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(56, 56, 56))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel1)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jDateChooser1, javax.swing.GroupLayout.PREFERRED_SIZE, 154, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addComponent(jPanel3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jDateChooser2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                    .addComponent(connectPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 64, Short.MAX_VALUE)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel10, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.PREFERRED_SIZE, 126, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jButton2, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.PREFERRED_SIZE, 185, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(jLabel2)
                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jButton2, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGroup(jPanel1Layout.createSequentialGroup()
                            .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                .addComponent(jPanel3, javax.swing.GroupLayout.PREFERRED_SIZE, 34, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addComponent(connectPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                            .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                .addGroup(jPanel1Layout.createSequentialGroup()
                                    .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                                        .addComponent(jDateChooser1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                                        .addComponent(jLabel1))
                                    .addGap(1, 1, 1))
                                .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                                    .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                                    .addComponent(jDateChooser2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))))))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jLabel10)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(dateLabel)
                    .addComponent(timeLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 77, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane5, javax.swing.GroupLayout.DEFAULT_SIZE, 83, Short.MAX_VALUE))
        );

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(0, 0, 0))
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // Call all customers
        call_all_customers();
    }//GEN-LAST:event_jButton2ActionPerformed

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
            java.util.logging.Logger.getLogger(HQFeeder.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(HQFeeder.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(HQFeeder.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(HQFeeder.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                try {
                    new HQFeeder().setVisible(true);
                } catch (InterruptedException ex) {
                    Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
                } catch (IOException ex) {
                    Logger.getLogger(HQFeeder.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel connectPanel;
    private javax.swing.JLabel connectionTxt;
    private javax.swing.JTable customers_tbl;
    private javax.swing.JLabel dateLabel;
    private javax.swing.JButton jButton2;
    private com.toedter.calendar.JDateChooser jDateChooser1;
    private com.toedter.calendar.JDateChooser jDateChooser2;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel10;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel9;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JScrollPane jScrollPane5;
    private javax.swing.JTextArea log_area;
    private javax.swing.JLabel timeLabel;
    private javax.swing.JLabel upload_time_txt1;
    // End of variables declaration//GEN-END:variables
}
