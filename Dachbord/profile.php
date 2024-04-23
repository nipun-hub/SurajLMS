<!-- database connection include -->
<?php include('sql/conn.php'); ?>
<?php include_once('sql/function.php'); ?>
<?php include_once('include/main.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'profile'; ?>


<!doctype html>
<html lang="en">

<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta -->
	<meta name="description" content="Suraj S Kumara - A/L ICT - Online">
	<meta name="keywords" content="surajskumara , suraj s kumara , A/L ict , Online">
	<title>Surajskumara.lk | Profile</title>
	<meta property="og:site_name" content="surajskumara.lk">
	<meta property="og:title" content="Suraj S Kumara" />
	<meta property="og:description" content="Suraj S Kumara - A/L ICT - Online" />
	<meta property="og:image" itemprop="image" content="https://surajskumara.lk/assets/images/suraj-imge-01.jpg">
	<meta property="og:type" content="website" />
	<meta name="author" content="Suraj S Kumara">
	<link rel="shortcut icon" href="../assets/images/ict.ico">

	<?php include('include/header.php'); ?>

	<link rel="stylesheet" href="assets/css/alert.css">

	<style>
		.StylingText01 {
			min-height: 75px;
			width: auto;
			display: grid;
			place-content: center;
			background-color: black;
			/* min-height: 25vh; */
			font-family: "Oswald", sans-serif;
			/* font-size: clamp(0.5rem, 0rem + 12vw, 2rem); */
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
					<?php
					if (true) {
						$sql = "SELECT user.Status as userStatus,user.InstiId as  InstiIdUser,user.*,insti.* FROM user LEFT JOIN insti ON user.InstiName = user.InstiName WHERE user.UserId = ?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("i", $UserId);
						$stmt->execute();
						$reusalt = $stmt->get_result();
						$stmt->close();
						if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
							$UName = $row['UserName'];
							$RegisterCode = $row['RegCode'];
							$dp = $row['Picture'];
							$InstiName = empty($row['InstiName']) ? "Not Registered Institute" : $row['InstiName'];
							$Point = $row['Point'];
							$InstiId = empty($row['InstiName']) ? "Not Registered Institute" : $row['InstiIdUser'];
							$Email = $row['Email'];
							$SubDict = $row['SubDict'];
							$InstiPic = empty($row['InstiPic']) ? "assets/img/site use/searcing.gif" : "assets/img/site use/instiimge/" . $row['InstiPic'];
							$Email = $row['Email'];
							$Place = empty($row['InstiPlace']) ? "" : " ( " . $row['InstiPlace'] . " ) ";
							$StatusIndi = $row['userStatus'] == 'active' ? "<p class='text-success'><i class='bi bi-check-circle'></i> active</p>" : ($row['userStatus'] == 'pending' ? "<p class='text-warning'><i class='bi bi-clock-history'></i> Pending activation</p>" : "<p class='text-red'><i class='bi bi-x-circle'></i> Not Registered Institute</p>");
							$StatusIndi = empty($row['InstiName']) ? "" : $StatusIndi;
							$compPrecentage = 30;
						}
					}
					?>

					<div class="row">
						<div class="col-auto">
							<div class="card">
								<div class="card-body product-added-card m-3 row justify-content-center">
									<div class="col-auto">
										<img class="product-added-img" width="100" height="100" src="<?php echo $dp; ?>" alt="User profile picture" onerror="imgNotFound()">
									</div>
									<div class="col-auto row">
										<div class="col-auto m-2">
											<h5><?php echo $UName; ?></h5>
											<P class="text-dark"><?php echo $InstiName; ?></P>
											<p class="text-success"><i class="bi bi-check-circle"></i> active</p>
										</div>
										<div class="col-auto m-2 ">
											<h5 class=" p-2 alert alert-info">points <?php echo $Point; ?></h5>
										</div>
										<div class="col">
											<div class="income-graph">
												<input type="hidden" name="precentage" id="compPrecentage" value="<?php echo $compPrecentage ?>">
												<div id="overallSales" class="svg-container"></div>
											</div>
											<div class="income-high-low text-center">
												Compleated
											</div>
										</div>
									</div>
									<!-- <div class="col-auto"><h5>Susipwan</h5></div> -->
								</div>
							</div>
						</div>

						<div class="col-auto">

						</div>

					</div>

					<!-- Row start -->
					<div class="row">

						<div class="col-xxl-4 col-md-6  col-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title w-100">User Information
										<hr class="text-dark">
									</div>
								</div>
								<div class="card-body ">
									<div class="row border border-1 m-1 p-1 pb-3">
										<div class=" col-12">
											<div class="product-added-card-body">
												<p class="text-dark">User Name</p>
												<div>
													<p class="ms-3"><?php echo $UName; ?></p>
												</div>
												<p class="text-dark">Basic information</p>
												<div class="row">
													<div class="col-12 row m-1">
														<div class="col-4">
															<p> Register Code </p>
														</div>
														<div class="col-auto">
															<p> - </p>
														</div>
														<div class="col-auto">
															<p> <?php echo $RegisterCode; ?> </p>
														</div>
													</div>
													<div class="col-12 row m-1">
														<div class="col-4">
															<p> Insti Id </p>
														</div>
														<div class="col-auto">
															<p> - </p>
														</div>
														<div class="col-auto">
															<p> <?php echo $InstiId; ?> </p>
														</div>
													</div>
													<div class="col-12 row m-1">
														<div class="col-4">
															<p> Email </p>
														</div>
														<div class="col-auto">
															<p> - </p>
														</div>
														<div class="col-auto">
															<p> <?php echo $Email; ?> </p>
														</div>
													</div>
												</div>
												<div class="d-flex">
													<p class="text-dark pe-5">Account status</p>
													<img class="" src="assets/img/site use/pending.gif" width="25" height="25">
												</div>
												<div class="product-added-actions mt-3 item-center">
													<button class="btn btn-light remove-from-cart" onclick="loadModelData('editInfo')" data-bs-toggle="modal" data-bs-target="#editInfoModel">Change information</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xxl-4 col-md-6 col-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title w-100">Institute Information
										<hr class="text-dark">
									</div>
								</div>
								<div class="card-body d-flex row border border-1 m-3">
									<div class="col-auto">
										<img class="product-added-img" width="100" src="<?php echo $InstiPic; ?>" alt="User profile picture">
									</div>
									<div class="col-auto d-flex row">
										<div class="col-12 align-self-center">
											<h5><?php echo "{$InstiName} {$Place}" ?></h5>
											<P class="text-dark"><?php echo $SubDict; ?></P>
											<?php echo $StatusIndi; ?>
											<!-- <p class="text-success"><i class="bi bi-check-circle"></i> active</p> -->
											<!-- <p class="text-red"><i class="bi bi-x-circle"></i> Not Registered Institute</p> -->
											<!-- <p class="text-warning"><i class="bi bi-clock-history"></i></i> Pending activation</p> -->
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xxl-4 col-md-6  col-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title w-100">Class Information
										<hr class="text-dark">
									</div>
								</div>
								<div class="card-body d-flex row m-1">
									<?php
									if (true) {
										$sql = "SELECT class.* FROM user JOIN class ON user.Year = class.Year and user.InstiName = class.InstiName and class.Status = 'active' WHERE user.UserId = ?";
										$stmt = $conn->prepare($sql);
										$stmt->bind_param("i", $UserId);
										$stmt->execute();
										$reusalt = $stmt->get_result();
										$stmt->close();
										while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
											$classId = $row['ClassId'];
											$className = $row['ClassName'];
											$year = $row['year'];
											$payStatus = null;

											// get payment data 
											$sql = "SELECT Status FROM payment WHERE UserId = ? and ClassId = ?";
											$stmt = $conn->prepare($sql);
											$stmt->bind_param("ii", $UserId, $classId);
											$stmt->execute();
											$reusalt2 = $stmt->get_result();
											$stmt->close();
											if ($reusalt2->num_rows > 0 && $row2 = $reusalt2->fetch_assoc()) {
												$payStatus = $row2['Status'];
											}
											$payIndi = $payStatus == 'active' ? "<p class='text-success'><i class='bi bi-check-circle'></i> active</p>" : ($payStatus == 'pending' ? "<p class='text-red'><i class='bi bi-x-circle'></i> Payment pending . . .</p>" : "<p class='text-red'><i class='bi bi-x-circle'></i> Payment pending . . .</p>");
									?>
											<div class="col-12 d-flex row border border-1 m-1 p-1">
												<div class="col-4">
													<div class="StylingText01">
														<!-- <div class="subtitle"></div> -->
														<div class="top"><?php echo $className; ?></div>
														<div class="bottom" aria-hidden="true"><?php echo $className; ?></div>
													</div>
												</div>
												<div class="col-8 align-self-center">
													<h5><?php echo $year . " " . $className; ?> </h5>
													<?php echo $payIndi; ?>
												</div>
											</div>
										<?php }
										if (!$reusalt->num_rows > 0) { ?>
											<div class="text-center text-red">
												<h3>Oops!</h3>
												Not Registered Institute
											</div>
									<?php
										}
									} ?>
								</div>
								<!-- <div class="card-body text-center text-red" hidden> -->
								<!-- </div> -->
							</div>
						</div>

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

	<!-- model section -->
	<div class="modal fade" id="editInfoModel" tabindex="-1" aria-labelledby="editInfoModelLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content" id="model-edit-info">
			</div>
		</div>
	</div>


	<!-- alert include -->
	<?php include('include/alert.php'); ?>
	<?php include('include/animated-special.php'); ?>


	<!-- *************
			************ Required JavaScript Files *************
		************* -->
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

	<!-- Circleful -->
	<script src="assets/vendor/circliful/circliful.min.js"></script>
	<script>
		$(document).ready(function() {
			var compPrecentage = document.querySelectorAll('#compPrecentage');
			compPrecentage.forEach((self) => {
				$("#overallSales").circliful({
					animation: 1,
					animationStep: 5,
					foregroundBorderWidth: 25,
					backgroundBorderWidth: 25,
					percent: self.value,
					textStyle: 'font-size: 12px;',
					fontColor: '#ff0000',
					foregroundColor: '#ff0000',
					backgroundColor: 'rgba(0, 0, 0, 0.1)',
					multiPercentage: 1,
					percentages: [10, 20, 30],
				});
			});
		});
	</script>

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

	<!-- alert js -->
	<script src="assets/js/alert.js"></script>
	<script src="assets/js/error.js"></script>

	<script>
		function loadModelData(type) {
			formData = "loadModel" + "&type=" + type + "&id=" + <?php echo $UserId; ?>;
			$.post("sql/process.php", formData, function(response, status) {
				// console.log(response);
				$('#model-edit-info').html(response);
			});
		}

		function imgNotFound() {
			null;
		}

		window.onload = function() {

			var specialAnimation = document.querySelectorAll('.special-animate');
			console.log('success');
			specialAnimation.forEach(function(self) {
				self.classList.add('snowflake');
			});

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

</body>

</html>