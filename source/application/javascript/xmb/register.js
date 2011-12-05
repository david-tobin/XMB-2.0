function check_username(username)
{
	$(document).ready(function(){

		$.ajaxSetup({
			  url: siteurl+"/ajax/checkusername/",
			  global: false,
			  type: "POST"
			});

		var data = "username="+username+"&securitytoken="+securitytoken; 

		 $.ajax({
			   type: "POST",
			   data: data,
			   success: function(msg){
			   	if (msg != null)
			   	{
			     $("#usernamemessage").html(msg);
			     $("#usenamemessage").css('class')
			   	}
			}
			 });

									
	});
}

function checkemail(email)
{
	$(document).ready(function(){

		$.ajaxSetup({
			  url: siteurl+"/ajax/checkemail/",
			  global: false,
			  type: "POST"
			});

		var data = "email="+email+"&securitytoken="+securitytoken; 

		 $.ajax({
			   type: "POST",
			   data: data,
			   success: function(msg){
			   	if (msg != null)
			   	{
			     $("#email").html(msg);
			   	}
			}
			 });

									
	});
}

function checkpassword()
{
	$(document).ready(function(){
		
		var pass1 = $("#password1input").attr('value');
		var pass2 = $("#password2input").attr('value');
		
		 var username = $("#username").attr('value');
		 var pattern = new RegExp(username, 'gi');

		 if (pattern.test(pass1) == true) {
			$("#password").html('<span class="smallfont"><span style="color: red;">&#10008;</span> Password must not contain username.</span>');
			exit;
		 }
		
		$.ajaxSetup({
			  url: siteurl+"/ajax/checkpassword/",
			  global: false,
			  type: "POST"
			});

		var data = "password="+pass1+"&securitytoken="+securitytoken; 

		 $.ajax({
			   type: "POST",
			   data: data,
			   success: function(msg) {
			   	if (msg != null)
			   	{
			   		$("#password").html(msg);
			   	}
			}
			 });
		 
		 if (pass1 != pass2) {
			$("#password2").html('<span class="smallfont"><span style="color: red;">&#10008;</span> Passwords don\'t match.</span>');
		 } else {
			 $("#password2").html('<span style="color: green; font-weight: bold;">&#10004;</span>');
		 }
	});
}
