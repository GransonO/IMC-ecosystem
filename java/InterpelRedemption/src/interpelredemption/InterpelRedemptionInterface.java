/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interpelredemption;

import java.awt.Color;
import java.awt.Toolkit;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import static java.lang.Thread.sleep;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;

/**
 *
 * @author Granson
 */
public class InterpelRedemptionInterface extends javax.swing.JFrame {

    int dayDate;
    Connection conn;
    String Time, Date, trans_Id1, trans_Id2, Total_Points, Trans_Id;

    /**
     * Creates new form InterpelRedemptionInterface
     */
    public InterpelRedemptionInterface() {
        initComponents();
        
        this.setIconImage(Toolkit.getDefaultToolkit().getImage(getClass().getResource("circle.png")));
        //Gets connection to the azure database
        conn = AzureConnectionClass.newConnection();

        clockWork();

        //Checks for network availability and the users ip
        if (netIsAvailable() == true) {
            statusTxt.setText("Connected");
            statusTxt.setForeground(Color.GREEN);
            GetConnectionStatus();
        } else {
            statusTxt.setText("Disconnected");
            statusTxt.setForeground(Color.RED);
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

            String ip = in.readLine(); //you get the IP as a String
            stsTxt.setText("your current ip is: " + ip + ",   Current operations performed by : Aisha");            
        } catch (MalformedURLException ex) {
            Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ex) {
            Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Get day of the week
    private void getDay() {
        switch (dayDate) {
            case 2:
                dayLabel.setText("Monday");
                break;
            case 3:
                dayLabel.setText("Tuesday");
                break;
            case 4:
                dayLabel.setText("Wednesday");
                break;
            case 5:
                dayLabel.setText("Thursday");
                break;
            case 6:
                dayLabel.setText("Friday");
                break;
            case 7:
                dayLabel.setText("Saturday");
                break;
            case 1:
                dayLabel.setText("Sunday");
                break;
        }
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
                        getDay();

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
                        timeLabel.setText(" " + hr + hour + ": " + min + minute
                                + ": " + sec + seconds);
                        trans_Id2 = " " + hr + hour + min + minute
                                + sec + seconds;

                        int myMonth = month + 1;
                        if (myMonth == 13) {
                            myMonth = 1;

                            dateLabel.setText(" " + day + "/" + myMonth + "/"
                                    + year);

                            //Transaction time
                            Date =  year + myMonth + day + "";
                            trans_Id1 = "" + day + myMonth + year;
                            sleep(1000);
                        } else {
                            dateLabel.setText(" " + day + "/" + myMonth + "/"
                                    + year);

                            //Transaction time                            
                            Date =  year +"-"+ myMonth +"-"+ day;
                            trans_Id1 = "" + day + myMonth + year;
                            sleep(1000);
                        }

                    }
                } catch (InterruptedException e) {
                    // TODO Auto-generated catch block
                    JOptionPane.showMessageDialog(null, e);
                }
            }
        };
        thread.start();

    }

    //Insert data to redemption database
    public void insertToredemptionDB() {
        try {
            String query = "insert into RedemptionTB (RR_Log_CardRefNo,RR_Log_Points_Rdm,RR_Log_RdmTrxNo,RR_Log_Rdm_Date) values(?,?,?,?)";
            PreparedStatement ps = conn.prepareStatement(query);

            Trans_Id = trans_Id1 + trans_Id2;//Transaction Identifier
            ps.setString(1, MembersCodeTxt.getText().trim());
            ps.setString(2, RedeemedPointsEdit.getText().trim());
            ps.setString(3, Trans_Id);
            ps.setString(4, Date);

            ps.execute();

            JOptionPane.showMessageDialog(null,
                    "Redemption Submitted successfully !!!");
        } catch (SQLException ex) {
            Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(Level.SEVERE, null, ex);
            JOptionPane.showMessageDialog(null,
                    "Submitt failed !!!\n" + ex);
            ex.printStackTrace();
        }
    }

    //Get data from the Transaction Table
    public void getDataFromTheTarnsactioDB() {

        if (MembersCodeTxt.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "Please enter an identification card Number");
        } else {
            try {
                String Sql = "select * from Interpel_Database_File where [Customer Id] = '" + MembersCodeTxt.getText().trim() + "'";

                PreparedStatement ps = conn.prepareStatement(Sql);
                ResultSet Rs = ps.executeQuery();
                while (Rs.next()) {
                    Total_Points = Rs.getString("Loyalty Value");
                    ClientNameEdit.setText(Rs.getString("Customer Name"));
                    PointsAmount.setText(Total_Points);

                }

            } catch (SQLException ex) {
                Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(Level.SEVERE, null, ex);
                JOptionPane.showMessageDialog(null, ex);
            }
        }
    }

    //Subtracts points from the redeemed table
    public void transact_Data() {

        if (RedeemedPointsEdit.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "Please enter the points to be redeemed points");
        } else {
            int initial_points = Integer.parseInt(Total_Points.trim());
            int redeemed_points = Integer.parseInt(RedeemedPointsEdit.getText());
            if (initial_points < redeemed_points) {
                JOptionPane.showMessageDialog(null, "Request can't be processed\n The redeemed points is greater than the available points");
            } else {
                try {
                    int final_points = initial_points - redeemed_points;

                    String Update_Data = "Update Interpel_Database_File set [Loyalty Value] =('" + final_points + "')where [Customer Id] = '" + MembersCodeTxt.getText() + "'";
                    PreparedStatement updatePs = conn.prepareStatement(Update_Data);
                    updatePs.execute();

                } catch (SQLException ex) {
                    Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(Level.SEVERE, null, ex);
                } finally {
                    insertToredemptionDB();
                    TransactionDetails();
                    getDataFromTheTarnsactioDB();
                    
                    MembersCodeTxt.setText("");
                    ClientNameEdit.setText("");
                }
            }
        }
    }

    public void TransactionDetails() {
        JOptionPane.showMessageDialog(null, "Transaction id " + Trans_Id + "\n Redemption amount: " + RedeemedPointsEdit.getText().trim(), ClientNameEdit.getText().trim() + " redemption", 0);
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel6 = new javax.swing.JPanel();
        jLabel12 = new javax.swing.JLabel();
        MembersCodeTxt = new javax.swing.JTextField();
        jLabel11 = new javax.swing.JLabel();
        PointsAmount = new javax.swing.JLabel();
        jPanel4 = new javax.swing.JPanel();
        jLabel13 = new javax.swing.JLabel();
        jLabel15 = new javax.swing.JLabel();
        ClientNameEdit = new javax.swing.JTextField();
        RedeemedPointsEdit = new javax.swing.JTextField();
        jButton2 = new javax.swing.JButton();
        jButton3 = new javax.swing.JButton();
        Submit_Code_Btn = new javax.swing.JButton();
        jLabel7 = new javax.swing.JLabel();
        statusTxt = new javax.swing.JLabel();
        jButton1 = new javax.swing.JButton();
        jPanel5 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        jLabel3 = new javax.swing.JLabel();
        dateLabel = new javax.swing.JLabel();
        dayLabel = new javax.swing.JLabel();
        timeLabel = new javax.swing.JLabel();
        stsTxt = new javax.swing.JLabel();
        logo = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("INTERPEL TRANSACTOR POINT");
        setResizable(false);

        jPanel6.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel12.setFont(new java.awt.Font("sansserif", 1, 24)); // NOI18N
        jLabel12.setText(" Card Identifier :");

        MembersCodeTxt.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N

        jLabel11.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel11.setText("Point as on : (Last transaction date)");

        PointsAmount.setFont(new java.awt.Font("Adobe Hebrew", 1, 60)); // NOI18N
        PointsAmount.setText("00");

        jPanel4.setBorder(javax.swing.BorderFactory.createTitledBorder("Clients Data"));

        jLabel13.setFont(new java.awt.Font("sansserif", 3, 14)); // NOI18N
        jLabel13.setText("Name :");

        jLabel15.setFont(new java.awt.Font("sansserif", 3, 14)); // NOI18N
        jLabel15.setText("Redemption Points:");

        ClientNameEdit.setEditable(false);
        ClientNameEdit.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                ClientNameEditActionPerformed(evt);
            }
        });

        jButton2.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton2.setText("Redeem Points");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        jButton3.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton3.setText("Cancel");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel4Layout = new javax.swing.GroupLayout(jPanel4);
        jPanel4.setLayout(jPanel4Layout);
        jPanel4Layout.setHorizontalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel4Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addComponent(jLabel13)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(ClientNameEdit, javax.swing.GroupLayout.PREFERRED_SIZE, 229, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(0, 0, Short.MAX_VALUE))
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addComponent(jLabel15)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(RedeemedPointsEdit))
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addComponent(jButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jButton3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addContainerGap())))
        );
        jPanel4Layout.setVerticalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel13)
                    .addComponent(ClientNameEdit, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(RedeemedPointsEdit, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel15))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jButton2, javax.swing.GroupLayout.DEFAULT_SIZE, 47, Short.MAX_VALUE)
                    .addComponent(jButton3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap())
        );

        Submit_Code_Btn.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        Submit_Code_Btn.setText("Submit");
        Submit_Code_Btn.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                Submit_Code_BtnActionPerformed(evt);
            }
        });

        jLabel7.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel7.setText("Status :");

        statusTxt.setFont(new java.awt.Font("sansserif", 1, 24)); // NOI18N
        statusTxt.setText("-----------");

        jButton1.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton1.setText("Reset Connection");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel6Layout = new javax.swing.GroupLayout(jPanel6);
        jPanel6.setLayout(jPanel6Layout);
        jPanel6Layout.setHorizontalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                    .addGroup(jPanel6Layout.createSequentialGroup()
                        .addComponent(jLabel12)
                        .addGap(18, 18, 18)
                        .addComponent(MembersCodeTxt)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(Submit_Code_Btn, javax.swing.GroupLayout.PREFERRED_SIZE, 74, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(4, 4, 4))
                    .addGroup(jPanel6Layout.createSequentialGroup()
                        .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                            .addGroup(jPanel6Layout.createSequentialGroup()
                                .addComponent(jLabel7)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(statusTxt, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                    .addGroup(jPanel6Layout.createSequentialGroup()
                                        .addComponent(jButton1)
                                        .addGap(0, 0, Short.MAX_VALUE))))
                            .addComponent(PointsAmount, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jLabel11, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel4, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))))
        );
        jPanel6Layout.setVerticalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(Submit_Code_Btn, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                        .addComponent(MembersCodeTxt, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addComponent(jLabel12)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel6Layout.createSequentialGroup()
                        .addComponent(jLabel11, javax.swing.GroupLayout.PREFERRED_SIZE, 32, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(PointsAmount, javax.swing.GroupLayout.PREFERRED_SIZE, 47, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(jLabel7)
                            .addComponent(statusTxt))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addContainerGap())
                    .addGroup(jPanel6Layout.createSequentialGroup()
                        .addComponent(jPanel4, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(0, 0, Short.MAX_VALUE))))
        );

        jPanel5.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel1.setFont(new java.awt.Font("sansserif", 1, 16)); // NOI18N
        jLabel1.setText("Day:");

        jLabel2.setFont(new java.awt.Font("sansserif", 1, 16)); // NOI18N
        jLabel2.setText("Date:");

        jLabel3.setFont(new java.awt.Font("sansserif", 1, 16)); // NOI18N
        jLabel3.setText("Time:");

        dateLabel.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        dateLabel.setText("11/12/2016");

        dayLabel.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        dayLabel.setText("Wednesday");

        timeLabel.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        timeLabel.setText("12:00 pm");

        javax.swing.GroupLayout jPanel5Layout = new javax.swing.GroupLayout(jPanel5);
        jPanel5.setLayout(jPanel5Layout);
        jPanel5Layout.setHorizontalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel5Layout.createSequentialGroup()
                .addContainerGap(8, Short.MAX_VALUE)
                .addComponent(jLabel2)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(dateLabel)
                .addGap(89, 89, 89)
                .addComponent(jLabel1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(dayLabel)
                .addGap(96, 96, 96)
                .addComponent(jLabel3)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(timeLabel)
                .addGap(25, 25, 25))
        );
        jPanel5Layout.setVerticalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel5Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(jLabel1)
                    .addComponent(jLabel3)
                    .addComponent(dateLabel)
                    .addComponent(dayLabel)
                    .addComponent(timeLabel)))
        );

        stsTxt.setText("tuury");

        logo.setIcon(new javax.swing.ImageIcon(getClass().getResource("/interpelredemption/interpellogo.png"))); // NOI18N

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel5, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jPanel6, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(layout.createSequentialGroup()
                        .addComponent(stsTxt, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addContainerGap())))
            .addGroup(layout.createSequentialGroup()
                .addGap(166, 166, 166)
                .addComponent(logo)
                .addGap(0, 0, Short.MAX_VALUE))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addComponent(jPanel5, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(logo, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel6, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(stsTxt)
                .addContainerGap())
        );

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        if (netIsAvailable() == true) {
            statusTxt.setText("Connected");
            statusTxt.setForeground(Color.GREEN);
            GetConnectionStatus();
        } else {
            statusTxt.setText("Disconnected");
            statusTxt.setForeground(Color.RED);
        }
    }//GEN-LAST:event_jButton1ActionPerformed

    private void Submit_Code_BtnActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_Submit_Code_BtnActionPerformed
        // TODO add your handling code here:

        getDataFromTheTarnsactioDB();
    }//GEN-LAST:event_Submit_Code_BtnActionPerformed

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // TODO add your handling code here:
        
        if(MembersCodeTxt.getText().equals("")){
            JOptionPane.showMessageDialog(null, "No target party data entered");
        }else{
                int response = JOptionPane.showConfirmDialog(null, "Redeem "+ RedeemedPointsEdit.getText()+" for " + MembersCodeTxt.getText(), "Perform operation",
                JOptionPane.YES_NO_OPTION, JOptionPane.QUESTION_MESSAGE);

        if (response == JOptionPane.NO_OPTION) {

            JOptionPane.showMessageDialog(null, "Operation cancelled ...");

        } else if (response == JOptionPane.YES_OPTION) {

        transact_Data();       
            
        } else if (response == JOptionPane.CLOSED_OPTION) {
        }
    }
    }//GEN-LAST:event_jButton2ActionPerformed

    private void ClientNameEditActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_ClientNameEditActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_ClientNameEditActionPerformed

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        // TODO add your handling code here:
        
    }//GEN-LAST:event_jButton3ActionPerformed

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
            java.util.logging.Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(InterpelRedemptionInterface.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new InterpelRedemptionInterface().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTextField ClientNameEdit;
    private javax.swing.JTextField MembersCodeTxt;
    private javax.swing.JLabel PointsAmount;
    private javax.swing.JTextField RedeemedPointsEdit;
    private javax.swing.JButton Submit_Code_Btn;
    private javax.swing.JLabel dateLabel;
    private javax.swing.JLabel dayLabel;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton2;
    private javax.swing.JButton jButton3;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel11;
    private javax.swing.JLabel jLabel12;
    private javax.swing.JLabel jLabel13;
    private javax.swing.JLabel jLabel15;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JPanel jPanel4;
    private javax.swing.JPanel jPanel5;
    private javax.swing.JPanel jPanel6;
    private javax.swing.JLabel logo;
    private javax.swing.JLabel statusTxt;
    private javax.swing.JLabel stsTxt;
    private javax.swing.JLabel timeLabel;
    // End of variables declaration//GEN-END:variables
}
