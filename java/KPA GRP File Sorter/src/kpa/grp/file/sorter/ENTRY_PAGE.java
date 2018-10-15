/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kpa.grp.file.sorter;

/**
 *
 * @author Granson
 */
import java.awt.HeadlessException;
import java.awt.TrayIcon;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;
import javax.swing.JTable;
import javax.swing.filechooser.FileSystemView;
import javax.swing.table.TableModel;
import net.proteanit.sql.DbUtils;
public final class ENTRY_PAGE extends javax.swing.JFrame {

  /**
   * Entry Point
   */
    /**
     * Creates new form ENTRY_PAGE
     */
    Connection conn = null;
    String path_to_sqlite_file = null;
    String Life = "";
    
    public ENTRY_PAGE() throws IOException {
        initComponents();
        
            conn = SqlConnector.newConnection();
            drop_all();
            add_General_Data();
            add_KPA_Data();
            add_GRP_Data();

     }
    
    public void drop_all(){
    
        String DROP_KPA_TABLE = "DROP TABLE KPA_TABLE";
        String DROP_GRP_TABLE = "DROP TABLE GRP_TABLE";
        String DROP_GENERAL_TABLE = "DROP TABLE GENERAL_TABLE";
        
       PreparedStatement KPA_PS,GRP_PS,GENERAL_PS;
        ResultSet KPA_RS,GRP_RS,GENERAL_RS;
        try {
        KPA_PS = conn.prepareStatement(DROP_KPA_TABLE);
        GRP_PS = conn.prepareStatement(DROP_GRP_TABLE);
        GENERAL_PS = conn.prepareStatement(DROP_GENERAL_TABLE);
        
        KPA_PS.execute();
        GRP_PS.execute();
        GENERAL_PS.execute();
        
                } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }finally{
            Uploading.setText("Creating tables.");
            create_tables();
        }
    }
     
    public String ImportFileName(){
    
        JFileChooser choose = new JFileChooser();
        choose.showOpenDialog(null);
        File file = choose.getSelectedFile();
        String fileName = file.getAbsolutePath();
       
        return fileName;
    }
    
    //Create BD tables.
    public void create_tables(){
    
        Uploading.setText("Creating tables. Please wait");
        String Create_KPA = "CREATE TABLE KPA_TABLE (ID VARCHAR PRIMARY KEY  NOT NULL , VOUCHER_DATE VARCHAR, VOUCHER_REF VARCHAR, MEMBER_ID VARCHAR, MEMBER_NAME VARCHAR, REFERENCE_KEY VARCHAR, GROSS_AMOUNT_FC_DEBIT VARCHAR, GROSS_AMOUNT_CREDIT VARCHAR)";
        String Create_GRP = "CREATE TABLE GRP_TABLE (ID VARCHAR PRIMARY KEY  NOT NULL , VOUCHER_DATE VARCHAR, VOUCHER_REF VARCHAR, MEMBER_ID VARCHAR, MEMBER_NAME VARCHAR, REFERENCE_KEY VARCHAR, GROSS_AMOUNT_FC_DEBIT VARCHAR, GROSS_AMOUNT_CREDIT VARCHAR)";
        String Create_GENERAL = "CREATE TABLE GENERAL_DATA (ID VARCHAR PRIMARY KEY  NOT NULL , VOUCHER_DATE VARCHAR, VOUCHER_REF VARCHAR, MEMBER_ID VARCHAR, MEMBER_NAME VARCHAR, REFERENCE_KEY VARCHAR, GROSS_AMOUNT_FC_DEBIT VARCHAR, GROSS_AMOUNT_CREDIT VARCHAR)";
   
        PreparedStatement KPA_PS,GRP_PS,GENERAL_PS;
        ResultSet KPA_RS,GRP_RS,GENERAL_RS;
        try {
        KPA_PS = conn.prepareStatement(Create_KPA);
        GRP_PS = conn.prepareStatement(Create_GRP);
        GENERAL_PS = conn.prepareStatement(Create_GENERAL);
        
        KPA_PS.execute();
        GRP_PS.execute();
        GENERAL_PS.execute();
        
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }finally{
            Uploading.setText("Creating tables. Done");
        }
    }
    
    //------------------------------This uploads data to the Transaction_Table---------------------------------------------------------
    public void upload_Transaction_File() {
       
        String FileName = ImportFileName();
        importName.setText(FileName);
        try {
         if (FileName.equals("")) {
            JOptionPane.showMessageDialog(null, "No import file Path Error");
        } else {
            Thread thread = new Thread() {

                @Override
                public void run() {

                    try {
                        InputStream read = new FileInputStream(FileName);
                        try (BufferedReader br = new BufferedReader(new InputStreamReader(read))) {
                            String line;
                            while ((line = br.readLine().replaceAll("'", "`").replace("\"", "")) != null) {
                                Uploading.setText("Uploading Data Please wait");
                                Life = "Uploading";
                                    
                                    String[] value = line.split(",");
                                    String sql = "Insert into GENERAL_DATA values ('" + value[0] + "','" + value[1] + "','" + value[2] + "','" + value[3] + "','" + value[4] + "','" + value[5] + "','" + value[6] +"' ,'" + value[7] +"')";
                                    PreparedStatement pst = conn.prepareStatement(sql);
                                    pst.executeUpdate();
                                    
                                    add_General_Data();
                            }
                        }
                      } catch (IOException | SQLException e) {
                        e.printStackTrace();
                        //JOptionPane.showMessageDialog(null, e + "File Upload Error", "File Upload Alert", JOptionPane.ERROR_MESSAGE);
                        //Uploading.setText("");
                        create_tables();
                        add_General_Data();

                    }finally{
                        if(Life.equals("")){
                        JOptionPane.showMessageDialog(null, "File Upload Error", "File Upload Alert", JOptionPane.ERROR_MESSAGE);
                        Uploading.setText("");
                        }else{  
                            
                        JOptionPane.showMessageDialog(null, "File Upload Completed", "File Upload Alert", JOptionPane.INFORMATION_MESSAGE); 
                        Uploading.setText("");                               
                            }
                    }

                }

            };
            thread.start();

        }
        } catch (NullPointerException e) {
            JOptionPane.showMessageDialog(null, "You have not selected a file to upload");
        
        }         
        
    }
    //-----------------------------------------------------------DO NOT ALTER----------------------------------------------------------------------------------------------------------
       
    public void sort_KPA_GRP_DATA() throws SQLException{
                
        Thread thread = new Thread(){
            
                @Override
                public void run() {
                    try {
                        delete_KPA_table();
                        delete_GRP_table();
                        
                        String Sql = "SELECT * FROM GENERAL_DATA";
                        
                        PreparedStatement ps = conn.prepareStatement(Sql);
                        ResultSet Rs = ps.executeQuery();
                        while (Rs.next()) {
                            
                            String SUB_REF_KEY = Rs.getString("REFERENCE_KEY");
                            if(SUB_REF_KEY != null){
                                
                            String REF_KEY;
                            
                            if(SUB_REF_KEY.equals("")){
                                
                                REF_KEY = "";
                            }else{
                                
                                REF_KEY = SUB_REF_KEY.substring(0,1);
                            }
                            
                            String ID = Rs.getString("ID");
                            String VOUCHER_DATE = Rs.getString("VOUCHER_DATE");
                            String VOUCHER_REF = Rs.getString("VOUCHER_REF");
                            String MEMBER_ID = Rs.getString("MEMBER_ID");
                            String MEMBER_NAME = Rs.getString("MEMBER_NAME");
                            String REFERENCE_KEY = Rs.getString("REFERENCE_KEY");
                            String GROSS_AMOUNT_FC_DEBIT = Rs.getString("GROSS_AMOUNT_FC_DEBIT");
                            String GROSS_AMOUNT_CREDIT = Rs.getString("GROSS_AMOUNT_CREDIT");
                            
                            if(REF_KEY.equals("K")){
                                //add to KPA Table
                                String sql = "Insert into KPA_TABLE  "
                                        + "values ('" + ID + "','" + VOUCHER_DATE + "','" + VOUCHER_REF + "','" + MEMBER_ID + "','" + MEMBER_NAME + "','" + REFERENCE_KEY + "','" + GROSS_AMOUNT_FC_DEBIT + "','" + GROSS_AMOUNT_CREDIT +"')";
                                
                                PreparedStatement Insert_Data_Details = conn.prepareStatement(sql);
                                Insert_Data_Details.execute();
                                add_KPA_Data();
                                
                            } else {
                                //Add to GRP table
                                String sql = "Insert into GRP_TABLE "
                                        + "values ('" + ID + "','" + VOUCHER_DATE + "','" + VOUCHER_REF + "','" + MEMBER_ID + "','" + MEMBER_NAME + "','" + REFERENCE_KEY + "','" + GROSS_AMOUNT_FC_DEBIT + "','" + GROSS_AMOUNT_CREDIT +"')";
                                
                                PreparedStatement Insert_Data_Details = conn.prepareStatement(sql);
                                Insert_Data_Details.execute();
                                add_GRP_Data();
                                }
                            }
                        }
                        //END OF THREAD 
                    } catch (SQLException ex) {
                        Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
                    }finally{
                    JOptionPane.showMessageDialog(null, "Sorting Completed." , "System Alert", JOptionPane.INFORMATION_MESSAGE);
                    }

                }
           };
            thread.start();
            
              
    }
    
    public void upate_table(String TableName, String OldREF, String newREF) {
        try {
            String Update_Grouped = "UPDATE "+TableName+" SET REFERENCE_KEY = '" + newREF + "' WHERE REFERENCE_KEY = '" + OldREF + "'";
            PreparedStatement Update_SEP = conn.prepareStatement(Update_Grouped);
            Update_SEP.execute();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }finally{
            try {
                String Update_General = "UPDATE GENERAL_DATA SET REFERENCE_KEY = '" + newREF + "' WHERE REFERENCE_KEY = '" + OldREF + "'";
                PreparedStatement Update_GT = conn.prepareStatement(Update_General);
                Update_GT.execute();
            } catch (SQLException ex) {
                Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        refresh();
    }
        
    public void refresh(){
    add_KPA_Data();
    add_GRP_Data();
    add_General_Data();
    }
    
    //Add data to the table
    private void add_General_Data() {

        String Sql = "SELECT * FROM GENERAL_DATA";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                general_table.setModel(DbUtils.resultSetToTableModel(rs));
                ENTRY_COUNT.setText("" + general_table.getRowCount());
            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //Add data to the table
    private void add_KPA_Data() {

        String Sql = "SELECT * FROM KPA_TABLE";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                KPA_TABLE.setModel(DbUtils.resultSetToTableModel(rs));
                KPA_ENTRIES.setText("" + KPA_TABLE.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }

    }
    
    //Add data to the table
    private void add_GRP_Data() {

        String Sql = "SELECT * FROM GRP_TABLE";

        try {
            ResultSet rs;
            try (PreparedStatement Pst = conn.prepareStatement(Sql)) {
                rs = Pst.executeQuery();
                GRP_TABLE.setModel(DbUtils.resultSetToTableModel(rs));
                GRP_ENTRIES.setText("" + GRP_TABLE.getRowCount());

            }
            rs.close();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }

    }
    
    public void exportTable(JTable table) throws IOException {
        String file_details = null;
        JFileChooser jfc = new JFileChooser(FileSystemView.getFileSystemView().getHomeDirectory());

           //int returnValue = jfc.showOpenDialog(null);
            int returnValue = jfc.showSaveDialog(null);

           if (returnValue == JFileChooser.APPROVE_OPTION) {
                   File selectedFile = jfc.getSelectedFile();
                   file_details = selectedFile.getAbsolutePath() +".csv";
                    File file = new File(file_details);
                    TableModel model = table.getModel();
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
                    JOptionPane.showMessageDialog(null, "The file has been saved successfully.", "Infomation prompt", JOptionPane.INFORMATION_MESSAGE);
           }else{
           
                 JOptionPane.showMessageDialog(null, "The file has not been saved.", "Infomation prompt", JOptionPane.ERROR_MESSAGE);
           }

    }
    
    public void Edit_KPA_Entry(){
    
        int row = KPA_TABLE.getSelectedRow();
        String Clicked = (KPA_TABLE.getModel().getValueAt(row, 5)).toString();
        
        KPA_OLD.setText(Clicked);
    }
    
    public void Edit_GRP_Entry(){
    
        int row = GRP_TABLE.getSelectedRow();
        String Clicked = (GRP_TABLE.getModel().getValueAt(row, 5)).toString();
        
        GRP_OLD.setText(Clicked);
    }
    
    public void delete_table(){
    
        String DeleteData = "Delete from GENERAL_DATA";
        PreparedStatement deletePs;
        try {
            deletePs = conn.prepareStatement(DeleteData);
            deletePs.execute();
            add_General_Data();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    public void delete_GRP_table(){
    
        String DeleteData = "Delete from GRP_TABLE";
        PreparedStatement deletePs;
        try {
            deletePs = conn.prepareStatement(DeleteData);
            deletePs.execute();
            add_General_Data();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    public void delete_KPA_table(){
    
        String DeleteData = "Delete from KPA_TABLE";
        PreparedStatement deletePs;
        try {
            deletePs = conn.prepareStatement(DeleteData);
            deletePs.execute();
            add_General_Data();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
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

        jTabbedPane1 = new javax.swing.JTabbedPane();
        jPanel3 = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        general_table = new javax.swing.JTable();
        jPanel4 = new javax.swing.JPanel();
        jScrollPane2 = new javax.swing.JScrollPane();
        KPA_TABLE = new javax.swing.JTable();
        jPanel5 = new javax.swing.JPanel();
        jScrollPane3 = new javax.swing.JScrollPane();
        GRP_TABLE = new javax.swing.JTable();
        jPanel1 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        GRP_OLD = new javax.swing.JTextField();
        GRP_NEW = new javax.swing.JTextField();
        jButton2 = new javax.swing.JButton();
        jButton7 = new javax.swing.JButton();
        jPanel2 = new javax.swing.JPanel();
        jLabel2 = new javax.swing.JLabel();
        KPA_OLD = new javax.swing.JTextField();
        KPA_NEW = new javax.swing.JTextField();
        jButton3 = new javax.swing.JButton();
        jButton8 = new javax.swing.JButton();
        importName = new javax.swing.JTextField();
        jButton4 = new javax.swing.JButton();
        Uploading = new javax.swing.JLabel();
        jButton5 = new javax.swing.JButton();
        jPanel6 = new javax.swing.JPanel();
        jButton6 = new javax.swing.JButton();
        jLabel3 = new javax.swing.JLabel();
        ENTRY_COUNT = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        jLabel5 = new javax.swing.JLabel();
        KPA_ENTRIES = new javax.swing.JLabel();
        GRP_ENTRIES = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("INTERPEL KPA GRP FILE SORTER");

        jTabbedPane1.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

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
        jScrollPane1.setViewportView(general_table);

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 645, Short.MAX_VALUE)
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 497, Short.MAX_VALUE)
        );

        jTabbedPane1.addTab("GENERAL DATA", jPanel3);

        KPA_TABLE.setModel(new javax.swing.table.DefaultTableModel(
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
        KPA_TABLE.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                KPA_TABLEMouseClicked(evt);
            }
        });
        jScrollPane2.setViewportView(KPA_TABLE);

        javax.swing.GroupLayout jPanel4Layout = new javax.swing.GroupLayout(jPanel4);
        jPanel4.setLayout(jPanel4Layout);
        jPanel4Layout.setHorizontalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 645, Short.MAX_VALUE)
        );
        jPanel4Layout.setVerticalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 497, Short.MAX_VALUE)
        );

        jTabbedPane1.addTab("KPA ENTRIES", jPanel4);

        GRP_TABLE.setModel(new javax.swing.table.DefaultTableModel(
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
        GRP_TABLE.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                GRP_TABLEMouseClicked(evt);
            }
        });
        jScrollPane3.setViewportView(GRP_TABLE);

        javax.swing.GroupLayout jPanel5Layout = new javax.swing.GroupLayout(jPanel5);
        jPanel5.setLayout(jPanel5Layout);
        jPanel5Layout.setHorizontalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 645, Short.MAX_VALUE)
        );
        jPanel5Layout.setVerticalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 497, Short.MAX_VALUE)
        );

        jTabbedPane1.addTab("GRP ENTRIES", jPanel5);

        jPanel1.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel1.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel1.setText("GRP EDITOR");

        GRP_OLD.setEditable(false);
        GRP_OLD.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        GRP_OLD.setForeground(new java.awt.Color(204, 0, 0));
        GRP_OLD.setHorizontalAlignment(javax.swing.JTextField.CENTER);
        GRP_OLD.setToolTipText("Old entry key");

        GRP_NEW.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        GRP_NEW.setHorizontalAlignment(javax.swing.JTextField.CENTER);
        GRP_NEW.setToolTipText("New entry key");

        jButton2.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton2.setText("Edit GPR");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        jButton7.setText("Save GRP File CSV");
        jButton7.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton7ActionPerformed(evt);
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
                        .addComponent(GRP_OLD))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGap(57, 57, 57)
                        .addComponent(jLabel1)
                        .addGap(0, 42, Short.MAX_VALUE))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(GRP_NEW))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jButton7, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jButton2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addComponent(jLabel1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(GRP_OLD, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(GRP_NEW, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jButton2)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton7)
                .addContainerGap(10, Short.MAX_VALUE))
        );

        jPanel2.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel2.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel2.setText("KPA EDITOR");

        KPA_OLD.setEditable(false);
        KPA_OLD.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        KPA_OLD.setForeground(new java.awt.Color(255, 0, 51));
        KPA_OLD.setHorizontalAlignment(javax.swing.JTextField.CENTER);
        KPA_OLD.setToolTipText("Old kpa entry");

        KPA_NEW.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        KPA_NEW.setHorizontalAlignment(javax.swing.JTextField.CENTER);
        KPA_NEW.setToolTipText("New KPA Entry");

        jButton3.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton3.setText("Edit KPA");
        jButton3.setToolTipText("");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });

        jButton8.setText("Save KPA File CSV");
        jButton8.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton8ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(KPA_NEW, javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(jButton3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(KPA_OLD, javax.swing.GroupLayout.Alignment.TRAILING)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addGap(53, 53, 53)
                        .addComponent(jLabel2)
                        .addGap(0, 0, Short.MAX_VALUE))
                    .addComponent(jButton8, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap())
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addComponent(jLabel2)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(KPA_OLD, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(KPA_NEW, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jButton3)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton8)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        jButton4.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton4.setText("Upload Data");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        Uploading.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        Uploading.setForeground(new java.awt.Color(0, 153, 51));
        Uploading.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);

        jButton5.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton5.setText("Clear Table");
        jButton5.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed(evt);
            }
        });

        jPanel6.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jButton6.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton6.setText("SORT DATA");
        jButton6.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton6ActionPerformed(evt);
            }
        });

        jLabel3.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel3.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel3.setText("TOTAL ENTRIES");

        ENTRY_COUNT.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        ENTRY_COUNT.setText("0");

        jLabel4.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel4.setForeground(new java.awt.Color(255, 0, 0));
        jLabel4.setText("KPA ENTRIES");

        jLabel5.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jLabel5.setForeground(new java.awt.Color(0, 0, 204));
        jLabel5.setText("GRP ENTRIES");

        KPA_ENTRIES.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        KPA_ENTRIES.setForeground(new java.awt.Color(255, 0, 51));
        KPA_ENTRIES.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        KPA_ENTRIES.setText("0");

        GRP_ENTRIES.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        GRP_ENTRIES.setForeground(new java.awt.Color(0, 0, 204));
        GRP_ENTRIES.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        GRP_ENTRIES.setText("0");

        javax.swing.GroupLayout jPanel6Layout = new javax.swing.GroupLayout(jPanel6);
        jPanel6.setLayout(jPanel6Layout);
        jPanel6Layout.setHorizontalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jLabel3, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addComponent(ENTRY_COUNT, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addGap(38, 38, 38)
                .addComponent(jButton6)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                    .addComponent(KPA_ENTRIES, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel4, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel5)
                    .addComponent(GRP_ENTRIES, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
        );
        jPanel6Layout.setVerticalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addComponent(jButton6)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jLabel3)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(ENTRY_COUNT)
                .addGap(19, 19, 19)
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel4)
                    .addComponent(jLabel5))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(KPA_ENTRIES)
                    .addComponent(GRP_ENTRIES))
                .addGap(0, 0, Short.MAX_VALUE))
        );

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(layout.createSequentialGroup()
                        .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jPanel6, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jTabbedPane1))
                    .addComponent(Uploading, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(layout.createSequentialGroup()
                        .addComponent(importName)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jButton4, javax.swing.GroupLayout.PREFERRED_SIZE, 126, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton5)))
                .addContainerGap())
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(importName, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jButton4)
                    .addComponent(jButton5))
                .addGap(4, 4, 4)
                .addComponent(Uploading)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(layout.createSequentialGroup()
                        .addComponent(jPanel6, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                    .addComponent(jTabbedPane1))
                .addContainerGap())
        );

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        // TODO add your handling code here:
        delete_table();
        upload_Transaction_File();
    }//GEN-LAST:event_jButton4ActionPerformed

    private void jButton5ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed
        // TODO add your handling code here:

        delete_table();
        delete_KPA_table();
        delete_GRP_table();
        
        add_General_Data();
        add_KPA_Data();
        add_GRP_Data();
        
    }//GEN-LAST:event_jButton5ActionPerformed

    private void jButton6ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton6ActionPerformed
        try {
            // TODO add your handling code here:
            sort_KPA_GRP_DATA();
        } catch (SQLException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }
    }//GEN-LAST:event_jButton6ActionPerformed

    private void jButton7ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton7ActionPerformed
        try {
            // TODO add your handling code here:
            exportTable(GRP_TABLE);
        } catch (IOException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }
    }//GEN-LAST:event_jButton7ActionPerformed

    private void jButton8ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton8ActionPerformed
        // TODO add your handling code here:
        try {
            // TODO add your handling code here:
            exportTable(KPA_TABLE);
        } catch (IOException ex) {
            Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
        }
    }//GEN-LAST:event_jButton8ActionPerformed

    private void KPA_TABLEMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_KPA_TABLEMouseClicked
        // TODO add your handling code here:
        Edit_KPA_Entry();
    }//GEN-LAST:event_KPA_TABLEMouseClicked

    private void GRP_TABLEMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_GRP_TABLEMouseClicked
        // TODO add your handling code here:
        Edit_GRP_Entry();
    }//GEN-LAST:event_GRP_TABLEMouseClicked

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // TODO add your handling code here:
        String OldREF = GRP_OLD.getText();
        String newREF = GRP_NEW.getText();        
        if(OldREF.equals("")||newREF.equals("")){
            JOptionPane.showMessageDialog(null, "Please enter the reference keys to edit", "Alert", JOptionPane.WARNING_MESSAGE);
        }else{
             upate_table("GRP_TABLE", OldREF, newREF);        
        }
    }//GEN-LAST:event_jButton2ActionPerformed

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        // TODO add your handling code here:
        String OldREF = KPA_OLD.getText();
        String newREF = KPA_NEW.getText();
        
        if(OldREF.equals("")||newREF.equals("")){
            JOptionPane.showMessageDialog(null, "Please enter the reference keys to edit", "Alert", JOptionPane.WARNING_MESSAGE);
        }else{
             upate_table("KPA_TABLE", OldREF, newREF);        
        }
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
            java.util.logging.Logger.getLogger(ENTRY_PAGE.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(ENTRY_PAGE.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(ENTRY_PAGE.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(ENTRY_PAGE.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                try {
                    new ENTRY_PAGE().setVisible(true);
                } catch (IOException ex) {
                    Logger.getLogger(ENTRY_PAGE.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JLabel ENTRY_COUNT;
    private javax.swing.JLabel GRP_ENTRIES;
    private javax.swing.JTextField GRP_NEW;
    private javax.swing.JTextField GRP_OLD;
    private javax.swing.JTable GRP_TABLE;
    private javax.swing.JLabel KPA_ENTRIES;
    private javax.swing.JTextField KPA_NEW;
    private javax.swing.JTextField KPA_OLD;
    private javax.swing.JTable KPA_TABLE;
    private javax.swing.JLabel Uploading;
    private javax.swing.JTable general_table;
    private javax.swing.JTextField importName;
    private javax.swing.JButton jButton2;
    private javax.swing.JButton jButton3;
    private javax.swing.JButton jButton4;
    private javax.swing.JButton jButton5;
    private javax.swing.JButton jButton6;
    private javax.swing.JButton jButton7;
    private javax.swing.JButton jButton8;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JPanel jPanel4;
    private javax.swing.JPanel jPanel5;
    private javax.swing.JPanel jPanel6;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JTabbedPane jTabbedPane1;
    // End of variables declaration//GEN-END:variables
}
