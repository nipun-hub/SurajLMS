<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'setting'; ?>

<?php include_once('sql/function.php'); ?>

<?php include_once('include/main.php'); ?>




<!doctype html>
<html lang="en">

<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta -->
	<link rel="shortcut icon" href="../assets/images/ict.ico">

	<!-- Title -->
	<title>Surajskumara.lk | Admin</title>

	<?php include('include/header.php'); ?>

	<link rel="stylesheet" href="assets/css/alert.css">

	<style>
		.select2 {
			width: 100% !important;
		}
	</style>

	<style>
		/* Style for positioning toast */
		.toast {
			z-index: 10100;
		}

		.rotatr-continuar {
			animation: in 2s linear 0s infinite normal;
		}

		@keyframes in {
			from {
				transform: rotate(0deg);
			}

			to {
				transform: rotate(360deg);
			}
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


					<!-- Row start -->
					<div class="row">

						<!-- Column start -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Change Landing Page Main image</h4>
								</div>
								<div class="card-body">

									<div class="col-lg-3 col-sm-4 col-6">
										<div class="card text-center">
											<div class="card-header d-flex justify-content-center">
												<img src="../assets/images/suraj-imge-01.jpg" class="img-fluid" id="mainImage" alt="Surajskumara.lk admin">
											</div>
											<div class="card-body">
												<input class="btn btn-primary mt-2" type="file" onchange="showImage(event)" />
											</div>
											<script>
												function showImage(event) {
													const image = document.getElementById('mainImage');
													const currentUrl = image.src;
													const formData = new FormData();
													formData.append('replaceFile', "");
													formData.append('image', event.target.files[0]);
													formData.append('url', currentUrl);

													fetch('sql/process.php', {
															method: 'POST',
															body: formData
														})
														.then(response => console.log(response))
														.then(data => {
															if (data.success) {
																image.src = "image/" + data.filename;
															}
														})
														.catch(error => console.error('Error:', error));
													image.src = URL.createObjectURL(event.target.files[0]);
													image.onload = () => URL.revokeObjectURL(image.src);
												}
											</script>
										</div>
									</div>

								</div>
							</div>
						</div>
						<!-- Column end -->

					</div>
					<!-- Content wrapper end -->

					<div class="row">
						<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<div class="card-body d-flex flex-column item-center gap-3">
									<img width="200" height="200" id="previewSlider1Image" src="../assets/images/slides/imge-1.jpg" alt="">
									<input class="btn btn-primary mt-2 w-75" type="file" onchange="showImageSlider1(event)" />

									<script>
										function showImageSlider1(event) {
											const image = document.getElementById('previewSlider1Image');
											image.src = URL.createObjectURL(event.target.files[0]);
											image.onload = () => URL.revokeObjectURL(image.src);
										}
									</script>
								</div>
							</div>
							<!-- Column end -->
						</div>
						<!-- Row end -->

						<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<div class="card-body d-flex flex-column item-center gap-3">
									<img width="200" height="200" src="../assets/images/slides/imge-2.jpg" alt="" id="previewSlider2Image">
									<input class="btn btn-primary mt-2 w-75" type="file" onchange="showImageSlider2(event)" />
									<script>
										function showImageSlider2(event) {
											const image = document.getElementById('previewSlider2Image');
											image.src = URL.createObjectURL(event.target.files[0]);
											image.onload = () => URL.revokeObjectURL(image.src);

										}
									</script>
								</div>
							</div>
							<!-- Column end -->
						</div>

						<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<div class="card-body d-flex flex-column item-center gap-3">
									<img width="200" height="200" src="../assets/images/slides/imge-3.jpg" alt="" id="previewSlider3Image">
									<input class="btn btn-primary mt-2 w-75" type="file" onchange="showImageSlider3(event)" />
									<script>
										function showImageSlider3(event) {
											const image = document.getElementById('previewSlider3Image');
											image.src = URL.createObjectURL(event.target.files[0]);
											image.onload = () => URL.revokeObjectURL(image.src);
										}
									</script>
								</div>
							</div>
							<!-- Column end -->
						</div>

						<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<div class="card-body d-flex flex-column item-center gap-3">
									<img width="200" height="200" src="../assets/images/slides/imge-4.jpg" alt="" id="previewSlider4Image">
									<input class="btn btn-primary mt-2 w-75" type="file" onchange="showImageSlider4(event)" />
									<script>
										function showImageSlider4(event) {
											const image = document.getElementById('previewSlider4Image');
											image.src = URL.createObjectURL(event.target.files[0]);
											image.onload = () => URL.revokeObjectURL(image.src);
										}
									</script>
								</div>
							</div>
							<!-- Column end -->
						</div>
					</div>

					<!-- app footer -->
					<?php include('include/footer.php'); ?>

				</div>
				<!-- Content wrapper scroll end -->

			</div>
			<!-- *************
				************ Main container end *************
			************* -->

		</div>

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

		<!-- Main Js Required -->
		<script src="assets/js/main.js"></script>

		<!-- alert js -->
		<!-- <script src="assets/js/alert.js"></script> -->
		<!-- <script src="assets/js/error.js"></script> -->
		<!-- <script src="assets/js/validate.js"></script> -->
		<!-- <script src="assets/js/nTost.js"></script> -->
</body>

</html>