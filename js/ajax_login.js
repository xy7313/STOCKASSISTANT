$(document).ready(function() {
$('#link_login').click(function() {

			$('#login').dialog({
			dialogClass : 'login alert',
			title : 'Login',
			width: 200,
			show : {
				effect : "shake",
				distance : 5,
				times : 2,
				duration : 300
			},
			draggable : false,
			resizable : false,
			modal : true,
			position : 'center',
			buttons : {
				Login : function() {
					var emailValue = $('#email').val();
					var passwordValue = $('#password').val();
					
					$.ajax({
						type : 'POST',
						url : 'ajax/login.php',
						data : {
							email : emailValue,
							password : passwordValue,
						},
						beforeSend : function() {
							// this is where we append a loading image
						},
						success : function(success) {
							if(success == 1) {
								$('#login').dialog("close");
								location.reload();
							}
							else {
								$('#message_login').text("Invalid username and password!");
							}
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + '|' + thrownError);
						}
					});
				},
				Cancel : function() {
					$(this).dialog("close");
				}
			},
		});
	});
	
	$('#menu .welcome').click(function() {
		$('#menu .dropdown').slideToggle(200);
	});
});