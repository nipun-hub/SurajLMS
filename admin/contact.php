<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'contact'; ?>

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

						<?php
						$sql = "SELECT * FROM adminuser WHERE ShowAdm = 1";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$reusalt = $stmt->get_result();

						while ($reusalt->num_rows && $row = $reusalt->fetch_assoc()) { ?>
							<?php
							$accessStr = '';
							$accessList = explode("][", substr($row['Access'], 1, -1));
							foreach ($accessList as $key => $value) {
								$accessStr .= $value . "  ";
							}
							$image = empty($row['image']) ? "../admin/assets/img/admin/admin.jpg" : "../admin/assets/img/admin/" . $row['image'];
							?>
							<div class="col-lg-3 col-sm-4 col-6">
								<div class="card text-center">
									<div class="card-header d-flex justify-content-center">
										<img src="<?php echo $image; ?>" width="100" height="100" class="rounded-circle border border-5" alt="Surajskumara.lk admin">
									</div>
									<div class="card-body">
										<h5 class="card-title"><?php echo $row['UName'] ?></h5>
										<p class="mb-3"><?php echo $row['Type'] . " " . $accessStr; ?></p>
										<!-- <a class="aler/ alert-success px-3 py-2 rounded-pill w-auto">Admin Susipwan 2024</a> -->
										<!-- <a href="#" class="">Update</a> -->
									</div>
									<div class="card-footer d-flex justify-content-between">
										<p><i class="bi bi-whatsapp text-success me-2"></i><?php echo empty($row['MobNum']) ? "undefind" : $row['MobNum'] ?></p>
										<p>|</p>
										<p><i class="bi bi-telephone text-info me-2"></i> <?php echo empty($row['WhaNum']) ? "undefind" : $row['WhaNum'] ?></p>
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