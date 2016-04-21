$(document).ready(function() {

	$('#registerSubmit').click(function(e) {
	
		var firstName = $('#input_firstName').val();
		var lastName = $('#input_lastName').val();
		var email = $('#input_emailReg').val();
		var password1 = $('#input_password1').val();
		var password2 = $('#input_password2').val();
		var phone = $('#input_phone').val();
		var carrier = $('#select_carrier').val();
		var error = false;
		
		if (firstName == '') {
			$('#firstName .message').text('This field cannot be blank');
			if (!error) error = true;
		}
		else $('#firstName .message').empty();
			
		if (lastName == '') {
			$('#lastName .message').text('This field cannot be blank');
			if (!error) error = true;
		}
		else $('#lastName .message').empty();

		var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;	
		if (!filter.test(email)) {
			$('#email .message').text('Invalid email address');
			if (!error) error = true;
		}
		else $('#emailReg .message').empty();

		if (password1.length < 8) {
			$('#password1 .message').text('Password must be at least 8 characters long');
			if (!error) error = true;
		}
		else $('#password1 .message').empty();
		
		if (password1 !== password2) {
			$('#password2 .message').text('Passwords do not match');
			if (!error) error = true;
		}
		else $('#password2 .message').empty();
		
		if (phone != parseInt(phone) && phone != '') {
			$('#phone .message').text('Invalid mobile number');
			if (!error) error = true;
		}
		else $('#phone .message').empty();
		
		if (phone != '' && carrier == '') {
			$('#carrier .message').text('Invalid carrier');
			if (!error) error = true;
		}
		else $('#carrier .message').empty();
		
		alert(error);
		
		if (error) e.preventDefault();
		else {
			
			if (phone == '') {
				phone = '0';
				carrier = '0';
			}
						
			$.ajax({
						type : 'POST',
						url : 'ajax/register.php',
						data : {
							firstName : firstName,
							lastName : lastName,
							email : email,
							password : password1,
							phone : phone,
							carrier : carrier
						},
						beforeSend : function() {

						},
						success : function(success) {
							if(success == 1) {
								location.reload();
							}
							else {
								$('#emailReg .message').text('This email address already exists');
							}
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + ' | ' + thrownError);
						}
					});
		}
	});
});