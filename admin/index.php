<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'dashbord'; ?>

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
					<div class="row item-center">
						<div class="col-xxl-4 col-sm-8 col-10 ">
							<?php
							$sql = "SELECT * FROM class WHERE Conducting = 1 ";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt = $stmt->get_result();
							if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
								$className = "{$row['year']}  {$row['ClassName']} {$row['InstiName']}";
								$decodeDict = json_decode($row['Dict'], true);
								$ending = $decodeDict[1] . " - " . $decodeDict[2];
							?>
								<div class="stats-tile p-2">
									<img src="assets/img/site use/acvitating.gif" alt="" width="30">
									<div class="sale-details ms-3">
										<h5 class="alert alert-success p-1 ps-2">Now Class in progress</h5><i onclick="submitCurrentClass('del')" class="bi bi-trash text-dark position-absolute top-0 end-0 m-2"></i>
										<h6 class="text-dark"><?php echo $className; ?></h6>
										<p class="">Class time with <?php echo $ending; ?></p>
									</div>
								</div>
							<?php
							} else { ?>
								<div class="stats-tile p-2" data-bs-toggle="modal" data-bs-target="#setcurrentclass">
									<img src="assets/img/site use/notfound.jpg" alt="" width="50">
									<!-- <div class="sale-icon-bdr">
								</div> -->
									<div class="sale-details ms-3 w-100">
										<h5 class="alert alert-info p-1 ps-2">Now Class in progress</h5>
										<h6 class="text-dark">No class has started yet</h6>
										<p class="">Click heer to start a class</p>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<!-- Row end -->

					<!-- Row start -->
					<div class="row">
						<?php
						if (false) {
							$sql = "SELECT SUM(CASE WHEN user.RegCode IS NOT NULL AND user.Status = 'active' THEN 1 ELSE 0 END) AS activeUser, SUM(CASE WHEN user.RegCode IS NOT NULL AND user.Status = 'pending' THEN 1 ELSE 0 END) AS pendingUser, user.InstiName ,insti.InstiPic FROM user LEFT JOIN insti ON user.InstiName COLLATE utf8mb4_unicode_ci = insti.InstiName COLLATE utf8mb4_unicode_ci WHERE user.InstiName IS NOT NULL GROUP BY user.InstiName";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							while ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
								$activeUser = $row1['activeUser'];
								$pendingUser = $row1['pendingUser'];
								$instiName = $row1['InstiName'];
								$InstiPic = $row1['InstiPic'];
						?>
								<div class="col-xxl-6 col-sm-6 col-12">
									<div class="card">
										<div class="card-body row justify-content-between">
											<div class="col-8 ps-4">
												<h4 class="mb-2 text-success"><?php echo $pendingUser; ?> / <?php echo $activeUser; ?></h4>
												<p class="mb-2">Registered Student OF <?php echo $instiName; ?></p>
											</div>
											<div class="col-4">
												<img class="float-end" src="../Dachbord/assets/img/site use/instiimge/<?php echo $InstiPic; ?>" width="100">
											</div>
										</div>
									</div>
								</div>
						<?php }
						} ?>
						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as stuCount FROM user WHERE RegCode IS NOT NULL and Status = 'active'";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Student Regisrered</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-people text-info fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['stuCount']; ?></h3>
												<!-- <p class="mb-2">Registered Student OF</p> -->
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Registered Student on the site </p>
										</div>
									</div>
								</div>
						<?php }
						} ?>

						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as stuCount FROM user WHERE RegCode IS NULL";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Not Regisrered</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-person-x text-danger fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['stuCount']; ?></h3>
												<!-- <p class="mb-2">Registered Student OF</p> -->
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Not Registered Student on the site </p>
										</div>
									</div>
								</div>
						<?php }
						} ?>

						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as instiCount FROM insti WHERE Status = 'active'";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Institutes</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-building text-info fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['instiCount']; ?></h3>
												<!-- <p class="mb-2">Registered Student OF</p> -->
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Created Institute</p>
										</div>
									</div>
								</div>
						<?php }
						} ?>

						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as classCount FROM class WHERE Status = 'active'";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Class</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-person-workspace text-info fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['classCount']; ?></h3>
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Created class count</p>
										</div>
									</div>
								</div>
						<?php }
						} ?>

						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as lesCount FROM lesson";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Lesson</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-person-video3 text-info fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['lesCount']; ?></h3>
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Created lessons count</p>
										</div>
									</div>
								</div>
						<?php }
						} ?>

						<?php if (true) { ?>
							<?php
							$sql = "SELECT COUNT(*) as adminCount FROM adminuser WHERE Status = 'active'";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$reusalt1 = $stmt->get_result();
							if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
							?>
								<div class="col-xxl-3 col-sm-6 col-12">
									<div class="card">
										<div class="card-header">
											<div class="card-title">Admin</div>
										</div>
										<div class="card-body row justify-content-between">
											<div class="col-8 item-center m-0">
												<!-- <img class="float-end" src="../Dachbord/assets/img/site use/instiimge/" width="100"> -->
												<!-- <div class="stats-tile item-center bg-da"> -->
												<!-- <div class="sale-icon-bdr "> -->
												<!-- <i class="bi bi-pie-chart"></i> -->
												<!-- </div> -->
												<!-- </div> -->
												<i class="bi bi-person-lines-fill text-info fs-1"></i>
											</div>
											<div class="col-4">
												<h3 class="text-success float-start"><?php echo $row1['adminCount']; ?></h3>
											</div>
										</div>
										<div>
											<p class="m-4 mt-0">Acmin count on site Registered</p>
										</div>
									</div>
								</div>
						<?php }
						} ?>
					</div>
					<!-- Row end -->

					<!-- model section start  -->
					<div class="modal fade" id="setcurrentclass" tabindex="-1" aria-labelledby="setcurrentclassLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content addcurrentclass">
								<div class="modal-header">
									<h5 class="modal-title" id="setcurrentclassLabel">Set current class</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-12 mb-3 currentClassData">
											<label class="form-label d-flex">Select current Class</label>
											<select id="className" name="className" class="form-select">
												<option> Select the current class</option>
											</select>
										</div>
										<div class="col-6 mb-3 currentClassData">
											<label class="form-label d-flex">Start time</label>
											<input type="time" class="form-control" name="startTime" placeholder="hrs:mins">
										</div>
										<div class="col-6 mb-3 currentClassData">
											<label class="form-label d-flex">End time</label>
											<input type="time" class="form-control" name="endTime" placeholder="hrs:mins">
										</div>
										<div class="col-6 mb-3 currentClassData">
											<label class="form-label d-flex">Youtube Link</label>
											<input type="text" class="form-control" name="endTime" placeholder="Youtube Link">
										</div>
										<div class="col-6 mb-3 currentClassData">
											<label class="form-label d-flex">Zoom Link</label>
											<input type="text" class="form-control" name="endTime" placeholder="Zoom Link">
										</div>
										<div class="col-12 mb-3 currentClassData">
											<label class="form-label d-flex">Title</label>
											<input type="text" class="form-control" name="endTime" placeholder="Enter Title">
										</div>
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-success" id="submitCurrentClass" onclick="submitCurrentClass()">Set a class</button>
									<!-- <button type="button" class="btn btn-success"><i class="bi bi-check2-all"></i></button> -->
								</div>

								<div class="my-3 rusaltLog mx-3">
									<div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
									<div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
								</div>
							</div>
						</div>
					</div>
					<!-- model section end  -->

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
	<script src="assets/js/validate.js"></script>
	<script>
		$(document).ready(function() {
			formData = "getClassList=";
			$.post("sql/process.php", formData, function(response, status) {
				$('#className').html(response);
			});
		});

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

</body>

</html>