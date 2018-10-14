//Details Registration
	var folio;
	var gr_card;
	var guest_name;
	var guest_type;
	var phone_no;
	var email_contact;
	var nationality;
	var booking_source;
	var room_number;
	var room_type;
	var tariff;
	var persons;
	var arrival_date;
	var arrival_time;
	var departure_date;
	var nights_spent;
	var restaurant_date;
	var restaurant_check_no;
	var restaurant_description;
	var restaurant_amount;
	var restaurant_pay_type;
	var pay_type;
	var guest_pay;
	var ots_bill;
	var total_bill;
	
//USER POSTING DATA
$('button#submit_btn').on('click',function(){	
	folio = $('input#folio').val();
	gr_card = $('input#gr_card').val();
	guest_name = $('input#guest_name').val();
	guest_type = $('select#guest_type').val();
	phone_no = $('input#phone_no').val();
	email_contact = $('input#email_contact').val();
	nationality = $('input#nationality').val();
	booking_source = $('input#source').val();
	room_number = $('input#room_no').val();
	room_type = $('select#room_type').val();
	tariff = $('input#tariff').val();
	persons = $('input#no_pax').val();
	arrival_date = $('input#arrival_date').val();
	arrival_time = $('input#arrival_time').val();
	departure_date = $('input#departure_date').val();
	nights_spent = $('input#nights_spent').val();
	pay_type = $('select#pay_type').val();
	guest_pay = $('input#guest_pay').val();
	ots_bill = $('input#ots_bill').val();
	total_bill = $('input#total_bill').val();
	
	if(($.trim(folio) != '')&&($.trim(gr_card) != '')&&($.trim(booking_source) != '')&&($.trim(ots_bill) != '')&&($.trim(total_bill) != '')&&($.trim(guest_pay) != '')&&
	   ($.trim(arrival_time) != '')&&($.trim(departure_date) != '')&&($.trim(nights_spent) != '')&&($.trim(room_number) != '')&&($.trim(room_type) != '')&&($.trim(tariff) != '')&&
	   ($.trim(persons) != '')&&($.trim(arrival_date) != '')&&($.trim(guest_name) != '')&&($.trim(guest_type) != '')&&($.trim(phone_no) != '')&&($.trim(email_contact) != '')&&($.trim(nationality) != '')){
		
		$.post('../query/php_files/posting_details.php', 
		{folio:folio,gr_card:gr_card,guest_name:guest_name,guest_type:guest_type,
		phone_no:phone_no,email_contact:email_contact,nationality:nationality,booking_source:booking_source,
		room_number:room_number,room_type:room_type,tariff:tariff,persons:persons,
		arrival_date:arrival_date,arrival_time:arrival_time,departure_date:departure_date,nights_spent:nights_spent,
		restaurant_date:restaurant_date,restaurant_check_no:restaurant_check_no,restaurant_description:restaurant_description,restaurant_amount:restaurant_amount,
		pay_type:pay_type,guest_pay:guest_pay,ots_bill:ots_bill,total_bill:total_bill},
		function(data){
		if(data == 2 ){
				//If posting done to DB.
				
				swal({
					  title: "Success!",
					  text: "Posted Successfully",
					  type: "success",
					  timer: 2000,
					  showConfirmButton: false
					});
				
				 	$('input#folio').val("");
					$('input#gr_card').val("");
					$('input#guest_name').val("");
					$('select#guest_type').val();
					$('input#phone_no').val("");
					$('input#email_contact').val("");
					$('input#nationality').val("");
					$('input#source').val("");
					$('input#room_no').val("");
					$('select#room_type').val();
					$('input#tariff').val("");
					$('input#no_pax').val("");
					$('input#arrival_date').val("");
					$('input#arrival_time').val("");
					$('input#departure_date').val("");
					$('input#nights_spent').val("");
					$('input#restaurant_date').val("");
					$('input#restaurant_check_no').val("");
					$('textarea#restaurant_description').val("");
					$('input#restaurant_amount').val("");
					$('input#guest_pay').val("");
					$('input#ots_bill').val("");
					$('input#total_bill').val("");
					$('input#service_date').val();
					$('input#service_check_no').val();
					$('select#service_type').val();
					$('input#service_amount').val();
					window.open("index.html",'_self',false);
			}else if(data == 0 ){
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
			  text: "You have to fill in all entries before posting",
			  timer: 2500,
			  showConfirmButton: false
			});
	}	
});

//USER POSTING RESTAURANT
$('button#restaurant_btn').on('click',function(){	

	folio = $('input#folio').val();
	gr_card = $('input#gr_card').val();
	guest_name = $('input#guest_name').val();
	restaurant_date = $('input#restaurant_date').val();
	restaurant_check_no = $('input#restaurant_check_no').val();
	restaurant_description = $('textarea#restaurant_description').val();
	restaurant_amount = $('input#restaurant_amount').val();
	restaurant_pay_type = $('select#restaurant_pay_type').val();
	
	if(($.trim(folio) != '')&&($.trim(gr_card) != '')&&($.trim(restaurant_amount) != '')&&($.trim(restaurant_date) != '')
		&&($.trim(restaurant_check_no) != '')&&($.trim(restaurant_description) != '')&&($.trim(guest_name) != '')&&($.trim(restaurant_pay_type) != '')){
		
		$.post('../query/php_files/posting_restaurant_details.php', 
		{folio:folio,gr_card:gr_card,guest_name:guest_name,
		restaurant_date:restaurant_date,restaurant_check_no:restaurant_check_no,restaurant_description:restaurant_description,restaurant_amount:restaurant_amount,
		restaurant_pay_type:restaurant_pay_type},
		function(data){
		if(data == 2 ){
				//If posting done to DB.
				
				swal({
					  title: "Posting Success!",
					  text: "Restaurant Transaction Posted",
					  type: "success",
					  timer: 1000,
					  showConfirmButton: false
					});			
				 	
					$('input#restaurant_date').val("");
					$('input#restaurant_check_no').val("");
					$('textarea#restaurant_description').val("");
					$('input#restaurant_amount').val("");
					
			}else if(data == 1 ){
				//If posting is NOT done to DB.
				swal({
					  title: "Entry Warning!",
					  text: "The entry you just keyed in already exists. Move on to the next entry or contact support.",
					  type: "warn",
					  timer: 2500,
					  showConfirmButton: false
					});
				
			}else if(data == 0 ){
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
			  text: "You have to fill in all restaurant entries before posting.",
			  timer: 1500,
			  showConfirmButton: false
			});
	}	
});

//USER POSTING OTHERS
$('button#service_btn').on('click',function(){	

	folio = $('input#folio').val();
	gr_card = $('input#gr_card').val();
	guest_name = $('input#guest_name').val();
	var service_date = $('input#service_date').val();
	var service_check_no = $('input#service_check_no').val();
	var service_type = $('select#service_type').val();
	var service_amount = $('input#service_amount').val();
	var service_pay_type = $('select#service_pay_type').val();
	
	if(($.trim(folio) != '')&&($.trim(gr_card) != '')&&($.trim(service_amount) != '')&&($.trim(service_date) != '')
		&&($.trim(service_check_no) != '')&&($.trim(service_type) != '')&&($.trim(guest_name) != '')&&($.trim(service_pay_type) != '')){
		
		$.post('../query/php_files/posting_other_details.php', 
		{folio:folio,gr_card:gr_card,guest_name:guest_name,
		service_date:service_date,service_check_no:service_check_no,service_type:service_type,service_amount:service_amount,
		service_pay_type:service_pay_type},
		function(data){
		if(data == 2 ){
				//If posting done to DB.
				
				swal({
					  title: "Posting Success!",
					  text: "Other Transaction Posted",
					  type: "success",
					  timer: 1000,
					  showConfirmButton: false
					});				
				 	
					$('input#service_date').val("");
					$('input#service_check_no').val("");
					$('input#service_amount').val("");
					
			}else if(data == 1 ){
				//If posting is NOT done to DB.
				swal({
					  title: "Entry Warning!",
					  text: "The entry you just keyed in already exists. Move on to the next entry or contact support.",
					  type: "warn",
					  timer: 2500,
					  showConfirmButton: false
					});
				
			}else if(data == 0 ){
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
			  text: "You have to fill in all entries before posting.",
			  timer: 1500,
			  showConfirmButton: false
			});
	}	
});

// register jQuery extension
jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, :input, [tabindex]');
    }
});

$(document).on('keydown', ':focusable', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        // Get all focusable elements on the page
        var $canfocus = $(':focusable');
        var index = $canfocus.index(this) + 1;
        if (index >= $canfocus.length) index = 0;
        $canfocus.eq(index).focus();
    }
});

//Posting Members Contacts Data
$('input#register_new_guest_submit').on('click',function(){	
	
	registration_card_no = $('input#registration_card_no').val();
	card_type = $('select#card_type').val();
	group_identifier = $('input#group_identifier').val();
	register_surname = $('input#register_surname').val();
	register_first_name = $('input#register_first_name').val();
	register_other_name = $('input#register_other_name').val();
	register_phone_number = $('input#register_phone_number').val();
	register_email_address = $('input#register_email_address').val();
	register_city = $('input#register_city').val();
	register_country = $('input#register_country').val();
	register_dob = $('input#register_dob').val();
	gender_type = $('select#gender_type').val();
	register_id_no = $('input#register_id_no').val();
	passport_expiry = $('input#passport_expiry').val();
	register_nationality = $('input#register_nationality').val();
	register_foreigner_certificate = $('input#register_foreigner_certificate').val();
	register_arrival_date = $('input#register_arrival_date').val();
	register_arrival_time = $('input#register_arrival_time').val();
	register_arrival_from = $('input#register_arrival_from').val();
	register_departure_date = $('input#register_departure_date').val();
	register_departure_time = $('input#register_departure_time').val();
	register_departure_destination = $('input#register_departure_destination').val();
	register_nights_spent = $('input#register_nights_spent').val();
	register_company_name = $('input#register_company_name').val();
	register_profession = $('input#register_profession').val();
	
	if(($.trim(registration_card_no) != '')&&($.trim(card_type) != '')&&($.trim(register_id_no) != '')){
		
		$.post('../query/php_files/posting_customer_details.php', 
		{registration_card_no:registration_card_no,card_type:card_type,group_identifier:group_identifier,register_surname:register_surname,register_first_name:register_first_name,
		 register_other_name:register_other_name,register_phone_number:register_phone_number,register_email_address:register_email_address,register_city:register_city,register_country:register_country,
		 register_dob:register_dob,gender_type:gender_type,register_id_no:register_id_no,passport_expiry:passport_expiry,register_nationality:register_nationality,register_foreigner_certificate:register_foreigner_certificate,
		 register_arrival_date:register_arrival_date,register_arrival_time:register_arrival_time,register_arrival_from:register_arrival_from,register_departure_date:register_departure_date,register_departure_time:register_departure_time,
		 register_departure_destination:register_departure_destination,register_nights_spent:register_nights_spent,register_company_name:register_company_name,register_profession:register_profession},
		function(data){
		alert(data);
		if(data == 2 ){
				//If posting done to DB.
				
				swal({
					  title: "Success!",
					  text: "Posted Successfully",
					  type: "success",
					  timer: 2000,
					  showConfirmButton: false
					});
				
					window.open("index.html",'_self',false);
			}else if(data == 1 ){
				//If double Entry
				swal({
					  title: "Double entry error!",
					  text: "Am sorry, the details entered already exist",
					  type: "error",
					  timer: 2500,
					  showConfirmButton: false
					});
				
			}else if(data == 0 ){
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
			  text: "You have to fill in all entries before posting",
			  timer: 2500,
			  showConfirmButton: false
			});
	}	
});


//USER REFRESH THE PAGE
$('input#admin_refresh').on('click',function(){	

	window.open("index.html",'_self',false);
});