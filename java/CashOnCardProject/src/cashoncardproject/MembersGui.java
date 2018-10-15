/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package cashoncardproject;

import com.itextpdf.text.BaseColor;
import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Font;
import com.itextpdf.text.FontFactory;
import com.itextpdf.text.Image;
import com.itextpdf.text.List;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;
import java.awt.HeadlessException;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;
import net.proteanit.sql.DbUtils;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartFrame;
import org.jfree.chart.ChartRenderingInfo;
import org.jfree.chart.ChartUtilities;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.entity.StandardEntityCollection;
import org.jfree.chart.plot.CategoryPlot;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.chart.renderer.category.BarRenderer;
import org.jfree.data.general.DefaultPieDataset;
import org.jfree.data.jdbc.JDBCCategoryDataset;
import java.util.Properties;

//Data encryption
import javax.crypto.*;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

//Imports for the mail API
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

/**
 *
 * @author Granson
 */
public class MembersGui extends javax.swing.JFrame {

    String cardNumber;
    boolean status = false;
    String Sql = null;
    Connection conn = null;
    String MembersName = null;
    String sending = "Sending";
    int Month;
    String choises[] = {"Line Graph", "Bar Graph", "Pie Chart"};
    String Product, vendor, month, price, time, Quantity, spent, Validation;
    String Productdata, vendordata, monthdata, pricedata, spentdata, validationdata, datedata;
    int dayDate;

    /**
     * Creates new form MembersGui
     */
    public MembersGui() {
        conn = DataBaseConnector.newConnection();
        initComponents();
    }
    ////Encryption /////////
    byte[] input ; 
    byte[] keyBytes = "12345678".getBytes();
    byte[] ivBytes = "input123".getBytes();
    SecretKeySpec key = new SecretKeySpec(keyBytes, "DES");
    IvParameterSpec ivSpec = new IvParameterSpec(ivBytes);
    Cipher cipher;
    byte[] cipherText;
    int ctLength;
    

    //Smtp Email method
    private void mailStmpMethod(String mail, String subject) {

        Thread mailer = new Thread() {
            public void run() {
                Properties props = new Properties();
                props.put("mail.smtp.host", "smtp.gmail.com");
                props.put("mail.smtp.socketFactory.port", "465");
                props.put("mail.smtp.socketFactory.class", "javax.net.ssl.SSLSocketFactory");
                props.put("mail.smtp.auth", "true");
                props.put("mail.smtp.port", "465");
                Session session = Session.getDefaultInstance(props,
                        new javax.mail.Authenticator() {
                            @Override
                            protected PasswordAuthentication getPasswordAuthentication() {
                                return new PasswordAuthentication("oyombegranson@gmail.com", "1Lmdwlcp*");
                            }
                        }
                );
                try {
                    Message message = new MimeMessage(session);
                    message.setFrom(new InternetAddress("oyombegranson@gmail.com"));
                    message.setRecipients(Message.RecipientType.TO, InternetAddress.parse("oyombegranson@gmail.com"));
                    message.setSubject(subject);
                    message.setText(mail);
                    //startProgressThread();
                    Transport.send(message);
                    JOptionPane.showMessageDialog(null, "Message Sent");
                } catch (MessagingException | HeadlessException e) {

                    JOptionPane.showMessageDialog(null, e);
                }
            }
        };
        mailer.start();

    }

    private void getProcess() {
        Thread mailer = new Thread() {
            public void run() {
                while (sending.equals("sending")) {
                    try {
                        sleep(1000);
                       // sendingLabel.setText("Sending...");
                    } catch (InterruptedException ex) {
                        Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
                    }

                }
            }
        };
        mailer.start();
    }

    //Generate A CSV File
    private void generateCSV(String FileName) {
        DataReport();
        try {
            try (FileWriter writer = new FileWriter(FileName)) {
                //Headings
                writer.append("Product");
                writer.append(',');
                writer.append("Price");
                writer.append(',');
                writer.append("Quantity");
                writer.append(',');
                writer.append("Vendor");
                writer.append(',');
                writer.append("Remaining_Points");
                writer.append(',');
                writer.append("Date");
                writer.append(',');
                writer.append("Time");
                writer.append(',');
                writer.append("Month");
                writer.append(',');
                writer.append("Amount_Spent");
                writer.append(',');
                writer.append("Previous_Points");
                writer.append(',');
                writer.append("Day");
                writer.append(',');
                writer.append("Entry");
                writer.append(',');
                writer.append("Validator");
                writer.append(',');
                writer.append("\n");

                //BODY
                writer.append(Productdata + "\n" + vendordata + "\n"
                        + monthdata + "\n" + pricedata + "\n" + spentdata
                        + "\n" + validationdata + "\n" + datedata);
                writer.flush();
            }

        } catch (Exception e) {
        }
    }

    //show progress of the mail
    public void startProgressThread() {
        // start the thread and from here call the function showProgress()
        showProgress();
    }
    //Shows The Progress

    public void showProgress() {
        status = true;
        int i = 0;
        while (status) {
            i++;
            // mailProgressbar.setValue(i);
            try {
                Thread.sleep(90);
            } catch (Exception exc) {
                JOptionPane.showMessageDialog(null, exc);
            }
        }
        if (status != true) {
            //       mailProgressbar.setValue(mailProgressbar.getMaximum());
        }
    }

    //Getting the Month Date Query...
    private void getQuery() {

        if (dayCombo3.getSelectedItem().toString().equals("none")) {
            Sql = "select entry,product,price,quantity,vendor,amount_spent,rem_points from'"
                    + cardNumber + "' where month = '" + monthCombo3.getSelectedItem().toString() + "'";
        } else {
            Sql = "select entry,product,price,quantity,vendor,amount_spent,rem_points from '"
                    + cardNumber + "' where month = '" + monthCombo3.getSelectedItem().toString() + "'and day = '"
                    + dayCombo3.getSelectedItem().toString() + "'";
        }
        addData(Sql);
    }

    //Get The Card Number
    public void getTheCardNumber(String number) {
        cardNumber = number;
        populateFields();
    }

    //Add data to the table
    public void addData(String Sql) {
        if (Sql.equals("xxxl")) {

            Sql = "select entry,product,price,quantity,vendor,amount_spent,rem_points from '" + cardfield.getText() + "'";

            try {
                PreparedStatement Pst = conn.prepareStatement(Sql);

                ResultSet rs = Pst.executeQuery();
                TransactionTable2.setModel(DbUtils.resultSetToTableModel(rs));
                Pst.close();
                rs.close();
            } catch (SQLException ex) {
                Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
            }

        } else {
            String sql = Sql;
            try {
                PreparedStatement pst = conn.prepareStatement(sql);

                ResultSet rs = pst.executeQuery();
                TransactionTable2.setModel(DbUtils.resultSetToTableModel(rs));
            } catch (SQLException ex) {
                Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
            }
        }

    }

    //Get day of the week
    private void getDay() {
        switch (dayDate) {
            case 1:
                dayLabel.setText("Monday");
                break;
            case 2:
                dayLabel.setText("Tuesday");
                break;
            case 3:
                dayLabel.setText("Wednesday");
                break;
            case 4:
                dayLabel.setText("Thursday");
                break;
            case 5:
                dayLabel.setText("Friday");
                break;
            case 6:
                dayLabel.setText("Saturday");
                break;
            case 7:
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
                        transTime.setText(" " + hr + hour + ": " + min + minute
                                + ": " + sec + seconds);
                        int myMonth = month + 1;
                        if (myMonth == 13) {
                            myMonth = 1;

                            dateLabel.setText(" " + day + "/" + myMonth + "/"
                                    + year);
                           
                            sleep(1000);
                            getMonth(day, myMonth, year);
                        } else {
                            dateLabel.setText(" " + day + "/" + myMonth + "/"
                                    + year);
                            transDate.setText(" " + day + "/" + myMonth + "/"
                                    + year);

                            sleep(1000);
                            getMonth(day, myMonth, year);
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

    //Get time remaining to expiry
    private void getRemainingTime(int DaysToExpiry) {

        Thread thread = new Thread() {
            public void run() {
                // TODO Auto-generated catch block

                for (;;) {
                    String hr, min, sec;
                    Calendar cal = new GregorianCalendar();
                    int hour = cal.get(Calendar.HOUR_OF_DAY);
                    int minute = cal.get(Calendar.MINUTE);
                    int seconds = cal.get(Calendar.SECOND);

                    int timeExpired = DaysToExpiry * 24;
                    int EpHour = (24 - hour) + timeExpired;
                    int EpMin = 60 - minute;
                    int EpSec = 60 - seconds;
                    if (EpHour < 10) {
                        hr = "0";
                    } else {
                        hr = "";
                    }
                    if (EpMin < 10) {
                        min = "0";
                    } else {
                        min = "";
                    }
                    if (EpSec < 10) {
                        sec = "0";
                    } else {
                        sec = "";
                    }

                    expirytime.setText(" " + hr + EpHour + ": " + min + EpMin
                            + ": " + sec + EpSec);
                }
            }
        };
        thread.start();
    }

    private void getRemainingDays(int year, int month, int day) {
        Calendar cal = new GregorianCalendar(year, month, day);
        int MonthDays = cal.getActualMaximum(Calendar.DAY_OF_MONTH);
        int RemainingDays = MonthDays - day;
        expirydate.setText("" + RemainingDays + " days Left");
        getRemainingTime(RemainingDays);
    }

    public void getMonth(int day, int mon, int year) {

        switch (mon) {
            case 1:
                monthLabel.setText("January");
                int jan = Calendar.JANUARY;
                getRemainingDays(year, jan, day);

                break;
            case 2:
                monthLabel.setText("February");

                int feb = Calendar.FEBRUARY;
                getRemainingDays(year, feb, day);

                break;
            case 3:
                monthLabel.setText("March");
                int mar = Calendar.MARCH;
                getRemainingDays(year, mar, day);
                break;
            case 4:
                monthLabel.setText("April");
                int apr = Calendar.APRIL;
                getRemainingDays(year, apr, day);
                break;
            case 5:
                monthLabel.setText("May");
                int may = Calendar.MAY;
                getRemainingDays(year, may, day);
                break;
            case 6:
                monthLabel.setText("June");
                int jun = Calendar.JUNE;
                getRemainingDays(year, jun, day);
                break;
            case 7:
                monthLabel.setText("July");
                int jul = Calendar.JULY;
                getRemainingDays(year, jul, day);
                break;
            case 8:
                monthLabel.setText("August");
                int aug = Calendar.AUGUST;
                getRemainingDays(year, aug, day);
                break;
            case 9:
                monthLabel.setText("September");
                int sep = Calendar.SEPTEMBER;
                getRemainingDays(year, sep, day);
                break;
            case 10:
                monthLabel.setText("October");
                int oct = Calendar.OCTOBER;
                getRemainingDays(year, oct, day);
                break;
            case 11:
                monthLabel.setText("November");
                int nov = Calendar.NOVEMBER;
                getRemainingDays(year, nov, day);
                break;
            case 12:
                monthLabel.setText("December");
                int dec = Calendar.DECEMBER;
                getRemainingDays(year, dec, day);
                break;
            default:
                break;
        }
    }

    private void populateFields() {
        //JOptionPane.showMessageDialog(null, "Your Card Number at populateFields() is :" + cardNumber);

        try {
            String sql = "select * from MembersTable where cardnumber = " + cardNumber;
            PreparedStatement ps = conn.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();

            if (rs.next()) {
                mailField.setText(rs.getString("email"));
                passField.setText(rs.getString("password"));

                MembersName = rs.getString("name");
                visitorsName.setText(MembersName);
                phonefield.setText(rs.getString("phone_no"));
                cardfield.setText(rs.getString("cardnumber"));
                card_expiryField.setText(rs.getString("card_expiry_date"));
                remainingPointsLabel.setText(rs.getString("phone_no"));

                clockWork();
                userTransactionData();
                addData("xxxl");
            } else {
                JOptionPane.showMessageDialog(null, "Your Card Number is Invalid\nPlease contact the administrator");
                LoginClass login = new LoginClass();
                login.setVisible(true);
                dispose();
            }
            ps.close();
            rs.close();

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    private void userTransactionData() {
        try {

            String sql = "SELECT Max(entry) FROM '" + cardNumber + "'";
            PreparedStatement ps = conn.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();

            if (rs.next()) {
                String LastEntry = rs.getString("Max(entry)");
                try {
                    String Lastsql = "select * from '" + cardNumber + "' where entry = " + LastEntry;
                    PreparedStatement lastps = conn.prepareStatement(Lastsql);
                    ResultSet lastrs = lastps.executeQuery();

                    if (lastrs.next()) {
                        remainingPointsLabel.setText(lastrs.getString("rem_points"));
                    } else {
                        JOptionPane.showMessageDialog(null, "Your Card Number is Invalid\nPlease contact the administrator");
                    }
                    lastps.close();
                    lastrs.close();

                } catch (SQLException ex) {
                    Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
                }
            } else {
                JOptionPane.showMessageDialog(null, "Your Card Number is Invalid\nPlease contact the administrator");
            }
            ps.close();
            rs.close();

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //SHOWS Line GRAPHS
    private void LineGraph() {

        try {
            String query = "select vendor,amount_spent from '123456789'";
            JDBCCategoryDataset dataset = new JDBCCategoryDataset(DataBaseConnector.newConnection(), query);
            JFreeChart chart = ChartFactory.createLineChart("Graphical Line Presentation of the total transactions", "Vendor", "Total Points Spent", dataset, PlotOrientation.VERTICAL, false, true, true);
            CategoryPlot plot = null;
            BarRenderer render = null;
            render = new BarRenderer();

            ChartFrame chartFrame = new ChartFrame(
                    MembersName + " Transactions Graphichal Presentation", chart);
            chartFrame.setVisible(true);
            chartFrame.setSize(500, 600);

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //SHOWS Bar GRAPHS
    private void BarGraph() {

        try {
            String query = "select vendor,amount_spent from '123456789'";
            JDBCCategoryDataset dataset = new JDBCCategoryDataset(DataBaseConnector.newConnection(), query);
            JFreeChart chart = ChartFactory.createBarChart("Graphical Bar Presentation of the total transactions", "Vendor", "Total Points Spent", dataset, PlotOrientation.VERTICAL, false, true, true);

            CategoryPlot plot = null;
            BarRenderer render = null;
            render = new BarRenderer();

            ChartFrame chartFrame = new ChartFrame(MembersName + " Transactions Graphichal Presentation", chart);
            chartFrame.setVisible(true);
            chartFrame.setSize(500, 650);

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //SHOWS Pie GRAPHS
    private void PieGraph() {

        try {
            String query = "select vendor,amount_spent from '123456789'";
            Statement state = conn.createStatement();
            ResultSet rs = state.executeQuery(query);
            DefaultPieDataset dataset = new DefaultPieDataset();
            while (rs.next()) {
                dataset.setValue(rs.getString("vendor"), Double.parseDouble(rs.getString("amount_spent")));
            }
            JFreeChart chart = ChartFactory.createPieChart("Graphical Bar Presentation of the total transactions", dataset, true, true, false);
            ChartFrame chartFrame = new ChartFrame(MembersName + " Transactions Graphichal Presentation", chart);
            chartFrame.setVisible(true);
            chartFrame.setSize(500, 650);

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    //Saving Charts
    private void SaveCharts() {
        String see = (String) JOptionPane.showInputDialog(null,
                "Choose the type of graph to view", "Graph Type Prompt",
                JOptionPane.QUESTION_MESSAGE, null, choises, choises[1]);
        switch (see) {
            case "Line Graph":
                try {
                    String query = "select vendor,amount_spent from '123456789'";
                    JDBCCategoryDataset dataset = new JDBCCategoryDataset(DataBaseConnector.newConnection(), query);
                    JFreeChart chart = ChartFactory.createLineChart("Graphical Line Presentation of the total transactions", "Vendor", "Total Points Spent", dataset, PlotOrientation.VERTICAL, false, true, true);
                    CategoryPlot plot = null;
                    BarRenderer render = null;
                    render = new BarRenderer();

                    final ChartRenderingInfo info = new ChartRenderingInfo(new StandardEntityCollection());
                    final File file = new File("chart.png");
                    JOptionPane.showMessageDialog(null, "Chart has been saved");
                    ChartUtilities.saveChartAsPNG(file, chart, 550, 350, info);
                } catch (SQLException | IOException ex) {
                    Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
                }
                break;
            case "Bar Graph":
                try {
                    String query = "select vendor,amount_spent from '123456789'";
                    JDBCCategoryDataset dataset = new JDBCCategoryDataset(DataBaseConnector.newConnection(), query);
                    JFreeChart chart = ChartFactory.createBarChart("Graphical Bar Presentation of the total transactions", "Vendor", "Total Points Spent", dataset, PlotOrientation.VERTICAL, false, true, true);

                    CategoryPlot plot = null;
                    BarRenderer render = null;
                    render = new BarRenderer();

                    final ChartRenderingInfo info = new ChartRenderingInfo(new StandardEntityCollection());
                    final File file = new File("chart.png");
                    JOptionPane.showMessageDialog(null, "Chart has been saved");
                    ChartUtilities.saveChartAsPNG(file, chart, 550, 350, info);

                } catch (SQLException | IOException ex) {
                    Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
                }
                break;
            case "Pie Chart":
                try {
                    String query = "select vendor,amount_spent from '123456789'";
                    Statement state = conn.createStatement();
                    ResultSet rs = state.executeQuery(query);
                    DefaultPieDataset dataset = new DefaultPieDataset();
                    while (rs.next()) {
                        dataset.setValue(rs.getString("vendor"), Double.parseDouble(rs.getString("amount_spent")));
                    }
                    JFreeChart chart = ChartFactory.createPieChart("Graphical Bar Presentation of the total transactions", dataset, true, true, false);
                    final ChartRenderingInfo info = new ChartRenderingInfo(new StandardEntityCollection());
                    final File file = new File("chart.png");
                    JOptionPane.showMessageDialog(null, "Chart has been saved");
                    ChartUtilities.saveChartAsPNG(file, chart, 550, 350, info);

                } catch (SQLException | IOException ex) {
                    Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
                }
                break;
            default:
                JOptionPane.showMessageDialog(null, "No chart was selected\nSystem will have to abort process");
                break;
        }

    }

    //Get DataBase Reports
    private void DataReport() {
        ArrayList vendorlist, productlist, spentlist, monthlist, datelist, validatorlist;

        vendorlist = new ArrayList();
        productlist = new ArrayList();
        spentlist = new ArrayList();
        monthlist = new ArrayList();
        datelist = new ArrayList();
        validatorlist = new ArrayList();

        try {
            String sql = "select * from '123456789'";
            PreparedStatement ps = conn.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();

            while (rs.next()) {
                vendorlist.add(" " + rs.getString("vendor") + " ");
                productlist.add(" " + rs.getString("product") + " ");
                spentlist.add(" Ksh " + rs.getString("amount_spent") + " ");
                monthlist.add(" " + rs.getString("month") + " ");
                datelist.add(" " + rs.getString("date") + " ");
                validatorlist.add(" " + rs.getString("validator") + " ");
            }
            vendordata = vendorlist.toString().replace("[", "").replace("]", "");
            Productdata = productlist.toString().replace("[", "").replace("]", "");
            spentdata = spentlist.toString().replace("[", "").replace("]", "");
            monthdata = monthlist.toString().replace("[", "").replace("]", "");
            datedata = datelist.toString().replace("[", "").replace("]", "");
            validationdata = validatorlist.toString().replace("[", "").replace("]", "");
            ps.close();
            rs.close();

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Generate Pdf from the supplied data
    private void generateReport() {
        DataReport();
        try {
            SaveCharts();
            Document document = new Document();
            PdfWriter.getInstance(document, new FileOutputStream("Report.pdf"));
            document.open();

            //Add Image To the Report
            Image image = Image.getInstance("bata.png");
            document.add(image);
            //The Heading
            document.add(new Paragraph("    Total Transactions Report",
                    FontFactory.getFont(FontFactory.TIMES_BOLD, 38, Font.BOLD, BaseColor.RED)));

            document.add(new Paragraph(new java.util.Date().toString(),
                    FontFactory.getFont(FontFactory.TIMES_BOLD, 14, Font.BOLD, BaseColor.BLACK)));

            document.add(new Paragraph("*********************************************************************************************************"));

            //Creates the Graph
            Image chartImage = Image.getInstance("chart.png");
            document.add(chartImage);

            document.add(new Paragraph("*********************************************************************************************************"));

            //Lists
            document.add(new Paragraph("                Detailed info of all Transactions",
                    FontFactory.getFont(FontFactory.TIMES_BOLD, 16, Font.BOLD, BaseColor.RED)));

            List list = new List();
            list.add("Total monthly Transactions Report :" + monthdata);
            list.add("Dates of Transactions Report:" + datedata);
            list.add("Vendors associated with the Transactions :" + vendordata);
            list.add("Total amount Spent per Transaction Report :" + spentdata);
            list.add("Product Aquired in each Transaction Report :" + Productdata);
            list.add("Validators of individual Transactions :" + validationdata);
            document.add(list);

            document.close();
            JOptionPane.showMessageDialog(null, "Process is complete");
        } catch (DocumentException | FileNotFoundException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    //Refresh Information
    public void RefreshInfo() {
        try {
            String sql = "select * from MembersTable where cardnumber = " + cardNumber;
            PreparedStatement ps = conn.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();

            if (rs.next()) {
                mailField.setText(rs.getString("email"));
                passField.setText(rs.getString("password"));
                visitorsName.setText(rs.getString("name"));
                phonefield.setText(rs.getString("phone_no"));
                cardfield.setText(rs.getString("cardnumber"));
                card_expiryField.setText(rs.getString("card_expiry_date"));

            }
            ps.close();
            rs.close();

        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
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

        buttonGroup1 = new javax.swing.ButtonGroup();
        jPopupMenu1 = new javax.swing.JPopupMenu();
        buttonGroup2 = new javax.swing.ButtonGroup();
        buttonGroup3 = new javax.swing.ButtonGroup();
        jLabel1 = new javax.swing.JLabel();
        visitorsName = new javax.swing.JLabel();
        jPanel1 = new javax.swing.JPanel();
        jLabel5 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        jLabel3 = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        jLabel7 = new javax.swing.JLabel();
        mailField = new javax.swing.JTextField();
        phonefield = new javax.swing.JTextField();
        cardfield = new javax.swing.JTextField();
        card_expiryField = new javax.swing.JTextField();
        jButton1 = new javax.swing.JButton();
        jButton2 = new javax.swing.JButton();
        checkPassword = new javax.swing.JCheckBox();
        passField = new javax.swing.JPasswordField();
        jPanel23 = new javax.swing.JPanel();
        jTabbedPane4 = new javax.swing.JTabbedPane();
        jPanel22 = new javax.swing.JPanel();
        jScrollPane2 = new javax.swing.JScrollPane();
        TransactionTable2 = new javax.swing.JTable();
        jPanel21 = new javax.swing.JPanel();
        jButton16 = new javax.swing.JButton();
        jButton17 = new javax.swing.JButton();
        jButton12 = new javax.swing.JButton();
        jPanel19 = new javax.swing.JPanel();
        jLabel31 = new javax.swing.JLabel();
        dayCombo3 = new javax.swing.JComboBox();
        jSeparator4 = new javax.swing.JSeparator();
        jLabel32 = new javax.swing.JLabel();
        vendorField3 = new javax.swing.JTextField();
        monthCombo3 = new javax.swing.JComboBox();
        jLabel30 = new javax.swing.JLabel();
        jButton9 = new javax.swing.JButton();
        jButton11 = new javax.swing.JButton();
        jPanel5 = new javax.swing.JPanel();
        jPanel13 = new javax.swing.JPanel();
        jPanel14 = new javax.swing.JPanel();
        jPanel15 = new javax.swing.JPanel();
        dayLabel = new javax.swing.JLabel();
        jPanel16 = new javax.swing.JPanel();
        transDate = new javax.swing.JLabel();
        jPanel17 = new javax.swing.JPanel();
        transTime = new javax.swing.JLabel();
        jPanel18 = new javax.swing.JPanel();
        physical = new javax.swing.JRadioButton();
        services = new javax.swing.JRadioButton();
        jSeparator1 = new javax.swing.JSeparator();
        jPanel20 = new javax.swing.JPanel();
        physicalName = new javax.swing.JTextField();
        jPanel24 = new javax.swing.JPanel();
        physicalQuantity = new javax.swing.JTextField();
        jPanel25 = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        serviceDescriptiontxt = new javax.swing.JTextArea();
        jPanel29 = new javax.swing.JPanel();
        physicalPrice = new javax.swing.JTextField();
        jLabel11 = new javax.swing.JLabel();
        jButton3 = new javax.swing.JButton();
        jButton4 = new javax.swing.JButton();
        totalpriceTxt = new javax.swing.JTextField();
        jLabel10 = new javax.swing.JLabel();
        jButton13 = new javax.swing.JButton();
        jPanel8 = new javax.swing.JPanel();
        graphPanel = new javax.swing.JPanel();
        jPanel26 = new javax.swing.JPanel();
        jLabel17 = new javax.swing.JLabel();
        jTextField3 = new javax.swing.JTextField();
        jPanel27 = new javax.swing.JPanel();
        jLabel18 = new javax.swing.JLabel();
        jTextField4 = new javax.swing.JTextField();
        jPanel28 = new javax.swing.JPanel();
        jScrollPane3 = new javax.swing.JScrollPane();
        jTextArea2 = new javax.swing.JTextArea();
        jButton5 = new javax.swing.JButton();
        jButton6 = new javax.swing.JButton();
        jButton7 = new javax.swing.JButton();
        jButton8 = new javax.swing.JButton();
        jScrollBar1 = new javax.swing.JScrollBar();
        jButton10 = new javax.swing.JButton();
        jPanel7 = new javax.swing.JPanel();
        jLabel12 = new javax.swing.JLabel();
        jPanel6 = new javax.swing.JPanel();
        remainingPointsLabel = new javax.swing.JLabel();
        jLabel14 = new javax.swing.JLabel();
        jPanel9 = new javax.swing.JPanel();
        monthLabel = new javax.swing.JLabel();
        jLabel16 = new javax.swing.JLabel();
        jPanel10 = new javax.swing.JPanel();
        expirydate = new javax.swing.JLabel();
        jPanel11 = new javax.swing.JPanel();
        expirytime = new javax.swing.JLabel();
        jPanel12 = new javax.swing.JPanel();
        jPanel2 = new javax.swing.JPanel();
        pieRadio = new javax.swing.JRadioButton();
        barRadio = new javax.swing.JRadioButton();
        lineRadio = new javax.swing.JRadioButton();
        jButton14 = new javax.swing.JButton();
        jPanel3 = new javax.swing.JPanel();
        dateLabel = new javax.swing.JLabel();
        timeLabel = new javax.swing.JLabel();
        jLabel8 = new javax.swing.JLabel();
        jLabel9 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        jMenuBar1 = new javax.swing.JMenuBar();
        jMenu1 = new javax.swing.JMenu();
        jMenu2 = new javax.swing.JMenu();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Members Graphical Interface");

        jLabel1.setFont(new java.awt.Font("Showcard Gothic", 3, 18)); // NOI18N
        jLabel1.setText("welcome,");

        visitorsName.setFont(new java.awt.Font("Showcard Gothic", 3, 24)); // NOI18N
        visitorsName.setForeground(new java.awt.Color(0, 153, 153));
        visitorsName.setText("VisitorsName");

        jPanel1.setBorder(javax.swing.BorderFactory.createTitledBorder("Members info,,,"));

        jLabel5.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel5.setText("Card No:");

        jLabel6.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel6.setText("User Password:");

        jLabel3.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel3.setText("Email:");

        jLabel4.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel4.setText("Phone No:");

        jLabel7.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel7.setText("Card Expiry Date:");

        mailField.setEditable(false);
        mailField.setFont(new java.awt.Font("Arial Narrow", 1, 12)); // NOI18N
        mailField.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        phonefield.setEditable(false);
        phonefield.setFont(new java.awt.Font("Arial Black", 1, 12)); // NOI18N
        phonefield.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        cardfield.setEditable(false);
        cardfield.setFont(new java.awt.Font("Arial Black", 1, 12)); // NOI18N
        cardfield.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        card_expiryField.setEditable(false);
        card_expiryField.setFont(new java.awt.Font("Arial Black", 1, 12)); // NOI18N
        card_expiryField.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jButton1.setFont(new java.awt.Font("sansserif", 3, 12)); // NOI18N
        jButton1.setText("Edit Information");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        jButton2.setFont(new java.awt.Font("sansserif", 3, 12)); // NOI18N
        jButton2.setText("Refresh Information");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        checkPassword.setText("Show Password");
        checkPassword.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                checkPasswordActionPerformed(evt);
            }
        });

        passField.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        passField.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        passField.setEchoChar('$');

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(mailField)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel3, javax.swing.GroupLayout.PREFERRED_SIZE, 95, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(0, 0, Short.MAX_VALUE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel4, javax.swing.GroupLayout.PREFERRED_SIZE, 131, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(35, 35, 35))
                    .addComponent(phonefield))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(checkPassword)
                    .addComponent(jLabel6)
                    .addComponent(passField))
                .addGap(39, 39, 39)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel5)
                            .addComponent(cardfield))
                        .addGap(43, 43, 43)))
                .addGap(31, 31, 31)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel7)
                    .addComponent(card_expiryField)
                    .addComponent(jButton2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap())
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel5, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel6, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel7, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(mailField)
                    .addComponent(phonefield)
                    .addComponent(cardfield)
                    .addComponent(card_expiryField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(passField))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(jButton2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jButton1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                        .addGap(118, 118, 118))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(checkPassword)
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
        );

        jPanel23.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jTabbedPane4.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        jTabbedPane4.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N

        jScrollPane2.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        TransactionTable2.setModel(new javax.swing.table.DefaultTableModel(
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
        TransactionTable2.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                TransactionTable2MouseClicked(evt);
            }
        });
        jScrollPane2.setViewportView(TransactionTable2);

        jPanel21.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jButton16.setFont(new java.awt.Font("AR JULIAN", 0, 12)); // NOI18N
        jButton16.setText("Graphical View");
        jButton16.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed(evt);
            }
        });

        jButton17.setBackground(new java.awt.Color(204, 204, 255));
        jButton17.setFont(new java.awt.Font("AR JULIAN", 0, 12)); // NOI18N
        jButton17.setText("Generate Report");
        jButton17.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton17ActionPerformed(evt);
            }
        });

        jButton12.setFont(new java.awt.Font("AR JULIAN", 0, 12)); // NOI18N
        jButton12.setText("Generate CSV file");
        jButton12.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton12ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel21Layout = new javax.swing.GroupLayout(jPanel21);
        jPanel21.setLayout(jPanel21Layout);
        jPanel21Layout.setHorizontalGroup(
            jPanel21Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel21Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jButton16, javax.swing.GroupLayout.DEFAULT_SIZE, 202, Short.MAX_VALUE)
                .addGap(43, 43, 43)
                .addComponent(jButton17, javax.swing.GroupLayout.DEFAULT_SIZE, 213, Short.MAX_VALUE)
                .addGap(37, 37, 37)
                .addComponent(jButton12, javax.swing.GroupLayout.DEFAULT_SIZE, 159, Short.MAX_VALUE)
                .addContainerGap())
        );
        jPanel21Layout.setVerticalGroup(
            jPanel21Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel21Layout.createSequentialGroup()
                .addGap(15, 15, 15)
                .addGroup(jPanel21Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel21Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                        .addComponent(jButton12, javax.swing.GroupLayout.PREFERRED_SIZE, 40, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addComponent(jButton17, javax.swing.GroupLayout.PREFERRED_SIZE, 40, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addGroup(jPanel21Layout.createSequentialGroup()
                        .addComponent(jButton16, javax.swing.GroupLayout.PREFERRED_SIZE, 41, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(0, 0, Short.MAX_VALUE)))
                .addContainerGap())
        );

        jPanel19.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        jPanel19.setEnabled(false);

        jLabel31.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel31.setText(":  Day");

        dayCombo3.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        dayCombo3.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "none", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" }));

        jSeparator4.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel32.setFont(new java.awt.Font("Arial Black", 1, 14)); // NOI18N
        jLabel32.setText("Vendor :");

        vendorField3.setToolTipText("Enter Vendors Name");

        monthCombo3.setFont(new java.awt.Font("Arial", 1, 12)); // NOI18N
        monthCombo3.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" }));

        jLabel30.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel30.setText("Month :");

        jButton9.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton9.setText("Submit Query");
        jButton9.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton9ActionPerformed(evt);
            }
        });

        jButton11.setFont(new java.awt.Font("sansserif", 1, 12)); // NOI18N
        jButton11.setText("Revert");
        jButton11.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton11ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel19Layout = new javax.swing.GroupLayout(jPanel19);
        jPanel19.setLayout(jPanel19Layout);
        jPanel19Layout.setHorizontalGroup(
            jPanel19Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jSeparator4, javax.swing.GroupLayout.Alignment.TRAILING)
            .addGroup(jPanel19Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jButton9, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(30, 30, 30)
                .addComponent(jButton11, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
            .addGroup(jPanel19Layout.createSequentialGroup()
                .addGap(9, 9, 9)
                .addComponent(jLabel30)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(monthCombo3, 0, 126, Short.MAX_VALUE)
                .addGap(18, 18, 18)
                .addComponent(jLabel31)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(dayCombo3, 0, 127, Short.MAX_VALUE)
                .addGap(34, 34, 34)
                .addComponent(jLabel32)
                .addGap(18, 18, 18)
                .addComponent(vendorField3, javax.swing.GroupLayout.PREFERRED_SIZE, 149, javax.swing.GroupLayout.PREFERRED_SIZE))
        );
        jPanel19Layout.setVerticalGroup(
            jPanel19Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel19Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel19Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel31)
                    .addComponent(dayCombo3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(monthCombo3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel30)
                    .addComponent(jLabel32)
                    .addComponent(vendorField3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jSeparator4, javax.swing.GroupLayout.PREFERRED_SIZE, 10, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel19Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton11)
                    .addComponent(jButton9))
                .addContainerGap())
        );

        javax.swing.GroupLayout jPanel22Layout = new javax.swing.GroupLayout(jPanel22);
        jPanel22.setLayout(jPanel22Layout);
        jPanel22Layout.setHorizontalGroup(
            jPanel22Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel22Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel22Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jScrollPane2)
                    .addComponent(jPanel21, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jPanel19, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
        );
        jPanel22Layout.setVerticalGroup(
            jPanel22Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel22Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jScrollPane2, javax.swing.GroupLayout.PREFERRED_SIZE, 292, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel19, javax.swing.GroupLayout.PREFERRED_SIZE, 108, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jPanel21, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jTabbedPane4.addTab("Query Transactions", jPanel22);

        jPanel13.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jPanel14.setBorder(javax.swing.BorderFactory.createTitledBorder("Transaction Details"));

        jPanel15.setBorder(javax.swing.BorderFactory.createTitledBorder("Day"));

        dayLabel.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        dayLabel.setText("day");

        javax.swing.GroupLayout jPanel15Layout = new javax.swing.GroupLayout(jPanel15);
        jPanel15.setLayout(jPanel15Layout);
        jPanel15Layout.setHorizontalGroup(
            jPanel15Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(dayLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        jPanel15Layout.setVerticalGroup(
            jPanel15Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel15Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(dayLabel, javax.swing.GroupLayout.PREFERRED_SIZE, 27, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jPanel16.setBorder(javax.swing.BorderFactory.createTitledBorder(javax.swing.BorderFactory.createTitledBorder(""), "Date"));

        transDate.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        transDate.setText("date");

        javax.swing.GroupLayout jPanel16Layout = new javax.swing.GroupLayout(jPanel16);
        jPanel16.setLayout(jPanel16Layout);
        jPanel16Layout.setHorizontalGroup(
            jPanel16Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel16Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(transDate, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(25, 25, 25))
        );
        jPanel16Layout.setVerticalGroup(
            jPanel16Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel16Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(transDate, javax.swing.GroupLayout.PREFERRED_SIZE, 29, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jPanel17.setBorder(javax.swing.BorderFactory.createTitledBorder("Time"));

        transTime.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        transTime.setText("Time");

        javax.swing.GroupLayout jPanel17Layout = new javax.swing.GroupLayout(jPanel17);
        jPanel17.setLayout(jPanel17Layout);
        jPanel17Layout.setHorizontalGroup(
            jPanel17Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel17Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(transTime, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel17Layout.setVerticalGroup(
            jPanel17Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel17Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(transTime, javax.swing.GroupLayout.PREFERRED_SIZE, 27, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        javax.swing.GroupLayout jPanel14Layout = new javax.swing.GroupLayout(jPanel14);
        jPanel14.setLayout(jPanel14Layout);
        jPanel14Layout.setHorizontalGroup(
            jPanel14Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel14Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jPanel15, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jPanel16, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel17, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel14Layout.setVerticalGroup(
            jPanel14Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel15, javax.swing.GroupLayout.PREFERRED_SIZE, 61, javax.swing.GroupLayout.PREFERRED_SIZE)
            .addGroup(jPanel14Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                .addComponent(jPanel17, javax.swing.GroupLayout.PREFERRED_SIZE, 61, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addComponent(jPanel16, javax.swing.GroupLayout.PREFERRED_SIZE, 59, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jPanel18.setBorder(javax.swing.BorderFactory.createTitledBorder("Product Details"));

        buttonGroup3.add(physical);
        physical.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        physical.setText("Physical Item");

        buttonGroup3.add(services);
        services.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        services.setText("Services");

        jSeparator1.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jPanel20.setBorder(javax.swing.BorderFactory.createTitledBorder("Name"));

        physicalName.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        physicalName.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        javax.swing.GroupLayout jPanel20Layout = new javax.swing.GroupLayout(jPanel20);
        jPanel20.setLayout(jPanel20Layout);
        jPanel20Layout.setHorizontalGroup(
            jPanel20Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(physicalName)
        );
        jPanel20Layout.setVerticalGroup(
            jPanel20Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel20Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(physicalName, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jPanel24.setBorder(javax.swing.BorderFactory.createTitledBorder("Quantity"));

        physicalQuantity.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        physicalQuantity.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        javax.swing.GroupLayout jPanel24Layout = new javax.swing.GroupLayout(jPanel24);
        jPanel24.setLayout(jPanel24Layout);
        jPanel24Layout.setHorizontalGroup(
            jPanel24Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(physicalQuantity)
        );
        jPanel24Layout.setVerticalGroup(
            jPanel24Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel24Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(physicalQuantity, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jPanel25.setBorder(javax.swing.BorderFactory.createTitledBorder("Description"));

        serviceDescriptiontxt.setColumns(20);
        serviceDescriptiontxt.setRows(5);
        jScrollPane1.setViewportView(serviceDescriptiontxt);

        javax.swing.GroupLayout jPanel25Layout = new javax.swing.GroupLayout(jPanel25);
        jPanel25.setLayout(jPanel25Layout);
        jPanel25Layout.setHorizontalGroup(
            jPanel25Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1)
        );
        jPanel25Layout.setVerticalGroup(
            jPanel25Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 123, Short.MAX_VALUE)
        );

        jPanel29.setBorder(javax.swing.BorderFactory.createTitledBorder("Price"));

        physicalPrice.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        physicalPrice.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel11.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel11.setText("Ksh");

        javax.swing.GroupLayout jPanel29Layout = new javax.swing.GroupLayout(jPanel29);
        jPanel29.setLayout(jPanel29Layout);
        jPanel29Layout.setHorizontalGroup(
            jPanel29Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel29Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(jLabel11)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(physicalPrice, javax.swing.GroupLayout.PREFERRED_SIZE, 75, javax.swing.GroupLayout.PREFERRED_SIZE))
        );
        jPanel29Layout.setVerticalGroup(
            jPanel29Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel29Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addGroup(jPanel29Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(physicalPrice, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel11)))
        );

        javax.swing.GroupLayout jPanel18Layout = new javax.swing.GroupLayout(jPanel18);
        jPanel18.setLayout(jPanel18Layout);
        jPanel18Layout.setHorizontalGroup(
            jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel18Layout.createSequentialGroup()
                .addGroup(jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel18Layout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(services)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel25, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                    .addGroup(jPanel18Layout.createSequentialGroup()
                        .addComponent(physical)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel20, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(45, 45, 45)
                        .addComponent(jPanel24, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel29, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(jSeparator1))
                .addContainerGap())
        );
        jPanel18Layout.setVerticalGroup(
            jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel18Layout.createSequentialGroup()
                .addGroup(jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel18Layout.createSequentialGroup()
                        .addGap(19, 19, 19)
                        .addComponent(physical))
                    .addGroup(jPanel18Layout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addComponent(jPanel24, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jPanel20, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jPanel29, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jSeparator1, javax.swing.GroupLayout.PREFERRED_SIZE, 11, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel18Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel18Layout.createSequentialGroup()
                        .addComponent(services)
                        .addGap(0, 0, Short.MAX_VALUE))
                    .addComponent(jPanel25, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap())
        );

        jButton3.setBackground(new java.awt.Color(0, 204, 0));
        jButton3.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton3.setText("Send Order");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });

        jButton4.setBackground(new java.awt.Color(255, 0, 0));
        jButton4.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton4.setText("Cancel Order");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        totalpriceTxt.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel10.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel10.setText("  Ksh");

        jButton13.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton13.setText("Total Price ");
        jButton13.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton13ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel13Layout = new javax.swing.GroupLayout(jPanel13);
        jPanel13.setLayout(jPanel13Layout);
        jPanel13Layout.setHorizontalGroup(
            jPanel13Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel14, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel13Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel13Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(jPanel18, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel13Layout.createSequentialGroup()
                        .addComponent(jButton13)
                        .addGap(1, 1, 1)
                        .addComponent(jLabel10)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(totalpriceTxt, javax.swing.GroupLayout.PREFERRED_SIZE, 84, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(156, 156, 156)
                        .addComponent(jButton3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton4, javax.swing.GroupLayout.PREFERRED_SIZE, 137, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addContainerGap())
        );
        jPanel13Layout.setVerticalGroup(
            jPanel13Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel13Layout.createSequentialGroup()
                .addComponent(jPanel14, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel18, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel13Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton3, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jButton4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(totalpriceTxt)
                    .addComponent(jLabel10)
                    .addComponent(jButton13, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap())
        );

        javax.swing.GroupLayout jPanel5Layout = new javax.swing.GroupLayout(jPanel5);
        jPanel5.setLayout(jPanel5Layout);
        jPanel5Layout.setHorizontalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel13, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        jPanel5Layout.setVerticalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel5Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jPanel13, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );

        jTabbedPane4.addTab("Place an Order", jPanel5);

        graphPanel.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jPanel26.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel17.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel17.setText("From :");

        jTextField3.setText("jTextField3");
        jTextField3.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        javax.swing.GroupLayout jPanel26Layout = new javax.swing.GroupLayout(jPanel26);
        jPanel26.setLayout(jPanel26Layout);
        jPanel26Layout.setHorizontalGroup(
            jPanel26Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel26Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel17)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jTextField3, javax.swing.GroupLayout.DEFAULT_SIZE, 345, Short.MAX_VALUE)
                .addContainerGap())
        );
        jPanel26Layout.setVerticalGroup(
            jPanel26Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel26Layout.createSequentialGroup()
                .addGroup(jPanel26Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jTextField3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel17))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        jPanel27.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jLabel18.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel18.setText("Ref :");

        jTextField4.setText("jTextField4");
        jTextField4.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        javax.swing.GroupLayout jPanel27Layout = new javax.swing.GroupLayout(jPanel27);
        jPanel27.setLayout(jPanel27Layout);
        jPanel27Layout.setHorizontalGroup(
            jPanel27Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel27Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel18)
                .addGap(18, 18, 18)
                .addComponent(jTextField4, javax.swing.GroupLayout.DEFAULT_SIZE, 351, Short.MAX_VALUE)
                .addContainerGap())
        );
        jPanel27Layout.setVerticalGroup(
            jPanel27Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel27Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel27Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jTextField4, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel18)))
        );

        jPanel28.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        jTextArea2.setColumns(20);
        jTextArea2.setRows(5);
        jScrollPane3.setViewportView(jTextArea2);

        javax.swing.GroupLayout jPanel28Layout = new javax.swing.GroupLayout(jPanel28);
        jPanel28.setLayout(jPanel28Layout);
        jPanel28Layout.setHorizontalGroup(
            jPanel28Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane3)
        );
        jPanel28Layout.setVerticalGroup(
            jPanel28Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 275, Short.MAX_VALUE)
        );

        jButton5.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton5.setText("Reply");
        jButton5.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed1(evt);
            }
        });

        jButton6.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton6.setText("Save");

        jButton7.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton7.setText("Delete");

        jButton8.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton8.setText("Scroll Up");

        jButton10.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jButton10.setText("Scroll Down");

        javax.swing.GroupLayout graphPanelLayout = new javax.swing.GroupLayout(graphPanel);
        graphPanel.setLayout(graphPanelLayout);
        graphPanelLayout.setHorizontalGroup(
            graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(graphPanelLayout.createSequentialGroup()
                .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(graphPanelLayout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jPanel28, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addGroup(graphPanelLayout.createSequentialGroup()
                                .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(jPanel27, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                                    .addComponent(jPanel26, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                                .addComponent(jScrollBar1, javax.swing.GroupLayout.PREFERRED_SIZE, 54, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                                .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(jButton8, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                    .addComponent(jButton10, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))))
                    .addGroup(graphPanelLayout.createSequentialGroup()
                        .addGap(183, 183, 183)
                        .addComponent(jButton5, javax.swing.GroupLayout.PREFERRED_SIZE, 160, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jButton6, javax.swing.GroupLayout.PREFERRED_SIZE, 143, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jButton7, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                .addContainerGap())
        );
        graphPanelLayout.setVerticalGroup(
            graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(graphPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addGroup(graphPanelLayout.createSequentialGroup()
                        .addComponent(jPanel26, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel27, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(jScrollBar1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(graphPanelLayout.createSequentialGroup()
                        .addComponent(jButton8, javax.swing.GroupLayout.PREFERRED_SIZE, 37, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(jButton10, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel28, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(graphPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jButton7, javax.swing.GroupLayout.PREFERRED_SIZE, 37, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jButton5, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jButton6, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addGap(0, 0, 0))
        );

        javax.swing.GroupLayout jPanel8Layout = new javax.swing.GroupLayout(jPanel8);
        jPanel8.setLayout(jPanel8Layout);
        jPanel8Layout.setHorizontalGroup(
            jPanel8Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel8Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(graphPanel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );
        jPanel8Layout.setVerticalGroup(
            jPanel8Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel8Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(graphPanel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );

        jTabbedPane4.addTab("Notifications", jPanel8);

        jPanel7.setBorder(javax.swing.BorderFactory.createTitledBorder("Holder's Card Details"));

        jLabel12.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel12.setText("Total Available Points :");

        jPanel6.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        remainingPointsLabel.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        remainingPointsLabel.setForeground(new java.awt.Color(204, 0, 0));
        remainingPointsLabel.setText("2000");

        javax.swing.GroupLayout jPanel6Layout = new javax.swing.GroupLayout(jPanel6);
        jPanel6.setLayout(jPanel6Layout);
        jPanel6Layout.setHorizontalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel6Layout.createSequentialGroup()
                .addGap(21, 21, 21)
                .addComponent(remainingPointsLabel)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel6Layout.setVerticalGroup(
            jPanel6Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(remainingPointsLabel, javax.swing.GroupLayout.Alignment.TRAILING)
        );

        jLabel14.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel14.setText("Points Validity Period :");

        jPanel9.setBorder(javax.swing.BorderFactory.createTitledBorder("Monthly"));

        monthLabel.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        monthLabel.setForeground(new java.awt.Color(0, 51, 204));
        monthLabel.setText("Monthly");

        javax.swing.GroupLayout jPanel9Layout = new javax.swing.GroupLayout(jPanel9);
        jPanel9.setLayout(jPanel9Layout);
        jPanel9Layout.setHorizontalGroup(
            jPanel9Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel9Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(monthLabel)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel9Layout.setVerticalGroup(
            jPanel9Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel9Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(monthLabel))
        );

        jLabel16.setFont(new java.awt.Font("AR JULIAN", 1, 14)); // NOI18N
        jLabel16.setText("Date & Time to points expiry  :");

        jPanel10.setBorder(javax.swing.BorderFactory.createTitledBorder("days to expiry"));

        expirydate.setFont(new java.awt.Font("AR JULIAN", 1, 18)); // NOI18N
        expirydate.setForeground(new java.awt.Color(255, 0, 0));
        expirydate.setText("Date");

        javax.swing.GroupLayout jPanel10Layout = new javax.swing.GroupLayout(jPanel10);
        jPanel10.setLayout(jPanel10Layout);
        jPanel10Layout.setHorizontalGroup(
            jPanel10Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel10Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(expirydate, javax.swing.GroupLayout.PREFERRED_SIZE, 126, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel10Layout.setVerticalGroup(
            jPanel10Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel10Layout.createSequentialGroup()
                .addGap(2, 2, 2)
                .addComponent(expirydate, javax.swing.GroupLayout.DEFAULT_SIZE, 25, Short.MAX_VALUE))
        );

        jPanel11.setBorder(javax.swing.BorderFactory.createTitledBorder("time to expiry"));

        expirytime.setFont(new java.awt.Font("AR JULIAN", 1, 18)); // NOI18N
        expirytime.setForeground(new java.awt.Color(255, 0, 0));
        expirytime.setText("Time");

        javax.swing.GroupLayout jPanel11Layout = new javax.swing.GroupLayout(jPanel11);
        jPanel11.setLayout(jPanel11Layout);
        jPanel11Layout.setHorizontalGroup(
            jPanel11Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel11Layout.createSequentialGroup()
                .addComponent(expirytime, javax.swing.GroupLayout.PREFERRED_SIZE, 118, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel11Layout.setVerticalGroup(
            jPanel11Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel11Layout.createSequentialGroup()
                .addGap(6, 6, 6)
                .addComponent(expirytime, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        javax.swing.GroupLayout jPanel7Layout = new javax.swing.GroupLayout(jPanel7);
        jPanel7.setLayout(jPanel7Layout);
        jPanel7Layout.setHorizontalGroup(
            jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel7Layout.createSequentialGroup()
                .addGap(12, 12, 12)
                .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel7Layout.createSequentialGroup()
                        .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel7Layout.createSequentialGroup()
                                .addComponent(jLabel14, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                .addGap(5, 5, 5))
                            .addComponent(jLabel12, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                        .addGap(22, 22, 22)
                        .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jPanel9, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                            .addComponent(jPanel6, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                    .addGroup(jPanel7Layout.createSequentialGroup()
                        .addGap(0, 0, Short.MAX_VALUE)
                        .addComponent(jPanel10, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(12, 12, 12)
                        .addComponent(jPanel11, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addContainerGap())))
            .addGroup(jPanel7Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel16, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel7Layout.setVerticalGroup(
            jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel7Layout.createSequentialGroup()
                .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel6, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel7Layout.createSequentialGroup()
                        .addGap(15, 15, 15)
                        .addComponent(jLabel12, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(12, 12, 12)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel9, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel14, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jLabel16, javax.swing.GroupLayout.PREFERRED_SIZE, 28, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel7Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel10, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jPanel11, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
        );

        jPanel12.setBorder(javax.swing.BorderFactory.createTitledBorder("Graph Options"));

        jPanel2.setBorder(javax.swing.BorderFactory.createTitledBorder("Select Graph Type"));

        buttonGroup2.add(pieRadio);
        pieRadio.setText("Pie Chart");

        buttonGroup2.add(barRadio);
        barRadio.setText("Bar Graph");

        buttonGroup2.add(lineRadio);
        lineRadio.setText("Line Graph");

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(pieRadio, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(38, 38, 38)
                .addComponent(barRadio, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(30, 30, 30)
                .addComponent(lineRadio, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(pieRadio)
                    .addComponent(barRadio)
                    .addComponent(lineRadio)))
        );

        javax.swing.GroupLayout jPanel12Layout = new javax.swing.GroupLayout(jPanel12);
        jPanel12.setLayout(jPanel12Layout);
        jPanel12Layout.setHorizontalGroup(
            jPanel12Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel12Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel12Layout.setVerticalGroup(
            jPanel12Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel12Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(jPanel2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        jButton14.setBackground(new java.awt.Color(255, 0, 0));
        jButton14.setFont(new java.awt.Font("AR JULIAN", 1, 18)); // NOI18N
        jButton14.setText("Log Out");
        jButton14.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton14ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel23Layout = new javax.swing.GroupLayout(jPanel23);
        jPanel23.setLayout(jPanel23Layout);
        jPanel23Layout.setHorizontalGroup(
            jPanel23Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel23Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jTabbedPane4, javax.swing.GroupLayout.DEFAULT_SIZE, 712, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(jPanel23Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel7, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jPanel12, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(jPanel23Layout.createSequentialGroup()
                        .addGap(95, 95, 95)
                        .addComponent(jButton14, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(131, 131, 131)))
                .addContainerGap())
        );
        jPanel23Layout.setVerticalGroup(
            jPanel23Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel23Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel23Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jTabbedPane4, javax.swing.GroupLayout.PREFERRED_SIZE, 0, Short.MAX_VALUE)
                    .addGroup(jPanel23Layout.createSequentialGroup()
                        .addComponent(jPanel7, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(jPanel12, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(58, 58, 58)
                        .addComponent(jButton14, javax.swing.GroupLayout.PREFERRED_SIZE, 54, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(51, 51, 51)))
                .addContainerGap())
        );

        jPanel3.setBorder(javax.swing.BorderFactory.createTitledBorder(""));

        dateLabel.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        dateLabel.setText("jLabel10");

        timeLabel.setFont(new java.awt.Font("sansserif", 1, 18)); // NOI18N
        timeLabel.setText("jLabel11");

        jLabel8.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel8.setText("Date :");

        jLabel9.setFont(new java.awt.Font("sansserif", 1, 14)); // NOI18N
        jLabel9.setText("Time :");

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jLabel9, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel8, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(dateLabel, javax.swing.GroupLayout.DEFAULT_SIZE, 143, Short.MAX_VALUE)
                    .addComponent(timeLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(dateLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel8, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addGap(4, 4, 4)
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(timeLabel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(jLabel9, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
        );

        jLabel2.setFont(new java.awt.Font("Lucida Calligraphy", 1, 18)); // NOI18N
        jLabel2.setForeground(new java.awt.Color(0, 0, 153));
        jLabel2.setText("Members Graphical User Interface");

        jMenu1.setText("File");
        jMenuBar1.add(jMenu1);

        jMenu2.setText("Edit");
        jMenuBar1.add(jMenu2);

        setJMenuBar(jMenuBar1);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(layout.createSequentialGroup()
                        .addComponent(jPanel23, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addGap(8, 8, 8))
                    .addGroup(layout.createSequentialGroup()
                        .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(layout.createSequentialGroup()
                                .addComponent(visitorsName, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                .addGap(423, 423, 423))
                            .addGroup(layout.createSequentialGroup()
                                .addComponent(jLabel1, javax.swing.GroupLayout.PREFERRED_SIZE, 105, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addGap(245, 245, 245)
                                .addComponent(jLabel2, javax.swing.GroupLayout.DEFAULT_SIZE, 497, Short.MAX_VALUE)
                                .addGap(110, 110, 110)))
                        .addComponent(jPanel3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(layout.createSequentialGroup()
                        .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(layout.createSequentialGroup()
                                .addGap(21, 21, 21)
                                .addComponent(jLabel1))
                            .addGroup(layout.createSequentialGroup()
                                .addContainerGap()
                                .addComponent(jLabel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(visitorsName))
                    .addGroup(layout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(jPanel3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, 150, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jPanel23, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGap(30, 30, 30))
        );

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void checkPasswordActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_checkPasswordActionPerformed
        // TODO add your handling code here:
        if (checkPassword.isSelected()) {
            passField.setEchoChar((char) 0);
        } else {
            passField.setEchoChar('$');
        }
    }//GEN-LAST:event_checkPasswordActionPerformed

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        if (passField.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "The system cannot access your data\n Please enter your password or contact the administrator");
        } else {
            EditDatailsForm edit = new EditDatailsForm();
            edit.getString(passField.getText());
            edit.setVisible(true);
        }
    }//GEN-LAST:event_jButton1ActionPerformed

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        // TODO add your handling code here:
        RefreshInfo();

    }//GEN-LAST:event_jButton2ActionPerformed

    private void jButton14ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton14ActionPerformed
        // TODO add your handling code here:
        LoginClass log = new LoginClass();
        log.setVisible(true);
        dispose();

    }//GEN-LAST:event_jButton14ActionPerformed

    private void jButton5ActionPerformed1(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed1
        // TODO add your handling code here:
        getDay();
    }//GEN-LAST:event_jButton5ActionPerformed1

    private void jButton13ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton13ActionPerformed
        // TODO add your handling code here:
        if (physicalPrice.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "Please Enter the price and Quantity first...");
        } else {
            int pay = Integer.parseInt(physicalPrice.getText());
            int Totalquantity = Integer.parseInt(physicalQuantity.getText());

            int Total = pay * Totalquantity;
            totalpriceTxt.setText("" + Total);
        }
    }//GEN-LAST:event_jButton13ActionPerformed

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        // TODO add your handling code here:
        physicalName.setText("");
        physicalPrice.setText("");
        physicalQuantity.setText("");
        totalpriceTxt.setText("");
        serviceDescriptiontxt.setText("");
    }//GEN-LAST:event_jButton4ActionPerformed

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        // TODO add your handling code here:
        String mail = null;
        if (physicalPrice.getText().equals("")) {
            JOptionPane.showMessageDialog(null, "Please Enter the price and Quantity first...");
        } else {
            int pay = Integer.parseInt(physicalPrice.getText());
            int Totalquantity = Integer.parseInt(physicalQuantity.getText());

            int Total = pay * Totalquantity;
            totalpriceTxt.setText("" + Total);

            if (physical.isSelected()) {
                mail = "Product Name:" + physicalName.getText() + "\n Quantity :" + physicalQuantity.getText()
                + "\n Price :" + physicalPrice.getText() + "\n Transaction Day :" + dayLabel.getText()
                + "\n DATE :" + dateLabel.getText() + "\n TIME :" + timeLabel.getText() + "\n Item price :" + physicalPrice.getText() + "\n Total Amount :" + Total;
            } else if (services.isSelected()) {
                mail = "Service offered.\nDescription :\n" + serviceDescriptiontxt.getText();
            }
            String prompt = JOptionPane.showInputDialog(null, "Enter your pin Number", "Pin Prompt", WIDTH);
            if (prompt.equals("")) {
                JOptionPane.showMessageDialog(null, "Please enter a valid pin");
            } else {
                JOptionPane.showMessageDialog(null, "You are sending the mail;\nSubject :\nContents :" + mail);
                mailStmpMethod(mail, null);
            }
        }
    }//GEN-LAST:event_jButton3ActionPerformed

    private void jButton11ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton11ActionPerformed
        // TODO add your handling code here:
        addData("xxxl");
    }//GEN-LAST:event_jButton11ActionPerformed

    private void jButton9ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton9ActionPerformed
        // TODO add your handling code here:
        if (vendorField3.getText().equals("")) {
            getQuery();
        } else {
            Sql = "select entry,product,price,quantity,vendor,amount_spent,rem_points from '"
            + cardNumber + "' where vendor = '" + vendorField3.getText() + "'";
            addData(Sql);
            vendorField3.setText("");
        }
    }//GEN-LAST:event_jButton9ActionPerformed

    private void jButton12ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton12ActionPerformed
        // TODO add your handling code here:
        String fileName = JOptionPane.showInputDialog(null, "Enter File Name", "File Name Prompt", 0);
        generateCSV("C:\\Users\\"
            + System.getProperty("user.name") + "\\Desktop\\" + fileName + ".csv");
            JOptionPane.showMessageDialog(null, fileName + ".csv file created in path" + "\nC:\\Users\\"
                + System.getProperty("user.name") + "\\Desktop\\");
    }//GEN-LAST:event_jButton12ActionPerformed

    private void jButton17ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton17ActionPerformed
        // TODO add your handling code here:
        generateReport();
    }//GEN-LAST:event_jButton17ActionPerformed

    private void jButton5ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed
        // TODO add your handling code here:
        if (pieRadio.isSelected()) {
            PieGraph();
        } else if (barRadio.isSelected()) {
            BarGraph();
        } else if (lineRadio.isSelected()) {
            LineGraph();
        } else {
            JOptionPane.showMessageDialog(null, "Please select a 'Graph Type' to view");
        }
    }//GEN-LAST:event_jButton5ActionPerformed

    private void TransactionTable2MouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_TransactionTable2MouseClicked
        try {
            // TODO add your handling code here:

            int row = TransactionTable2.getSelectedRow();
            String id = (TransactionTable2.getModel().getValueAt(row, 0))
            .toString();

            String sql = "select * from '" + cardNumber + "' where entry = " + id;
            PreparedStatement ps = conn.prepareStatement(sql);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                Product = rs.getString("product");
                vendor = rs.getString("vendor");
                price = rs.getString("price");
                time = rs.getString("date") + " : " + rs.getString("time");
                Quantity = rs.getString("quantity");
                month = rs.getString("month");
                spent = rs.getString("amount_spent");
                Validation = rs.getString("validator");
                JOptionPane.showMessageDialog(null,
                    "Product : " + Product + "\nVendor : " + vendor + "\nPrice : KSh"
                    + price + "\nMonth : " + month + "\nDate $ Time : " + time + "\nQuantity : "
                    + Quantity + "\nAmount Spent : KSh" + spent
                    + "\nThis transaction was validated by : " + Validation,
                    "Details of Selected Transactions", WIDTH, null);

            }
        } catch (SQLException ex) {
            Logger.getLogger(MembersGui.class.getName()).log(Level.SEVERE, null, ex);
        }
    }//GEN-LAST:event_TransactionTable2MouseClicked

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
            java.util.logging.Logger.getLogger(MembersGui.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(MembersGui.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(MembersGui.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(MembersGui.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new MembersGui().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTable TransactionTable2;
    private javax.swing.JRadioButton barRadio;
    private javax.swing.ButtonGroup buttonGroup1;
    private javax.swing.ButtonGroup buttonGroup2;
    private javax.swing.ButtonGroup buttonGroup3;
    private javax.swing.JTextField card_expiryField;
    private javax.swing.JTextField cardfield;
    private javax.swing.JCheckBox checkPassword;
    private javax.swing.JLabel dateLabel;
    private javax.swing.JComboBox dayCombo3;
    private javax.swing.JLabel dayLabel;
    private javax.swing.JLabel expirydate;
    private javax.swing.JLabel expirytime;
    private javax.swing.JPanel graphPanel;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton10;
    private javax.swing.JButton jButton11;
    private javax.swing.JButton jButton12;
    private javax.swing.JButton jButton13;
    private javax.swing.JButton jButton14;
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
    private javax.swing.JLabel jLabel12;
    private javax.swing.JLabel jLabel14;
    private javax.swing.JLabel jLabel16;
    private javax.swing.JLabel jLabel17;
    private javax.swing.JLabel jLabel18;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel30;
    private javax.swing.JLabel jLabel31;
    private javax.swing.JLabel jLabel32;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JLabel jLabel9;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JMenu jMenu2;
    private javax.swing.JMenuBar jMenuBar1;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel10;
    private javax.swing.JPanel jPanel11;
    private javax.swing.JPanel jPanel12;
    private javax.swing.JPanel jPanel13;
    private javax.swing.JPanel jPanel14;
    private javax.swing.JPanel jPanel15;
    private javax.swing.JPanel jPanel16;
    private javax.swing.JPanel jPanel17;
    private javax.swing.JPanel jPanel18;
    private javax.swing.JPanel jPanel19;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel20;
    private javax.swing.JPanel jPanel21;
    private javax.swing.JPanel jPanel22;
    private javax.swing.JPanel jPanel23;
    private javax.swing.JPanel jPanel24;
    private javax.swing.JPanel jPanel25;
    private javax.swing.JPanel jPanel26;
    private javax.swing.JPanel jPanel27;
    private javax.swing.JPanel jPanel28;
    private javax.swing.JPanel jPanel29;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JPanel jPanel5;
    private javax.swing.JPanel jPanel6;
    private javax.swing.JPanel jPanel7;
    private javax.swing.JPanel jPanel8;
    private javax.swing.JPanel jPanel9;
    private javax.swing.JPopupMenu jPopupMenu1;
    private javax.swing.JScrollBar jScrollBar1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JSeparator jSeparator1;
    private javax.swing.JSeparator jSeparator4;
    private javax.swing.JTabbedPane jTabbedPane4;
    private javax.swing.JTextArea jTextArea2;
    private javax.swing.JTextField jTextField3;
    private javax.swing.JTextField jTextField4;
    private javax.swing.JRadioButton lineRadio;
    private javax.swing.JTextField mailField;
    private javax.swing.JComboBox monthCombo3;
    private javax.swing.JLabel monthLabel;
    private javax.swing.JPasswordField passField;
    private javax.swing.JTextField phonefield;
    private javax.swing.JRadioButton physical;
    private javax.swing.JTextField physicalName;
    private javax.swing.JTextField physicalPrice;
    private javax.swing.JTextField physicalQuantity;
    private javax.swing.JRadioButton pieRadio;
    private javax.swing.JLabel remainingPointsLabel;
    private javax.swing.JTextArea serviceDescriptiontxt;
    private javax.swing.JRadioButton services;
    private javax.swing.JLabel timeLabel;
    private javax.swing.JTextField totalpriceTxt;
    private javax.swing.JLabel transDate;
    private javax.swing.JLabel transTime;
    private javax.swing.JTextField vendorField3;
    private javax.swing.JLabel visitorsName;
    // End of variables declaration//GEN-END:variables
}
