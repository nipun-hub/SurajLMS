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
	</div>

	<!-- login end and alert_custom start -->

	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<div class="row">
			<center>
				<img class="alert_logo w-50" src="../assets/images/gif/loading01.gif" alt="">
			</center>
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
	</div>
	<!-- alert_custom END -->

	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<!-- <center> -->
		<div class="row w-100 m-0">
			<h2 style="text-align: center;">title</h2>
			<div class="col-sm-4 col-12 w-100 m-0">
				<div class="card-body p-2">
					<input type="text" id="regcode_log" class="form-control mt-4" placeholder="Enter Recent to Ignored">
					<!-- <div id="log_invalid_feed" class="invalid-feedback">Invalid registation code or institute id</div> -->
				</div>
			</div>
		</div>
		<!-- </center> -->
		<center><img class="loading" src="../assets/images/gif/loading01.gif" width="75" height="75" alt=""></center>
		<div class="buttons">
			<button class="btn btn-info" id="login" onclick="close_alert()"><span class="">Close</span></button>
			<button id="ignoedbtn" class="btn btn-info"><span class="">Ignored</span></button>
		</div>
	</div>

	<div class="modal-box">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<div class="row">
			<center>
				<img class="alert_logo w-50" src="../assets/images/gif/loading01.gif" alt="">
			</center>
			<div class="col-sm-4 col-12 w-100" style="text-align: center;">
				<div class="card-body p-2">
					<h2></h2>
					<span></span>
				</div>
			</div>
		</div>
	</div>

	<div class="modal-box modal-box-max">
		<i onclick="close_alert()" class="fa-solid fa-xmark close-x"></i>
		<div class="row">
			<div class="col-sm-12 col-12 add-lesson">

				<!-- Card start -->
				<div class="card">
					<div class="card-header">
						<div class="card-title">Update Lessons</div>
					</div>
					<div class="card-body">
						<form id="addlesson">

							<!-- Row start -->
							<div class="row">
								<div class="col-xl-4 col-sm-6 col-12">
									<div class="mb-3 AddLesSub">
										<label for="inputName" class="form-label">Lesson Name *</label>
										<input name="lesname" type="text" class="form-control" id="inputName" placeholder="Enter Lesson Name">
									</div>
								</div>
								<div class="col-xl-4 col-sm-6 col-12">
									<div class="mb-3 AddLesSub">
										<label for="inputIndustryType" class="form-label">Select Lesson Type *</label>
										<select name="lestype" class="form-select" id="inputIndustryType">
											<option value="">Select Type</option>
											<option value="video">Video</option>
											<option value="note">Note</option>
										</select>
									</div>
								</div>
								<div class="col-xl-4 col-sm-6 col-12">
									<div class="mb-3 AddLesSub">
										<label for="inputEmail" class="form-label">Lesson Link *</label>
										<input name="leslink" type="text" class="form-control" id="inputEmail" placeholder="Enter Lesson Link">
									</div>
								</div>
								<div class="col-12">
									<div class="mb-3 AddLesSub">
										<label for="inputMessage" class="form-label">Desctiption ( optional )</label>
										<textarea name="lesdict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
									</div>
								</div>
							</div>
							<!-- Row end -->

							<!-- Form actions footer start -->
							<div class="form-actions-footer">
								<button id="cansal" class="btn btn-light">Cancel</button>
								<button class="btn btn-success" onclick="submitAddLesson()">Submit</button>
							</div>
							<!-- Form actions footer end -->

						</form>

					</div>
				</div>
				<!-- Card end -->

			</div>
		</div>
	</div>

	</div>
</section>