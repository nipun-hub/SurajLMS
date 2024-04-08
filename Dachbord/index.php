<!-- database connection include -->
<?php include('sql/conn.php'); ?>
<?php include_once('sql/function.php'); ?>
<?php include_once('include/main.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'dashbord'; ?>

<!doctype html>
<html lang="en">

<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta -->
	<meta name="description" content="Suraj S Kumara - A/L ICT - Online">
	<meta name="keywords" content="surajskumara , suraj s kumara , A/L ict , Online">
	<title>Surajskumara.lk | Dashbord</title>
	<meta property="og:site_name" content="surajskumara.lk">
	<meta property="og:title" content="Suraj S Kumara" />
	<meta property="og:description" content="Suraj S Kumara - A/L ICT - Online" />
	<meta property="og:image" itemprop="image" content="https://surajskumara.lk/assets/images/suraj-imge-01.jpg">
	<meta property="og:type" content="website" />
	<meta name="author" content="Suraj S Kumara">
	<link rel="shortcut icon" href="assets/img/site use/icons/logo.png">

	<?php include('include/header.php'); ?>

	<link rel="stylesheet" href="assets/css/alert.css">

	<!-- google icon link -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

	<!-- carosal link -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">

	<style>
		.StylingText01 {
			/* height: 150px; */
			display: grid;
			place-content: center;
			background-color: black;
			min-height: 25vh;
			font-family: "Oswald", sans-serif;
			font-size: clamp(0.5rem, 0rem + 12vw, 2rem);
			font-size: auto;
			font-weight: 700;
			text-transform: uppercase;
			color: hsl(0, 0%, 100%);
		}

		.StylingText01 .subtitle {
			position: absolute;
			justify-self: center;
			align-self: flex-end;
			margin-bottom: 20px;
			font-size: 20px;
		}

		.StylingText01>div {
			grid-area: 1/1/-1/-1;
		}

		.StylingText01 .top {
			clip-path: polygon(0% 0%, 100% 0%, 100% 48%, 0% 58%);
		}

		.StylingText01 .bottom {
			clip-path: polygon(0% 60%, 100% 50%, 100% 100%, 0% 100%);
			color: transparent;
			background: -webkit-linear-gradient(177deg, black 53%, hsl(0, 0%, 100%) 65%);
			background: linear-gradient(177deg, black 53%, hsl(0, 0%, 100%) 65%);
			background-clip: text;
			-webkit-background-clip: text;
			transform: translateX(-0.02em);
		}
	</style>

</head>

<body>

	<!-- Loading wrapper -->
	<?php include('include/snipper.php'); ?>

	<!-- Page wrapper start -->
	<div class="page-wrapper">

		<!-- Sidebar wrapper -->
		<?php include_once('include/sidebar.php'); ?>


		<!-- *************
				************ Main container start *************
			************* -->
		<div class="main-container">

			<!-- page header -->
			<?php include('include/navbar.php'); ?>

			<!-- Content wrapper scroll start -->
			<div class="content-wrapper-scroll">

				<!-- Content wrapper start -->
				<div class="content-wrapper">

					<!-- row start -->
					<?php if (true) { ?>
						<div class="row item-center" id="stuWinInforBaer">
							<div class="col-xxl-6 col-md-8 col-sm-10 col-12 carousel slide" id="carouselExampleSlidesOnly" data-bs-ride="carousel">
								<div class="carousel-inner">

									<?php
									mysqli_set_charset($conn, "utf8mb4");
									$sql = "SELECT Dict,Image From notification WHERE Type = 'winner' and Status = 'active' ORDER BY Date DESC";
									$stmt = $conn->prepare($sql);
									$stmt->execute();
									$reusalt = $stmt->get_result();
									$i = 0;
									while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
										$image = $row['Image'];
										$Data = json_decode($row['Dict']);
										$title = $Data[1];
										$place = $Data[3];
										$winnerName = $Data[5];
										$dict = $Data[7];
										$marks = $Data[9];
									?>
										<div class="card my-3 p-2 w-100 main-info-card carousel-item <?php if ($i == 0) {
																											echo "active";
																										} ?>">
											<div class="main-info-card-head">
												<p><?php echo $title; ?></p>
												<span class="text-success"><i class="bi bi-trophy"></i></span>
											</div>
											<div class="main-info-card-body">
												<img src="user_images/winner/<?php echo $image; ?>" width="75" height="75" alt="">
												<div class="ps-3">
													<span class="text-red"><?php echo $place; ?></span>
													<span class="fs-6"><?php echo $winnerName; ?></span>
												</div>
											</div>
											<div class="main-info-card-end">
												<span class="text-dark"><?php echo $dict; ?></span>
												<span class="text-success"><?php echo $marks; ?></span>
											</div>
										</div>
									<?php $i++;
									} ?>

								</div>
							</div>
						</div>
					<?php } ?>
					<!-- row end -->

					<!-- Row start -->
					<div class="row item-center">
						<?php
						mysqli_set_charset($conn, "utf8mb4");
						$sql_insti_data = "SELECT insti.* FROM insti,user WHERE insti.Status = 'active' and user.Status != 'active' and user.UserId = '$UserId' ";
						$rusalt_insti_data = $conn->query($sql_insti_data);
						while ($rusalt_insti_data->num_rows > 0 && $row_insti_data = $rusalt_insti_data->fetch_assoc()) {
							$rusaltFunc = instiBtn($conn, $UserId, $row_insti_data['InstiName']);
							if ($rusaltFunc == 'pending') {
								$indigator = "<i class='bi bi-patch-exclamation'></i>";
							} elseif ($rusaltFunc == 'login') {
								$indigator = "<i class='bi bi-check2-circle'></i>";
							} else {
								$indigator = "<i class='bi bi-lock'></i>";
							}
						?>
							<div class="col-xxl-4 col-sm-6 col-12">
								<div class="info-tile">
									<center><img class="w-auto" src="assets/img/site use/instiimge/<?php echo $row_insti_data['InstiPic']; ?>" alt="<?php echo $row_insti_data['InstiName'] . " " . $row_insti_data['InstiPlace'] ?>"></center>
									<div class="info-details" style="height: 125px;">
										<span class="green">
											<?php echo $row_insti_data['InstiPlace']; ?>
										</span>
										<?php echo $indigator; ?>
										<p class="pt-2">
											<?php echo $row_insti_data['Dict']; ?>
										</p>
									</div>
									<div class="card__data">
										<center>
											<p>
												<span class="card__description"><b>
														<?php echo $row_insti_data['InstiName']; ?>
													</b><br>
													<?php echo $row_insti_data['SubDict']; ?>
												</span>
											</p>
											<div class="card_btn">
												<button type="submit" class="btn btn-info mt-3 p-2">Info<span class="icon"> <i class="bi bi-info-circle"></i></span></button>
												<?php
												if ($rusaltFunc == 'register' || $rusaltFunc == 'login' || $rusaltFunc == 'pending') {
												?>
													<button onclick="nthj('<?php echo $rusaltFunc; ?>','<?php echo $row_insti_data['InstiName']; ?>')" class="btn btn-info mt-3 p-2"><?php echo $rusaltFunc; ?><span class="icon"> <i class="bi bi-arrow-right-circle"></i> </span></button>
												<?php } ?>
											</div>
										</center>

									</div>
								</div>
							</div>
							<?php }
						if (!$rusalt_insti_data->num_rows > 0) {
							$thisMonth = GetToday('ym');
							// $sql = "SELECT c.*,p.Status AS 'payStatus' FROM user u JOIN class c ON u.Year = c.year and u.InstiName = c.InstiName and c.Status = 'active' LEFT JOIN payment p ON u.UserId = p.UserId and c.ClassId = p.ClassId and p.Month = ? WHERE u.UserId = ? ";
							$sql = "SELECT c.* FROM user u JOIN class c ON u.Year = c.year and u.InstiName = c.InstiName and c.Status = 'active'  WHERE u.UserId = ?";
							$stmt = $conn->prepare($sql);
							$stmt->bind_param("i", $UserId);
							$stmt->execute();
							$reusalt = $stmt->get_result();
							$stmt->close();
							while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
								$inclassindi = $row['Conducting'] == 1 ? "<span class='alert alert-success p-0 px-2'><img src='assets/img/site use/pending.gif' style='width: 25px; height:25px;'>Now Class in progress</span>" : null ;
							?>
								<div class="col-xxl-4 col-sm-6 col-12">
									<div class="info-tile">
										<div class="StylingText01">
											<!-- <div class="subtitle"></div> -->
											<div class="top"><?php echo $row['year'] . " " . $row['ClassName']; ?></div>
											<div class="bottom" aria-hidden="true"><?php echo $row['year'] . " " . $row['ClassName']; ?></div>
										</div>
										<!-- <center><img class="w-auto" src="assets/img/site use/instiimge/Free Class.jpg"></center> -->
										<div class="info-details" style="height: 125px;">
											<div class="d-flex justify-content-between">
												<span class='alert alert-success'>Actived</span>
												<?php echo $inclassindi; ?>
											</div>
											<p class="pt-2 text-center">
												<?php echo $row['year'] . " " . $row['ClassName'] . " - " . $row['InstiName'] . " " . $row['Type']; ?>
											</p>
										</div>
										<div class="card__data">
											<center>
												<p>
													<span class="card__description">
														<b><?php echo $row['Type']; ?> </b><br><?php echo $row['year'] . " " . $row['ClassName'] . ' - ' . $row['InstiName']; ?>
													</span>
												</p>
												<div class="card_btn class_fot">
													<button id="<?php echo $row['ClassId']; ?>" class="btn btn-info mt-3 p-2">Login<span class="icon"> <i class="bi bi-arrow-right-circle"></i> </span></button>
													<img class="loading_btn" src="../assets/images/gif/loading01.gif" width="25" height="25" alt="">
												</div>
											</center>

										</div>
									</div>
								</div>
						<?php }
						}
						?>
						<!-- <div class="col-auto">
							<div class="info-tile">
								<div class="fb-post" data-href="https://web.facebook.com/permalink.php?story_fbid=pfbid02bgkYujHhNBSuknxBz5cSh2N8ocwEpPikgGAmmLqcbwgGaKk9MN5z52YwdVvNFTHWl&amp;id=100064866994960 " data-width="300" data-show-text="false">
									<blockquote cite="https://www.facebook.com/permalink.php?story_fbid=852373003601592&amp;id=100064866994960" class="fb-xfbml-parse-ignore">
										<p>2024 AL ICT Revision එක 😍
											Network ඇරඹේ 😍
											https://t.me/Suraj24Re
											සුසිප්වන් ගම්පහ මාර්තු 12
											අගහරැවදා 8.00-1.00...</p>Posted by <a href="https://www.facebook.com/people/Suraj-S-kumara/100064866994960/">Suraj S kumara</a> on&nbsp;<a href="https://www.facebook.com/permalink.php?story_fbid=852373003601592&amp;id=100064866994960">Monday, 11 March 2024</a>
									</blockquote>
								</div>
							</div>
						</div> -->
					</div>
					<!-- Row end -->

				</div>
				<!-- Content wrapper end -->

				<!-- app footer -->
				<?php include('include/footer.php'); ?>

			</div>
			<!-- Content wrapper scroll end -->

		</div>
		<!-- *************
				************ Main container end *************
			************* -->

	</div>


	<!-- alert include -->
	<?php include('include/alert.php'); ?>

	<!-- *************
			************ Required JavaScript Files *************
		************* -->

	<script>
		window.onload = function() {
			url_data = window.location.search;
			if (url_data == '?success_login') {
				history.pushState({
					page: 'new-page'
				}, 'New Page', './');
				nthj(3);
			} else if (url_data == '?success_register') {
				history.pushState({
					page: 'new-page'
				}, 'New Page', './');
				nthj(4);
			}
		};

		function clear_btn() {
			var clz_log_btn = document.querySelectorAll(".class_fot");
			clz_log_btn.forEach(function(self) {
				self.querySelector('img').style.display = "none";
				self.querySelector('button').style.display = "block";
			});
		}
		var clz_log_btn = document.querySelectorAll(".class_fot");
		clz_log_btn.forEach(function(self) {
			self.querySelector('button').addEventListener("click", function() {
				clear_btn();
				// console.log(this.id);
				var parentDiv = this.parentElement;
				parentDiv.querySelector('.loading_btn').style.display = "block";
				event.target.style.display = 'none';

				formData = "ClassLogin" + "&id=" + this.id;
				$.post("sql/process.php", formData, function(response, status) {
					if (response == " success") {
						clear_btn();
						window.location.href = "lesson.php";
					} else {
						clear_btn();
						$("#regcode_log").addClass("is-invalid");
					}
				});
			});
		});
	</script>

	<!-- Required jQuery first, then Bootstrap Bundle JS -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/modernizr.js"></script>
	<script src="assets/js/moment.js"></script>

	<!-- *************
			************ Vendor Js Files *************
		************* -->

	<!-- Overlay Scroll JS -->
	<script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
	<script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

	<!-- Apex Charts -->
	<!-- <script src="assets/vendor/apex/apexcharts.min.js"></script> -->
	<!-- <script src="assets/vendor/apex/custom/sales/salesGraph.js"></script> -->
	<!-- <script src="assets/vendor/apex/custom/sales/revenueGraph.js"></script> -->
	<!-- <script src="assets/vendor/apex/custom/sales/taskGraph.js"></script> -->

	<!-- ajex -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

	<!-- alert js -->
	<script src="assets/js/alert.js"></script>
	<script src="assets/js/error.js"></script>

	<script>
		// $(".xdtgyr").owlCarousel({
		// 	// margin: 20,
		// 	loop: true,
		// 	autoplay: true,
		// 	autoplayTimeout: 2000,
		// 	autoplayHoverPause: false,
		// 	responsive: {
		// 		0: {
		// 			items: 1,
		// 			nav: false
		// 		}
		// 	}
		// });
	</script>

</body>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v19.0&appId=995792611527792" nonce="bp2eOU6n"></script>

</html>