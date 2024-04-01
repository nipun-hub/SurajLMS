const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
// const loadingBtns = document.querySelector(".btn-loding");
const submitBtns = document.querySelector(".submit-btn");
const loding_img = document.querySelector('.form img');
const element = document.querySelector(".form");
const register_form = document.querySelector(".register_form");
// const multi = document.getElementById("multi");
// const formSteps = document.querySelectorAll(".form-step");
// const multiSteps = document.querySelectorAll(".multi-step");

const login_sec = document.querySelector('.login');
const register_sec = document.querySelector('.register');

// input value get
const uname = document.querySelector('.uname input');
const upass = document.querySelector('.upass input');

const reguname = document.querySelector('.reguname input');
const mobNum = document.querySelector('.mobnum input');
const email = document.querySelector('.email input');
const pass = document.querySelector('.pass input');
const repass = document.querySelector('.repass input');
// const nic = document.querySelector('.nic input');
// const nicpic = document.querySelector('.nicpic input');
// const mobnum = document.querySelector('.mobnum input');
// const whatnum = document.querySelector('.whanum input');
// const dob = document.querySelector('.dob input');
// const school = document.querySelector('.school input');
// const year = document.querySelector('.year select');
// const streem = document.querySelector('.streem select');
// const shy = document.querySelector('.shy select');
// const medium = document.querySelector('.medium select');
// const address = document.querySelector('.address input');
// const dictric = document.querySelector('.dictric select');
// const city = document.querySelector('.city select');
// const guaname = document.querySelector('.guaname input');
// const gunnum = document.querySelector('.gunnum input');

let formStepsNum = 0;

if (element.classList.contains("login")) {
  submitBtns.addEventListener("click", () => {
    event.preventDefault();
    UserName_val();
    UserPass_val();
    if (UserName_val() && UserPass_val()) {
      submitBtns.style.display = 'none';
      loding_img.style.display = 'block';
      Login();
    }
  });
} else if (element.classList.contains("register")) {

  // nextBtns.forEach((btn) => {
  //   btn.addEventListener("click", () => {
  //     event.preventDefault();
  //     if (formStepsNum == 0 && step_1()) {
  //       formStepsNum++;
  //       updateFormSteps();
  //       updatemultibar();
  //     } else if (formStepsNum == 1 && step_2()) {
  //       formStepsNum++;
  //       updateFormSteps();
  //       updatemultibar();
  //     } else if (formStepsNum == 2 && step_3()) {
  //       formStepsNum++;
  //       updateFormSteps();
  //       updatemultibar();
  //     } else if (formStepsNum == 3 && step_4()) {
  //       formStepsNum++;
  //       updateFormSteps();
  //       updatemultibar();
  //     } else if (formStepsNum == 4 && step_5()) {
  //       formStepsNum++;
  //       updateFormSteps();
  //       updatemultibar();
  //     }
  //   });
  // });

  // prevBtns.forEach((btn) => {
  //   btn.addEventListener("click", () => {
  //     event.preventDefault();
  //     formStepsNum--;
  //     updateFormSteps();
  //     updatemultibar();
  //   });
  // });

  // submitBtns.forEach((btn) => {
  submitBtns.addEventListener("click", () => {
    event.preventDefault();
    if (step_1()) {
      submitBtns.style.display = 'none';
      loding_img.style.display = 'block';
      // prevBtns.style.display = 'none';
      // prevBtns.forEach((btn) => {
      //   btn.style.display = "none";
      // });
      Register();
    }
  });
}

// function section start

// multi form function start

function updateFormSteps() {
  formSteps.forEach((formStep) => {
    formStep.classList.contains("form-step-active") &&
      formStep.classList.remove("form-step-active");
  });

  formSteps[formStepsNum].classList.add("form-step-active");
}

function updatemultibar() {
  multiSteps.forEach((multiStep, idx) => {
    if (idx < formStepsNum + 1) {
      multiStep.classList.add("multi-step-active");
    } else {
      multiStep.classList.remove("multi-step-active");
    }
  });

  const multiActive = document.querySelectorAll(".multi-step-active");

  multi.style.width =
    ((multiActive.length - 1) / (multiSteps.length - 1)) * 100 + "%";

}

function sineup() {
  login_sec.style.display = 'none';
  register_sec.style.display = 'block';
}


function sinein() {
  login_sec.style.display = 'block';
  register_sec.style.display = 'none';
}

// multi form function end

// form validation function start
function TextValidation(text, type = 'null') {
  if (type == "text") {
    const TextRegex = /^[a-zA-Z\s]+$/;
    return TextRegex.test(text);
  } else if (type == 'nic') {
    return text.length == 12;
  } else if (type == 'tel') {
    return 8 < text.length && text.length < 13;
  } else if (type == 'select') {
    return !text == "";
  } else if (type == 'pass') {
    if (text.length > 5 && text.length < 11) { return true; } else { return false; }
  } else {
    return !!text;
  }
}
function validateEmail(email) {
  const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-]+.)+[a-zA-Z]{2,}))$/;
  return emailRegex.test(email);
}
function PassMatch(pass, repass) {
  return pass == repass;
}
function FileValidate(file) {
  if (file && validateDocumentType(file)) {
    // console.log('File is valid');
    return true;
  } else {
    return false
  }
}
function validateDocumentType(file) {
  var validTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
  return validTypes.includes(file.type);
}

function UserName_val() {
  if (TextValidation(uname.value)) {
    uname.classList.toggle("is-valid", true);
    uname.classList.toggle("is-invalid", false);
    return true;
  } else {
    uname.classList.toggle("is-valid", false);
    uname.classList.toggle("is-invalid", true);
    return false;
  }
}

function UserPass_val() {
  if (TextValidation(upass.value, "pass")) {
    upass.classList.toggle("is-valid", true);
    upass.classList.toggle("is-invalid", false);
    return true;
  } else {
    upass.classList.toggle("is-valid", false);
    upass.classList.toggle("is-invalid", true);
    return false;
  }
}

// validate function start
function fname_val() {
  if (TextValidation(reguname.value, 'text')) {
    reguname.classList.toggle("is-valid", true);
    reguname.classList.toggle("is-invalid", false);
    return true;
  } else {
    reguname.classList.toggle("is-valid", false);
    reguname.classList.toggle("is-invalid", true);
    return false;
  }
}


function email_val() {
  if (TextValidation(email.value)) {
    if (validateEmail(email.value)) {
      email.classList.toggle("is-valid", true);
      email.classList.toggle("is-invalid", false);
      return true;
    } else {
      email.classList.toggle("is-valid", false);
      email.classList.toggle("is-invalid", true);
      return false;
    }
  } else {
    email.classList.toggle("is-valid", false);
    email.classList.toggle("is-invalid", true);
    return false;
  }
}

function pass_val() {
  if (TextValidation(pass.value, "pass") && TextValidation(repass.value, "pass") && PassMatch(pass.value, repass.value)) {
    pass.classList.toggle("is-valid", true);
    pass.classList.toggle("is-invalid", false);
    repass.classList.toggle("is-valid", true);
    repass.classList.toggle("is-invalid", false);
    return true;
  } else {
    pass.classList.toggle("is-valid", false);
    pass.classList.toggle("is-invalid", true);
    repass.classList.toggle("is-valid", false);
    repass.classList.toggle("is-invalid", true);
    return false;
  }
}

function Mn_val() {
  if (TextValidation(mobNum.value, 'tel')) {
    mobNum.classList.toggle("is-valid", true);
    mobNum.classList.toggle("is-invalid", false);
    return true;
  } else {
    mobNum.classList.toggle("is-valid", false);
    mobNum.classList.toggle("is-invalid", true);
    return false;
  }
}


// validate function end

function step_1() {
  fname_val();
  Mn_val();
  email_val();
  pass_val();
  // return true;
  return fname_val() && Mn_val() && email_val() && pass_val();
}

// function step_2() {
//   Nic_val();
//   NicPic_val();
//   Mn_val();
//   Wn_val();
//   Dob_val();
//   return true;
//   // return Nic_val() && NicPic_val() && Mn_val() && Wn_val() && Dob_val();
// }

// function step_3() {
//   School_val();
//   year_val();
//   streem_val();
//   shy_val();
//   medium_val();
//   return true;
//   // return School_val() && year_val() && streem_val() && shy_val() && medium_val();
// }

// function step_4() {
//   address_val();
//   Dictric_val();
//   city_val();
//   return true;
//   // return address_val() && Dictric_val() && city_val();
// }

// function step_5() {
//   gunName_val();
//   GuaNum_val();
//   return gunName_val() && GuaNum_val();
// }

// form validation function end

// form data uploader start
// form data uploader start
// login form data start

function Register() {
  event.preventDefault();
  // $(document).ready(function () {
  var form_element = document.getElementsByClassName('Register_Data');
  var Register_Data = new FormData();
  Register_Data.append("admin_register", "");

  for (var count = 0; count < form_element.length; count++) {
    Register_Data.append(form_element[count].name, form_element[count].value);
  }

  fetch("sql/process_data.php", {
    method: "POST",
    body: Register_Data
  })
    .then(response => response.json())
    .then(data => {
      // console.log(data.rusalt);
      if (data.rusalt == "Successfull") {
        window.location.href = "./?success_register";
      } else {
        submitBtns.style.display = 'flex';
        loding_img.style.display = 'none';
        error_alert();
      }
    })
    .catch(error => {
      // console.log("Error:", error);
      submitBtns.style.display = 'flex';
      loding_img.style.display = 'none';
      error_alert();
    });

}

function Login() {
  event.preventDefault();
  var form_element = document.getElementsByClassName('Login_Data');
  var Login_Data = new FormData();
  Login_Data.append("admin_login", "");

  for (var count = 0; count < form_element.length; count++) {
    Login_Data.append(form_element[count].name, form_element[count].value);
  }

  fetch("sql/process_data.php", {
    method: "POST",
    body: Login_Data
  })
    .then(response => response.json())
    .then(data => {
      console.log(data.rusalt);
      if (data.rusalt == "Successfull") {
        window.location.href = "../";
      } else {
        submitBtns.style.display = 'flex';
        loding_img.style.display = 'none';
        error_alert();
      }
    })
    .catch(error => {
      // console.log("Error:", error);
      submitBtns.style.display = 'flex';
      loding_img.style.display = 'none';
      error_alert();
    });

  // var ajax_request = new XMLHttpRequest();

  // ajax_request.open('POST', '../sql/process_data.php');

  // ajax_request.send(Login_Data);

  // ajax_request.onreadystatechange = function () {
  //   if (ajax_request.readyState == 4 && ajax_request.status == 200) {

  //     try {
  //       var response = JSON.parse(ajax_request.responseText);
  //       if (response.rusalt == "successfyll") {
  //         window.location.href = "../Dachbord/?success_login";
  //       } else {
  //         submitBtns.style.display = 'flex';
  //         loding_img.style.display = 'none';
  //         error_log();
  //       }
  //     } catch (error) {
  //       response = "null";
  //       submitBtns.style.display = 'flex';
  //       loding_img.style.display = 'none';
  //       error_log();
  //     }
  //     console.log(response);
  //   }
  // }
}

// login form data end
// register form data start

// register form data end
// form data uploader end

// function section end