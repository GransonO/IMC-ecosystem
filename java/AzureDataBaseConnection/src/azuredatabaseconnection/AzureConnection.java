/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package azuredatabaseconnection;

import java.awt.Graphics;
import java.awt.image.BufferedImage;
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
import javax.imageio.ImageIO;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.table.TableModel;
import net.proteanit.sql.DbUtils;

/**
 *
 * @author Granson
 */
public class AzureConnection extends javax.swing.JFrame {

    /**
     * Creates new form NewJFrame
     */
    Connection connection = null;

    public AzureConnection() {
        connection = SqlConnector.newConnection();
        initComponents();
    }

    //Insert Users to the registered table
    public void RegisterUsers() {

        if (signedUsername.getText().equals("") || signedUserID.getText().equals("") || signedPassword.getText().equals("")) {

            JOptionPane.showMessageDialog(null, "Fill the fields first");
        } else {
            try {
                String Query = "Insert into Users ([user_id],[username],[password],[first_name],[last_name],[email],[company_id],[company_name],[telephone],[address],[country]) "
                        + "values ('" + signedUserID.getText().trim() + "','" + signedUsername.getText().trim() + "','" + signedPassword.getText().trim() + "','" + signedfirstname.getText().trim() + "','" + signedlastname.getText().trim() + "'"
                        + ",'" + signedmail.getText().trim() + "','" + signedcompanyid.getText().trim() + "','" + signedcompanyname.getText().trim() + "','" + signedtelephone.getText().trim() + "','" + signedaddress.getText().trim() + "','" + signedcountry.getText().trim() + "')";
                PreparedStatement pst = connection.prepareStatement(Query);
                pst.executeUpdate();
            } catch (SQLException ex) {
                Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
            } finally {
                JOptionPane.showMessageDialog(null, "Data upload Success");
            }
            getRegisteredData();
        }
    }

    //  upload data to the Total_Points_Table

    public void uploadPoints() {
        if (importName.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No import file Path Error");
        } else {
            Thread thread = new Thread() {

                @Override
                public void run() {

                    try {
                        InputStream read = new FileInputStream(importName.getText());
                        try (BufferedReader br = new BufferedReader(new InputStreamReader(read))) {
                            String line;
                            while ((line = br.readLine().replaceAll("'", "`").replace("\"", "")) != null) {

                                String[] value = line.split(",");
                                String sql = "Insert into Total_Points_Table ([Customer_Ident],[Customer_Name],[Total Loyalty Value])"
                                        + "values ('" + value[0] + "','" + value[1] + "'," + value[2] + ")";
                                PreparedStatement pst = connection.prepareStatement(sql);
                                pst.executeUpdate();
                                Uploading.setText("Uploading Data Please wait");
                            }
                        }
                    } catch (IOException | SQLException e) {
                        JOptionPane.showMessageDialog(null, e + "\nNo file Path Error");

                    } finally {
                        addData();
                        JOptionPane.showMessageDialog(null, "Done you may resume...");
                    }

                }

            };
            thread.start();

        }
    }

    //-----------------------------Export Table--------------------------------------------------------------------------------------------------- 
    public void exportTable() throws IOException {

        File file = new File("C:\\Users\\" + System.getProperty("user.name") + "\\Desktop\\" + FileName.getText() + ".csv");
        TableModel model = DataTable.getModel();
        try (FileWriter out = new FileWriter(file)) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                out.write(model.getColumnName(i) + "," + "\t");
            }
            out.write("\n");

            for (int i = 0; i < model.getRowCount(); i++) {
                for (int j = 0; j < model.getColumnCount(); j++) {
                    out.write(model.getValueAt(i, j).toString() + "," + "\t");
                }
                out.write("\n");
            }
        }
        System.out.println("write out to: " + file);
    }
    //------------------------------------------- upload data to Members_Contacts -----------------------------------------------------------

    public void uploadTotalMembers() {
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
                        while ((line = br.readLine().replaceAll("'", "`").replace("\"", "")) != null) {

                            String[] value = line.split(",");
                            String sql = "Insert into Members_Contacts ([Customer_Ident],[Customer_Name],[Name Of Contact],[Email Address]) "
                                    + "values ('" + value[0] + "','" + value[1] + "','" + value[2] + "','" + value[3] + "')";
                            PreparedStatement pst = connection.prepareStatement(sql);
                            pst.executeUpdate();
                            // addData();

                        }
                        br.close();
                    } catch (IOException | SQLException e) {
                        JOptionPane.showMessageDialog(null, e + "\nNo file Path Error");

                    } finally {
                        addData();
                        JOptionPane.showMessageDialog(null, "Done you may resume...");
                    }

                }

            };
            thread.start();

        }
    }

    //------------------------------------------- End of uploading data  
    //upload data to Member_Usage
    public void uploadMemberUsage() {
        if (importName.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No import file Path Error");
        } else {
            Thread thread = new Thread() {

                @Override
                public void run() {

                    try {
                        InputStream read = new FileInputStream(importName.getText());
                        try (BufferedReader br = new BufferedReader(new InputStreamReader(read))) {
                            String line;

                            while ((line = br.readLine().replaceAll("'", "`").replace("\"", "")) != null) {
                                String[] value = line.split(",");
                                String sql = "Insert into Member_Usage ([Customer Ident],[Customer Name],[Purchase Value],[Transactions Count],[Last Transaction Date],[Days To Expiry]) "
                                        + "values ('" + value[0] + "','" + value[1] + "','" + value[2] + "','" + value[3] + "','" + value[4] + "','" + value[5] + "')";
                                PreparedStatement pst = connection.prepareStatement(sql);
                                pst.executeUpdate();
                                Uploading.setText("Uploading Data Please wait");

                            }
                        }
                    } catch (IOException | SQLException e) {
                        e.printStackTrace();
                        JOptionPane.showMessageDialog(null, e + "\nNo file Path Error");

                    } finally {
                        addData();
                        JOptionPane.showMessageDialog(null, "Done you may resume...");
                    }

                }

            };
            thread.start();

        }
    }

    //------------------------------This uploads data to the Transaction_Table---------------------------------------------------------
    public void upload_Transaction_Table() {
        if (importName.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "No import file Path Error");
        } else {
            Thread thread = new Thread() {

                @Override
                public void run() {

                    try {
                        InputStream read = new FileInputStream(importName.getText());
                        try (BufferedReader br = new BufferedReader(new InputStreamReader(read))) {
                            String line;
                            while ((line = br.readLine().replaceAll("'", "`").replace("\"", "")) != null) {

                                String[] value = line.split(",");
                                String sql = "Insert into Interpel_Transactions_Table ([Customer_Ident],[Customer_Name],[Transaction_ID],[Transaction_Date],[Purchase_Value],[Loyalty_Campaign],[Points_Value],[month_year]) "
                                        + "values ('" + value[0] + "','" + value[1] + "','" + value[2] + "','" + value[3] + "'," + value[4] + ",'" + value[5] + "'," + value[6] + ",'" + value[7] + "')";
                                PreparedStatement pst = connection.prepareStatement(sql);
                                pst.executeUpdate();
                                Uploading.setText("Uploading Data Please wait");

                            }
                        }
                    } catch (IOException | SQLException e) {
                        JOptionPane.showMessageDialog(null, e + "\nNo file Path Error");

                    } finally {
                        addData();
                        JOptionPane.showMessageDialog(null, "Done you may resume...");
                    }

                }

            };
            thread.start();

        }
    }
    //-----------------------------------------------------------DO NOT ALTER----------------------------------------------------------------------------------------------------------

    //Insert Statement
    public void insertToDb() {
        if (cardNoTxt.getText().equals("")) {
            JOptionPane.showMessageDialog(null,
                    "Nothing to send !!!");
        } else {
            try {
                String query = "insert into ICEA_Member_Data (RR_Log_CardRefNo,RR_Log_Mem_Name) values(?,?,?,?,?)";
                PreparedStatement ps = connection.prepareStatement(query);

                ps.setString(1, cardNoTxt.getText());
                ps.setString(2, nametxt.getText());

                ps.execute();
                JOptionPane.showMessageDialog(null,
                        "Submitted successfully !!!");
                addData();
            } catch (SQLException ex) {
                Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
                JOptionPane.showMessageDialog(null,
                        "Submitt failed !!!" + ex);
                ex.printStackTrace();
            }
        }
    }

    public void getRegisteredData() {

        String Sql = "select * from Users";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                RegisteredTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + RegisteredTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void deleteData(String runThis) {
        try {
            // String retrieve = "Delete from Interpel_Database_File";
            String retrieve = runThis;
            PreparedStatement ps = connection.prepareStatement(retrieve);
            ps.execute();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            addData();
            JOptionPane.showMessageDialog(null,
                    "Data Deleted .....");
        }
    }

    public void getSum() {
        String select = "SELECT sum([Total_Points])as loyalty FROM Total_Points_Table";
        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(select)) {
                rs = Pst.executeQuery();

                JOptionPane.showMessageDialog(null, rs.getString("loyalty"));

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Add data to the table
    public void addData() {

        String Sql = "select * from Total_Points_Table";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }

    }
    
    //Show Redemptions
    public void Redemptions() {

        String Sql = "select * from Interpel_Redemption_Table";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }

    }
    
        //Show Expired
    public void Expired() {

        String Sql = "select * from Expired_Table";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    public void ShowTransData() {

        String Sql = "select * from Interpel_Transactions_Table";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    public void ShowUsageData() {

        String Sql = "select * from Member_Usage";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                DataTable.setModel(DbUtils.resultSetToTableModel(rs));
                countTxt.setText("" + DataTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //Show data redemed history
    public void showRedemptionData() {
        String Sql = "select * from RedemptionTB";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = connection.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                RegisteredTable.setModel(DbUtils.resultSetToTableModel(rs));
                RedeemTxt.setText("" + RegisteredTable.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Get Total members points from the total Taransactions
    public void get_Total_Points() {

        String members_points = "Select * from Total_Points_Table";
        try {
            PreparedStatement ps = connection.prepareStatement(members_points);
            ResultSet Rs = ps.executeQuery();
            while (Rs.next()) {
                String Customer_Ident = Rs.getString("Customer_Ident");

                String get_points = "Select SUM(Points_Value) as Total_Points from Interpel_Transactions_Table where Customer_Ident = '" + Customer_Ident + "'";
                PreparedStatement Points_Ps = connection.prepareStatement(get_points);
                ResultSet points_Rs = Points_Ps.executeQuery();
                while (points_Rs.next()) {
                    int sum_points;

                    if (points_Rs.getString("Total_Points") == null) {

                        sum_points = Integer.parseInt("0");
                    } else {
                        sum_points = Integer.parseInt(points_Rs.getString("Total_Points"));
                    }
                    Uploading.setText("Updating Data Please wait");

                    try {
                        String Update_Points = "UPDATE Total_Points_Table SET [Total Loyalty Value] = '" + sum_points + "' where [Customer_Ident] = '" + Customer_Ident + "'";
                        PreparedStatement Update_Ps = connection.prepareStatement(Update_Points);
                        Update_Ps.execute();

                    } catch (SQLException ex) {
                        Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
                        ex.printStackTrace();
                    }
                }
            }
        } catch (SQLException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        } finally {

            addData();
            JOptionPane.showMessageDialog(null, "Done you may resume...");
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

        jPanel3 =  new JPanel() {
            BufferedImage img;
            {
                try {
                    img = ImageIO.read(getClass().getResource("crystal.jpg"));
                } catch (IOException ex) {  ex.printStackTrace(); }
            }

            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                g.drawImage(img, 0, 0, getWidth(), getHeight(), this);

            }
        };
        jPanel2 = new javax.swing.JPanel();
        jLabel8 = new javax.swing.JLabel();
        signedUsername = new javax.swing.JTextField();
        signedUserID = new javax.swing.JTextField();
        signedPassword = new javax.swing.JTextField();
        jLabel15 = new javax.swing.JLabel();
        jLabel16 = new javax.swing.JLabel();
        jButton12 = new javax.swing.JButton();
        jButton13 = new javax.swing.JButton();
        jButton14 = new javax.swing.JButton();
        jButton15 = new javax.swing.JButton();
        signedlastname = new javax.swing.JTextField();
        signedmail = new javax.swing.JTextField();
        signedcompanyid = new javax.swing.JTextField();
        signedtelephone = new javax.swing.JTextField();
        signedaddress = new javax.swing.JTextField();
        signedcountry = new javax.swing.JTextField();
        jScrollPane2 = new javax.swing.JScrollPane();
        RegisteredTable = new javax.swing.JTable();
        jLabel9 = new javax.swing.JLabel();
        jScrollPane1 = new javax.swing.JScrollPane();
        DataTable = new javax.swing.JTable();
        Uploading = new javax.swing.JLabel();
        jPanel1 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        jLabel3 = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        jLabel5 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        cardNoTxt = new javax.swing.JTextField();
        nametxt = new javax.swing.JTextField();
        mobileTxt = new javax.swing.JTextField();
        SpentTxt = new javax.swing.JTextField();
        jButton1 = new javax.swing.JButton();
        jButton2 = new javax.swing.JButton();
        userTxt = new javax.swing.JLabel();
        jButton3 = new javax.swing.JButton();
        jLabel7 = new javax.swing.JLabel();
        countTxt = new javax.swing.JLabel();
        jButton4 = new javax.swing.JButton();
        jLabel10 = new javax.swing.JLabel();
        RedeemTxt = new javax.swing.JLabel();
        jButton9 = new javax.swing.JButton();
        jButton11 = new javax.swing.JButton();
        jButton16 = new javax.swing.JButton();
        jButton17 = new javax.swing.JButton();
        jButton8 = new javax.swing.JButton();
        jButton6 = new javax.swing.JButton();
        jButton10 = new javax.swing.JButton();
        jButton7 = new javax.swing.JButton();
        FileName = new javax.swing.JTextField();
        jLabel11 = new javax.swing.JLabel();
        importName = new javax.swing.JTextField();
        jButton5 = new javax.swing.JButton();
        jPanel4 = new javax.swing.JPanel();
        signedfirstname = new javax.swing.JTextField();
        signedcompanyname = new javax.swing.JTextField();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);

        jPanel2.setOpaque(false);

        jLabel8.setText("Enter User Data");

        signedUsername.setText("Username");

        signedUserID.setText("User id");
        signedUserID.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                signedUserIDActionPerformed(evt);
            }
        });

        signedPassword.setText("password");

        jLabel15.setText("Activation State : ");

        jLabel16.setText("Inactivated");

        jButton12.setText("Add User");
        jButton12.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton12ActionPerformed(evt);
            }
        });

        jButton13.setText("Activate User");

        jButton14.setBackground(new java.awt.Color(255, 0, 0));
        jButton14.setText("Delete Entries");
        jButton14.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton14ActionPerformed(evt);
            }
        });

        jButton15.setText("Show Data");
        jButton15.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton15ActionPerformed(evt);
            }
        });

        signedlastname.setText("last_name");

        signedmail.setText("email");

        signedcompanyid.setText("company_id");

        signedtelephone.setText("telephone");

        signedaddress.setText("address");

        signedcountry.setText("country");

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                                .addGroup(jPanel2Layout.createSequentialGroup()
                                    .addComponent(signedtelephone, javax.swing.GroupLayout.PREFERRED_SIZE, 160, javax.swing.GroupLayout.PREFERRED_SIZE)
                                    .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                    .addComponent(signedaddress, javax.swing.GroupLayout.PREFERRED_SIZE, 160, javax.swing.GroupLayout.PREFERRED_SIZE))
                                .addGroup(javax.swing.GroupLayout.Alignment.LEADING, jPanel2Layout.createSequentialGroup()
                                    .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                                        .addComponent(signedlastname, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, 160, Short.MAX_VALUE)
                                        .addComponent(signedUserID, javax.swing.GroupLayout.Alignment.LEADING))
                                    .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                    .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                        .addComponent(signedUsername, javax.swing.GroupLayout.DEFAULT_SIZE, 160, Short.MAX_VALUE)
                                        .addComponent(signedmail))))
                            .addGroup(jPanel2Layout.createSequentialGroup()
                                .addComponent(jButton12)
                                .addGap(18, 18, 18)
                                .addComponent(jButton15)
                                .addGap(18, 18, 18)
                                .addComponent(jButton13)))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jButton14)
                            .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                .addComponent(signedcountry)
                                .addComponent(signedcompanyid, javax.swing.GroupLayout.DEFAULT_SIZE, 178, Short.MAX_VALUE)
                                .addComponent(signedPassword)))
                        .addGap(18, 18, 18)
                        .addComponent(jLabel15)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jLabel16)
                        .addContainerGap(51, Short.MAX_VALUE))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                        .addComponent(jLabel8)
                        .addGap(313, 313, 313))))
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel8)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(signedUsername, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedUserID, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedPassword, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(signedlastname, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedmail, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedcompanyid, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(signedtelephone, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedaddress, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedcountry, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton12)
                    .addComponent(jButton15)
                    .addComponent(jButton13)
                    .addComponent(jButton14)
                    .addComponent(jLabel15)
                    .addComponent(jLabel16)))
        );

        RegisteredTable.setModel(new javax.swing.table.DefaultTableModel(
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
        jScrollPane2.setViewportView(RegisteredTable);

        jLabel9.setFont(new java.awt.Font("28 Days Later", 1, 12)); // NOI18N
        jLabel9.setForeground(new java.awt.Color(255, 255, 255));
        jLabel9.setText("Members Redemption Table");

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

        Uploading.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        Uploading.setForeground(new java.awt.Color(255, 255, 255));
        Uploading.setText("Members Transaction Table");

        jPanel1.setOpaque(false);

        jLabel1.setText("INTERPEL MEMBER DATA TABLE");

        jLabel2.setText("Card No:");

        jLabel3.setText("Member Name:");

        jLabel4.setText("Member Mobile:");

        jLabel5.setText("Spent Amt:");

        jLabel6.setText("Points Earned:");

        cardNoTxt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cardNoTxtActionPerformed(evt);
            }
        });

        mobileTxt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                mobileTxtActionPerformed(evt);
            }
        });

        jButton1.setText("Show Trans");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        jButton2.setText("Show Points");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        userTxt.setText("Operation Details");

        jButton3.setBackground(new java.awt.Color(255, 0, 0));
        jButton3.setText("Delete Trans");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });

        jLabel7.setText("Total Members:");

        countTxt.setText("00");

        jButton4.setText("Delete Member Usage");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        jLabel10.setText("Total Redemptions");

        RedeemTxt.setText("00");

        jButton9.setText("Delete Points");
        jButton9.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton9ActionPerformed(evt);
            }
        });

        jButton11.setText("Show Usage");
        jButton11.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton11ActionPerformed(evt);
            }
        });

        jButton16.setText("Show Redemptions");
        jButton16.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton16ActionPerformed(evt);
            }
        });

        jButton17.setText("Show Expired");
        jButton17.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton17ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addGap(16, 16, 16)
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                                    .addComponent(jLabel4)
                                    .addComponent(jLabel6)
                                    .addComponent(jLabel5, javax.swing.GroupLayout.Alignment.LEADING)
                                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                        .addComponent(jLabel2)
                                        .addComponent(jLabel3)))
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                    .addComponent(cardNoTxt, javax.swing.GroupLayout.DEFAULT_SIZE, 134, Short.MAX_VALUE)
                                    .addComponent(nametxt)
                                    .addComponent(mobileTxt)
                                    .addComponent(SpentTxt)))
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addGap(92, 92, 92)
                                .addComponent(userTxt))
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addContainerGap()
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(jLabel7)
                                    .addComponent(jLabel10))
                                .addGap(22, 22, 22)
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(countTxt)
                                    .addComponent(RedeemTxt))))
                        .addGap(0, 0, Short.MAX_VALUE))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jButton11, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                                .addGap(0, 0, Short.MAX_VALUE)
                                .addComponent(jLabel1))
                            .addComponent(jButton1, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jButton4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jButton2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addGroup(jPanel1Layout.createSequentialGroup()
                                .addComponent(jButton3)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                .addComponent(jButton9))
                            .addComponent(jButton16, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jButton17, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel1)
                .addGap(23, 23, 23)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(cardNoTxt, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel3)
                    .addComponent(nametxt, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel4)
                    .addComponent(mobileTxt, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel5)
                    .addComponent(SpentTxt, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addComponent(jLabel6)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton11)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton2)
                .addGap(2, 2, 2)
                .addComponent(jButton16)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton17)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton3)
                    .addComponent(jButton9))
                .addGap(12, 12, 12)
                .addComponent(jButton4)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(userTxt)
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel7)
                    .addComponent(countTxt))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel10)
                    .addComponent(RedeemTxt)))
        );

        jButton8.setText("Update Points");
        jButton8.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton8ActionPerformed(evt);
            }
        });

        jButton6.setText("Upload Trans");
        jButton6.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton6ActionPerformed(evt);
            }
        });

        jButton10.setText("Upload Usage");
        jButton10.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton10ActionPerformed(evt);
            }
        });

        jButton7.setText("Export Table");
        jButton7.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton7ActionPerformed(evt);
            }
        });

        jLabel11.setText("Import file path");

        jButton5.setText("Import Points");
        jButton5.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed(evt);
            }
        });

        signedfirstname.setText("first_name");
        signedfirstname.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                signedfirstnameActionPerformed(evt);
            }
        });

        signedcompanyname.setText("company_name");

        javax.swing.GroupLayout jPanel4Layout = new javax.swing.GroupLayout(jPanel4);
        jPanel4.setLayout(jPanel4Layout);
        jPanel4Layout.setHorizontalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(signedfirstname, javax.swing.GroupLayout.PREFERRED_SIZE, 195, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(signedcompanyname, javax.swing.GroupLayout.PREFERRED_SIZE, 195, javax.swing.GroupLayout.PREFERRED_SIZE))
        );
        jPanel4Layout.setVerticalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(signedfirstname, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(signedcompanyname, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jButton10, javax.swing.GroupLayout.PREFERRED_SIZE, 253, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addComponent(FileName, javax.swing.GroupLayout.PREFERRED_SIZE, 126, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(18, 18, 18)
                        .addComponent(jButton7, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel11)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addComponent(importName, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton5, javax.swing.GroupLayout.PREFERRED_SIZE, 142, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                        .addComponent(jButton8, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, 253, Short.MAX_VALUE)
                        .addComponent(jButton6, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(jLabel9)
                        .addGap(289, 289, 289))
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jScrollPane1))
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addGap(256, 256, 256)
                        .addComponent(Uploading)
                        .addGap(0, 0, Short.MAX_VALUE))
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addContainerGap())
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jScrollPane2, javax.swing.GroupLayout.PREFERRED_SIZE, 0, Short.MAX_VALUE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel4, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))))
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addComponent(Uploading)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jScrollPane1, javax.swing.GroupLayout.PREFERRED_SIZE, 0, Short.MAX_VALUE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jLabel9)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 151, Short.MAX_VALUE)
                            .addComponent(jPanel4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jButton6)))
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addGap(4, 4, 4)
                        .addComponent(jButton8)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton10)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(FileName, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                            .addComponent(jButton7))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jLabel11)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(importName, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                            .addComponent(jButton5)))
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addContainerGap())
        );

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jPanel3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jPanel3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void cardNoTxtActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cardNoTxtActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_cardNoTxtActionPerformed

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        //  insertToDb();
        ShowTransData();
    }//GEN-LAST:event_jButton1ActionPerformed

    private void mobileTxtActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_mobileTxtActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_mobileTxtActionPerformed

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // TODO add your handling code here:
        
        addData();
    }//GEN-LAST:event_jButton2ActionPerformed

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        // TODO add your handling code here:
        deleteData("Delete from Interpel_Transactions_Table");
    }//GEN-LAST:event_jButton3ActionPerformed

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        // TODO add your handling code here:
        deleteData("Delete from Member_Usage");
    }//GEN-LAST:event_jButton4ActionPerformed

    private void jButton5ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed
        // TODO add your handling code here:
        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
        importName.setText(fileName);
        uploadPoints();
    }//GEN-LAST:event_jButton5ActionPerformed

    private void jButton6ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton6ActionPerformed

        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
        importName.setText(fileName);
        upload_Transaction_Table();
    }//GEN-LAST:event_jButton6ActionPerformed

    private void jButton7ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton7ActionPerformed
        try {
            // TODO add your handling code here:
            exportTable();
        } catch (IOException ex) {
            Logger.getLogger(AzureConnection.class.getName()).log(Level.SEVERE, null, ex);
        } finally {

            JOptionPane.showMessageDialog(null, "Done");
        }
    }//GEN-LAST:event_jButton7ActionPerformed

    private void jButton8ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton8ActionPerformed
        // TODO add your handling code here:
        //get_Total_Points();
    }//GEN-LAST:event_jButton8ActionPerformed

    private void jButton9ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton9ActionPerformed
        // TODO add your handling code here:
        deleteData("Delete from Total_Points_Table");
    }//GEN-LAST:event_jButton9ActionPerformed

    private void jButton10ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton10ActionPerformed
        // TODO add your handling code here:

        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
        importName.setText(fileName);
        uploadMemberUsage();
    }//GEN-LAST:event_jButton10ActionPerformed

    private void jButton11ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton11ActionPerformed
        // TODO add your handling code here:

        ShowUsageData();
    }//GEN-LAST:event_jButton11ActionPerformed

    private void jButton12ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton12ActionPerformed
        // TODO add your handling code here:
        RegisterUsers();
    }//GEN-LAST:event_jButton12ActionPerformed

    private void jButton14ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton14ActionPerformed
        // TODO add your handling code here:
        deleteData("Delete from Users");
    }//GEN-LAST:event_jButton14ActionPerformed

    private void jButton15ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton15ActionPerformed
        // TODO add your handling code here:
        getRegisteredData();
    }//GEN-LAST:event_jButton15ActionPerformed

    private void signedfirstnameActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_signedfirstnameActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_signedfirstnameActionPerformed

    private void signedUserIDActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_signedUserIDActionPerformed
        // TODO add your handling code here:
        
    }//GEN-LAST:event_signedUserIDActionPerformed

    private void jButton16ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton16ActionPerformed
        // TODO add your handling code here:
        Redemptions();
    }//GEN-LAST:event_jButton16ActionPerformed

    private void jButton17ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton17ActionPerformed
        // TODO add your handling code here:
        Expired();
    }//GEN-LAST:event_jButton17ActionPerformed

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
            java.util.logging.Logger.getLogger(AzureConnection.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(AzureConnection.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(AzureConnection.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(AzureConnection.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new AzureConnection().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTable DataTable;
    private javax.swing.JTextField FileName;
    private javax.swing.JLabel RedeemTxt;
    private javax.swing.JTable RegisteredTable;
    private javax.swing.JTextField SpentTxt;
    private javax.swing.JLabel Uploading;
    private javax.swing.JTextField cardNoTxt;
    private javax.swing.JLabel countTxt;
    private javax.swing.JTextField importName;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton10;
    private javax.swing.JButton jButton11;
    private javax.swing.JButton jButton12;
    private javax.swing.JButton jButton13;
    private javax.swing.JButton jButton14;
    private javax.swing.JButton jButton15;
    private javax.swing.JButton jButton16;
    private javax.swing.JButton jButton17;
    private javax.swing.JButton jButton2;
    private javax.swing.JButton jButton3;
    private javax.swing.JButton jButton4;
    private javax.swing.JButton jButton5;
    private javax.swing.JButton jButton6;
    private javax.swing.JButton jButton7;
    private javax.swing.JButton jButton8;
    private javax.swing.JButton jButton9;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel10;
    private javax.swing.JLabel jLabel11;
    private javax.swing.JLabel jLabel15;
    private javax.swing.JLabel jLabel16;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JLabel jLabel9;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JPanel jPanel4;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JTextField mobileTxt;
    private javax.swing.JTextField nametxt;
    private javax.swing.JTextField signedPassword;
    private javax.swing.JTextField signedUserID;
    private javax.swing.JTextField signedUsername;
    private javax.swing.JTextField signedaddress;
    private javax.swing.JTextField signedcompanyid;
    private javax.swing.JTextField signedcompanyname;
    private javax.swing.JTextField signedcountry;
    private javax.swing.JTextField signedfirstname;
    private javax.swing.JTextField signedlastname;
    private javax.swing.JTextField signedmail;
    private javax.swing.JTextField signedtelephone;
    private javax.swing.JLabel userTxt;
    // End of variables declaration//GEN-END:variables
}
