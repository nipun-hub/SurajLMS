// let progressBar = document.querySelectorAll(".circular-progress");
let circular = document.querySelectorAll(".circular");
// let prograsinput = document.querySelectorAll("input");
// let valuecircular = document.querySelectorAll(".value-circular");

// let progressEndValue = 100;
let speed = 10;

for (let i = 0; i < circular.length; i++) {
  let progressEndValue = circular[i].querySelector("input").value;
  let progressBar = circular[i].querySelector(".circular-progress");
  // let valuecircular = circular[i].querySelector(".value-circular");
  let progressValue = 0;
  let progress = setInterval(() => {
    if (progressValue == progressEndValue) {
      clearInterval(progress);
    }
    // valuecircular.textContent = `${progressValue}%`;
    progressBar.style.background = `conic-gradient(
        #f00 ${progressValue * 3.6}deg,
        #fff ${progressValue * 3.6}deg
    )`;
    progressValue++;
  }, speed);
}
