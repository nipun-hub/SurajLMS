<?php
?>

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
							<div class="login-welcome" style="text-align: center;">
								<h4>Login</h4>
							</div>

							<div class="mb-3 uname">
								<label class="form-label">Register Code</label>
								<input onkeyup="UserName_val()" name="URegCode" type="text" class="form-control Login_Data" required>
							</div>
							<div class="mb-3 upass">
								<div class="d-flex justify-content-between">
									<label class="form-label">Password</label>
									<!-- <a href="forgot-password.html" class="btn-link ml-auto">Forgot password?</a> -->
								</div>
								<input maxlength="10" minlength="6" onkeyup="UserPass_val()" name="URegPass" type="password" class="form-control Login_Data" required>
							</div>
							<div class="login-form-actions">
							<label class="form-lable">Password must be more than 6 characters and less than 10 characters.</label>
								<!-- <input type="hidden" name="login_submit"> -->
								<!-- <a onclick="Login()" id="login" class="btn"> <span class="icon"> <i class="bi bi-arrow-right-circle"></i> </span>Login</a> -->
								<input type="hidden" name="login" class="Login_Data">
								<button id="login" class="btn submit-btn"><span class="icon"><i class="bi bi-arrow-right-circle"></i></span> Login</button>
							</div>
							<center><img style="display: none;" src="../assets/images/gif/loading01.gif" width="50" height="50" alt=""></center>
							<div class="login-form-footer">
								<div class="additional-link">
									Don't have an account? <br> <a class="suneup" href="?register"> Register</a>
								</div>
								<div class="additional-link mt-2">
									<span>යම් කිසි ගැටලුවක් වේනම් 0742966266 අමතන්න.</span>
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
							<!-- <a href="index.html" class="login-logo">
						<img src="../Dachbord/assets/images/logo.svg" alt="Vico Admin" />
					</a> -->
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
								<div class="multi-step" data-title="Guardian "></div>
							</div>

							<div class="form-step row form-step-active ">
								<div class=" mb-3 col-6 fname">
									<label class="form-label">First Name</label>
									<input name="fname" onkeyup="fname_val()" type="text" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 col-6 lname">
									<label class="form-label">Last Name</label>
									<input name="lname" onkeyup="lname_val()" type="text" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 email">
									<label class="form-label">Email</label>
									<input name="email" onkeyup="email_val()" type="Email" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 col-6 pass">
									<div class="d-flex justify-content-between">
										<label class="form-label">Password</label>
										<!-- <a href="forgot-password.html" class="btn-link ml-auto">Forgot password?</a> -->
									</div>
									<input maxlength="10" minlength="6" name="pass" onkeyup="pass_val()" type="password" class="form-control Register_Data" required>
								</div>
								<div class=" mb-3 col-6 repass">
									<div class="d-flex justify-content-between">
										<label class="form-label">Re Type Password</label>
										<!-- <a href="forgot-password.html" class="btn-link ml-auto">Forgot password?</a> -->
									</div>
									<input maxlength="10" minlength="6" onkeyup="pass_val()" type="password" class="form-control Register_Data">
								</div>
								<label class="form-lable"></label>
								<label class="form-lable">Password must be more than 6 characters and less than 10 characters.</label>
								<div class="login-form-actions onennext">
									<button class="btn btn-next"> <span class="icon"> <i class="bi bi-box-arrow-in-right"></i> </span> Next</button>
								</div>
							</div>

							<div class="row form-step">
								<div class="mb-3 col-6 nic">
									<label class="form-label">Nic Number</label>
									<input name="nic" onkeyup="Nic_val()" type="Number" class="form-control Register_Data">
								</div>
								<div class="mb-3 col-6 nicpic">
									<label class="form-label">Nic Pic</label>
									<input name="nic_pic" onchange="NicPic_val()" type="file" class="form-control" accept=".jpeg, .jpg, .png, .tiff">
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
									<label class="form-label">Date Of Birth</label>
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
									<label class="form-label">Select Year</label>
									<select name="year" onclick="year_val()" class="form-select Register_Data">
										<!-- <option value="">Select</option> -->
										<option value="2024">2024</option>
										<option value="2025">2025</option>
										<option value="2026">2026</option>
									</select>
								</div>
								<div class="mb-3 col-6 streem">
									<label class="form-label">Select Streem</label>
									<select name="streem" onclick="streem_val()" class="form-select Register_Data">
										<!-- <option value="">Select</option> -->
										<option value="Science">Science</option>
										<option value="Tec">Tec</option>
										<option value="Art">Art</option>
										<option value="Commers">Commers</option>
									</select>
								</div>
								<div class="mb-3 col-6 shy">
									<label class="form-label">Select Shy</label>
									<select name="shy" onclick="shy_val()" class="form-select Register_Data">
										<!-- <option value="">Select</option> -->
										<option value="1">1'st shy</option>
										<option value="2">2'nd shy</option>
										<option value="3">3'rd shy</option>
									</select>
								</div>
								<div class="mb-3 col-6 medium">
									<label class="form-label">Select Medium</label>
									<select name="medium" onclick="medium_val()" class="form-select Register_Data">
										<!-- <option value="">Select</option> -->
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
									<label class="form-label">Address</label>
									<input name="address" onkeyup="address_val()" type="Text" class="form-control Register_Data">
								</div>
								<div class="mb-3 dictric">
									<label class="form-label">Select Dictric</label>
									<!-- <input type="text" class="form-control Register_Data"> -->
									<select name="dictric" onclick="Dictric_val()" class="form-select Register_Data">
										<option value="kegalle">kegalle</option>
										<option value="kandy">kandy</option>
									</select>
								</div>
								<div class="mb-3 city">
									<label class="form-label">Select City</label>
									<!-- <input type="text" class="form-control Register_Data"> -->
									<select name="city" onclick="city_val()" class="form-select Register_Data">
										<option value="galigamuwa">galigamuwa</option>
										<option value="kegalle">kegalle</option>
										<option value="kandy">kandy</option>
									</select>
								</div>
								<div class="login-form-actions">
									<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>
									<button class="btn btn-next"> <span class="icon"> <i class="bi bi-box-arrow-in-right"></i> </span> Next</button>
								</div>
							</div>

							<div class="row form-step">
								<div class="mb-3 guaname">
									<label class="form-label">Guardian Name</label>
									<input name="guaname" onkeyup="gunName_val()" type="Text" class="form-control Register_Data">
								</div>
								<div class="mb-3 gunnum">
									<label class="form-label">Guardian Phone Number</label>
									<input name="guanum" onkeyup="GuaNum_val()" type="number" class="form-control Register_Data">
								</div>
								<div class="login-form-actions">
									<input type="hidden" name="register" class="Register_Data">
									<button class="btn btn-prev"> <span class="icon"> <i class="bi bi-box-arrow-in-left"></i> </span> Previous</button>
									<button id="register" class="btn submit-btn"><span class="icon"><i class="bi bi-arrow-right-circle"></i></span> Register</button>
									<button class="btn btn-loding" style="display:none; width:100px; height: 40px; background: lightblue url('../assets/images/gif/loding2.gif') no-repeat center; background-size: 100px 100px;"></button>
								</div>
								<center><img style="display: none;" src="../assets/images/gif/loading01.gif" width="50" height="50" alt=""></center>
							</div>

							<div class="login-form-footer">
								<div class="additional-link">
									Alredy have an account? <br> <a href="?login"> Login</a>
								</div>
								<div class="additional-link mt-2">
									<span>යම් කිසි ගැටලුවක් වේනම් 0742966266 අමතන්න.</span>
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
	<!-- Required jQuery first, then Bootstrap Bundle JS -->
	<script src="../Dachbord/assets/js/jquery.min.js"></script>
	<script src="../Dachbord/assets/js/bootstrap.bundle.min.js"></script>
	<script src="../Dachbord/assets/js/modernizr.js"></script>
	<script src="../Dachbord/assets/js/moment.js"></script>
	<script src="../assets/js/loginjs/multi.js"></script>
	<script src="../assets/js/loginjs/form-validation.js"></script>

	<!-- *************
			************ Vendor Js Files *************
		************* -->

	<!-- Main Js Required -->
	<script src="../Dachbord/assets/js/main.js"></script>

	<script>
		const section = document.querySelector("section"),
			popup_overlay = document.querySelector(".popup-overlay"),
			blur_body = document.querySelector(".page-wrapper"),
			image = document.querySelector('.modal-box img'),
			title = document.querySelector('.modal-box h2'),
			stitle = document.querySelector('.modal-box .card-body span'),
			btn = document.querySelector('.modal-box .buttons button'),
			btn_span = document.querySelector('.modal-box .buttons span'),
			closex = document.querySelector(".close-x");

		closex.addEventListener("click", () => {
			close();
		});

		function show_pop() {
			blur_body.classList.add("active");
			section.classList.add("active");
		}

		function close() {
			section.classList.remove("active");
			blur_body.classList.remove("active")
		}

		function wait() {
			image.src = "../assets/images/gif/loading01.gif";
			closex.style.display = "none";
			title.innerHTML = "Please Wait ...";
			stitle.innerHTML = "";
			btn.style.display = "none";
			show_pop();
		}

		function success(code) {
			image.src = "../assets/images/gif/success.gif";
			closex.style.display = "block";
			title.innerHTML = "Sucessfully Registerd";
			stitle.innerHTML = `Register No : ${code}`;
			btn.style.display = "block";
			btn_span.innerHTML = "Login";
			closex.style.display = "none";
			btn.addEventListener("click", () => {
				window.location.href = "?login";
			});
			show_pop();
		}

		function error_alert() {
			image.src = "../assets/images/gif/error.gif";
			closex.style.display = "block";
			title.innerHTML = "Something Went Wrong!";
			stitle.innerHTML = "Plase try again";
			btn.style.display = "none";
			show_pop();
		}

		function error_log() {
			image.src = "../assets/images/gif/error.gif";
			closex.style.display = "block";
			title.innerHTML = "Something went wrong!";
			stitle.innerHTML = "Plase try again";
			btn.style.display = "none";
			show_pop();
		}
	</script>

</body>

</html>