const section = document.querySelector("section"),
			popup_overlay = document.querySelector(".popup-overlay"),
			blur_body = document.querySelector(".page-wrapper"),
			image = document.querySelector('.modal-box img'),
			title = document.querySelector('.modal-box h2'),
			stitle = document.querySelector('.modal-box .card-body span'),
			btn = document.querySelector('.modal-box .buttons button'),
			btn_span = document.querySelector('.modal-box .buttons span'),
			closex = document.querySelector(".close-x");

		closex.addEventListener("click", () => {
			close();
		});

		function show_pop() {
			blur_body.classList.add("active");
			section.classList.add("active");
		}

		function close() {
			section.classList.remove("active");
			blur_body.classList.remove("active")
		}

		function wait() {
			image.src = "../assets/images/gif/loading01.gif";
			closex.style.display = "none";
			title.innerHTML = "Please Wait ...";
			stitle.innerHTML = "";
			btn.style.display = "none";
			show_pop();
		}

		function success(code) {
			image.src = "../assets/images/gif/success.gif";
			closex.style.display = "block";
			title.innerHTML = "Sucessfully Registerd";
			stitle.innerHTML = `Register No : ${code}`;
			btn.style.display = "block";
			btn_span.innerHTML = "Login";
			closex.style.display = "none";
			btn.addEventListener("click", () => {
				window.location.href = "?login";
			});
			show_pop();
		}

		function error_alert() {
			image.src = "../assets/images/gif/error.gif";
			closex.style.display = "block";
			title.innerHTML = "Something Went Wrong!";
			stitle.innerHTML = "Please try again";
			btn.style.display = "none";
			show_pop();
		}

		function error_log() {
			image.src = "../assets/images/gif/error.gif";
			closex.style.display = "block";
			title.innerHTML = "Something went wrong!";
			stitle.innerHTML = "Please try again";
			btn.style.display = "none";
			show_pop();
		}
		
		function search(){
			image.src = "../assets/images/gif/search.gif";
			closex.style.display = "block";
			title.innerHTML = "Account Not Found!";
			stitle.innerHTML = "Please register as a new student";
			btn.innerHTML = "Register now";
			btn.style.display = "block";
			btn.addEventListener("click", () => {
				window.location.href = "?register";
			});
			closex.addEventListener("click", () => {
				localStorage.clear();
			});
			show_pop();
		}