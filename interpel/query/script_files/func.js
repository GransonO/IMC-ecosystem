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
	
	var reward_id = $('input#reward_id').val();
	var process_code = 1;
	if(($.trim(reward_id) != '')){
	
		$.post('../query/php_files/redemption_func.php', 
		{reward_id:reward_id,process_code:process_code},
		function(data){
			if(data == 0){
				swal({
					title: "Entry Warning!",
					text: "No details found for the customer",
					type: "warning",
					timer: 2500,
					showConfirmButton: false
					});
			}else{
				var result = JSON.parse(data);
				$('input#reward_name').val(result['name']);
				$('input#points_avail').val(result['points']);
				}
		});
		
	}else{
		swal({
			title: "No Customer Entered",
			type: "warning",
			text: "Please enter the customers ID",
			timer: 2500,
			showConfirmButton: false
		});
	}
	
});

//Reward Guest Function.
$('input#query_customer_availability').on('click',function(){
	
	var reward_id = $('input#reward_id').val();
	var process_code = 1;
	
	if(($.trim(reward_id) != '')){
	
		$.post('../../query/php_files/redemption_func.php', 
		{reward_id:reward_id,process_code:process_code},
		function(data){
			if(data == 0){
				//ID is free to use
				swal({
					title: "Verified.",
					text: "You can assign the ID to the new customer",
					type: "success",
					timer: 2500,
					showConfirmButton: false
					});
			}else{
			//ID is taken
			var result = JSON.parse(data);
				swal({
					title: "Double Entry Warning!",
					text: "The ID requested belong to: "+ result['name'] +". Please use a different ID",
					type: "warning",
					timer: 3500,
					showConfirmButton: false
					});				
				}
		});
		
	}else{
		swal({
			title: "No Customer ID Entered",
			type: "warning",
			text: "Please enter the customers ID",
			timer: 2500,
			showConfirmButton: false
		});
	}
	
});

//Reward Guest Function.
$('input#reward_guest_submit').on('click',function(){
	
		
	var reward_id = $('input#reward_id').val();
	var reward_request = $('input#reward_request').val();
	var points_avail = $('input#points_avail').val();
	var process_code = 2;
	if(($.trim(reward_id) != '') && ($.trim(reward_request) != '')){
	
		if((points_avail - reward_request) < 0){
			swal({
				title: "Accounting Error!",
				text: "The requested amount is more than the available amount. Available: " + points_avail + " || requested : " + reward_request,
				type: "error",
				timer: 3000,
				showConfirmButton: false
				});
		}else{
			
			swal({
			  title: "Validating...",
			  text: "Redeem " + reward_request + " Points for Member ID: " + reward_id,
			  type: "info",
			  confirmButtonColor: "#1dbf07",
			  confirmButtonText: "Yes, Confirmed",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
				},function(isConfirm){
					if (isConfirm) {									
										
						$.post('../query/php_files/redemption_func.php', 
						{reward_id:reward_id,process_code:process_code,reward_request:reward_request},
						function(data){
							if(data == 3){
								swal({
									title: "Processing Error!",
									text: "The details could not be entered. Please contact IT for assistance",
									type: "error",
									timer: 2500,
									showConfirmButton: false
									});
							}else{
											
									var returned_data = JSON.parse(data);
							
								//Print receipt option										
									swal({
									  title: returned_data['status'],
									  text: "Redemption of " +returned_data['red_pts']+" for " + returned_data['member']+" is confirmed. Remaining points: " + returned_data['rem_pts'] + ". Print receipt for this transaction?",
									  type: "success",
									  confirmButtonColor: "#1dbf07",
									  confirmButtonText: "Yes",
									  showCancelButton: true,
									  closeOnConfirm: false,
										},function(isConfirm){
											if (isConfirm) {									
												//Go ahead and print the receipt
												window.open("../dash/rpt/redemption_receipt.php?id="+reward_id+"&requested=" + reward_request,'_self',false);
											}
									});		
									
								}
						});
					
					}else{
						 swal({
							  title: "Cancelled!",
							  text: "Redemption cancelled",
							  type: "warning",
							  timer: 2500,
							  showConfirmButton: false
							});
					}
			});	
			
			}
			
	}else{
		swal({
			title: "Entries Error",
			type: "info",
			text: "Please enter all the entries.",
			timer: 2500,
			showConfirmButton: false
		});
	} 
});

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

//Register new Member to Program tables.
$('input#register_new_member_submit').on('click',function(){
	
	var member_id = $('input#reward_id').val();
	var register_company_name = $('input#register_company_name').val();
	var register_poc_name = $('input#register_poc_name').val();
	var register_phone_number = $('input#register_phone_number').val();
	var register_email_address = $('input#register_email_address').val();
				
			if(($.trim(member_id) != '')&&($.trim(register_company_name) != '')&&($.trim(register_phone_number) != '')&&($.trim(register_email_address) != '')){

				//Posting done					
						swal({
						  title: "Registering...",
						  text: "Registration for the member ID: " +member_id,
						  type: "info",
						  confirmButtonColor: "#1dbf07",
						  confirmButtonText: "Yes,I confirm",
						  showCancelButton: true,
						  closeOnConfirm: false,
						  showLoaderOnConfirm: true,
							},function(isConfirm){
								if (isConfirm) {		
									$.post('../../query/php_files/member_registration.php', 
									{member_id:member_id,register_company_name:register_company_name,register_poc_name:register_poc_name,register_phone_number:register_phone_number,
									register_email_address:register_email_address},
									function(data){	
										if(data == 1){
											swal({
												title: "Registration Successful!",
												text: "The customer has been registered successfully",
												type: "success",
												timer: 2500,
												showConfirmButton: false
												});
													
											$('input#reward_id').val("");
											$('input#register_company_name').val("");
											$('input#register_poc_name').val("");
											$('input#register_phone_number').val("");
											$('input#register_email_address').val("");
										}else{
														
										swal({
												title: "Processing Error!",
												text: "The details could not be entered. Please contact IT for assistance",
												type: "error",
												timer: 2500,
												showConfirmButton: false
												});
											
											}
									});
								
								}else{
									 swal({
										  title: "Cancelled!",
										  text: "Registration cancelled",
										  type: "warning",
										  timer: 2500,
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

});

//Update Member Contacts table.
$('input#update_member_submit').on('click',function(){
	
	var member_id = $('input#reward_id').val();
	var register_company_name = $('input#update_company_name').val();
	var register_poc_name = $('input#update_poc_name').val();
	var register_phone_number = $('input#update_phone_number').val();
	var register_email_address = $('input#update_email_address').val();
				
			if(($.trim(member_id) != '')&&($.trim(register_company_name) != '')&&($.trim(register_phone_number) != '')&&($.trim(register_email_address) != '')){

				//Posting done					
						swal({
						  title: "Updating...",
						  text: "Updating account details for the member ID: " +member_id,
						  type: "info",
						  confirmButtonColor: "#1dbf07",
						  confirmButtonText: "Yes,I confirm",
						  showCancelButton: true,
						  closeOnConfirm: false,
						  showLoaderOnConfirm: true,
							},function(isConfirm){
								if (isConfirm) {		
									$.post('../../query/php_files/member_update.php', 
									{member_id:member_id,register_company_name:register_company_name,register_poc_name:register_poc_name,register_phone_number:register_phone_number,
									register_email_address:register_email_address},
									function(data){	
										if(data == 1){
											swal({
												title: "Update Successful!",
												text: "The customer's account has been updated successfully",
												type: "success",
												timer: 2500,
												showConfirmButton: false
												});
													
											$('input#reward_id').val("");
											$('input#update_company_name').val("");
											$('input#update_poc_name').val("");
											$('input#update_phone_number').val("");
											$('input#update_email_address').val("");
										}else{
										swal({
												title: "Processing Error!",
												text: "The details could not be entered. Please contact IT for assistance",
												type: "error",
												timer: 2500,
												showConfirmButton: false
												});
											
											}
									});
								
								}else{
									 swal({
										  title: "Cancelled!",
										  text: "Update cancelled",
										  type: "warning",
										  timer: 2500,
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

});

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