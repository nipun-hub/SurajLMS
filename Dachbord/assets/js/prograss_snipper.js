function prograss_snipper() {
  let circular = document.querySelectorAll(".circular");
  let speed = 10;
  for (let i = 0; i < circular.length; i++) {
    let progressEndValue = circular[i].querySelector("input").value;
    let progressBar = circular[i].querySelector(".circular-progress");
    let progressValue = 0;
    let progress = setInterval(() => {
      if (progressValue == progressEndValue) {
        clearInterval(progress);
      }
      progressBar.style.background = `conic-gradient(
        #f00 ${progressValue * 3.6}deg,
        #fff ${progressValue * 3.6}deg
    )`;
      progressValue++;
    }, speed);
  }
}