const section = document.querySelector("section"),
    popup_overlay = document.querySelector(".popup-overlay"),
    blur_body = document.querySelector(".page-wrapper"),
    alert = document.querySelectorAll("section .modal-box"),
    doneBtn = document.querySelector(".done_btn"),
    closeBtn = document.querySelector(".close-x"),
    Loading_img = document.querySelectorAll(".modal-box .loading"),
    logoImg = alert[2].querySelector('.alert_logo'),
    title = document.querySelector('.modal-box .card-body h2'),
    stitle = document.querySelector('.modal-box .card-body span'),
    btn = document.querySelectorAll('.modal-box .buttons button'),
    btn_span = document.querySelector('.modal-box .buttons span');

function nthj(type, val = 'undefind', val2 = 'undefind') {
    if (type == 'login') {  // login
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[1].style.display = "block";
        btn[1].addEventListener("click", () => {
            login(val);
        });
        show_alert();
    } else if (type == 'register') { // register
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[0].style.display = "block";
        btn[0].addEventListener("click", () => {
            register(val);
        });
        show_alert();
    } else if (type == 3) { // success alert
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[2].style.display = "block";
        logoImg.src = "../assets/images/gif/success.gif";
        title.innerHTML = "Successfull login";
        btn[2].innerHTML = "Continue";
        btn[2].style.display = "block";
        btn[2].addEventListener("click", () => {
            close_alert()
        });
        let intervalID = setInterval(() => {
            close_alert();
            clearInterval(intervalID);
        }, 10000);
        show_alert();
    } else if (type == 4) { // success register
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[2].style.display = "block";
        logoImg.src = "../assets/images/gif/success.gif";
        title.innerHTML = "Successfull Register.";
        btn[2].innerHTML = "Continue";
        btn[2].style.display = "block";
        btn[2].addEventListener("click", () => {
            close_alert()
        });
        let intervalID = setInterval(() => {
            close_alert();
            clearInterval(intervalID);
        }, 10000);
        show_alert();
    } else if (type == 5) { // payment  ignoed
        emptyinput();
            console.log(1);
            $("#ignoedbtn").on("click", function () {
            console.log(2);
        });
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[3].style.display = "block";
        show_alert(1);
    } else if (type == 6) { // image preview
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[4].style.display = "block";
        alert[4].querySelector('img').src = val;
        show_alert();
    }else if (type == 7) { // image preview
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[5].style.display = "block";
        show_alert();
        loadlessonData(val);
    }
}

function emptyinput() {
    var emptyInput = document.querySelectorAll('.modal-box input');
    emptyInput.forEach((self) => {
        self.value = "";
    });
}

function show_alert(val) {
    if (val == 0) {
        section.classList.add("active");
    } else {
        blur_body.classList.add("active");
        section.classList.add("active");
    }
}

function close_alert() {
    section.classList.remove("active");
    blur_body.classList.remove("active")
}

function login(insti) {
    $(document).ready(function () {
        let regcode = $("#regcode_log").val();
        if (regcode.length < 4) {
            document.querySelector('#log_invalid_feed').style.display = "block";
            $("#regcode_log").addClass("is-invalid");
        } else {
            btn[1].style.display = "none";
            Loading_img[1].style.display = "block";
            formData = "insti_login" + "&instiName=" + insti + "&InstiId=" + regcode;
            $.post("sql/process.php", formData, function (response, status) {
                if (response == " success") {
                    window.location.href = "lesson.php?success_login";
                } else {
                    Loading_img[1].style.display = "none";
                    btn[1].style.display = "block";
                    document.querySelector('#log_invalid_feed').style.display = "block";
                    $("#regcode_log").addClass("is-invalid");
                }
            });
        }
    });
}

function register(institi) {
    $(document).ready(function () {
        let regcode = $("#instiid").val();
        let isetimage = $("#insti_imge").val();
        let formData = new FormData();
        formData.append("file", insti_imge.files[0]);
        formData.append("insti_register", "");
        formData.append("instiName", institi);
        formData.append("regcode", regcode);

        if (regcode.length < 4) {
            document.querySelector('#reg_invalid_id').style.display = "block";
            $("#instiid").addClass("is-invalid");
        } else {
            document.querySelector('#reg_invalid_id').style.display = "none";
            $("#instiid").removeClass("is-invalid");
        }
        if (!(isetimage.length > 0)) {
            document.querySelector('#reg_invalid_file').style.display = "block";
            $("#insti_imge").addClass("is-invalid");
        } else {
            document.querySelector('#reg_invalid_file').style.display = "none";
            $("#insti_imge").removeClass("is-invalid");
        }
        console.log();

        if (isetimage.length > 0 && !(regcode.length < 4)) {
            btn[0].style.display = "none"
            Loading_img[0].style.display = "block";
            // $.post("sql/process.php", formData, function (data, status) {
            $.ajax({
                url: "sql/process.php",
                type: "POST",
                data: formData,
                processData: false, // Prevent jQuery from processing FormData
                contentType: false, // Set content type to multipart/form-data
                success: function (data, status) {
                    if (data == " success") {
                        window.location.href = "?success_register";
                    } else {
                        btn[0].style.display = "block";
                        Loading_img[0].style.display = "none";
                        document.querySelector('#reg_invalid_id').innerHTML = errorHadel(data) + " please try again";
                        document.querySelector('#reg_invalid_id').style.display = "block";
                        $("#instiid").addClass("is-invalid");
                    }
                }
            });
        }

    });
}


// nitification Ignored sestion end

// update loesson sction start

// load old lesson data start
function loadlessonData(id){

}
// load old lesson data end

// update loesson sction end

// clear forl empty
function clearFormData() {
    const forms = document.querySelectorAll('#Formclear');
    forms.forEach((form) => {
        const elements = form.elements;

        for (let i = 0; i < elements.length; i++) {
            const element = elements[i];

            switch (element.type) {
                case 'hidden':
                    break;
                case 'text':
                case 'number':
                case 'file':
                case 'password':
                case 'email':
                case 'textarea':
                case 'select':
                    element.value = '';
                    break;
                case 'checkbox':
                case 'radio':
                    element.checked = false;
                    break;
                // Add more cases for other input types if needed
            }
        }
    });
}
