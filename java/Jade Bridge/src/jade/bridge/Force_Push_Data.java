/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package jade.bridge;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Toolkit;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import javax.swing.JOptionPane;
import javax.swing.JPanel;


/**
 *
 * @author Granson
 */
public final class Force_Push_Data extends javax.swing.JFrame {
    
    String from_date ,to_date,ip = "1.1.1.1";
    Rest_Requester requester = new Rest_Requester();
    String New_Time = "nothing";
    String Query_Type = null;
    Connection conn = null;
    /**
     * Creates new form Force_Push_Data
     */
    public Force_Push_Data() {
        initComponents();
        
        this.setIconImage(Toolkit.getDefaultToolkit().getImage(getClass().getResource("circle.png")));
        
        if (netIsAvailable() == true) {
            connectPanel.setBackground(Color.GREEN);
            connectionTxt.setText("Connected");
            GetConnectionStatus();
            createConnection();
        } else {
            connectPanel.setBackground(Color.RED);
            connectionTxt.setText("Disonnected");
            log_area.append(">> Connection failure\n>> Check your connectivity and refresh...\n");
            GetConnectionStatus();
            createConnection();
        }
        
    }
    
    public void appendTotxt(String word){    
        log_area.append(word + "\n");
    }
    
    public void createConnection(){
            try {
            // TODO add your handling code here:
            //add_General_Data();
            conn = SqlConnector.newConnection();
                    
            if(conn == null){
                log_area.append(">> SQL onnection failure.\n>> Contact support for assistance...\n");
            }else{
            
                 log_area.append("\n> Local SQL Connection success...");
            }
        } catch (ClassNotFoundException | InstantiationException | IllegalAccessException ex) {
            Logger.getLogger(JadeBridge.class.getName()).log(Level.SEVERE, null, ex);
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
                      
    //Upload data to the Indulgence server
    private void RegisterMembersOnline(){
        Thread thread = new Thread(){
            @Override
            public void run() {
                    String Sql,account_id,customer_id,name,phone,email,entry_date,last_visit;

                    Sql = "SELECT * FROM Customer WHERE LastVisit >= ' "+ from_date +"' AND LastVisit < '" + to_date + "'" ;

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
            } catch (SQLException ex) {
                ex.printStackTrace();
                log_area.append("\n> SQLException Error Logs...\n" + ex);
            }
            return phone;
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
        
        Thread thread = new Thread(){
            @Override
            public void run() {
                                
                String Sql,store_id ,transaction_number,transaction_time,customer_id,customer_phone,total_spend,cashier_id,ItemID,SalesRepID,Quantity;
        ArrayList<String> result = new ArrayList<>();        
                Sql = "SELECT * FROM dbo.[Transaction] WHERE Time >= ' "+ from_date +"' AND Time < '" + to_date + "'" ;


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
                            //ITEM DETAILS
                            result = GetPurchasedItem(transaction_number);
                            if( result == null){
                                ItemID = null;
                                SalesRepID = null;
                                Quantity = null;
                            }else{
                                log_area.append("Size is: " + result.size());    
                                ItemID = result.get(0);
                                SalesRepID = result.get(1);
                                Quantity = result.get(2);
                            }
                            result.clear();

                            log_area.append("\n> Output from Server ...");
                            log_area.append("\n> The Query : store_id : " + store_id + " transaction_number : " + transaction_number + " transaction_time : " + transaction_time + " customer_id : " + customer_id + " total_spend : " + total_spend + " cashier_id : " + cashier_id + " customer_phone : " + customer_phone);
                            String responce = requester.RestProtocol(store_id,transaction_number,transaction_time,customer_id, total_spend, cashier_id,customer_phone,ItemID,SalesRepID,Quantity);
                            log_area.append("\n> " + responce + "...");
                            //log_area.append("\n Test For the pushed data \n  ~ " + store_id + " " + transaction_number + " " + transaction_time + " " + customer_id + " " + total_spend + " " + cashier_id + " " + customer_phone + " " + ItemID + " " + SalesRepID + " " + Quantity);
                        }

                    } catch (SQLException ex) {
                        log_area.append("\n> SQLException Data entry error occured.Check Logs...\n" + ex);
                    }
            }
            
        };
        thread.start();
    }        

    public void methodBeingCalled(){    
            try {

                DateFormat date_format = new SimpleDateFormat("yyyy-MM-dd");
                from_date = date_format.format(jdate_from.getDate());
                to_date = date_format.format(jdate_to.getDate());
                
                if(transaction_btn.isSelected()){
                    Query_Type = "transaction";
                    PostDataOnline();

                }else if(customer_btn.isSelected()){
                    Query_Type = "customer";
                    RegisterMembersOnline();   

                    }else{
                    JOptionPane.showMessageDialog(null, "Please indicate the data to upload");

                }
            } catch (Exception e) {
          JOptionPane.showMessageDialog(null, "You have not indicated the required dates.");   
        }
    }
    
    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        upload_group = new javax.swing.ButtonGroup();
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
        jLabel1 = new javax.swing.JLabel();
        connectPanel = new javax.swing.JPanel();
        connectionTxt = new javax.swing.JLabel();
        transaction_btn = new javax.swing.JRadioButton();
        customer_btn = new javax.swing.JRadioButton();
        jdate_from = new com.toedter.calendar.JDateChooser();
        jdate_to = new com.toedter.calendar.JDateChooser();
        jButton1 = new javax.swing.JButton();
        jScrollPane1 = new javax.swing.JScrollPane();
        log_area = new javax.swing.JTextArea();
        jLabel3 = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Jade Collection Loyalty Bridge");
        setBackground(new java.awt.Color(255, 255, 255));

        jPanel1.setBackground(new java.awt.Color(51, 51, 51));

        jLabel1.setFont(new java.awt.Font("Kaushan Script", 0, 14)); // NOI18N
        jLabel1.setForeground(new java.awt.Color(255, 255, 255));
        jLabel1.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel1.setText("Force Push Data");

        connectPanel.setBackground(new java.awt.Color(255, 0, 0));

        connectionTxt.setFont(new java.awt.Font("Kaushan Script", 0, 14)); // NOI18N
        connectionTxt.setForeground(new java.awt.Color(255, 255, 255));
        connectionTxt.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        connectionTxt.setText("Connection Status");

        javax.swing.GroupLayout connectPanelLayout = new javax.swing.GroupLayout(connectPanel);
        connectPanel.setLayout(connectPanelLayout);
        connectPanelLayout.setHorizontalGroup(
            connectPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(connectionTxt, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        connectPanelLayout.setVerticalGroup(
            connectPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(connectPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(connectionTxt)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        transaction_btn.setBackground(new java.awt.Color(255, 255, 255));
        upload_group.add(transaction_btn);
        transaction_btn.setFont(new java.awt.Font("Kaushan Script", 0, 14)); // NOI18N
        transaction_btn.setForeground(new java.awt.Color(255, 255, 255));
        transaction_btn.setText("Upload Transactions Data:");

        customer_btn.setBackground(new java.awt.Color(255, 255, 255));
        upload_group.add(customer_btn);
        customer_btn.setFont(new java.awt.Font("Kaushan Script", 0, 14)); // NOI18N
        customer_btn.setForeground(new java.awt.Color(255, 255, 255));
        customer_btn.setText("Upload Customer Data");

        jButton1.setFont(new java.awt.Font("Kaushan Script", 0, 14)); // NOI18N
        jButton1.setText("Submit Data");
        jButton1.setOpaque(false);
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        log_area.setColumns(20);
        log_area.setRows(5);
        jScrollPane1.setViewportView(log_area);

        jLabel3.setFont(new java.awt.Font("Kaushan Script", 0, 12)); // NOI18N
        jLabel3.setForeground(new java.awt.Color(255, 255, 255));
        jLabel3.setText("Date From:");

        jLabel4.setFont(new java.awt.Font("Kaushan Script", 0, 12)); // NOI18N
        jLabel4.setForeground(new java.awt.Color(255, 255, 255));
        jLabel4.setText("Date to:");

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jLabel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addComponent(connectPanel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                .addComponent(jdate_from, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                .addComponent(transaction_btn, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                            .addComponent(jLabel3))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 54, Short.MAX_VALUE)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addComponent(jdate_to, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 172, Short.MAX_VALUE)
                            .addComponent(jLabel4)
                            .addComponent(customer_btn, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                        .addGap(33, 33, 33))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addContainerGap())))
            .addComponent(jScrollPane1, javax.swing.GroupLayout.Alignment.TRAILING)
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addComponent(jLabel1, javax.swing.GroupLayout.PREFERRED_SIZE, 26, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(connectPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(transaction_btn)
                    .addComponent(customer_btn))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel3)
                    .addComponent(jLabel4))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jdate_from, javax.swing.GroupLayout.DEFAULT_SIZE, 31, Short.MAX_VALUE)
                    .addComponent(jdate_to, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jButton1, javax.swing.GroupLayout.PREFERRED_SIZE, 37, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 330, Short.MAX_VALUE))
        );

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
          methodBeingCalled();  
    }//GEN-LAST:event_jButton1ActionPerformed

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
            java.util.logging.Logger.getLogger(Force_Push_Data.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(Force_Push_Data.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(Force_Push_Data.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(Force_Push_Data.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                
                new Force_Push_Data().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel connectPanel;
    private javax.swing.JLabel connectionTxt;
    private javax.swing.JRadioButton customer_btn;
    private javax.swing.JButton jButton1;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JScrollPane jScrollPane1;
    private com.toedter.calendar.JDateChooser jdate_from;
    private com.toedter.calendar.JDateChooser jdate_to;
    private javax.swing.JTextArea log_area;
    private javax.swing.JRadioButton transaction_btn;
    private javax.swing.ButtonGroup upload_group;
    // End of variables declaration//GEN-END:variables
}
