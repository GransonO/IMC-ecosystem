/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package hqcustomerfeeder;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import javax.swing.JOptionPane;
import net.proteanit.sql.DbUtils;

/**
 *
 * @author Granson
 */
public class SQL_INSERT {

    String Phone_Number, Email_Address, Customer_Name, Reg_date, gen_number;
    Connection conn = null;
    String sql_statement,DB_NAME;
    
    public SQL_INSERT(String Name,String Email, String Phone, String date, String Gen_ID) throws IOException {
        DB_NAME = new String(Files.readAllBytes(Paths.get("C:\\INDULGENCE_DATA\\DATABASE_NAME.txt")));
        Phone_Number = Phone;
        Email_Address = Email;
        Customer_Name = Name;
        Reg_date = date; 
        gen_number = Gen_ID;
        
        sql_statement = 
        " INSERT INTO [" + DB_NAME + "].[dbo].[Customer] ( " +
        "       [AccountNumber]" +
        "      ,[AccountTypeID]" +
        "      ,[Address2]" +
        "      ,[AssessFinanceCharges]" +
        "      ,[Company]" +
        "      ,[Country]" +
        "      ,[CustomDate1]" +
        "      ,[CustomDate2]" +
        "      ,[CustomDate3]" +
        "      ,[CustomDate4]" +
        "      ,[CustomDate5]" +
        "      ,[CustomNumber1]" +
        "      ,[CustomNumber2]" +
        "      ,[CustomNumber3]" +
        "      ,[CustomNumber4]" +
        "      ,[CustomNumber5]" +
        "      ,[CustomText1]" +
        "      ,[CustomText2]" +
        "      ,[CustomText3]" +
        "      ,[CustomText4]" +
        "      ,[CustomText5]" +
        "      ,[GlobalCustomer]" +
        "      ,[HQID]" +
        "      ,[LastStartingDate]" +
        "      ,[LastClosingDate]" +
        "      ,[LastUpdated]" +
        "      ,[LimitPurchase]" +
        "      ,[LastClosingBalance]" +
        "      ,[PrimaryShipToID]" +
        "      ,[State]" +
        "      ,[StoreID]" +
        "      ,[ID]" +
        "      ,[LayawayCustomer]" +
        "      ,[Employee]" +
        "      ,[FirstName]" +
        "      ,[LastName]" +
        "      ,[Address]" +
        "      ,[City]" +
        "      ,[Zip]" +
        "      ,[AccountBalance]" +
        "      ,[CreditLimit]" +
        "      ,[TotalSales]" +
        "      ,[AccountOpened]" +
        "      ,[LastVisit]" +
        "      ,[TotalVisits]" +
        "      ,[TotalSavings]" +
        "      ,[CurrentDiscount]" +
        "      ,[PriceLevel]" +
        "      ,[TaxExempt]" +
        "      ,[Notes]" +
        "      ,[Title]" +
        "      ,[EmailAddress]" +
        "      ,[TaxNumber]" +
        "      ,[PictureName]" +
        "      ,[DefaultShippingServiceID]" +
        "      ,[PhoneNumber]" +
        "      ,[FaxNumber]" +
        "      ,[CashierID]" +
        "      ,[SalesRepID]" +
        "      ,[Vouchers]" +
        ") VALUES ('" +
        Phone_Number +
        "'      ,0" +
        "      ,'000000'" +
        "      ,1" +
        "      ,'JADE COL'" +
        "      ,'KENYA'" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09'" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,'jade_col'" +
        "      ,'jade_col'" +
        "      ,'jade_col'" +
        "      ,'jade_col'" +
        "      ,'jade_col'" +
        "      ,1" +
        "      ,0" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09','" +
        Reg_date +
        "'      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,'Kenya'" +
        "      ,0   ,'" +
        gen_number +
        "'      ,0" +
        "      ,71, '" +
        Customer_Name +
        "'      ,''" +
        "      ,'000'" +
        "      ,'Kenyan'" +
        "      ,'0100'" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,'1999-09-09'" +
        "      ,'1999-09-09'" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "      ,''" +
        "      ,'','" +
        Email_Address +
        "'     ,''" +
        "      ,''" +
        "      ,0,'" +
        Phone_Number +
        "'      ,''" +
        "      ,0" +
        "      ,0" +
        "      ,0" +
        "); ";
    }
   
    public String InsertData() throws SQLException, ClassNotFoundException, InstantiationException, IllegalAccessException{

            conn = SqlConnector.newConnection();

            try {
                try (PreparedStatement Pst = conn.prepareStatement(sql_statement)) {
                    Pst.execute();
                    Pst.close();
                }

                return "Customer Insertion successful. AccountNumber : " + Phone_Number;
            
            } catch (Exception ex) {
                
                return "\n >> Insertion error : AccountNumber : " + Phone_Number + " Error: \n >> " + ex + "\n\n >> " + sql_statement;
            }finally{
                conn.close();
            }
            
    };
}
