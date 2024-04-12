<!-- database connection include -->
<?php include('sql/conn.php');
mysqli_set_charset($conn, "utf8mb4"); ?>

<?php include('sql/function.php'); ?>
<?php include_once('include/main.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'lesson'; ?>

<?php

// check isset class 
if (!isset($_SESSION['clz']) || !isset(explode("-", $_SESSION['clz'])[0]) || !isset(explode("-", $_SESSION['clz'])[1]) || !isset(explode("-", $_SESSION['clz'])[2])) {
	header('location:index.php');
	exit;
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
	<title>Surajskumara.lk | Lessons</title>
	<meta property="og:site_name" content="surajskumara.lk">
	<meta property="og:title" content="Suraj S Kumara" />
	<meta property="og:description" content="Suraj S Kumara - A/L ICT - Online" />
	<meta property="og:image" itemprop="image" content="https://surajskumara.lk/assets/images/suraj-imge-01.jpg">
	<meta property="og:type" content="website" />
	<meta name="author" content="Suraj S Kumara">
	<link rel="shortcut icon" href="../assets/images/ict.ico">


	<?php include('include/header.php'); ?>

	<!-- alert style -->
	<link rel="stylesheet" href="assets/css/alert.css">

	<!-- lesson style -->
	<link rel="stylesheet" href="assets/css/video_player.css">
	<link rel="stylesheet" href="assets/css/quiz.css">

	<!-- peaper upload style  -->
	<link rel="stylesheet" href="assets/css/peaperUpload.css">

	<!-- <link rel="stylesheet" href="assets/css/alertNthj.css"> -->

	<!-- google icon -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

	<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js"></script> -->


</head>

<style>
	.StylingText01 {
		/* height: 150px; */
		display: grid;
		place-content: center;
		background-color: black;
		min-height: 20vh;
		font-family: "Oswald", sans-serif;
		font-size: clamp(0.5rem, 0rem + 5vw, 1rem);
		font-size: auto;
		font-weight: 700;
		text-transform: uppercase;
		color: hsl(0, 0%, 100%);
	}

	.StylingText01 .subtitle {
		position: absolute;
		justify-self: center;
		align-self: center;
		margin-top: 20%;
		font-size: 15px;
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

					<!-- row start -->
					<div class="row" id="PageControles">

					</div>
					<!-- row end -->

					<!-- row start  -->
					<div class="row" id="PaymentStatus"></div>
					<!-- row end -->

					<!-- Row start -->
					<div class="row" id="mainCardContent">


						<?php if (false) { ?>
							<!-- quiz section start -->
							<div class="mainGroupOptions">
								<div class="card item-center m-1 p-2">

									<div class="quiz_content col-xxl-6 col-sm-10 col-md-8 col-12">
										<!-- start Quiz button -->
										<div class="start_btn"><button>Start Now</button></div>

										<!-- Info Box -->
										<div class="info_box w-100">
											<div class="info-title"><span>Some Rules of this Quiz</span></div>
											<div class="info-list">
												<div class="info">1. You will have only <span>15 seconds</span> per each question.</div>
												<div class="info">2. Once you select your answer, it can't be undone.</div>
												<div class="info">3. You can't select any option once time goes off.</div>
												<div class="info">4. You can't exit from the Quiz while you're playing.</div>
												<div class="info">5. You'll get points on the basis of your correct answers.</div>
											</div>
											<div class="buttons">
												<button class="quit">Exit Quiz</button>
												<button class="restart">Continue</button>
											</div>
										</div>

										<!-- Quiz Box -->
										<div class="quiz_box w-100">
											<header>
												<div class="title">Information and communication tecnollagy</div>
												<div class="timer">
													<div class="time_left_txt">Time Left</div>
													<div class="timer_sec">--:--</div>
												</div>
												<div class="time_line"></div>
											</header>
											<div class="section">
												<div class="que_text">
													<!-- Here I've inserted question from JavaScript -->
												</div>
												<div class="option_list">
													<!-- Here I've inserted options from JavaScript -->
												</div>
											</div>

											<!-- footer of Quiz Box -->
											<footer>
												<div class="total_que">
													<!-- Here I've inserted Question Count Number from JavaScript -->
												</div>
												<button class="next_btn">Next Que</button>
											</footer>
										</div>

										<!-- Result Box -->
										<div class="result_box w-100">
											<div class="icon">
												<i class="fas fa-crown"></i>
											</div>
											<div class="complete_text">You've completed the Quiz!</div>
											<div class="score_text">
												<!-- Here I've inserted Score Result from JavaScript -->
											</div>
											<div class="buttons">
												<button class="restart">Replay Quiz</button>
												<button class="quit">Quit Quiz</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- quiz section end -->
						<?php } ?>

					</div>
					<!-- Row end -->

					<!-- video player start -->
					<!-- <div class="row g-4" id="mainCardContent">
						
					</div> -->
					<!-- video player end -->


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

	<!-- model section start  -->

	<button id='uploadPdfAlert' type="button" class="btn btn-info d-none" data-bs-toggle="modal" data-bs-target="#uploadPdf"></button>

	<!-- upload pdf section  -->
	<div class="modal fade" id="uploadPdf" tabindex="-1" aria-labelledby="uploadPdfLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" id="uploadPdfAlertContent">
				<div class="modal-header">
					<h5 class="modal-title" id="uploadPdfLabel">Upload Peaper ( peaper Name )</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="Formclear">
						<div class="peaperUploader">
							<input type="file" id="file-input" accept="image/*,.pdf" multiple />
							<label for="file-input">
								<i class="fa-solid fa-arrow-up-from-bracket"></i>
								&nbsp; Choose Files To Upload
							</label>
							<div id="num-of-files">No Files Choosen</div>
							<ul id="files-list"></ul>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-bs-dismiss="modal" onclick="clearFormData()">Close</button>
					<button type="button" class="btn btn-success" id="fileUploadSubmit">Upload Files</button>
				</div>
				<div class="my-3 rusaltLog mx-3">
					<div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull Upload files</div>
					<div class="valid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed upload files</div>
					<div class="valid-feedback alert alert-info text-center alert-dismissible fade show py-2"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- model section end -->


	<!-- alert include -->
	<?php include('include/alert.php'); ?>
	<?php include('include/animated-special.php'); ?>
	<?php // include('include/alertNthj.php'); 
	?>


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

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>
	<script src="assets/js/prograss_snipper.js"></script>
	<!-- video player js -->
	<!-- <script src="assets/js/player.js"></script> -->

	<!-- alert js -->
	<script src="assets/js/alert.js"></script>
	<script src="assets/js/error.js"></script>
	<!-- <script src="assets/js/alertNthj.js"></script> -->

	<!-- quiz js -->
	<!-- <script src="assets/js/quiz/quiz.js"></script> -->
	<!-- <script src="assets/js/quiz/quction.js"></script> -->

	<?php

	?>

	<script>
		// let questions
		prograss_snipper();
		PageControles();
		PaymentStatus();
		mainCardContent();

		// function PayDetails(value) {
		// 	console.log(value);
		// }

		// $(document).ready(function() {
		// 	formData = "getGid=";
		// 	$.post("sql/process.php", formData, function(response, status) {
		// 		if (response == "undefind") {
		// 			mainCardContent();
		// 		} else {
		// 			changemainCardContent();
		// 		}
		// 	});
		// });

		// ####  main function start ####

		function stuWinInforBaer(dtaa = null) {
			// update 
		}

		function PageControles(data = null) {
			if (data == 'empty') {
				$('#PageControles').html('');
			} else {
				formData = "updatePageControles=";
				$.post("sql/process.php", formData, function(response, status) {
					$('#PageControles').html(response);
				});
			}
		}

		function PaymentStatus(data) {
			if (data == 'empty') {
				$('#PaymentStatus').html('');
			} else {
				formData = "updatePaymentStatus=" + data;
				$.post("sql/process.php", formData, function(response, status) {
					$('#PaymentStatus').html(response);
				});
			}
		}

		function mainCardContent(data = null) {
			if (data == 'empty') {
				$('#mainCardContent').html('');
			} else {
				formData = "updatemainCardContent=" + data;
				$.post("sql/process.php", formData, function(response, status) {
					$('#mainCardContent').html(response);
					prograss_snipper();
				});
			}
		}

		// ####  main function end ####

		// ####  sub function start ####
		function changePageControles(data) {
			formData = "changePageControles=" + "&data=" + data;
			$.post("sql/process.php", formData, function(response, status) {
				// console.log(response);
				$('#PageControles').html(response);
			});
		}

		function changePageControlesLec(data) {
			formData = "changePageControles=" + 'lesevent' + "&data=" + data;
			$.post("sql/process.php", formData, function(response, status) {
				// console.log(response);
				$('#PageControles').html(response);
			});
		}

		function changemainCardContent(type, data = null) { // respons click group 
			formData = "changemainCardContent=" + "&type=" + type + "&data=" + data;
			$.post("sql/process.php", formData, function(response, status) {
				// console.log(response);
				$('#mainCardContent').html(response);
				prograss_snipper();
			});
		}
		// ####  sub function end ####

		function changeContent(data) { // click page contrall 
			mainCardContent(data);
		}

		function mainCardAction(data, type) {
			if (type == 1) {
				PaymentStatus();
				changePageControles(data);
				changemainCardContent('clickGroup', data);
			} else if (type == 'month') {
				PaymentStatus(data);
				changePageControles(data);
				changemainCardContent('clickMonth', data);
			}
		}

		function backtoclass(data, type = null) {
			if (type == 2) {
				// console.log('datauojh');
				changePageControles(data);
				// PaymentStatus();
				changemainCardContent();
			} else {
				PageControles();
				PaymentStatus();
				mainCardContent();
			}
		}

		function payReload() {
			PaymentStatus();
			formData = "checkGid";
			$.post("sql/process.php", formData, function(response, status) {
				if (response == ' normal') {
					mainCardContent();
				} else if (response == ' lesson') {
					changemainCardContent('clickGroup');
				} else if (response == ' month') {
					changemainCardContent('clickMonth');
				}
			})
		}

		var videoId;
		var Lesid;

		function lesEvent(value, type) {
			PaymentStatus('empty');
			if (type == 'video') {
				changePageControlesLec(value);
				PassData = "LessonData=" + "&type=" + type;
				$.post("sql/process.php", PassData, function(response, status) {
					PassData = "lesRespons=" + "&value=" + value + "&type=" + type;
					$.post("sql/process.php", PassData, function(data, status) {
						if (response != " undefind") {
							data = data.replace(" ", "");
							videoId = data;
							Lesid = value;
							$('#mainCardContent').html(response);
							loadScript('assets/js/player.js');
							onYouTubeIframeAPIReady();
						}
					});
				});
			} else if (type == 'quiz') {
				changePageControlesLec(value);
				PassData = "LessonData=" + value + "&type=" + type;
				$.post("sql/process.php", PassData, function(response, status) {
					// console.log(response);
					// response == ' success' ? console.log('prepare quiz successfull') : console.log('failed prepare the quiz');
					PassData = "lesRespons=" + "&value=" + value + "&type=" + type;
					$.post("sql/process.php", PassData, function(data, status) {
						// console.log(data);
						if (response != "undefind") {
							data = data.replace(" ", "");
							// videoId = data;
							Lesid = value;
							$('#mainCardContent').html(response);
							loadScript('assets/js/quiz/quction.js');
							loadScript('assets/js/quiz/quiz.js');
						}
					});
				});
			} else if (type == 'upload') {
				// console.log('done');
				document.getElementById('uploadPdfAlert').click();
				localStorage.setItem('UploadFileData', value);
				loadScript('assets/js/fuleUploader.js');
			}
		}

		function markUnCompleate(data) {
			console.log('mardsifgh');
			formData = "markUnCompleate" + "&data=" + data;
			$.post("sql/process.php", formData, function(response, status) {
				console.log(response);
				changemainCardContent();
			});
		}


		function loadScript(url) {
			const script = document.createElement('script');
			script.src = url; // Set the script source
			document.head.appendChild(script); // Append the script to the head
		}
	</script>


	<script>
		window.onload = function() {
			// special animatioin 
			var specialAnimation = document.querySelectorAll('.special-animate');
			console.log('success');
			specialAnimation.forEach(function(self) {
				self.classList.add('snowflake');
			});

			url_data = window.location.search;
			if (url_data == '?success_login') {
				history.pushState({
					page: 'new-page'
				}, 'New Page', 'lesson.php');
				nthj(3);
			} else if (url_data == '?success_register') {
				history.pushState({
					page: 'new-page'
				}, 'New Page', 'lesson.php');
				nthj(4);
			}
		}
	</script>
</body>

</html>