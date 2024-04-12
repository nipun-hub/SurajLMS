<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<?php if (isset($_REQUEST['logout'])) {
	session_destroy();
	header('location:./');
	exit;
} ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'contact'; ?>

<?php include_once('sql/function.php'); ?>

<?php
// chech logged user ?
if (!isset($_SESSION['login'])) {
	header('location:../login');
	exit;
} else {
	$UserId = $_SESSION['login'];
	$sql = "SELECT UserName,RegCode FROM user WHERE UserId = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $UserId);
	$stmt->execute();
	$result_data = $stmt->get_result();
	if ($row = $result_data->fetch_assoc()) {
		$_SESSION['username'] = $row['UserName'];
		$_SESSION['regcode'] = $row['RegCode'];
	} else {
		header('location:../login');
		exit;
	}
}
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
	<title>Surajskumara.lk | Contact</title>
	<meta property="og:site_name" content="surajskumara.lk">
	<meta property="og:title" content="Suraj S Kumara" />
	<meta property="og:description" content="Suraj S Kumara - A/L ICT - Online" />
	<meta property="og:image" itemprop="image" content="https://surajskumara.lk/assets/images/suraj-imge-01.jpg">
	<meta property="og:type" content="website" />
	<meta name="author" content="Suraj S Kumara">
	<link rel="shortcut icon" href="../assets/images/ict.ico">

	<?php include('include/header.php'); ?>

	<link rel="stylesheet" href="assets/css/alert.css">

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

						<div class="col-sm-12 col-12 notification">
							<!-- <div class="card bg-transparent"> -->
							<div class="card-header">
								<div class="card-title">COntact Us</div>
							</div>
							<div class="card-body p-0 m-0">
								<ul class="user-messages">
									<li>
										<img src="assets/img/development.gif" alt="" width="500">
									</li>
								</ul>
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

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

	<!-- alert js -->
	<script src="assets/js/alert.js"></script>
	<script src="assets/js/error.js"></script>

	<script>
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