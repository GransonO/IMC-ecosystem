//Logs The user into the dashboard.
$('input#log_me_in_btn').on('click',function(){
	
	var login_username = $('input#login_username').val();
	var login_pass = $('input#login_pass').val();
	var selected_profile = $('p#profile_txt').text();

	
 	if(($.trim(login_username) != '')&&($.trim(login_pass) != '')&&($.trim(selected_profile) != 'indulgence')){
		$.post('../query/php_files/entry_login.php', 
		{login_username:login_username,login_pass:login_pass,selected_profile:selected_profile},
		function(data){
			if(data == 1){
				//If email exists in DB.
				swal({
					  title: "Login error!",
					  text: "The email and password do not match\n. Please contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
			}else if(data == 0){
				//If posting is NOT done to DB.
				swal({
					  title: "Registration error!",
					  text: "You are not registered yet.\n Please register to access the platform",
					  type: "info",
					  timer: 3000,
					  showConfirmButton: false
					});
			}else if(data == 3){
				swal({
					  title: "Profile error!",
					  text: "You cannot access this account. Kindly check your profile and try again.",
					  type: "warning",
					  timer: 3000,
					  showConfirmButton: false
					});			
			}else{			
		
 			if(data == 2){
				//MANAGEMENT
				window.open("../dash",'_self',false);	
			}else if(data == 5){
				//MARKETING
				window.open("../dash",'_self',false);
				
			}else if(data == 8){
				//FRONTDESK
				window.open("../dash/stats_guests.php",'_self',false);
				
			}else if(data == 7){
				//IT
				window.open("../dash/itspt",'_self',false);	
				
			} 
			}
			});	
		}
		else{
			
			swal({
				  title: "Entry Warning!",
				  text: "Please enter your Username, Password and Profile to login.\n",
				  type: "warning",
				  timer: 2500,
				  showConfirmButton: false
				});
		} 
});

//Forgot Password functions.
$('input#recover_pass_submit').on('click',function(){
	
	var recover_pass_name = $('input#recover_pass_name').val();
	var recover_pass_email = $('input#recover_pass_email').val();
	
		swal({
			  title: "Validating...",
			  text: "Your password will be reset. Do you approve of this action?",
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Yes, Go On!",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 Send_reset_Mail(recover_pass_name,recover_pass_email);
					}
			});	
 
});

//Reward Guest Function.
$('input#query_user_submit').on('click',function(){
	//Get members number
	//Compare points value to table of offers
	//Display offers for the member
	var reward_id = $('input#reward_id').val();
	
 	if(($.trim(reward_id) != "")){
			
			$.post('../query/php_files/points_validator.php',
			{reward_id:reward_id},
			function(data){
			var love = JSON.parse(data);
			var puff = (love.rewards).toString();
			//var selector = document.getElementById("reward_selector");
			//selector.Child = "";
					var entries = puff.split(',');
					var len = entries.length;
			
			var stand = "";	
			if(love.my_status > 1000){
				stand = "Good Customer";
			}else{
				stand = "Not there yet!!";
				}
					
					$('input#reward_name').val(love.name);
					$('input#reward_status').val(stand);
	
					var i = 0;
					for (;entries[i];) {
						appender(entries[i]);
						i++;
					}
			});	
			
		}else{

		
			swal({
					title:"Entry error",
					text:"Please enter the member's ID",
					timer:2000,
					showConfirmButton:false
			});
		}
});

function appender(value){
		var selector = document.getElementById("reward_selector");
						
		var option = document.createElement("option");
		option.setAttribute("value",value);
		option.text = value;
		selector.appendChild(option);
	}

//Reward Guest Function.
$('input#reward_guest_submit').on('click',function(){
				
		var reward_name = $('input#reward_name').val();		
		var reward_option = $('select#reward_selector').val();	
		var reward_id = $('input#reward_id').val();	
		swal({
			  title: "Rewarding " + reward_name,
			  text: "The reward selected : " + reward_option,
			  type: "success",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Reward " + reward_name,
			  showCancelButton: true,
			  closeOnConfirm: false,
				},function(isConfirm){
					if (isConfirm) {
					//Reward the individual function
					swal({
						  title: "Validating...",
						  text: "Process the current transaction",
						  type: "info",
						  confirmButtonColor: "#1dbf07",
						  confirmButtonText: "Yes, Go On!",
						  showCancelButton: true,
						  closeOnConfirm: false,
						  showLoaderOnConfirm: true,
							},function(isConfirm){
								if (isConfirm) {
								reward_member(reward_id,reward_option,reward_name);
								}
						});	
					}else{
						 swal({
							  title: "Rejected!",
							  text: reward_name + " turned down the offer",
							  type: "warning",
							  showConfirmButton: true
							});
					}
			});	

 
});

function reward_member(reward_id,reward_option,reward_name){
	$.post('../query/php_files/redemption_func.php',
	{reward_id:reward_id,reward_option:reward_option},
	function(data){
		//Reward success return 1
		  if(data == 1){
			swal({
			title: "Success",
			text: reward_name + " successfully rewarded.",
			type: "success",
			showConfirmButton: true});
		}else{
		//Reward failed return 0
			swal({
			title: "Error",
			text: "Request failure.Please contact support ID: " + data,
			type: "warning",
			showConfirmButton: true});
			}	  
		});
}

//Sending Reset email
function Send_reset_Mail(recover_pass_name,recover_pass_email){
 	
 	if(($.trim(recover_pass_name) != '')&&($.trim(recover_pass_email) != '')){
		
		$.post('../../query/php_files/recover_password.php', 
		{recover_pass_name:recover_pass_name,recover_pass_email:recover_pass_email},
		function(data){
			//alert(data);
				if(data == 1){
						//If email exists in DB.
						swal({
							  title: "Verification error!",
							  text: "The username and email do not match\n. Please contact support for assistance",
							  type: "error",
							  timer: 2500,
							  showConfirmButton: false
							});
					}else if(data == 0){
						//If posting is NOT done to DB.
						swal({
							  title: "Username error!",
							  text: "The username is not present. Contact Support for assistance.",
							  type: "info",
							  timer: 3000,
							  showConfirmButton: false
							});
						
					}else if(data == 3){			
				
					swal({
							  title: "Reset Error!",
							  text: "Something just happened, and it isn't right. Please Contact Support ",
							  type: "success",
							  timer: 3000,
							  showConfirmButton: false
							});	
					}else if(data == 2){			
				
					//SEND MAIL TO Email
						swal({
								  title: "Reset Success!",
								  text: "A password reset link has been sent to your email. Use the link to change your password. If mail not received, contact support for assistance.",
								  type: "success",
								  timer: 3000,
								  showConfirmButton: false
								});	
						} 
			});	
		}
		else{
			
			swal({
				  title: "Entry Warning!",
				  text: "Please enter your Username and Email before submitting.\n",
				  type: "warning",
				  timer: 2500,
				  showConfirmButton: false
				});
		}	
	
};

//Reset Password functions.
$('input#reset_password_submit').on('click',function(){
	
	var reset_password = $('input#reset_password').val();
	var reset_password_confirm = $('input#reset_password_confirm').val();

	if(($.trim(reset_password) != '')&&($.trim(reset_password_confirm) != '')){		
	
 	if(reset_password == reset_password_confirm ){
		
		$.post('../../query/php_files/password_reset.php', 
		{reset_password:reset_password},
		function(data){
			
			if(data == 1){
				//If email exists in DB.
				swal({
					  title: "Reset error!",
					  text: "A reset error occurred. Contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
			}else  if(data == 2){			
		
				//Reset Su8ccess
						
					swal({
					  title: "Reset Success!",
					  text: "Your Password was reset. Would you like to log in now?",
					  type: "success",
					  confirmButtonColor: "#1dbf07",
					  confirmButtonText: "Okay, Let's Go!",
					  showCancelButton: true,
					  closeOnConfirm: false,
					  showLoaderOnConfirm: true,
						},function(isConfirm){
							if (isConfirm) {
								window.open("../../ini",'_self',false);
							}
					});
				
				}
			});				
			
		}
		else{
			swal({
				  title: "March error!",
				  text: "The Password does not match. Please try again.\n",
				  type: "error",
				  timer: 2500,
				  showConfirmButton: false
				});
			
		} 
	
		
	}else{
		swal({
				  title: "Entry Warning!",
				  text: "Please enter your New Password and Confirm it before submitting.\n",
				  type: "warning",
				  timer: 2500,
				  showConfirmButton: false
				});
	}
	});

//Register new User to Privileged tables.
$('input#register_user_submit').on('click',function(){
	
	var register_username = $('input#register_username').val();
	var register_email = $('input#register_email').val();
	var register_pass = $('input#register_password').val();
	var register_confirm_password = $('input#register_confirm_password').val();
		
 	if( register_confirm_password == register_pass ){
		
		if(register_pass.length < 8){
			
			swal({
				  title: "info!",
				  text: "Your Password should have at least 8 characters\n",
				  type: "info",
				  timer: 2500,
				  showConfirmButton: false
				});
			
		}else{
				
 			if(($.trim(register_username) != '')&&($.trim(register_email) != '')&&($.trim(register_pass) != '')){
			$.post('../query/php_files/user_registration.php', 
			{register_username:register_username,register_email:register_email,register_pass:register_pass},
			function(data){
				if(data == 1){
					//Posting done
					window.open("../ini/",'_self',false);
				}else if(data == 0){
					//If posting is NOT done to DB.
					swal({
					  title: "Registration error!",
					  text: "Your details could not be posted. Please try again.",
					  type: "info",
					  timer: 3000,
					  showConfirmButton: false
					});
				}
			});	
			}
			else{
				
				swal({
					  title: "Entry Warning!",
					  text: "Please fill all the entries before submitting.\n",
					  type: "warning",
					  timer: 2500,
					  showConfirmButton: false
					});
			}		
		}
						
	}else{
		
		swal({
				  title: "Passwords Error",
				  text: "Your Passwords don't march.\n Kindly try again.",
				  type: "info",
				  timer: 2500,
				  showConfirmButton: false
				});
		
	} 

});

//Register new Customer to Member Contacts tables.
$('input#register_new_guest_submit').on('click',function(){
	
	var guest_register_first_name = $('input#register_first_name').val();
	var guest_register_last_name = $('input#register_last_name').val();
	var guest_register_email = $('input#register_email_address').val();
	var guest_register_phone = $('input#register_phone_number').val();
	var guest_register_id_card = $('input#register_id_card').val();
	var guest_gender_type = $('select#gender_type').val();
	
	if(($.trim(guest_register_first_name) != '')&&($.trim(guest_register_email) != '')&&($.trim(guest_register_id_card) != '')&&($.trim(guest_gender_type) != '')){	
		
			swal({
			  title: "Processing...",
			  text: "Add the customers details to the program\n Name: "+ guest_register_first_name +" Email: "+ guest_register_email,
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Yes, Go On!",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 register_member_email(guest_register_first_name,guest_register_last_name,guest_register_email,guest_register_phone,guest_register_id_card,guest_gender_type);
					}
			});	
		}else{
		swal({
			  title: "Entries error",
			  type: "warning",
			  text: "You have to enter the recipient email address,ID or Passport,Gender & First Name.",
			  timer: 2500,
			  showConfirmButton: false
			});
	}
});

function register_member_email(guest_register_first_name,guest_register_last_name,guest_register_email,guest_register_phone,guest_register_id_card,guest_gender_type){
		
		$.post('../../query/php_files/guest_registration.php', 
		{guest_register_first_name:guest_register_first_name,guest_register_last_name:guest_register_last_name,
			guest_register_email:guest_register_email,guest_register_phone:guest_register_phone,
			guest_register_id_card:guest_register_id_card,guest_gender_type:guest_gender_type},
		function(data){
		
		
		if(data == 2 ){
				//If posting done to DB.
					
				swal({
					  title: "Registration success!",
					  text: "The member has been registered successfully.",
					  type: "success",
					  confirmButtonColor: "#1dbf07",
					  confirmButtonText: "Continue",
					  showCancelButton: false,
					  closeOnConfirm: true
						},function(isConfirm){
							if (isConfirm) {
							window.open("../",'_self',false);
							}
				});	
				
			}else if(data == 1){
				//If email exists in DB.
				swal({
					  title: "Duplicate error!",
					  text: "The email is already in use. Please contact support for assistance",
					  type: "error",
					  timer: 3500,
					  showConfirmButton: false
					});
				}else if(data == 0){
				//If email exists in DB.
				swal({
					  title: "Posting error!",
					  text: "The registration was not completed. Please contact support for assistance",
					  type: "error",
					  timer: 3500,
					  showConfirmButton: false
					});
				}else {
				//If posting is NOT done to DB.
				swal({
					  title: "Posting error!",
					  text: "Am sorry, your details could not be posted. Please contact support for assistance",
					  type: "error",
					  timer: 3500,
					  showConfirmButton: false
					});
			} 
		});
}

//Do the admin invitation
$('input#invite_user_submit').on('click',function(){
	
	var recipient_mail = $('input#invite_email').val();
	var register_name = $('input#invite_name').val();
	var profile_type = $('select#profile_type').val();
	var register_asignee = $('p#user_txt').text();
	var message_type = 1;

	if(($.trim(recipient_mail) != '')&&($.trim(register_name) != '')){
		
			swal({
			  title: "Hello there!",
			  text: "Please validate this email before sending:\n" + recipient_mail,
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Okay, Submit!",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 Send_The_Mail(recipient_mail,message_type,register_name,profile_type,register_asignee);
					}
			});		
		}
		else{
			
			swal({
				  title: "Entry error!",
				  text: "Please enter the User's Name and Email before submitting.\n",
				  type: "error",
				  timer: 2500,
				  showConfirmButton: false
				});
		}

});

function Send_The_Mail(recipient_mail,message_type,register_name,profile_type,register_asignee){	
	if(($.trim(recipient_mail) != '')){		
		$.post('../query/php_files/admin_mailer.php', 
		{recipient_mail:recipient_mail,message_type:message_type,register_name:register_name,
		profile_type:profile_type,register_asignee},
		function(data){
		
			if(data == 2 ){
				//If posting done to DB.
					
				swal({
					  title: "Mail success!",
					  text: "The invite email has been sent successfully.",
					  type: "success",
					  confirmButtonColor: "#1dbf07",
					  confirmButtonText: "Okay, Submit!",
					  showCancelButton: false,
					  closeOnConfirm: true
						},function(isConfirm){
							if (isConfirm) {
							window.open("../dash",'_self',false);
							}
				});	
				
				}else if(data == 'x'){
				//If email exists in DB.
				swal({
					  title: "Duplicate error!",
					  text: "The email is already in use. Please contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
				}else {
				//If posting is NOT done to DB.
				swal({
					  title: "Posting error!",
					  text: "Am sorry, your details could not be posted. Please contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
			} 
		});
	}else{
		swal({
			  title: "Entries error",
			  type: "warning",
			  text: "You have to enter the recipient email address.",
			  timer: 2500,
			  showConfirmButton: false
			});
	}	
}


$('input#admin_cancel').on('click',function(){
	window.history.back();	
});

//Do the guest invitation
$('input#invite_guest_submit').on('click',function(){
	
	var guest_mail = $('input#invite_guest_email').val();
	var guest_name = $('input#invite_guest_name').val();

	if(($.trim(guest_mail) != '')&&($.trim(guest_name) != '')){
		
			swal({
			  title: "Hello there!",
			  text: "Please validate this email before sending:\n" + guest_mail,
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Okay, Submit!",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 Send_Guest_Mail(guest_mail,guest_name);
					}
			});		
		}
		else{
			
			swal({
				  title: "Entry error!",
				  text: "Please enter the guest's Name and Email before submitting.\n",
				  type: "error",
				  timer: 2500,
				  showConfirmButton: false
				});
		}

});

function Send_Guest_Mail(guest_mail,guest_name){	
	if(($.trim(guest_mail) != '')){		
		$.post('../query/php_files/guest_mailer.php', 
		{guest_mail:guest_mail,guest_name:guest_name},
		function(data){		
			if(data == 2 ){
				//If posting done to DB.
					
				swal({
					  title: "Mail success!",
					  text: "The invite email has been sent successfully.",
					  type: "success",
					  confirmButtonColor: "#1dbf07",
					  confirmButtonText: "Okay, Submit!",
					  showCancelButton: false,
					  closeOnConfirm: true
						},function(isConfirm){
							if (isConfirm) {
							window.open("../",'_self',false);
							}
				});	
				
				}else if(data == 'x'){
				//If email exists in DB.
				swal({
					  title: "Duplicate error!",
					  text: "The email is already in use. Please contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
				}else {
				//If posting is NOT done to DB.
				swal({
					  title: "Posting error!",
					  text: "Am sorry, your details could not be posted. Please contact support for assistance",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
			} 
		});
	}else{
		swal({
			  title: "Entries error",
			  type: "warning",
			  text: "You have to enter the recipient email address.",
			  timer: 2500,
			  showConfirmButton: false
			});
	}	
}


//Register new Customer to Member Contacts tables.
$('input#new_guest_extra_submit').on('click',function(){
	
	var register_first_name = $('input#register_first_name').val();
	var register_last_name = $('input#register_last_name').val();
	var register_phone_number = $('input#register_phone_number').val();
	var register_email_address = $('input#register_email_address').val();
	var state = $('input#state').val();
	var destination_description = $('textarea#destination_description').val();
	var weekend_entry = $('input#weekend_entry').val();
	var experience_description = $('textarea#experience_description').val();
	
	if(($.trim(experience_description) != '')&&($.trim(destination_description) != '')){	
		
			swal({
			  title: "Posting...",
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "continue",
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 post_member_details(register_first_name,register_last_name,register_phone_number,register_email_address,state,destination_description,weekend_entry,experience_description);
					}
			});	
		}else{
		swal({
			  title: "Entries error",
			  type: "warning",
			  text: "Please fill all the entries.",
			  timer: 2500,
			  showConfirmButton: false
			});
	}
});


function  post_member_details(register_first_name,register_last_name,register_phone_number,register_email_address,state,destination_description,weekend_entry,experience_description){
		
		$.post('../../query/php_files/guest_details_posting.php', 
		{register_first_name:register_first_name,register_last_name:register_last_name,
			register_phone_number:register_phone_number,register_email_address:register_email_address,
			state:state,destination_description:destination_description,weekend_entry:weekend_entry,experience_description:experience_description},
		function(data){	
		
		if(data == 2 ){
				//If posting done to DB.					
			swal({
				  title: "Posting success!",
				  text: "Thank you for your time.",
				  type: "success",
				  timer: 3500,
				  showConfirmButton: false
				});	
			
			$('input#register_first_name').val("");	
			$('input#register_last_name').val("");	
			$('input#register_phone_number').val("");	
			$('input#register_email_address').val("");	
			$('textarea#destination_description').val("");	
			$('input#weekend_entry').val("");	
			$('textarea#experience_description').val("");	
				
			}else {
				//If posting is NOT done to DB.
				swal({
					  title: "Posting error!",
					  text: "Am sorry, your details could not be posted. Please try again later",
					  type: "error",
					  timer: 3500,
					  showConfirmButton: false
					});
			} 
		});
}


//Register new Customer to Member Contacts tables.
$('input#new_rewards_entry').on('click',function(){
	
	var item_name = $('input#item_name').val();
	var threshold_points = $('input#threshold_points').val();
	var expiry_date = $('input#expiry_date').val();
	
	if(($.trim(threshold_points) != '')&&($.trim(item_name) != '')){	
		
			swal({
			  title: "Posting...",
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "continue",
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {
					 post_rewards_details(item_name,threshold_points,expiry_date);
					}
			});	
		}else{
		swal({
			  title: "Entries error",
			  type: "warning",
			  text: "Please enter the Name and Threshold.",
			  timer: 2500,
			  showConfirmButton: false
			});
	}
});


function  post_rewards_details(item_name,threshold_points,expiry_date){
		
		$.post('../query/php_files/rewards_details_posting.php', 
		{item_name:item_name,threshold_points:threshold_points,expiry_date:expiry_date},
		function(data){	
		
		if(data == 2 ){
				//If posting done to DB.					
			swal({
				  title: "Posting success!",
				  text: "Item added to rewards.",
				  type: "success",
				  timer: 3500,
				  showConfirmButton: false
				});	
			
			$('input#item_name').val("");	
			$('input#threshold_points').val("");	
			$('input#expiry_date').val("");	
			location.reload(true)	
			}else {
				//If posting is NOT done to DB.
				swal({
					  title: "Posting error!",
					  text: "Am sorry, the details could not be posted. Please try again later",
					  type: "error",
					  timer: 3500,
					  showConfirmButton: false
					});
			} 
		});
}
