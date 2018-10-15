/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package imcdashboard;

import com.sun.jersey.api.client.Client;
import com.sun.jersey.api.client.ClientResponse;
import com.sun.jersey.api.client.WebResource;
import com.sun.jersey.core.util.MultivaluedMapImpl;
import java.util.Random;
import javax.ws.rs.core.MultivaluedMap;

/**
 *
 * @author Granson
 */
public class Rest_Framework {
    
    public String LoginToServer(String name,String password){
        
            String output = "Nothing";
            
	try {
		Client client = Client.create();
		WebResource webResource = client
		   .resource("http://178.62.100.247/admin/API/master_dash/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "1");
                formData.add("name", name);
                formData.add("password", password);
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
    
        
    public String AddUsersToServer(String name,String email,String user,String customer){
        
            String output = "Nothing";
            
	try {
		Client client = Client.create();
		WebResource webResource = client
		   .resource("http://178.62.100.247/admin/API/master_dash/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "2");
                formData.add("name", name);
                formData.add("email", email);
                formData.add("user", user);
                formData.add("client", customer);
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
        
    public String AuthenticateUser(String name){
        
            String output = "Nothing";
            Random rand = new Random(); 
            String authenticate = (rand.nextInt(50000)) + ""; 
            
	try {
		Client client = Client.create();
		WebResource webResource = client
		   .resource("http://178.62.100.247/admin/API/master_dash/index.php");
                MultivaluedMap formData = new MultivaluedMapImpl();
                formData.add("action", "3");
                formData.add("name", name);
                formData.add("authenticate_id", authenticate);
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
