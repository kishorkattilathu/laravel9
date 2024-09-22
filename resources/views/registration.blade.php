<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<head>
<title>Visitors an Admin Panel Category Bootstrap Responsive Website Template | Registration :: w3layouts</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="{{url('customers/css/bootstrap.min.css')}}" >

<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{{url('customers/css/style.css')}}" rel='stylesheet' type='text/css' />
<link href="{{url('customers/css/style-responsive.css')}}" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="{{url('customers/css/font.css')}}" type="text/css"/>
<link href="{{url('customers/css/font-awesome.css')}}" rel="stylesheet"> 
<!-- //font-awesome icons -->
<script src="{{url('customers/js/jquery2.0.3.min.js')}}"></script>
<script>
	var base_url = "{{url('')}}";
</script>
<link rel="stylesheet" href="{{url('customers/css/bootstrap.min.css')}}" >
<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
<div class="reg-w3">
<div class="w3layouts-main">
	<h2>Register Now</h2>
	<form id="register_form" action="#" method="post">
		<div>
			<input type="text" class="ggg" name="name" id="name" placeholder="NAME" required="">
			<span id="error-name" class="text-danger"></span>
		</div>
		<div>
			<input type="email" class="ggg" name="email" id="email" placeholder="E-MAIL" required="">
			<span id="error-email" class="text-danger"></span>

		</div>
		
		<div>
			<input type="password" class="ggg" name="password" id="password" placeholder="PASSWORD" required="">
			<span id="error-password" class="text-danger"></span>
			
		</div>
		<div>
			<input type="password" class="ggg" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required="">
			<span id="error-password_confirmation" class="text-danger"></span>

		</div>
		
			<div class="clearfix"></div>
			<input type="button" id="register_btn" value="submit" name="register_btn">
	</form>
		<p>Already Registered.<a href="{{url('login')}}">Login</a></p>
</div>
</div>
<script src="{{url('customers/js/bootstrap.js')}}"></script>
<script src="{{url('customers/js/jquery.dcjqaccordion.2.7.js')}}"></script>
<script src="{{url('customers/js/scripts.js')}}"></script>
<script src="{{url('customers/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('customers/js/jquery.nicescroll.js')}}"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="{{url('customers/js/jquery.scrollTo.js')}}"></script>
</body>
</html>

<script>
	$(document).ready(function(){
		console.log('doc ready');
		$('#register_btn').on('click',function(){
			register();
		});
	});

	function validate(){
		var Valid = true;
		if($('#name').val().trim() === ""){
			Valid = false;
			// alert("Name field cannot be empty!"); 
		}

		if($('#email').val().trim() === ""){
			Valid = false;
			// alert("Email field cannot be empty!"); 
		}
		
		if($('#password').val().trim() === ""){
			Valid = false;
			// alert("Password field cannot be empty!"); 
		}
		if($('#password_confirmation').val().trim() === ""){
			Valid = false;
			// alert("Confirm Password field cannot be empty!"); 
		}

		if($('#password_confirmation').val() != $('#password').val()){
			Valid = false;
			// alert("Password does not match!"); 
		}

		return Valid; 
	}

	function register(){

		var input_array = ['name','email','phone','password'];
		removeErrorMessage(input_array);
		if (validate()) {
			console.log('validated');
			console.log('base_url',base_url);

			var formData = $('#register_form').serialize();
			$.ajax({
				data : formData,
				type : 'POST',
				dataType : 'JSON',
				url : base_url+'/signup',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success : function(response){
					if(response.status){
						window.location.href = response.redirect_url;
					}else{
						alert(response.message);
					}

				},
				error : function(error,xhr,status){
					console.log('xhr',xhr);
					console.log('error',error);
					console.log('status',status);
					var response_error = error.responseJSON.errors;
					console.log(response_error);

					displayError(response_error);
				}

			});
			console.log('form',formData);
		}else{
			console.log('not validated');
			
		}
		
	}

	function displayError(response_error){
          
          $.each(response_error,function(input_id,error_message){
            $('#error-'+input_id).html(error_message);
          });
        }

        function removeErrorMessage(input_array)
        {
          $.each(input_array,function(key,input_id){
            $('#error-'+input_id).html('');
          });
        }
</script>