
const section = document.querySelector("section"),
  
  // middle alrt 
  title = document.querySelector(".title"),
  subtitle = document.querySelector(".subtitle"),
  doneBtn = document.getElementById("done"),
  closeBtn = document.getElementById("close"),
  icon = document.getElementById("icon"),
  rb_alert = document.querySelector(".modal_box"),
  buttons = document.querySelectorAll(".button");

  // rb_modal_box = document.querySelectorAll(".rb_modal_box");
  // rt_modal_box = document.querySelectorAll(".rt_modal_box");
  // subtitle = document.querySelector(""),
  // subtitle = document.querySelector(""),

  // right buttom alert
  const model_title = document.getElementById("model_title"),
        model_subtitle = document.getElementById("model_subtitle"),
        model_done_btn = document.querySelector(".model_done_btn"),
        model_close_btn = document.querySelector(".model_close_btn");

doneBtn.addEventListener("click", () => section.classList.remove("active_middle"));
// model_done_btn.addEventListener("click", () => section.classList.remove("active_rb"));

// var type='',
//     logo='success',
//     titleText=' Hello world!',
//     subtitleText='',
//     doneBtnText='Ok, Done',
//     closeBtnText='Ok, Close',
//     doneBtnShow=true,
//     closeBtnShow=false;


function Nthj(
  {
    type='',
    logo='',
    titleText=' Hello world!',
    subtitleText='',
    doneBtnText='Done',
    closeBtnText='Close',
    doneBtnShow=true,
    closeBtnShow=false,
    time='',
  }
  ){

    closeBtn.style.display = 'none';

    if (type=='') {
      icon.style.display = 'none';
      section.classList.add("active_middle");

      title.innerText = titleText;
      subtitle.innerText = subtitleText;
      doneBtn.innerText = doneBtnText;
      closeBtn.innerText = closeBtnText;
      // close
      doneBtn.addEventListener("click", () => section.classList.remove("active_middle"));
      if (doneBtnShow===false) {
        doneBtn.style.display = 'none';
      }
      if (closeBtnShow===true) {
        closeBtn.style.display = 'block';
      }
      if (time!='') {timeDura(type,time);}
    }else if(type=='withicon'){
      icon.style.display = 'block';
      section.classList.add("active_middle");
      title.innerText = titleText;
      subtitle.innerText = subtitleText;
      doneBtn.innerText = doneBtnText;
      closeBtn.innerText = closeBtnText;
      // close
      doneBtn.addEventListener("click", () => section.classList.remove("active_middle"));
      if (logo=='') { icon.src = '../assets/images/gif/success.gif'; }
      else if(logo!=''){ icon.src = '../assets/images/gif/'+logo+'.gif'; }
      
      if (doneBtnShow===false) {
        doneBtn.style.display = 'none';
      }
      if (closeBtnShow===true) {
        closeBtn.style.display = 'block';
      }
      if (time!='') {timeDura(type,time);}
    }else if (type=='rightButtom') {
      section.classList.remove("rt_modal_box")
      section.classList.add('active_rb','rb_modal_box');
      model_title.innerText = titleText;
      model_subtitle.innerText = subtitleText;
      model_done_btn.innerText = doneBtnText;
      model_close_btn.innerText = closeBtnText;
      // close
      model_done_btn.addEventListener("click", () => section.classList.remove("active_rb"));
      if (doneBtnShow===false) {
        model_done_btn.style.display = 'none';
      }
      if (closeBtnShow===true) {
        model_close_btn.style.display = 'block';
      }
      if (time!='') {timeDura(type,time);}
    }else if(type=='rightTop'){
      section.classList.remove("rb_modal_box")
      section.classList.add('active_rt','rt_modal_box');
      model_title.innerText = titleText;
      model_subtitle.innerText = subtitleText;
      model_done_btn.innerText = doneBtnText;
      model_close_btn.innerText = closeBtnText;
      // close
      model_done_btn.addEventListener("click", () => section.classList.remove("active_rt"));
      if (doneBtnShow===false) {
        model_done_btn.style.display = 'none';
      }
      if (closeBtnShow===true) {
        model_close_btn.style.display = 'block';
      }
      if (time!='') {timeDura(type,time);}
    }
    else{
      icon.src = 'assets/img/'+logo+'.gif';
      section.classList.add("active_middle");

      title.innerText = titleText;
      subtitle.innerText = subtitleText;
      doneBtn.innerText = doneBtnText;
      closeBtn.innerText = closeBtnText;
      // close
      doneBtn.addEventListener("click", () => section.classList.remove("active_middle"));
      if (doneBtnShow===false) {
        doneBtn.style.display = 'none';
      }
      if (closeBtnShow===true) {
        closeBtn.style.display = 'block';
      }
      if (time!='') {timeDura(type,time);}
    }

    
    if (doneBtnShow===false) {
      doneBtn.style.display = 'none';
    }
    if (closeBtnShow===true) {
      closeBtn.style.display = 'block';
    }
}

//  *************************** alert main function start ***************************

function ImgeNotFound(){
  icon.style.display = 'none';
}

function NthjClose(){
  section.classList.remove("active_middle");
  section.classList.remove("active_rb");
}

function timeDura(value,time){
  if (value=='') { setTimeout(() => section.classList.remove("active_middle"), time); }
  else if (value=='active_middle') { setTimeout(() => section.classList.remove("active_middle"), time); }
  else if (value=='rt_modal_box') { setTimeout(() => section.classList.remove("active_rt"), time); }
  else if (value=='rb_modal_box') { setTimeout(() => section.classList.remove("active_rb"), time); }
}
//  *************************** alert main function end ***************************


