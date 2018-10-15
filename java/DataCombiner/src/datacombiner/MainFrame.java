/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package datacombiner;

import java.awt.Color;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;
import net.proteanit.sql.DbUtils;

/**
 *
 * @author Granson
 */
public class MainFrame extends javax.swing.JFrame {

    
    /**
     * Creates new form MainFrame
     */
    Connection conn = null;

    public MainFrame() {
        initComponents();
        conn = SqlConnector.newConnection("C:\\Users\\Granson\\Desktop\\TransactFiles\\FilesCombiner.sqlite");
    }

    //<------------------------------------------------------Interpel Programs--------------------------------------------------------------->4
    
    //Inserting Allocation Data
    public void populating_Files_Function() {
        try {
            String data_From_Final_Combined_Interpel_Data = "Select * from Final_Combined_Interpel_Data";

            PreparedStatement Ps = conn.prepareStatement(data_From_Final_Combined_Interpel_Data);
            ResultSet Rs = Ps.executeQuery();
            while (Rs.next()) {
                String Customer_Name = Rs.getString("Customer_Name");

                //Queries For data from the allocation table
                String allocation_Data = "SELECT SUM([Loyalty Value]) AS Sum_Loyalty FROM allocation2016 where [Customer Name] ='" + Customer_Name + "'";
                PreparedStatement Pt = conn.prepareStatement(allocation_Data);
                ResultSet Rt = Pt.executeQuery();
                
                //addData("Final_Combined_Interpel_Data");
                while (Rt.next()) {
                    String final_points = Rt.getString("Sum_Loyalty");
                    //Updates the Final_Combined_Interpel_Data
                    String Update_Data = "Update Final_Combined_Interpel_Data set Loyalty_Value =('" + final_points + "')where Customer_Name = '" + Customer_Name + "'";
                    PreparedStatement updatePs = conn.prepareStatement(Update_Data);
                    updatePs.execute();
                }

            }
        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            JOptionPane.showMessageDialog(null, "I am Done.....");
        }

    }

    //Adding Feb values to the final file
    public void Add_Db_Values() {
        try {
            String data_From_Final_Combined_Interpel_Data = "Select * from Final_Combined_Interpel_Data";

            PreparedStatement ps = conn.prepareStatement(data_From_Final_Combined_Interpel_Data);
            ResultSet Rs = ps.executeQuery();
            while (Rs.next()) {
                String Customer_Id = Rs.getString("Customer_Id");
                int loyalty = Integer.parseInt(Rs.getString("Loyalty_Value"));

                String data_From_FebValue = "SELECT [Loyalty Value] FROM febValue where [Customer Ident] = '" + Customer_Id + "'";

                PreparedStatement pt = conn.prepareStatement(data_From_FebValue);
                ResultSet Rt = pt.executeQuery();
                while (Rt.next()) {
                    int feb_loyalty = Integer.parseInt(Rt.getString("Loyalty Value"));
                    int final_loyalty = feb_loyalty + loyalty;

                    String Update_points = "Update Final_Combined_Interpel_Data set Loyalty_Value =('" + final_loyalty + "')where Customer_Id = '" + Customer_Id + "'";
                    PreparedStatement Update_ps = conn.prepareStatement(Update_points);
                    Update_ps.execute();

                }
            }
        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            JOptionPane.showMessageDialog(null, "I am Done.....");
        }
    }

    //Inserting Db data to the Final Data File    
    //<--------------------------------------------------End of Interpel Programs------------------------------------------------------------>
    //  upload data to the inhouse Database
    public void upload() {
        if (importName.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No import file Path Error");
        } else {
            Thread thread = new Thread() {

                @Override
                public void run() {

                    try {
                        InputStream read = new FileInputStream(importName.getText());
                        BufferedReader br = new BufferedReader(new InputStreamReader(read));

                        String line;
                        loadinglabel.setText("uploading The csv file");
                        loadinglabel.setForeground(Color.red);
                        while ((line = br.readLine().replaceAll("'", "`")) != null) {

                            String[] value = line.split(",");
                            String sql = "Insert into CombinerTable ([Transaction ID],[Transaction Type],[Transaction Date],[Processed Date],"
                                    + "[Merchant Name],[Customer Name],[Customer Ident],[Purchase Value],[Loyalty Value]) "
                                    + "values ('" + value[0] + "','" + value[1] + "','" + value[2] + "','" + value[3] + "','" + value[4] + "','" + value[5] + "','" + value[6]
                                    + "','" + value[7] + "','" + value[8] + "')";
                            PreparedStatement pst = conn.prepareStatement(sql);
                            pst.executeUpdate();
                            // addData();

                        }
                        br.close();
                    } catch (IOException | SQLException e) {
                        JOptionPane.showMessageDialog(null, e + "\nNo file Path Error");

                    } finally {

                        loadinglabel.setText("Done you may resume...");
                        loadinglabel.setForeground(Color.blue);
                    }

                }

            };
            thread.start();

        }
    }

    //Sort out the radical data
    public void sortMeUp() {
        Thread combine = new Thread() {

            @Override
            public void run() {
                String Sql = "SELECT * FROM [Copy of Interpel DB]  where [Transaction Type] = \"Loyalty Allocation\"";//Gets all Entries with the 0 purchase value

                try {

                    loadinglabel.setText("Combining Data in Progress");
                    loadinglabel.setForeground(Color.red);
                    PreparedStatement pst = conn.prepareStatement(Sql);
                    ResultSet rs = pst.executeQuery();

                    while (rs.next()) {

                        String UniqueId = rs.getString("Transaction ID");
                        String Loyalties = rs.getString("Loyalty Value");
                        String Campaign = rs.getString("Campaign Name");
                        String Type = "Purchase & Loyalty Allocation";

                        String Updater = "Update [Copy of Interpel DB] Set \"Transaction Type\" = '" + Type + "',\"Loyalty Value\" = '" + Loyalties + "',\"Campaign Name\" = '" + Campaign + "' where \"Transaction ID\" = '" + UniqueId + "' and \"Transaction Type\" = 'Purchase'";
                        try {
                            PreparedStatement ps = conn.prepareStatement(Updater);
                            ps.execute();
                            // addData();
                        } catch (SQLException ex) {
                            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                        } finally {

                            String Deleter = "Delete from [Copy of Interpel DB] where \"Transaction ID\" = '" + UniqueId + "' and \"Transaction Type\" = 'Loyalty Allocation' ";
                            PreparedStatement psDeleter = conn.prepareStatement(Deleter);
                            psDeleter.execute();
                            //  addData();
                        }

                    }

                } catch (SQLException ex) {

                    Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                } finally {
                    loadinglabel.setText("Done Combining Data ");
                    loadinglabel.setForeground(Color.RED);
                }
            }

        };
        combine.start();

    }

    //Add data to the table
    public void addData() {

        String Sql = "SELECT * FROM Upload_Customer_Points";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                jLabel6.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //Saving final Data
    public void SaveFinalData(String FilePath) {

        String SaveQuery = "Select [Transaction ID],[Transaction Type],[Transaction Date],[Processed Date],"
                + "[Merchant Name],[Customer Name],[Customer Ident],[Purchase Value],[Loyalty Value]  from CombinerTable ";
        try {
            PreparedStatement pst = conn.prepareStatement(SaveQuery);
            ResultSet rs = pst.executeQuery();

            FileWriter writer = new FileWriter(FilePath);
            writer.append("Transaction ID");
            writer.append(',');
            writer.append("Transaction Type");
            writer.append(',');
            writer.append("Transaction Date");
            writer.append(',');
            writer.append("Processed Date");
            writer.append(',');
            writer.append("Merchant Name");
            writer.append(',');
            writer.append("Customer Name");
            writer.append(',');
            writer.append("Customer Ident");
            writer.append(',');
            writer.append("Purchase Value");
            writer.append(',');
            writer.append("Loyalty Value");
            writer.append("\n");

            while (rs.next()) {
                loadinglabel.setText("Exporting Data ");
                loadinglabel.setForeground(Color.blue);

                writer.append(rs.getString("Transaction ID"));
                writer.append(',');
                writer.append(rs.getString("Transaction Type"));
                writer.append(',');
                writer.append(rs.getString("Transaction Date"));
                writer.append(',');
                writer.append(rs.getString("Processed Date"));
                writer.append(',');
                writer.append(rs.getString("Merchant Name"));
                writer.append(',');
                writer.append(rs.getString("Customer Name"));
                writer.append(',');
                writer.append(rs.getString("Customer Ident"));
                writer.append(',');
                writer.append(rs.getString("Purchase Value"));
                writer.append(',');
                writer.append(rs.getString("Loyalty Value"));
                writer.append("\n");

            }
            writer.flush();
        } catch (SQLException | IOException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        } finally {

            loadinglabel.setText("Done Exporting Data ");
            loadinglabel.setForeground(Color.GREEN);
        }
    }

    //Calculate the points and insert campaign name for Interpel files
    public void Points_Generation_For_Interpel_Data() {

        Thread PointsandCampaign = new Thread() {

            @Override
            public void run() {
                String Sql = "SELECT * FROM [Feb 2016-INTERPEL]";//Gets all Entries from the table

                try {

                    loadinglabel.setText("Analysing Interpel Data in Progress");
                    loadinglabel.setForeground(Color.red);
                    PreparedStatement pst = conn.prepareStatement(Sql);
                    ResultSet rs = pst.executeQuery();

                    while (rs.next()) {

                        String Updater = "";
                        String trans_ref = rs.getString("trans_ref");
                        String Campaign1 = "Rewards Campaign 0.20%";
                        String Campaign2 = "Rewards Campaign 0.40%";
                        String Campaign3 = "Rewards Campaign 0.60%";
                        String Campaign4 = "Rewards Campaign 0.80%";
                        String Campaign5 = "Rewards Campaign 1%";
                        String Campaign6 = "Rewards Campaign 1.2%";

                        int trans_amt = (int) Double.parseDouble(rs.getString("trans_amt"));

                        if (trans_amt <= 199999) {
                            //points for values between 1-199,999 

                            int points = (int) (trans_amt * 0.2) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign1 + "' where \"trans_ref\" = '" + trans_ref + "'";
                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                // addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }

                        } else if (199999 < trans_amt && trans_amt <= 399999) {
                            //points for values between 200,000 - 399,999

                            int points = (int) (trans_amt * 0.4) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign2 + "' where \"trans_ref\" = '" + trans_ref + "'";

                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                // addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                        } else if (399999 < trans_amt && trans_amt <= 599999) {
                            //points for values between 400,000 - 599,999

                            int points = (int) (trans_amt * 0.6) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign3 + "' where \"trans_ref\" = '" + trans_ref + "'";

                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                //   addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                        } else if (599999 < trans_amt && trans_amt <= 799999) {
                            //points for values between 600,000 - 799,999 

                            int points = (int) (trans_amt * 0.8) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign4 + "' where \"trans_ref\" = '" + trans_ref + "'";

                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                //   addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                        } else if (799999 < trans_amt && trans_amt <= 1000000) {
                            //points for values equal & above 800,000

                            int points = (int) (trans_amt * 1) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign5 + "' where \"trans_ref\" = '" + trans_ref + "'";

                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                //  addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                        }else if (trans_amt >= 1000000) {
                            //points for values equal & above 1,000,000

                            int points = (int) (trans_amt * 1.2) / 100;
                            Updater = "Update [Feb 2016-INTERPEL] Set \"points earned feb\" = '" + points + "',\"Campaign Name\""
                                    + " = '" + Campaign6 + "' where \"trans_ref\" = '" + trans_ref + "'";

                            try {
                                PreparedStatement ps = conn.prepareStatement(Updater);
                                ps.execute();
                                //  addData();
                            } catch (SQLException ex) {
                                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                        }
                    }

                } catch (SQLException ex) {

                    Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                } finally {
                    loadinglabel.setText("Done analysing Data ");
                    loadinglabel.setForeground(Color.RED);
                }
            }

        };
        PointsandCampaign.start();
    }
    
    
//<<------------------------------------------------------------------------------------------------------------------------------
    //Add points to the main Database for InterPel
    public void Add_Points_To_Interpel_Main_Database_File() {
        String query_added_table = "Select * from Upload_Customer_Points";

        try {
            PreparedStatement ps = conn.prepareStatement(query_added_table);
            ResultSet Rs = ps.executeQuery();
            while (Rs.next()) {
                String Customer_Id = Rs.getString("Customer_Ident");//Identification to be added to final file
                
                String query_Total_points_from_main_table = "Select SUM(Points_Value) AS Total_Points from December where [Customer_Ident] = '" + Customer_Id + "' ";
                PreparedStatement ps1 = conn.prepareStatement(query_Total_points_from_main_table);
                ResultSet Rs1 = ps1.executeQuery();

                while (Rs1.next()) {
                    //int total_points = (int) Integer.parseInt(Rs1.getString("Total_Points"));//All accumulated points 
                    String result = Rs1.getString("Total_Points");
                    String T;
                    if(result == null){
                        T = "0"; 
                    }else{
                        T = result;
                    }
                    int final_points = (int) Integer.parseInt(T);

                    // JOptionPane.showMessageDialog(null, "final_updated_points: "+final_updated_points+"\ntotal_points: "+ total_points + "\npoints_to_add_to_main_file :" +points_to_add_to_main_file);
                    String Update_points_in_the_main_file = "Update Upload_Customer_Points set [Loyalty_Value] =('" + final_points + "')where [Customer_Ident] = '" + Customer_Id + "'";
                    PreparedStatement update_members_points = conn.prepareStatement(Update_points_in_the_main_file);
                    update_members_points.execute();
                    addData();
                    
                }
            }

        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        }finally
        {
            JOptionPane.showMessageDialog(null, "am done");
        }
    }

    //replaces () with -
    public void Add_Points_To_File() {
        String Updater = "Select * from INTERPELCURRENT";
        try {
            PreparedStatement ps = conn.prepareStatement(Updater);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                String points = rs.getString("Loyalty Value");
                String ident = rs.getString("Customer Ident");
                if (points.contains(",")) {
                    points = points.replace(",", "");//.replace(")", "");

                    String Update_points_in_the_main_file = "Update INTERPELCURRENT set [Loyalty Value] =('" + points + "')where [Customer Ident] = '" + ident + "'";
                    PreparedStatement update_members_points = conn.prepareStatement(Update_points_in_the_main_file);
                    update_members_points.execute();
                    addData();
                }
            }

        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Adding data to the main file for ICEA
    public void add_Data_To_Main_ICEA_File() {
        String query_added_table = "Select * from RR_LION_CLUB_POINT29 ";

        try {
            PreparedStatement ps = conn.prepareStatement(query_added_table);
            ResultSet Rs = ps.executeQuery();
            while (Rs.next()) {
                String RR_Log_CardRefNo = Rs.getString("RR_Log_CardRefNo");
                int points_to_add_to_main_file = Integer.parseInt(Rs.getString("RR_Log_RecAmtToApply")) / 200;//points to be added to main file

                String query_points_from_main_table = "Select * from Member_Points_As_On_29_April_2016 where MemberNo = '" + RR_Log_CardRefNo + "' ";
                PreparedStatement ps1 = conn.prepareStatement(query_points_from_main_table);
                ResultSet Rs1 = ps1.executeQuery();

                while (Rs1.next()) {
                    int total_points = Integer.parseInt(Rs1.getString("TotalPoints"));//All accumulated points
                    int final_updated_points = points_to_add_to_main_file + total_points;

                    // JOptionPane.showMessageDialog(null, "final_updated_points: "+final_updated_points+"\ntotal_points: "+ total_points + "\npoints_to_add_to_main_file :" +points_to_add_to_main_file);
                    String Update_points_in_the_main_file = "Update Member_Points_As_On_29_April_2016 set TotalPoints =('" + final_updated_points + "')where MemberNo = '" + RR_Log_CardRefNo + "'";
                    PreparedStatement update_members_points = conn.prepareStatement(Update_points_in_the_main_file);
                    update_members_points.execute();
                    //   addData();
                }
            }

        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void add_Data_To_Main_Interpel_File() {
        String query_added_table = "Select * from members ";

        try {
            PreparedStatement ps = conn.prepareStatement(query_added_table);
            ResultSet Rs = ps.executeQuery();
            while (Rs.next()) {
                String Customer_Name = Rs.getString("Customer_name");

                String query_points_from_main_table = "SELECT SUM(Loyalty)AS SUM_Loyalty FROM March_June where Customer_name ='" + Customer_Name + "'";
                PreparedStatement ps1 = conn.prepareStatement(query_points_from_main_table);
                ResultSet Rs1 = ps1.executeQuery();
                while (Rs1.next()) {
                    String Points = Rs1.getString("SUM_Loyalty");

                    String Update = "UPDATE members SET Total_Loyalty = '"+Points+"' where Customer_name = '"+Customer_Name+"'";
            
                    PreparedStatement update_members_points = conn.prepareStatement(Update);
                    update_members_points.execute();
                    addData();
                }
            }

        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            JOptionPane.showMessageDialog(null, "I am done");

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

        jPanel1 = new javax.swing.JPanel();
        jPanel2 = new javax.swing.JPanel();
        ConnectBtn = new javax.swing.JButton();
        uploadBtn = new javax.swing.JButton();
        jButton2 = new javax.swing.JButton();
        NameText = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        combineBtn = new javax.swing.JButton();
        clearBtn = new javax.swing.JButton();
        jButton4 = new javax.swing.JButton();
        SaveCsvFile = new javax.swing.JButton();
        jScrollPane1 = new javax.swing.JScrollPane();
        DataTable = new javax.swing.JTable();
        jLabel2 = new javax.swing.JLabel();
        importName = new javax.swing.JTextField();
        jButton5 = new javax.swing.JButton();
        loadinglabel = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        jLabel1 = new javax.swing.JLabel();
        editlad = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        jButton1 = new javax.swing.JButton();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);

        jPanel2.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        ConnectBtn.setText("Show Points");
        ConnectBtn.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                ConnectBtnActionPerformed(evt);
            }
        });

        uploadBtn.setText("Upload csv file");
        uploadBtn.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                uploadBtnActionPerformed(evt);
            }
        });

        jButton2.setText("Show Transactions");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                .addComponent(uploadBtn)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(ConnectBtn)
                .addGap(18, 18, 18)
                .addComponent(jButton2)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(uploadBtn, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                        .addComponent(ConnectBtn, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(jButton2)))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        NameText.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                NameTextActionPerformed(evt);
            }
        });

        jLabel3.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        jLabel3.setText("DataBase file path:");

        combineBtn.setBackground(new java.awt.Color(255, 0, 0));
        combineBtn.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        combineBtn.setText("Combine");
        combineBtn.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                combineBtnActionPerformed(evt);
            }
        });

        clearBtn.setBackground(new java.awt.Color(255, 204, 153));
        clearBtn.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        clearBtn.setText("Clear Table for next transaction");
        clearBtn.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                clearBtnActionPerformed(evt);
            }
        });

        jButton4.setText("Attach path");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        SaveCsvFile.setBackground(new java.awt.Color(0, 255, 0));
        SaveCsvFile.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        SaveCsvFile.setText("Save Table");
        SaveCsvFile.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                SaveCsvFileActionPerformed(evt);
            }
        });

        DataTable.setModel(new javax.swing.table.DefaultTableModel(
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
        jScrollPane1.setViewportView(DataTable);

        jLabel2.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        jLabel2.setText("import table path :");

        jButton5.setText("Attach Path");
        jButton5.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed(evt);
            }
        });

        loadinglabel.setFont(new java.awt.Font("Snap ITC", 1, 36)); // NOI18N
        loadinglabel.setForeground(new java.awt.Color(0, 204, 0));

        jLabel4.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel4.setText("Total Entries Count:");

        jLabel1.setText("[Transaction ID , Transaction Type , Transaction Date , Processed Date , Merchant Name , Customer Name , Customer Ident , Purchase Value , Loyalty Value]");

        editlad.setText("Collumns to enter:");

        jLabel6.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel6.setText("jLabel6");

        jButton1.setText("Do Stuff");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addComponent(jLabel2)
                                .addGap(0, 0, Short.MAX_VALUE))
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addComponent(jLabel3)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(loadinglabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGap(13, 13, 13)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel1)
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                    .addGroup(jPanel1Layout.createSequentialGroup()
                                        .addGap(12, 12, 12)
                                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                                            .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                                .addComponent(clearBtn, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                                .addComponent(combineBtn, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                                .addComponent(jPanel2, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                                .addGroup(jPanel1Layout.createSequentialGroup()
                                                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                                                        .addComponent(importName, javax.swing.GroupLayout.PREFERRED_SIZE, 265, javax.swing.GroupLayout.PREFERRED_SIZE)
                                                        .addComponent(NameText, javax.swing.GroupLayout.PREFERRED_SIZE, 264, javax.swing.GroupLayout.PREFERRED_SIZE))
                                                    .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                                        .addComponent(jButton4)
                                                        .addComponent(jButton5)))
                                                .addComponent(SaveCsvFile, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                                            .addGroup(jPanel1Layout.createSequentialGroup()
                                                .addComponent(jLabel4)
                                                .addGap(32, 32, 32)
                                                .addComponent(jLabel6)
                                                .addGap(149, 149, 149))))
                                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                                        .addComponent(editlad)
                                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                        .addComponent(jButton1)))
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(jScrollPane1)))))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGap(5, 5, 5)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jLabel3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(loadinglabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(jButton4)
                            .addComponent(NameText, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                        .addGap(13, 13, 13)
                        .addComponent(jLabel2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jButton5)
                            .addComponent(importName, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(combineBtn, javax.swing.GroupLayout.PREFERRED_SIZE, 44, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(SaveCsvFile, javax.swing.GroupLayout.PREFERRED_SIZE, 37, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(clearBtn, javax.swing.GroupLayout.PREFERRED_SIZE, 40, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(18, 18, 18)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(jLabel4)
                            .addComponent(jLabel6))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(editlad))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addGap(0, 341, Short.MAX_VALUE)
                        .addComponent(jButton1))
                    .addComponent(jScrollPane1, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.PREFERRED_SIZE, 0, Short.MAX_VALUE))
                .addGap(18, 18, 18)
                .addComponent(jLabel1)
                .addContainerGap())
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
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void combineBtnActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_combineBtnActionPerformed
        // TODO add your handling code here:
        if (NameText.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No Sqlite file Path Error");
        } else {

            try {
                //Takes data from the Main Table
                String CombineSql = "Select * From Member_Points_As_On_13_April_2016";
                PreparedStatement CombinePs = conn.prepareStatement(CombineSql);
                ResultSet CombineRs = CombinePs.executeQuery();
                while (CombineRs.next()) {
                    String points = CombineRs.getString("Points");
                    String points2 = CombineRs.getString("Points12");
                    String points3 = CombineRs.getString("Points13");
                    String CardNo = CombineRs.getString("MemberNo");
                    int tp = Integer.parseInt(points) + Integer.parseInt(points2) + Integer.parseInt(points3);

                    String updateData = "Update Member_Points_As_On_13_April_2016 set TotalPoints =('" + tp + "') where MemberNo = '" + CardNo + "'";
                    PreparedStatement ps3 = conn.prepareStatement(updateData);
                    ps3.execute();
                    //    addData();

                }
            } catch (SQLException ex) {
                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
            }
        }


    }//GEN-LAST:event_combineBtnActionPerformed

    private void NameTextActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_NameTextActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_NameTextActionPerformed

    private void ConnectBtnActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_ConnectBtnActionPerformed
        // TODO add your handling code here:
        // if (NameText.getText().equals("")) {
        //     JOptionPane.showMessageDialog(null, "No Sqlite file Path Error");
            addData();
        //}
    }//GEN-LAST:event_ConnectBtnActionPerformed

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        // TODO add your handling code here:
        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
        NameText.setText(fileName);
    }//GEN-LAST:event_jButton4ActionPerformed

    private void SaveCsvFileActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_SaveCsvFileActionPerformed
        // TODO add your handling code here:
        if (NameText.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No Sqlite file Path Error");
        } else {
            conn = SqlConnector.newConnection(NameText.getText());
            String fileName = JOptionPane.showInputDialog(null, "Enter File Name", "File Name Prompt", 0);
            File file = new File("C:\\Users\\"
                    + System.getProperty("user.name") + "\\Desktop\\ThorProcessedFiles");
            file.mkdir();

            JOptionPane.showMessageDialog(null, "Create ThorDataFile and save data on the Desktop");

            SaveFinalData("C:\\Users\\"
                    + System.getProperty("user.name") + "\\Desktop\\ThorProcessedFiles\\" + fileName + ".csv");
            JOptionPane.showMessageDialog(null, fileName + ".csv file created in path" + "\nC:\\Users\\"
                    + System.getProperty("user.name") + "\\Desktop\\ThorProcessedFiles");

        }


    }//GEN-LAST:event_SaveCsvFileActionPerformed

    private void jButton5ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed
        // TODO add your handling code here:
        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
        importName.setText(fileName);
    }//GEN-LAST:event_jButton5ActionPerformed

    private void uploadBtnActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_uploadBtnActionPerformed
        // TODO add your handling code here:
        if (NameText.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No Sqlite file Path Error");
        } else {
            conn = SqlConnector.newConnection(NameText.getText());
            // upload();
            String replacement = "Select * From RR_LION_CLUB_POINT_ALLOCATION_DATA";
            try {
                PreparedStatement ps = conn.prepareStatement(replacement);
                ResultSet rs = ps.executeQuery();
                while (rs.next()) {
                    String cardNo = rs.getString("RR_Log_CardRefNo");
                    if (cardNo.contains("i")) {
                        String NewCard = cardNo.replace("i", "");

                        String CounterSql = "Update ICEA_DEC2015_JAN2016 Set RR_Log_CardRefNo = '" + NewCard + " where RR_Log_CardRefNo = '" + cardNo + "'";
                        PreparedStatement counterps = conn.prepareStatement(CounterSql);
                        ResultSet counterRs = counterps.executeQuery();
                        while (counterRs.next()) {
                            //                   addData();
                        }
                    }
                }
            } catch (SQLException ex) {
                Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
            }

        }
    }//GEN-LAST:event_uploadBtnActionPerformed

    private void clearBtnActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_clearBtnActionPerformed
        // TODO add your handling code here:
        if (NameText.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No Sqlite file Path Error");
        } else {
            conn = SqlConnector.newConnection(NameText.getText());

            //Entry to the dataBase
            String Sql = "Select * from RR_LION_CLUB_POINT_ALLOCATION_DATA";
            Thread d = new Thread() {

                @Override
                public void run() {
                    try {
                        PreparedStatement ps = conn.prepareStatement(Sql);
                        ResultSet rs = ps.executeQuery();
                        while (rs.next()) {
                            String CardNo = rs.getString("RR_Log_CardRefNo");
                            //String TransactionDate = rs.getString("RR_Log_TrxDate");
                            if (CardNo.contains("rr")) {
                                JOptionPane.showMessageDialog(null, "I AM DONE BRA");
                            } else {

                                //Acquisition of the details in specified card numbers
                                String Sql1 = "SELECT TOTAL(Points),TOTAL(RR_Log_RecAmtToApply) FROM RR_LION_CLUB_POINT_ALLOCATION_DATA WHERE RR_Log_CardRefNo= '" + CardNo + "'";
                                PreparedStatement newPs = conn.prepareStatement(Sql1);
                                ResultSet getPass = newPs.executeQuery();
                                while (getPass.next()) {

                                    // String r = rs.getString("TOTAL( \"MARGIN %\")");
                                    String combinedPoints = getPass.getString("TOTAL(Points)");
                                    String combinedAmount = getPass.getString("TOTAL(RR_Log_RecAmtToApply)");

                                    //insertion of new data
                                    String iquery = "insert into RR_LION_CLUB_POINT_ALLOCATION_DATA "
                                            + "([RR_Log_TrxNo], [RR_Log_CardRefNo], [RR_Log_RecNo], [RR_Log_RecAmtToApply], [RR_Log_TrxDate], [Points])"
                                            + " values(?, ?, ?, ?, ?, ?)";
                                    PreparedStatement psInsert = conn.prepareStatement(iquery);
                                    psInsert.setString(1, "00000000i");
                                    psInsert.setString(2, CardNo + ".rr");
                                    psInsert.setString(3, "R/2016/01-00000.rr");
                                    psInsert.setString(4, combinedAmount);
                                    psInsert.setString(5, "00-03-2016.rr");
                                    psInsert.setString(6, combinedPoints);
                                    psInsert.execute();

                                    String DeleteData = "Delete from RR_LION_CLUB_POINT_ALLOCATION_DATA where RR_Log_CardRefNo = '" + CardNo + "'";
                                    PreparedStatement deletePs = conn.prepareStatement(DeleteData);
                                    deletePs.execute();
                                }
                            }
                            //  addData();
                            loadinglabel.setText("Table Cleared For Next Transaction");
                            loadinglabel.setForeground(Color.BLUE);
                        }

                    } catch (SQLException ex) {
                        Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
                    }

                }

            };
            d.start();

        }
    }//GEN-LAST:event_clearBtnActionPerformed

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        // populating_Files_Function();
        //add_Data_To_Main_Interpel_File();//Adds data to
       Add_Points_To_Interpel_Main_Database_File(); 
        // add_Data_To_Main_ICEA_File();
        //Points_Generation_For_Interpel_Data();
        //sortMeUp();
    }//GEN-LAST:event_jButton1ActionPerformed

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // TODO add your handling code here:
        
                String Sql = "SELECT * FROM November";

        try {
            ResultSet rs;
            
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                jLabel6.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(MainFrame.class.getName()).log(Level.SEVERE, null, ex);
        }
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
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new MainFrame().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton ConnectBtn;
    private javax.swing.JTable DataTable;
    private javax.swing.JTextField NameText;
    private javax.swing.JButton SaveCsvFile;
    private javax.swing.JButton clearBtn;
    private javax.swing.JButton combineBtn;
    private javax.swing.JLabel editlad;
    private javax.swing.JTextField importName;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton2;
    private javax.swing.JButton jButton4;
    private javax.swing.JButton jButton5;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JLabel loadinglabel;
    private javax.swing.JButton uploadBtn;
    // End of variables declaration//GEN-END:variables
}
