$(document).ready(function(){
	$("#submit_login").click(function(){
			$(".error_data").fadeOut();
			$.post("process/login.php",$("#form_login").serializeArray(),
				   function(data){
					console.log(data);
				   		switch(data)
				   		{
				   			case "":
				   				$(".error_data").addClass("error").html("Fields are not set").fadeIn();
		   						break;
				   			case "10":	//empty field
								$(".error_data").addClass("error").html("All fields are required").fadeIn();
		   						break;
		   					case "11":
		   						$("#form_login").find("input[type='password']").val("");
		   						$(".error_data").addClass("error").html("Username or Password is invalid").fadeIn();
		   						break;
		   					case "1":
		   						window.location.href="home.php";
		   						break;
		   					default:
		   						$(".error_data").addClass("error").html(data).fadeIn();
				   		}

				   });
		return false;
	})
});