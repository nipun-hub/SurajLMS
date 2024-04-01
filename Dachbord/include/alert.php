<!-- alert section Start -->
<section>
	<div class="popup-overlay"></div>
	<!-- register a class alert -->
	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<div class="row w-100 m-0">
			<h2 id="instiName" style="text-align: center">Register Class</h2>
			<div class="col-sm-4 col-12 w-100">
				<div class="card">
					<div class="card-body p-2">
						<label class="form-label">Register Code</label>
						<input id="instiid" type="text" class="form-control" placeholder="Enter institute id number">
						<div id="reg_invalid_id" class="invalid-feedback">Invalid Institute Id</div>
					</div>
					<div class="card-body p-2">
						<!-- <label class="form-label">Upload Nic photo</label> -->
						<div class=" pb-2">
							<label class="form-label" for="inputGroupFile02">Upload institute id photo</label>
							<input id="insti_imge" type="file" class="form-control" id="inputGroupFile02">
							<div id="reg_invalid_file" class="invalid-feedback">Please Select institute id photo</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- <h3>You have sucessfully downloaded all the source code files.</h3> -->
		<center><img class="loading" src="../assets/images/gif/loading01.gif" width="75" height="75" alt=""></center>
		<div class="buttons">
			<button class="btn btn-info" id="register"><span class="reg_text_01">Register</span></button>
		</div>
		<div class="model-error-indi alert alert-danger p-1 px-2 m-0 mx-4 mt-3 text-center ">
			<p class="w-100">Invalid Id , Plece Try again.</p>
		</div>
	</div>
	<!-- register END and login start-->
	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<!-- <center> -->
		<div class="row w-100 m-0">
			<h2 style="text-align: center;">Login ( Susipwan - gampaha )</h2>
			<div class="col-sm-4 col-12 w-100 m-0">
				<div class="card-body p-2">
					<input type="text" id="regcode_log" class="form-control mt-4" placeholder="Enter Registation Code or institute id" maxlength="10">
					<div id="log_invalid_feed" class="invalid-feedback">Invalid registation code or institute id</div>
				</div>
			</div>
		</div>
		<!-- </center> -->
		<center><img class="loading" src="../assets/images/gif/loading01.gif" width="75" height="75" alt=""></center>
		<div class="buttons">
			<button class="btn btn-info" id="login"><span class="reg_text_01">Login</span></button>
		</div>
		<div class="model-error-indi alert alert-danger p-1 px-2 m-0 mx-4 mt-3 text-center ">
			<p class="w-100">Invalid Id , Plece Try again.</p>
		</div>
	</div>

	<!-- login end and alert_custom start -->

	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<div class="row">
			<form id="Formclear">
				<center>
					<img class="alert_logo w-50" src="../assets/images/gif/loading01.gif" alt="">
				</center>
			</form>
			<div class="col-sm-4 col-12 w-100" style="text-align: center;">
				<div class="card-body p-2">
					<h2></h2>
					<span></span>
				</div>
			</div>
		</div>
		<center><img class="loading" src="" width="75" height="75" alt=""></center>
		<div class="buttons pt-0 mt-0">
			<button class="btn btn-info mt-3"><span class="log_text_01">Login</span></button>
		</div>
		<div class="model-error-indi alert alert-danger p-1 px-2 m-0 mx-4 mt-3 text-center ">
			<p class="w-100">Invalid Id , Plece Try again.</p>
		</div>
	</div>

	<!-- payment class section -->
	<div class="modal-box">
		<?php
		if(isset($_SESSION['clz'])){
		$insti = explode("-", $_SESSION['clz'])[0];
		$activeClass = explode("-", $_SESSION['clz'])[1];
		$sql = "SELECT * FROM class WHERE ClassId = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $activeClass);
		$stmt->execute();
		$reusalt = $stmt->get_result();
		if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
			if ($row['Type'] == 'physical') {
		?>
				<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
				<!-- <center> -->
				<div class="row w-100 m-0">
					<h2 style="text-align: center;"></h2>
					<div class="col-sm-4 col-12 w-100 m-0">
						<!-- <div class="card-body p-2 PaymentPhy">
							<input type="text" value="" id="" class="form-control mt-4" placeholder="Enter institute id number" maxlength="10">
							<div id="invalid_feed" class="invalid-feedback">Invalid institute id number</div>
						</div> -->
						<div class="card-body p-2 pt-0 PaymentPhy">
							<label class="form-check-label mt-4 text-dark" for="uploadslip">Upload The Institute Id Photo</label>
							<input type="file" value="" id="uploadslip" class="form-control mt-1" placeholder="Upload institute id photo" accept="image/*">
							<div id="invalid_feed" class="invalid-feedback">Plece upload the Institute Id Photo</div>
						</div>
					</div>
				</div>
				<!-- </center> -->

				<center><img class="loading" src="../assets/images/gif/loading01.gif" width="75" height="75" alt=""></center>
				<div class="buttons">
					<button class="btn btn-info" id="login"><span class="reg_text_01">Payment</span></button>
					<button hidden></button>
				</div>
				<div class="model-error-indi alert alert-danger p-1 px-2 m-0 mx-4 mt-3 text-center">
					<p class="w-100">Invalid Id , Plece Try again.</p>
				</div>
			<?php } elseif ($row['Type'] == 'online') {  ?>
				<form id="Formclear">
					<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
					<!-- <center> -->
					<div class="row w-100 m-0">
						<h2 style="text-align: center;"></h2>
						<p class="text-success price"></p>
						<div class="col-12  m-0">
							<div class="card-body p-2 item-center PaymentOnl">
								<label class="form-check-label" for="inlineRadio1">Bank Deposite</label>&nbsp;
								<input class="form-check-input" type="radio" name="paymethod" id="inlineRadio1" value="bds">
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<input class="form-check-input" type="radio" name="paymethod" id="inlineRadio2" value="cod" checked>&nbsp;
								<label class="form-check-label" for="inlineRadio2">Cash On Delivary</label>
								<!-- <input value="" class="form-check-input" type="radio" name="paymethod" id="inlineRadio3" checked hidden>&nbsp; -->
							</div>
							<div id="invalid_feed" class="invalid-feedback text-center">Select Payment Type.</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 pt-0 ">
								<label for="slip" class="form-label">Upload Slip</label>
								<input type="file" id="slip" name="slip" class="form-control PaymentOnlslip" placeholder="Upload institute id photo" accept="image/*">
							</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="number" name="numwha" class="form-control mt-4" placeholder="Enter Whatsapp Number">
							</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="number" name="num01" class="form-control mt-2" placeholder="Enter contact number-1">
							</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="number" name="num02" class="form-control mt-2" placeholder="Enter contact number-2">
							</div>
						</div>
						<div class="col-12  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="text" name="address" class="form-control mt-2" placeholder="Enter tyte delivary address">
							</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="text" name="distric" class="form-control mt-2" placeholder="Enter distric">
							</div>
						</div>
						<div class="col-6  m-0">
							<div class="card-body p-2 PaymentOnl">
								<input type="text" name="city" class="form-control mt-2" placeholder="Enter city">
							</div>
						</div>
						<input type="hidden" value="" class="PaymentOnlhidden">
						<div class="col-12  m-0">
							<div class="card-body p-2 PaymentOnl">
								<textarea class="form-control mt-2" name="dict" placeholder="Comment"></textarea>
							</div>
						</div>
					</div>
					<!-- </center> -->

					<center><img class="loading" src="../assets/images/gif/loading01.gif" width="75" height="75" alt=""></center>
					<div class="buttons">
						<button class="btn btn-info" id="PayDetails"><span class="reg_text_01">Payment Details</span></button>
						<button class="btn btn-info" id="payonl"><span class="reg_text_01">Payment</span></button>
					</div>
					<div class="model-error-indi alert alert-danger p-1 px-2 m-0 mx-4 mt-3 text-center ">
						<p class="w-100">Invalid Id , Plece Try again.</p>
					</div>
				</form>
		<?php }
		}}
		$stmt->close(); ?>
	</div>

	<!-- alert_custom END -->
	</div>
</section>