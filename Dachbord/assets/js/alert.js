const section = document.querySelector("section"),
    popup_overlay = document.querySelector(".popup-overlay"),
    blur_body = document.querySelector(".page-wrapper"),
    alert = document.querySelectorAll("section .modal-box"),
    doneBtn = document.querySelector(".done_btn"),
    closeBtn = document.querySelectorAll(".close-x"),
    Loading_img = document.querySelectorAll(".modal-box .loading"),
    errorbox = document.querySelectorAll('.model-error-indi'),
    logoImg = alert[2].querySelector('.alert_logo'),
    title = document.querySelector('.modal-box .card-body h2'),
    stitle = document.querySelector('.modal-box .card-body span'),
    btn = document.querySelectorAll('.modal-box .buttons button'),
    btn_span = document.querySelector('.modal-box .buttons span');
var inputfeelds;
var inputfeelds2;


function getpayphyinputs() {
    inputfeelds = document.querySelectorAll(".PaymentPhy input");
}

function getpayOnlInputs() {
    inputfeelds = document.querySelectorAll(".PaymentOnl input[type=text] , .PaymentOnl input[type=number] , .PaymentOnl input[type=radio]:checked , .PaymentOnl textarea");
    inputfeelds2 = document.querySelectorAll(".PaymentOnl input[type=radio]");
}

function nthj(type, val = null) {
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
        alert[0].querySelector('h2').innerHTML = "Register Institute" + "<br>(" + val + ")";
        var lablecontent = val == 'Online' ? "Register Code" : "Institute Id Numner";
        var UserRegCode = alert[0].querySelector('#hiddenRegCode').value;
        var enterRegCodeVal = val == 'Online' ? UserRegCode : null;
        document.getElementById('instiid').disabled = val == "Online" ? true : false;
        alert[0].querySelector('#instiid').value = enterRegCodeVal;
        alert[0].querySelectorAll('.card-body label')[0].innerHTML = lablecontent;
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
            clearInterval(intervalID);
            close_alert();
        });
        let intervalID = setInterval(() => {
            clearInterval(intervalID);
            close_alert();
        }, 10000);
        show_alert();
    } else if (type == 4) { // success register
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[2].style.display = "block";
        logoImg.src = "../assets/images/gif/success.gif";
        val == 'payonl' ? title.innerHTML = "Successfull Payment" : (val == "payphy" ? title.innerHTML = "Successfully activation" : title.innerHTML = "Successfull Register");
        // if (val == 'payonl') {
        //     title.innerHTML = "Successfull Payment";
        // } else if (val == 'payphy') {
        //     title.innerHTML = "Successfully activation";
        // } else {
        //     title.innerHTML = "Successfull Register";
        // }
        stitle.innerHTML = "Please wait the until approved!";
        btn[2].innerHTML = "Continue";
        btn[2].style.display = "block";
        btn[2].addEventListener("click", () => {
            clearInterval(intervalID);
            close_alert()
        });
        let intervalID = setInterval(() => {
            clearInterval(intervalID);
            close_alert();
        }, 10000);
        show_alert();
    } else if (type == 5 && val != null && val != undefined) { // payment alert
        // console.log(val);
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[3].style.display = "block";
        clearFormData();
        loading(false, 3);
        clearFormData();
        formData = "getactiveClass=" + "&month=" + val;
        var price;
        $.post("sql/process.php", formData, function (response, status) {
            if (response.type == "online") {
                price = response.price;
                mainTitle = "Payment";
                alert[3].querySelector('.price').innerHTML = `Price : Rs ${response.price}.00`;
                alert[3].querySelector('.month').innerHTML = `Payment for ${response.month}`;
            } else if (response.type == "physical") {
                mainTitle = "Active";
                alert[3].querySelector('.month').innerHTML = `Payment for ${response.month}`;
            }
            alert[3].querySelector('h2').innerHTML = `${mainTitle}<br>${response.classHeader}`;
        });
        btn[3].addEventListener("click", (e) => {
            event.preventDefault();
            if (e.target.id == 'PayDetails') {
                console.log('payment datails');
            } else {
                clearFormData();
                loading(false, 3);
                Pay(1, val, price);
            }
        });
        btn[4].addEventListener("click", () => {
            event.preventDefault();
            // clearFormData();
            // loading(false, 3);
            Pay(2, val, price);
        });
        show_alert();
    } else if (type == 6) { // error alert
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[2].style.display = "block";
        logoImg.src = "../assets/images/gif/error.gif";
        title.innerHTML = "Something Wrong ";
        stitle.innerHTML = "Please try again";
        btn[2].innerHTML = "Continue";
        btn[2].style.display = "block";
        btn[2].addEventListener("click", () => {
            clearInterval(intervalID);
            close_alert();
        });
        let intervalID = setInterval(() => {
            close_alert();
            clearInterval(intervalID);
        }, 10000);
        show_alert();
    } else if (type == 7) { // error alert
        alert.forEach((self) => {
            self.style.display = "none";
        });
        alert[2].style.display = "block";
        logoImg.src = "../assets/images/gif/success.gif";
        title.innerHTML = "Successfully activation";
        stitle.innerHTML = "Please wait the until approved!";
        btn[2].innerHTML = "Continue";
        btn[2].style.display = "block";
        btn[2].addEventListener("click", () => {
            clearInterval(intervalID);
            close_alert();
        });
        let intervalID = setInterval(() => {
            close_alert();
            clearInterval(intervalID);
        }, 10000);
        show_alert();
    }
}

function loading(val, cou) {
    if (val) {
        Loading_img[cou].style.display = "block";
        btn[cou].style.display = "none";
    } else {
        // Loading_img[cou].style.display = "none";
        btn[cou].style.display = "block";
    }
}

function show_alert() {
    blur_body.classList.add("active");
    section.classList.add("active");
}

function close_alert() {
    // loading(false, 3);
    section.classList.remove("active");
    blur_body.classList.remove("active");
}

// function login(insti) {
//    console.log(undefined);
// }

function register(institi) {
    $(document).ready(function () {
        let instiId = $("#instiid").val();
        let isetimage = $("#insti_imge").val();
        let formData = new FormData();
        formData.append("file", insti_imge.files[0]);
        formData.append("insti_register", "");
        formData.append("instiName", institi);
        formData.append("instiId", instiId);

        if (instiId.length < 4) {
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

        if (isetimage.length > 0 && !(instiId.length < 4)) {
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

// paymrnt 

function Pay(type, val = null, val2 = null) {
    if (type == 1) {  // physical payment
        event.preventDefault();
        getpayphyinputs();
        var image = inputfeelds[0].files[0];
        if (image == undefined) {
            document.querySelectorAll('.PaymentPhy')[0].querySelector('.invalid-feedback').style.display = "block";
        } else {
            document.querySelectorAll('.PaymentPhy')[0].querySelector('.invalid-feedback').style.display = "none";
        }
        if (image != undefined) {
            loading(true, 3);
            var PassData = new FormData();
            PassData.append("PaymetPhy", "");
            PassData.append("image", image);
            PassData.append("month", val);
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (data, status) {
                    response = errorHadel(data);
                    console.log(data);
                    if (response == 'Successfull') {
                        PaymentStatus();
                        // update html content
                        formData = "getGid=";
                        $.post("sql/process.php", formData, function (response, status) {
                            var gid = response.replace(" ", '');
                            gid == 'undefind' ? mainCardContent() : changemainCardContent('clickGroup');
                        });
                        close_alert();
                        nthj(4, 'payphy');
                    } else {
                        // console.log(data);
                        loading(false, 3);
                        errorbox[3].querySelector('p').innerHTML = "Invalid  institute Id , Plece Try again.";
                        errorbox[3].style.display = "block";
                        nthj(6);
                    }
                }
            });
        }
    } else if (type == 2) { // online payment
        event.preventDefault();
        getpayOnlInputs();
        var image = document.querySelector(".PaymentOnlslip");
        var imageData = image.files[0];
        var price = document.querySelector('.PaymentOnlhidden').value;
        if (validonldata()) {
            console.log("donesfsf");
            var PassData = new FormData;
            PassData.append("PaymetOnl", "");
            PassData.append("payMonth", val)
            PassData.append("price", val2);
            console.log(val2);
            inputfeelds.forEach((self) => {
                PassData.append(self.name, self.value);
            });
            PassData.append("slip", imageData);
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    response = errorHadel(response);
                    console.log(response);
                    if (response == 'Successfull') {
                        PaymentStatus();
                        // update html content
                        formData = "getGid=";
                        $.post("sql/process.php", formData, function (response, status) {
                            var gid = response.replace(" ", '');
                            gid == 'undefind' ? mainCardContent() : changemainCardContent('clickMonth');
                        });
                        close_alert();
                        nthj(4, 'payonl');
                    } else {
                        // console.log(data);
                        loading(false, 3);
                        errorbox[3].querySelector('p').innerHTML = "Invalid  institute Id , Plece Try again.";
                        errorbox[3].style.display = "block";
                        nthj(6);
                    }
                }
            });
        }


    }

    function validonldata() {
        // debugger;
        var valid = true;
        if (imageData == undefined) {
            image.classList.toggle("is-invalid", true);
            valid = false;
        } else {
            image.classList.toggle("is-invalid", false);
        }
        if (inputfeelds2[0].checked || inputfeelds2[1].checked) {
            alert[3].querySelector('.invalid-feedback').style.display = "none";
        } else {
            alert[3].querySelector('.invalid-feedback').style.display = "block";
            valid = false;
        }
        for (var count = 0; count < inputfeelds.length - 1; count++) {
            if (inputfeelds[count].value.length > 0) {
                inputfeelds[count].classList.toggle("is-invalid", false);
                if (inputfeelds[1].value.length < 10 || inputfeelds[1].value.length > 12) {
                    inputfeelds[1].classList.toggle("is-invalid", true);
                    valid = false;
                } if (inputfeelds[2].value.length < 10 || inputfeelds[2].value.length > 12) {
                    inputfeelds[2].classList.toggle("is-invalid", true);
                    valid = false;
                } if (inputfeelds[3].value.length < 10 || inputfeelds[3].value.length > 12) {
                    inputfeelds[3].classList.toggle("is-invalid", true);
                    valid = false;
                }

            } else {
                inputfeelds[count].classList.toggle("is-invalid", true);
                valid = false;
            }
        }
        return valid;

    }
}

// payment details
// function PayDetails(value) {
//     event.preventDefault();
//     console.log(1);
// }

// model clear event catch close form 
// $(document).ready(function () {
//     $('#yourModal').on('hidden.bs.modal', function (event) {
//         console.log('Modal has been hidden!');
//     });
// });  


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