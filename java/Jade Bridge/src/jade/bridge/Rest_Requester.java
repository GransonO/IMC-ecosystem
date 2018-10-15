/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package jade.bridge;

import com.sun.jersey.api.client.Client;
import com.sun.jersey.api.client.ClientResponse;
import com.sun.jersey.api.client.WebResource;
import com.sun.jersey.core.util.MultivaluedMapImpl;
import javax.ws.rs.core.MultivaluedMap;

/**
 *
 * @author Granson
 * Gets sends the data to an rest protocol 
 */
public class Rest_Requester {
    //Send data to the api
    //Respond with a statement
    //Test a responce
    
    public String RegisterProtocol(String account_id,String name,String phone,String email,String entry_date,String last_visit,String customer_store_id){
        
            String output = "Nothing";
	try {

		Client client = Client.create();
		WebResource webResource = client.resource("http://178.62.100.247/admin/API/JADE_API/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "1");
                formData.add("account_id", account_id);
                formData.add("name", name);
                formData.add("phone", phone);
                formData.add("email", email);
                formData.add("entry_date", entry_date);
                formData.add("last_visit", last_visit);
                formData.add("customer_store_id", customer_store_id);
                formData.add("token", "AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPooaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzOmuVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw==ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAkRUApI9iCATvPuLa2hzJgvWU985gedPbsG551fAobaBIfDBm5FvISGUXmd72YhYFkCWd6H7CBwbBNjk9gcdb6xOcEorsabzXX1c8TSpUmSouitVuYLZs6g3BHwI8qWuAZYYlBn8/uwYPePgPeXfRQUVyrMFYW1O0QI7hVJB8KrhRB6xRMfDvzSqX4oTKtd82b8Koe7ccrLBzYH7n/JVwD9NC6mSRJFR/n7Ud03+rC4TCHeqtDJGWLz3SIzkFeywihTMIck6otCIF8TeXsgAS5yLYBL4jmomXb5lGipR8S5Cfzt/Yc5rwEAHNvrpf9Hr3TnsfbFb/pYcPiwlBL3eFzQ== rsa-key-20170404");
                ClientResponse response = webResource.type("application/x-www-form-urlencoded").post(ClientResponse.class, formData);

		if (response.getStatus() != 200) {
		   throw new RuntimeException("Failed : HTTP error code : "
			+ response.getStatus());
		}
		output = response.getEntity(String.class);

	  } catch (Exception e) {

		e.printStackTrace();
                output = "\nCould not reach the network. Check you connection \n" + e;

	  }
            return output;
    }  

    
    public String RestProtocol(String store_id,String transaction_number,String transaction_time,String customer_id, String total_spend,String cashier_id,String customer_phone_no,String ItemID,String SalesRepID,String Quantity){
        
            String output = "Nothing";
	try {

		Client client = Client.create();
		WebResource webResource = client
		   .resource("http://178.62.100.247/admin/API/JADE_API/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "2");
                formData.add("store_id", store_id);
                formData.add("transaction_number", transaction_number);
                formData.add("transaction_time", transaction_time);
                formData.add("customer_id", customer_id);
                formData.add("total_spend", total_spend);
                formData.add("cashier_id", cashier_id);
                formData.add("customer_phone_no", customer_phone_no);
                
                formData.add("ItemID", ItemID);
                formData.add("SalesRepID", SalesRepID);
                formData.add("Quantity", Quantity);
                formData.add("token", "AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPooaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzOmuVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw==ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAkRUApI9iCATvPuLa2hzJgvWU985gedPbsG551fAobaBIfDBm5FvISGUXmd72YhYFkCWd6H7CBwbBNjk9gcdb6xOcEorsabzXX1c8TSpUmSouitVuYLZs6g3BHwI8qWuAZYYlBn8/uwYPePgPeXfRQUVyrMFYW1O0QI7hVJB8KrhRB6xRMfDvzSqX4oTKtd82b8Koe7ccrLBzYH7n/JVwD9NC6mSRJFR/n7Ud03+rC4TCHeqtDJGWLz3SIzkFeywihTMIck6otCIF8TeXsgAS5yLYBL4jmomXb5lGipR8S5Cfzt/Yc5rwEAHNvrpf9Hr3TnsfbFb/pYcPiwlBL3eFzQ== rsa-key-20170404");
                ClientResponse response = webResource.type("application/x-www-form-urlencoded").post(ClientResponse.class, formData);

		if (response.getStatus() != 200) {
		   throw new RuntimeException("Failed : HTTP error code : "
			+ response.getStatus());
		}
		output = response.getEntity(String.class);

		System.out.println("Output from Server .... \n");
		System.out.println(output);

	  } catch (Exception e) {

		e.printStackTrace();
                output = "\nCould not reach the network. Check you connection ";

	  }
            return output;
    }  

    
    
    public String PingServer(String store_id){
        
            String output = "Nothing";
	try {

		Client client = Client.create();
		WebResource webResource = client
		   .resource("http://178.62.100.247/admin/API/JADE_API/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "3");
                formData.add("store_id", store_id);
                formData.add("token", "AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPooaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzOmuVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw==ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAkRUApI9iCATvPuLa2hzJgvWU985gedPbsG551fAobaBIfDBm5FvISGUXmd72YhYFkCWd6H7CBwbBNjk9gcdb6xOcEorsabzXX1c8TSpUmSouitVuYLZs6g3BHwI8qWuAZYYlBn8/uwYPePgPeXfRQUVyrMFYW1O0QI7hVJB8KrhRB6xRMfDvzSqX4oTKtd82b8Koe7ccrLBzYH7n/JVwD9NC6mSRJFR/n7Ud03+rC4TCHeqtDJGWLz3SIzkFeywihTMIck6otCIF8TeXsgAS5yLYBL4jmomXb5lGipR8S5Cfzt/Yc5rwEAHNvrpf9Hr3TnsfbFb/pYcPiwlBL3eFzQ== rsa-key-20170404");
                ClientResponse response = webResource.type("application/x-www-form-urlencoded").post(ClientResponse.class, formData);

		if (response.getStatus() != 200) {
		   throw new RuntimeException("Failed : HTTP error code : "
			+ response.getStatus());
		}
		output = response.getEntity(String.class);

		System.out.println("Output from Server .... \n");
		System.out.println(output);

	  } catch (Exception e) {

		e.printStackTrace();
                output = "\nCould not reach the network. Check you connection ";

	  }
            return output;
    } 
    }
