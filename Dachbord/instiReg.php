<!-- database connection include -->
<?php include('sql/conn.php'); ?>
<?php include_once('sql/function.php'); ?>
<?php include_once('include/main.php');?>

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
					<?php if(true){ ?>
					<div class="row item-center">
						<div class="col-xxl-6 col-md-8 col-sm-10 col-12 carousel slide" id="carouselExampleSlidesOnly" data-bs-ride="carousel">
							<div class="carousel-inner">

								<?php for ($i = 0; $i < 0; $i++) { ?>
									<div class="card my-3 p-2 w-100 main-info-card carousel-item <?php if ($i == 0) {
																										echo "active";
																									} ?>">
										<div class="main-info-card-head">
											<p>2025 AL Paper <?php echo $i; ?> සුසිප්වන් ගම්පහ</p>
											<span class="text-success"><i class="bi bi-trophy"></i></span>
										</div>
										<div class="main-info-card-body">
											<img src="assets/img/site use/icons/logo.png" width="75" height="75" alt="">
											<div class="ps-3">
												<span class="text-red">1'st Place</span>
												<span class="fs-6">W.D Nethuli Nimasha Hettiarachchi - kegalle</span>
											</div>
										</div>
										<div class="main-info-card-end">
											<span class="text-dark">physical peaper &nbsp; - &nbsp;14.00 to 16.00</span>
											<span class="text-success">90%</span>
										</div>
									</div>
								<?php } ?>

							</div>
						</div>
					</div>
					<?php } ?>
					<!-- row end -->

					<!-- Row start -->
					<div class="row">
						<?php
                        mysqli_set_charset($conn, "utf8mb4");
						$sql_insti_data = "SELECT insti.* FROM insti,user WHERE insti.Status = 'active' and user.Status != 'active' and user.UserId = '$UserId'";
						$rusalt_insti_data = $conn->query($sql_insti_data);
						while ($row_insti_data = $rusalt_insti_data->fetch_assoc()) {
                            $rusaltFunc = instiBtn($conn, $UserId, $row_insti_data['InstiName']);
                            if($rusaltFunc == 'pending'){
                                $indigator = "<i class='bi bi-patch-exclamation'></i>";
                            }elseif($rusaltFunc == 'login'){
                                $indigator = "<i class='bi bi-check2-circle'></i>";
                            }else{
                                $indigator = "<i class='bi bi-lock'></i>";
                            }
						?>
							<div class="col-xxl-4 col-sm-6 col-12">
								<div class="info-tile">
									<center><img class="w-auto" src="assets/img/site use/instiimge/<?php echo $row_insti_data['InstiPic']; ?>" alt="<?php echo $row_insti_data['InstiName']." ".$row_insti_data['InstiPlace']?>"></center>
									<div class="info-details" style="height: 125px;">
										<span>
											<?php echo $row_insti_data['InstiPlace']; ?>
										</span>
										<?php echo $indigator;?>
										<p class="pt-2">
											<?php echo $row_insti_data['Dict']; ?>
										</p>
									</div>
									<div class="card__data">
										<center>
											<p>
												<span class="card__description"><b>
														<?php echo $row_insti_data['InstiName'].$UserId; ?>
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
						<?php } ?>

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
	</script>
		
	<!-- Required jQuery first, then Bootstrap Bundle JS -->
	<!-- <script src="assets/js/jquery.min.js"></script> -->
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

</html>