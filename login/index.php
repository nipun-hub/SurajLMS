<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta -->
	<meta name="description" content="Suraj S Kumara - A/L ICT - Online">
	<meta name="keywords" content="surajskumara , suraj s kumara , A/L ict , Online">
	<title>Surajskumara.lk | Login</title>
	<meta property="og:site_name" content="surajskumara.lk">
	<meta property="og:title" content="Suraj S Kumara" />
	<meta property="og:description" content="Suraj S Kumara - A/L ICT - Online" />
	<meta property="og:image" itemprop="image" content="https://surajskumara.lk/assets/images/suraj-imge-01.jpg">
	<meta property="og:type" content="website" />
	<meta name="author" content="Suraj S Kumara">
	<link rel="shortcut icon" href="../assets/images/ict.ico">
	<link rel="icon" href="../assets/images/ict.ico">
	<!--<meta name="google-signin-client_id" content="1063565226603-1apdpm48cfi6hu1m4ub4l4rvgdfj8ekt.apps.googleusercontent.com">-->
	<script src="https://accounts.google.com/gsi/client" async></script>


	<!-- Title -->
	<title>SurajSKumara.lk | Login</title>


	<!-- *************
			************ Common Css Files *************
		************ -->


	<!-- Fontawesome CDN Link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

	<!-- Animated css -->
	<link rel="stylesheet" href="../Dachbord/assets/css/animate.css">

	<!-- Bootstrap font icons css -->
	<link rel="stylesheet" href="../Dachbord/assets/fonts/bootstrap/bootstrap-icons.css">

	<!-- Main css -->
	<link rel="stylesheet" href="../Dachbord/assets/css/main.min.css">
	<link rel="stylesheet" href="../assets/css/login-css/animated.css">
	<link rel="stylesheet" href="../assets/css/login-css/multi-form.css">
	
    <!-- Bootstrap Select CSS -->
    <!--<link rel="stylesheet" href="../Dachbord/assets/vendor/bs-select/bs-select.css" />-->

	<!-- alert css -->
	<link rel="stylesheet" href="../Dachbord/assets/css/alert.css">

</head>

<body class="login-container">

	<!-- animated canvers -->
	<?php include_once('../include/animated-canvers.php'); ?>

	<!-- page wrapper start -->
	<div class="page-wrapper">

		<!-- Loading wrapper -->
		<?php include_once('../include/preloader.php'); ?>


		<!-- Login box start -->
		<?php if (isset($_REQUEST['login'])) { ?>
			<div class="form login">
				<div class="login-box">
					<form>
						<div class="login-form">
							<!-- <a href="index.html" class="login-logo">
						<img src="../Dachbord/assets/images/logo.svg" alt="Vico Admin" />
					</a> -->
							<!-- <center> -->
							<div class="login-welcome" style="text-align: center;">
								<!-- <span class="">Welcome to surajskumara.lk</span> -->
								<!-- <span class="">Learning management system</span> -->
								<label class="form-lable pb-3">Welcome to <br> surajskumata.lk Learning management system</label>
								<img src="../assets/images/hello.gif" alt="" width="150">
							</div>

							<div class="login-form-actions ">
								<div id="g_id_onload" data-client_id="1063565226603-1apdpm48cfi6hu1m4ub4l4rvgdfj8ekt.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse" data-auto_prompt="true">
								</div>

								<div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left">
								</div>
								<!-- Display the user's profile info -->
							</div>
							<center><img class="loading" style="display: none;" src="../assets/images/gif/loading01.gif" width="50" height="50" alt=""></center>
							<div class="login-form-footer">
								<div class="additional-link mt-2 item-center text-center">
									<span>යම් කිසි ගැටලුවක් වේනම් <a class="suneup" href="#">How to register</a> හෝ <br>0742966266 අමතන්න.</span>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- Login box end -->
		<?php  } elseif (isset($_REQUEST['register'])) { ?>

			<!-- Register box start -->
			<div class="form register">
				<div class="login-box">
					<div class="login-form">
						<form class="register_form">
							<div class="login-welcome" style="text-align: center;">
								<h4>register</h4>
							</div>
							<!-- multi bar -->
							<div class="multi-form">
								<div class="multi" id="multi"></div>
								<div class="multi-step multi-step-active" data-title="Intro"></div>
								<div class="multi-step" data-title="Personal"></div>
								<div class="multi-step" data-title="School"></div>
								<div class="multi-step" data-title="Delivery "></div>
								<!--<div class="multi-step" data-title="Guardian "></div>-->
							</div>

							<div class="form-step row form-step-active ">
								<div class=" mb-3 col-6 fname">
									<label class="form-label">First Name)</label>
									<input name="fname" onkeyup="fname_val()" type="text" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 col-6 lname">
									<label class="form-label">Last Name</label>
									<input name="lname" onkeyup="lname_val()" type="text" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 email">
									<label class="form-label">Email</label>
									<input name="email" onkeyup="email_val()" type="Email" class="form-control Register_Data" required disabled>
								</div>
								<div class="login-form-actions onennext">
									<button class="btn btn-next"> <span class="icon"> <i class="bi bi-box-arrow-in-right"></i> </span> Next</button>
								</div>
							</div>

							<div class="row form-step">
								<div class="mb-3 col-6 nic">
									<label class="form-label">Nic Number )</label>
									<input name="nic" maxlength="10" type="Number" class="form-control Register_Data">
								</div>
								<div class="mb-3 col-6 nicpic">
									<label class="form-label">Nic Picture</label>
									<input name="nic_pic" type="file" class="form-control" accept=".jpeg, .jpg, .png, .tiff">
								</div>
								<div class="mb-3 mobnum">
									<label class="form-label">Mobile Number</label>
									<input name="NumMob" onkeyup="Mn_val()" type="Number" class="form-control Register_Data">
								</div>
								<div class="mb-3 whanum">
									<label class="form-label">Whatsapp Number</label>
									<input name="NumWha" onkeyup="Wn_val()" type="Number" class="form-control Register_Data">
								</div>
								<div class="mb-3 dob">
									<label class="form-label">Date Of Birth)</label>
									<div class="input-group">
										<span class="input-group-text">
											<i class="bi bi-calendar4"></i>
										</span>
										<input name="dob" onchange="Dob_val()" type="Date" class="form-control Register_Data" min="2000-01-01" max="2010-01-01">
									</div>
								</div>
								<div class="login-form-actions">
									<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>
									<button class="btn btn-next"> <span class="icon"> <i class="bi bi-box-arrow-in-right"></i> </span> Next</button>
								</div>
							</div>

							<div class="row form-step">
								<div class="mb-3 school">
									<label class="form-label">School Name</label>
									<input name="school" onkeyup="School_val()" type="text" class="form-control Register_Data">
								</div>
								<div class="mb-3 col-6 year">
									<!--<label class="form-label">විභාගයට මුහුණදෙන අවුරුද්ද</label>-->
									<select name="year" onchange="year_val()" class="form-select Register_Data">
										 <option value="">Select the exam year</option> 
										<option value="2024">2024</option>
										<option value="2025">2025</option>
										<option value="2026">2026</option>
									</select>
								</div>
								<div class="mb-3 col-6 streem">
									<!--<label class="form-label">විෂය ධාරාව</label>-->
									<select name="streem" onchange="streem_val()" class="form-select Register_Data">
										 <option value="">Select subject stream</option> 
										<option value="Maths">Maths</option>
										<option value="Bio">Bio</option>
										<option value="Tec">Tec</option>
										<option value="Art">Art</option>
										<option value="Commers">Commerce</option>
									</select>
								</div>
								<div class="mb-3 col-6 shy">
									<!--<label class="form-label">Select Shy</label>-->
									<select name="shy" onchange="shy_val()" class="form-select Register_Data">
										 <option value="">Select Shy</option> 
										<option value="1">1'st shy</option>
										<option value="2">2'nd shy</option>
										<option value="3">3'rd shy</option>
									</select>
								</div>
								<div class="mb-3 col-6 medium">
									<!--<label class="form-label">Select Medium</label>-->
									<select name="medium" onchange="medium_val()" class="form-select Register_Data">
									    <option value="">Select Medium</option>
										<option value="Sinhala">Sinhala</option>
										<option value="English">English</option>
									</select>
								</div>
								<div class="login-form-actions">
									<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>
									<button class="btn btn-next"> <span class="icon"> <i class="bi bi-box-arrow-in-right"></i> </span> Next</button>
								</div>
							</div>

							<div class="row form-step">
								<div class="mb-3 address">
									<label class="form-label">Address)</label>
									<input name="address" onkeyup="address_val()" type="Text" class="form-control Register_Data">
								</div>
								<div class="mb-3 col-12 dictric">
									<!--<label class="form-label d-flex"></label>-->
									<select name="dictric" onchange="Dictric_val()" style="" class=" Register_Data form-select">
										<option value="">Select District</option>
										<option value="Ampara">Ampara</option>
                                        <option value="Anuradhapura">Anuradhapura</option>
                                        <option value="Badulla">Badulla</option>
                                        <option value="Batticaloa">Batticaloa</option>
                                        <option value="Colombo">Colombo</option>
                                        <option value="Galle">Galle</option>
                                        <option value="Gampaha">Gampaha</option>
                                        <option value="Hambantota">Hambantota</option>
                                        <option value="Jaffna">Jaffna</option>
                                        <option value="Kalutara">Kalutara</option>
                                        <option value="Kandy">Kandy</option>
                                        <option value="Kegalle">Kegalle</option>
                                        <option value="Kilinochchi">Kilinochchi</option>
                                        <option value="Kurunegala">Kurunegala</option>
                                        <option value="Mannar">Mannar</option>
                                        <option value="Matale">Matale</option>
                                        <option value="Matara">Matara</option>
                                        <option value="Moneragala">Moneragala</option>
                                        <option value="Mullaitivu">Mullaitivu</option>
                                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                                        <option value="Polonnaruwa">Polonnaruwa</option>
                                        <option value="Puttalam">Puttalam</option>
                                        <option value="Ratnapura">Ratnapura</option>
                                        <option value="Trincomalee">Trincomalee</option>
                                        <option value="Vavuniya">Vavuniya</option>
									</select>
								</div>
								<div class="mb-3 city">
									<label class="form-label">Select City</label>
									<input name="city" onkeyup="city_val()" type="Text" class="form-control Register_Data">
									<!--<select name="city" onclick="city_val()" class="form-select Register_Data">-->
									<!--	<option value="galigamuwa">galigamuwa</option>-->
									<!--	<option value="kegalle">kegalle</option>-->
									<!--	<option value="kandy">kandy</option>-->
									</select>
								</div>
								<div class="login-form-actions">
									<input type="hidden" name="register" class="Register_Data">
									<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>
									<button id="register" class="btn submit-btn"><span class="icon"><i class="bi bi-arrow-right-circle"></i></span> Register</button>
								</div>
								<center><img class="loading" style="display: none;" src="../assets/images/gif/loading01.gif" width="50" height="50" alt=""></center>
							</div>

							<!--<div class="row form-step">-->
							<!--	<div class="mb-3 guaname">-->
							<!--		<label class="form-label">Guardian Name</label>-->
							<!--		<input name="guaname" onkeyup="gunName_val()" type="Text" class="form-control Register_Data">-->
							<!--	</div>-->
							<!--	<div class="mb-3 gunnum">-->
							<!--		<label class="form-label">Guardian Phone Number</label>-->
							<!--		<input name="guanum" onkeyup="GuaNum_val()" type="number" class="form-control Register_Data">-->
							<!--	</div>-->
							<!--	<div class="login-form-actions">-->
							<!--		<input type="hidden" name="register" class="Register_Data">-->
							<!--		<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>-->
							<!--		<button id="register" class="btn submit-btn"><span class="icon"><i class="bi bi-arrow-right-circle"></i></span> Register</button>-->
							<!--	</div>-->
							<!--	<center><img class="loading" style="display: none;" src="../assets/images/gif/loading01.gif" width="50" height="50" alt=""></center>-->
							<!--</div>-->

							<div class="login-form-footer">
								<div class="additional-link">
									Register in anathor email<br> <a href="?login"> Go back</a>
								</div>
								<div class="additional-link mt-2">
									<!--<span>යම් කිසි ගැටලුවක් වේනම් 0742966266 අමතන්න.</span>-->
									<span>යම් කිසි ගැටලුවක් වේනම් <a class="suneup" href="#">How to register</a> හෝ 0742966266 අමතන්න.</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } else {
			header('location:?login');
			exit;
		} ?>
		<!-- Register box end -->

	</div>

	<!-- alert section Start -->
	<section>
		<div class="popup-overlay"></div>
		<div class="modal-box">
			<i class="fa-solid fa-xmark close-x"></i>

			<div class="row">
				<center>
					<img class="w-50" src="" alt="">
				</center>
				<div class="col-sm-4 col-12 w-100" style="text-align: center;">
					<div class="card-body p-2">
						<h2>Please Wait ...</h2>
						<span>Please Login Now</span>
					</div>
				</div>
			</div>
			<div class="buttons pt-0 mt-0">
				<button class="btn btn-info mt-3 w-100"><span class="log_text_01">Login</span></button>
			</div>
		</div>
	</section>
	<!-- alert section END -->

	<!-- *************
			************ Required JavaScript Files *************
		************* -->
		
	<script>
	window.onload = function() {
		url_data = window.location.search;
		if (url_data == '?register') {
			if (!localStorage.getItem("regStatus") == 1) {
				window.location.href = "../login";
			}else{
				email.value = localStorage.getItem("email");
				fname.value = localStorage.getItem("fname");
				lname.value = localStorage.getItem("lname");
			}
		}
	};
	</script>
	<!-- Required jQuery first, then Bootstrap Bundle JS -->
	<script src="../Dachbord/assets/js/jquery.min.js"></script>
	<script src="../Dachbord/assets/js/bootstrap.bundle.min.js"></script>
	<script src="../Dachbord/assets/js/modernizr.js"></script>
	<script src="../Dachbord/assets/js/moment.js"></script>
	
	<!-- Bootstrap Select JS -->
	<!--<script src="../Dachbord/assets/vendor/bs-select/bs-select.min.js"></script>-->
	<!--<script src="../Dachbord/assets/vendor/bs-select/bs-select-custom.js"></script>-->

	<!--google login link-->
	<script src="https://apis.google.com/js/platform.js" async defer></script>

	<!-- *************
			************ Vendor Js Files *************
		************* -->

	<!-- Main Js Required -->
	<script src="../Dachbord/assets/js/main.js"></script>
	<script src="../assets/js/loginjs/alert.js"></script>
	<script src="../assets/js/loginjs/multi.js"></script>

</body>

</html>