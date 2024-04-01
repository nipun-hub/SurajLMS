const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
// const loadingBtns = document.querySelector(".btn-loding");
const submitBtns = document.querySelector(".submit-btn");
const g_id_signin = document.querySelector(".g_id_signin");
const loding_img = document.querySelector('.form .loading');
const element = document.querySelector(".form");
const register_form = document.querySelector(".register_form");
const multi = document.getElementById("multi");
const formSteps = document.querySelectorAll(".form-step");
const multiSteps = document.querySelectorAll(".multi-step");

// const login_sec = document.querySelector('.login');
const register_sec = document.querySelector('.register');

// input value get
// const uname = document.querySelector('.uname input');
// const upass = document.querySelector('.upass input');

const fname = document.querySelector('.fname input');
const lname = document.querySelector('.lname input');
const email = document.querySelector('.email input');
// const pass = document.querySelector('.pass input');
// const repass = document.querySelector('.repass input');
const nic = document.querySelector('.nic input');
const nicpic = document.querySelector('.nicpic input');
const mobnum = document.querySelector('.mobnum input');
const whatnum = document.querySelector('.whanum input');
const dob = document.querySelector('.dob input');
const school = document.querySelector('.school input');
const year = document.querySelector('.year select');
const streem = document.querySelector('.streem select');
const shy = document.querySelector('.shy select');
const medium = document.querySelector('.medium select');
const address = document.querySelector('.address input');
const dictric = document.querySelector('.dictric select');
const city = document.querySelector('.city input');
const guaname = document.querySelector('.guaname input');
const gunnum = document.querySelector('.gunnum input');
let formStepsNum = 0;

if (element.classList.contains("register")) {

  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      event.preventDefault();
      if (formStepsNum == 0 && step_1()) {
        formStepsNum++;
        updateFormSteps();
        updatemultibar();
      } else if (formStepsNum == 1 && step_2()) {
        formStepsNum++;
        updateFormSteps();
        updatemultibar();
      } else if (formStepsNum == 2 && step_3()) {
        formStepsNum++;
        updateFormSteps();
        updatemultibar();
      } else if (formStepsNum == 3 && step_4()) {
        formStepsNum++;
        updateFormSteps();
        updatemultibar();
      }
    //   else if (formStepsNum == 4 && step_5()) {
    //     formStepsNum++;
    //     updateFormSteps();
    //     updatemultibar();
    //   }
    });
  });

  prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      event.preventDefault();
      formStepsNum--;
      updateFormSteps();
      updatemultibar();
    });
  });

  submitBtns.addEventListener("click", () => {
    event.preventDefault();
    if (step_4()) {
      submitBtns.style.display = 'none';
      loding_img.style.display = 'block';
      prevBtns.forEach((btn) => {
        btn.style.display = "none";
      });
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

function FileValidate(file) {
  if (file && validateDocumentType(file)) {
    return true;
  } else {
    return false
  }
}
function validateDocumentType(file) {
  var validTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
  return validTypes.includes(file.type);
}

// validate function start
function fname_val() {
  if (TextValidation(fname.value, 'text')) {
    fname.classList.toggle("is-valid", true);
    fname.classList.toggle("is-invalid", false);
    return true;
  } else {
    fname.classList.toggle("is-valid", false);
    fname.classList.toggle("is-invalid", true);
    return false;
  }
}

function lname_val() {
  if (TextValidation(lname.value, 'text')) {
    lname.classList.toggle("is-valid", true);
    lname.classList.toggle("is-invalid", false);
    return true;
  } else {
    lname.classList.toggle("is-valid", false);
    lname.classList.toggle("is-invalid", true);
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

function Nic_val() {
  if (TextValidation(nic.value, 'nic')) {
    nic.classList.toggle("is-valid", true);
    nic.classList.toggle("is-invalid", false);
    return true;
  } else {
    nic.classList.toggle("is-valid", false);
    nic.classList.toggle("is-invalid", true);
    return false;
  }
}

function NicPic_val() {
  if (nicpic.files.length > 0) {
    nicpic.classList.toggle("is-valid", true);
    nicpic.classList.toggle("is-invalid", false);
    return true;
  } else {
    nicpic.classList.toggle("is-valid", false);
    nicpic.classList.toggle("is-invalid", true);
    return false;
  }

}

function Mn_val() {
  if (TextValidation(mobnum.value, 'tel')) {
    mobnum.classList.toggle("is-valid", true);
    mobnum.classList.toggle("is-invalid", false);
    return true;
  } else {
    mobnum.classList.toggle("is-valid", false);
    mobnum.classList.toggle("is-invalid", true);
    return false;
  }
}

function Wn_val() {
  if (TextValidation(whatnum.value, 'tel')) {
    whatnum.classList.toggle("is-valid", true);
    whatnum.classList.toggle("is-invalid", false);
    return true;
  } else {
    whatnum.classList.toggle("is-valid", false);
    whatnum.classList.toggle("is-invalid", true);
    return false;
  }
}

function Dob_val() {
  if (TextValidation(dob.value)) {
    dob.classList.toggle("is-valid", true);
    dob.classList.toggle("is-invalid", false);
    return true;
  } else {
    dob.classList.toggle("is-valid", false);
    dob.classList.toggle("is-invalid", true);
    return false;
  }
}

function School_val() {
  if (TextValidation(school.value)) {
    school.classList.toggle("is-valid", true);
    school.classList.toggle("is-invalid", false);
    return true;
  } else {
    school.classList.toggle("is-valid", false);
    school.classList.toggle("is-invalid", true);
    return false;
  }
}

function year_val() {
  if (TextValidation(year.value, 'select')) {
    year.classList.toggle("is-valid", true);
    year.classList.toggle("is-invalid", false);
    return true;
  } else {
    year.classList.toggle("is-valid", false);
    year.classList.toggle("is-invalid", true);
    return false;
  }
}

function streem_val() {
  if (TextValidation(streem.value, 'select')) {
    streem.classList.toggle("is-valid", true);
    streem.classList.toggle("is-invalid", false);
    return true;
  } else {
    streem.classList.toggle("is-valid", false);
    streem.classList.toggle("is-invalid", true);
    return false;
  }
}

function shy_val() {
  if (TextValidation(shy.value, 'select')) {
    shy.classList.toggle("is-valid", true);
    shy.classList.toggle("is-invalid", false);
    return true;
  } else {
    shy.classList.toggle("is-valid", false);
    shy.classList.toggle("is-invalid", true);
    return false;
  }
}

function medium_val() {
  if (TextValidation(medium.value, 'select')) {
    medium.classList.toggle("is-valid", true);
    medium.classList.toggle("is-invalid", false);
    return true;
  } else {
    medium.classList.toggle("is-valid", false);
    medium.classList.toggle("is-invalid", true);
    return false;
  }
}

function address_val() {
  if (TextValidation(address.value)) {
    address.classList.toggle("is-valid", true);
    address.classList.toggle("is-invalid", false);
    return true;
  } else {
    address.classList.toggle("is-valid", false);
    address.classList.toggle("is-invalid", true);
    return false;
  }
}

function Dictric_val() {
  if (TextValidation(dictric.value, 'select')) {
    dictric.classList.toggle("is-valid", true);
    dictric.classList.toggle("is-invalid", false);
    return true;
  } else {
    dictric.classList.toggle("is-valid", false);
    dictric.classList.toggle("is-invalid", true);
    return false;
  }
}

function city_val() {
  if (TextValidation(city.value)) {
    city.classList.toggle("is-valid", true);
    city.classList.toggle("is-invalid", false);
    return true;
  } else {
    city.classList.toggle("is-valid", false);
    city.classList.toggle("is-invalid", true);
    return false;
  }
}

function gunName_val() {
  if (TextValidation(guaname.value)) {
    guaname.classList.toggle("is-valid", true);
    guaname.classList.toggle("is-invalid", false);
    return true;
  } else {
    guaname.classList.toggle("is-valid", false);
    guaname.classList.toggle("is-invalid", true);
    return false;
  }
}

function GuaNum_val() {
  if (TextValidation(gunnum.value, 'tel')) {
    gunnum.classList.toggle("is-valid", true);
    gunnum.classList.toggle("is-invalid", false);
    return true;
  } else {
    gunnum.classList.toggle("is-valid", false);
    gunnum.classList.toggle("is-invalid", true);
    return false;
  }
}

// validate function end

function step_1() {
  fname_val();
  lname_val();
  email_val();
  return fname_val() && lname_val() && email_val();
  // return true;
}

function step_2() {
  Mn_val();
  Wn_val();
  Dob_val();
  return Mn_val() && Wn_val() && Dob_val();
  // return true;
}

function step_3() {
  School_val();
  year_val();
  streem_val();
  shy_val();
  medium_val();
  return School_val() && year_val() && streem_val() && shy_val() && medium_val();
  // return true;
}

function step_4() {
  address_val();
  Dictric_val();
  city_val();
  return address_val() && Dictric_val() && city_val();
  // return true;
}

// function step_5() {
//   gunName_val();
//   GuaNum_val();
//   return gunName_val() && GuaNum_val();
// }

// form validation function end

// form data uploader start
// form data uploader start
// login form data start


// google login section

// Credential response handler function
let responsePayload;
function handleCredentialResponse(response) {
  // Post JWT token to server-side
  g_id_signin.style.display = 'none';
  loding_img.style.display = 'block';
  fetch("../sql/conf.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      request_type: 'user_auth',
      credential: response.credential
    }),
  })
    .then(response => response.json())
    .then(data => {
        console.log(data.error);
      if (data.status == 1 || data.status == 3) {
        responsePayload = data.pdata;
        g_id_signin.style.display = 'flex';
        loding_img.style.display = 'none';

        localStorage.setItem("regStatus", "1");
        localStorage.setItem("email", responsePayload.email);
        localStorage.setItem("fname", responsePayload.given_name);
        localStorage.setItem("lname", responsePayload.family_name);        
        search();
      } else if (data.status == 2) {
        // Login and pass dashbord
        window.location.href = "../Dachbord";
      } else {
        error_alt();
      }
    })
    .catch(console.error);
}

function error_alt() {
  g_id_signin.style.display = 'flex';
  loding_img.style.display = 'none';
  error_log();
}


// login form data end

// register form data start
function Register() {
  event.preventDefault();
  var form_element = document.getElementsByClassName('Register_Data');
  var Register_Data = new FormData();

  for (var count = 0; count < form_element.length; count++) {
    Register_Data.append(form_element[count].name, form_element[count].value);
  }
  Register_Data.append("email" , localStorage.getItem('email'));
  Register_Data.append("fname" , localStorage.getItem('fname'));
  Register_Data.append("lname" , localStorage.getItem('lname'));
  localStorage.clear();
  var nic_pic = register_form.querySelector('[name="nic_pic"]').files[0];
  Register_Data.append('nic_pic', nic_pic);
  // Register_Data.append(form_element[count].name, form_element[count].value);

  // document.getElementById('Register').disabled = true;

  var ajax_request = new XMLHttpRequest();

  ajax_request.open('POST', '../sql/process_data.php');

  ajax_request.send(Register_Data);

  ajax_request.onreadystatechange = function () {
    if (ajax_request.readyState == 4 && ajax_request.status == 200) {
      try {
        var response = JSON.parse(ajax_request.responseText);
        console.log(response);
        if (response.rusalt == "Successfull") {
          window.location.href = "../Dachbord/?success_register";
        } else {
          submitBtns.style.display = 'flex';
          loding_img.style.display = 'none';
          prevBtns.forEach((btn) => {
            btn.style.display = "flex";
          });
          error_alert();
        }
      } catch (error) {
        var response = "null";
        submitBtns.style.display = 'flex';
        loding_img.style.display = 'none';
        prevBtns.forEach((btn) => {
          btn.style.display = "flex";
        });
        error_alert();
      }
    }
  }
}
// register form data end

// form data uploader end

// function section end