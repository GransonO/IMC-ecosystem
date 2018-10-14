//USER REGISTRATION
$('button#user_reg_btn').on('click',function(){
	var username = $('input#register_user_name').val();
	var phone_no = $('input#register_user_mobile').val();
	var reg_email = $('input#email_user_reg').val();
	var reg_password = $('input#reg_user_password').val();
	
	$.notify("It works.","warn");

/* 	if(($.trim(username) != '')&&($.trim(phone_no) != '')&&($.trim(reg_password) != '')){
		$.post('../query/php_files/user_registration.php', 
		{username:username,phone_no:phone_no,reg_email:reg_email,reg_password:reg_password},
		function(data){
		 if(data == 1 ){
				//If posting done to DB.
				$.notify("Registration Successful.\n Redirects to login page.", "success");				
								
			}else if(data == 0 ){
				//If posting is NOT done to DB.
				$.notify("Registration Failed.", "error");
				$('input#register_user_mobile').val(phone_no);		
				$('input#register_user_name').val("");
				$('input#email_user_reg').val("");
				$('input#reg_user_password').val("");
				
			}else if(data == 2 ){
				//If posting is NOT done to DB.
				$.notify("The member already exists.\nDouble entry error.", "warn");
				$('input#register_user_mobile').val(phone_no);		
				$('input#register_user_name').val("");
				$('input#email_user_reg').val("");
				$('input#reg_user_password').val("");
			} 
		});
	}else{
		$.notify("You have to fill in all the required entries.","warn");
	} */
});

//Member Registration
$('input#reg-member-submit').on('click',function(){
	var mem_name = $('input#register_member_name').val();
	var gender = $('select#gender').val();
	var phone_no = $('input#register_mobile').val();
	var reg_email = $('input#email_reg').val();
	var service_type = $('select#service_type').val();
	var customer_type = $('select#customer_type').val();
	var customer_comments = $('textarea#customer_comments').val();

	if(($.trim(mem_name) != '')&&($.trim(phone_no) != '')&&($.trim(service_type) != '')){
		$.post('../query/php_files/member_registration.php', 
		{mem_name:mem_name,gender:gender,phone_no:phone_no,reg_email:reg_email,service_type:service_type,customer_type:customer_type,customer_comments:customer_comments},
		function(data){
		 if(data == 1 ){
				//If posting done to DB.
				$.notify("Registration Successful.", "success");
				
				$('input#register_member_name').val("");
				$('select#gender').val("Gender");
				$('input#register_mobile').val("");
				$('input#email_reg').val("");
				$('select#service_type').val("Service Offerd*");
				$('select#customer_type').val("Customer Type");
				$('textarea#customer_comments').val("");
				
			}else if(data == 0 ){
				//If posting is NOT done to DB.
				$.notify("Registration Failed.", "error");
				$('input#register_mobile').val(phone_no);		
				$('input#register_member_name').val("");
				$('input#email_reg').val("");
				$('textarea#customer_comments').val("");
				
			}else if(data == 2 ){
				//If posting is NOT done to DB.
				$.notify("The member already exists.\nDouble entry error.", "warn");
				$('input#register_mobile').val(phone_no);		
				$('input#register_member_name').val("");
				$('input#email_reg').val("");
				$('textarea#customer_comments').val("");
			} 
		});
	}else{
		$.notify("You have to fill in all the required entries.","warn");
	}
});

//Mobile number verification for Customer details
$('input#rew_mobile_btn').on('click',function(){
	var phone_no = $('input#rew_mobile_ver').val();
	if($.trim(phone_no) != ''){
		$.post('../query/php_files/rewards_verify.php', {phone_no:phone_no},function(data){
			if(data != 0){
				//If number exists in the BD.
				var myObj = JSON.parse(data);
				var name =	myObj.name;
				var email =	myObj.email;
				var points = myObj.points;
				$.notify("Customer data is as populated.", "success");
				$('input#rew_mobile_verified').val(phone_no);
				$('input#rew_name').val(name);
				$('input#rew_mail').val(email);
				$("#points_value").text(points);
			}else{
				//If number does not exist in the DB.( Can Register)
				$.notify("Customer details verification NOT successful !!!\n The phone number is not registred.", "info");
				$('input#rew_mobile_ver').val(phone_no);
			}
		});
	}else{
		$.notify("Empty request !!!\n The phone number is required to run the request.", "warn");
	}
});

//Member redemptions posting
$('input#btn_update_data').on('click',function(){

	var prev_id = $('input#rew_mobile_ver').val();
	var mem_name = $('input#rew_name').val();
	var phone_no = $('input#rew_mobile_verified').val();
	var reg_email = $('input#rew_mail').val();
	var customer_type = $('select#customer_type').val();

	if(($.trim(phone_no) != '')&&($.trim(prev_id) != '')&&($.trim(reg_email) != '')){
		$.post('../query/php_files/customer_update.php',
		{mem_name:mem_name,phone_no:phone_no,prev_id:prev_id,reg_email:reg_email,customer_type:customer_type},
		function(data){
			 if(data == "1" ){
				//If posting is DONE to DB.
				$.notify("Update processed successfully.", "success");				
				$('input#rew_mobile_ver').val("");
				$('input#rew_name').val("");
				$('input#rew_mobile_verified').val("");
				$('input#rew_mail').val("");
				$('input#customer_type').val("");
			}
			else{
				//If posting is NOT done to DB.
				$.notify("Update failed.\n Contact support for assistance", "error");				
				$('input#rew_mobile_ver').val(prev_id);
				$('input#rew_name').val(mem_name);
				$('input#rew_mobile_verified').val(phone_no);
				$('input#rew_mail').val(reg_email);
				$('input#customer_type').val(customer_type);
			}
			});
		}else{
		$.notify("Fill all required entries to complete the process.", "info");
	}
});

//Checks if Chrome is in use
$(document).ready(function(){
	// please note, 
	// that IE11 now returns undefined again for window.chrome
	// and new Opera 30 outputs true for window.chrome
	// and new IE Edge outputs to true now for window.chrome
	// and if not iOS Chrome check
	// so use the below updated condition
	var isChromium = window.chrome,
		winNav = window.navigator,
		vendorName = winNav.vendor,
		isOpera = winNav.userAgent.indexOf("OPR") > -1,
		isIEedge = winNav.userAgent.indexOf("Edge") > -1,
		isIOSChrome = winNav.userAgent.match("CriOS");

	if(isIOSChrome){
	   //console.log('is Google Chrome on IOS');	   
	$.notify("Browser Identified.\nYou are using Chrome browser on IOS", "success");
	
	} else if(isChromium !== null && isChromium !== undefined && vendorName === "Google Inc." && isOpera == false && isIEedge == false) {
	  //console.log('is Google Chrome');
	$.notify("Browser Identified.\nYou are using Chrome browser on Windows", "success");
	
	} else { 
	   //console.log('not Google Chrome');
	$.notify("Browser Identified.\nSome elements might not work as expected.\nPlease use Chrome browser for the best experience.", "error");
	//   alert('not Google Chrome'); 
	}
});
