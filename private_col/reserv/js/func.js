		
	var firstname;
	var lastname;
	var identification;
	var zip;
	var mail;
	var phone;
	var city;
	var country;
	var mybox;

//Forgot Password functions.
$('a#reserve_for_me').on('click',function(){
	
	var arrival_day =  $('.day:eq(0)').text();
	var arrival_month =  $('.month:eq(0)').text();
	var arrival_year =  $('.year:eq(0)').text();
	var arr_date = arrival_day + "-" + arrival_month + "-" + arrival_year;
    //alert();
	
	var dep_day =  $('.day:eq(1)').first().text();
	var dep_month =  $('.month:eq(1)').first().text();
	var dep_year =  $('.year:eq(1)').first().text();
	var dep_date = dep_day + "-" + dep_month + "-" + dep_year;
    //alert(dep_day + " - " + dep_month + " - " + dep_year);
	
	var persons =  $('.qty-result-text').text();
    //alert(persons);
	
	
	var new_url = "reserv1.php?arr=" + arr_date + "&dep=" + dep_date + "&oc=" + persons;
	window.open(new_url,'_self',false);
 
});

//Forgot Password functions.
$('a#check_me_out').on('click',function(){
		
	firstname = $('input#firstname').val();
	lastname = $('input#lastname').val();
	identification = $('input#identification').val();
	zip = $('input#zip').val();
	mail = $('input#mail').val();
	phone = $('input#phone').val();
	city = $('input#city').val();
	country = $('input#country').val();
	
		
		if($('input#checkBoxId1').is(":checked")){
				mybox = $('input#checkBoxId1').val();
		}else
		if($('input#checkBoxId2').is(":checked")){
				mybox = $('input#checkBoxId2').val();
		}else
		if($('input#checkBoxId3').is(":checked")){
				mybox = $('input#checkBoxId3').val();
		}
		
	var new_url = "reserv2.php?plan=" + mybox + "&fn="+ firstname+"&ln="+lastname+"&id="+identification+"&zip="+zip+"&city="+city+"&co="+country+"&mail="+mail+"&phone="+phone ;
	window.open(new_url,'_self',false);
 
});

//Forgot Password functions.
$('a#confirm_transaction').on('click',function(){
	var name = $('span#name').text();
	var identification = $('span#identification').text();;
	var mail = $('span#mail').text();
	var phone = $('span#phone').text();
	var city = $('span#city').text();
	var country = $('span#country').text();
	var arrive = $('span#arrive_date').text();
	var depart = $('span#depart_date').text();
	var persons = $('span#persons').text();
	var mybox = $('span#amount').text();;
	
	$.post('guest_details_posting.php',
			{name:name,identification:identification,zip:zip,mail:mail,phone:phone,city:city,country:country,mybox:mybox},
			function(data){
				if(data == 2){
			
					window.open('rewards','_self',false);		
				}else{
					alert('submition error. Please try again.');
				}
		});	
 
});
