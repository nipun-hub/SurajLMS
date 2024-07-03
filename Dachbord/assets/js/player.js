// console.log(videoId);
var container = document.querySelector(".container_player"),
    mainVideo = container.querySelector("video"),
    videoTimeline = container.querySelector(".video-timeline"),
    progressBar = container.querySelector(".progress-bar"),
    volumeBtn = container.querySelector(".volume i"),
    volumeSlider = container.querySelector(".left input");
currentVidTime = container.querySelector(".current-time"),
    videoDuration = container.querySelector(".video-duration"),
    skipBackward = container.querySelector(".skip-backward i"),
    skipForward = container.querySelector(".skip-forward i"),
    playPauseBtn = container.querySelector(".play-pause i"),
    speedBtn = container.querySelector(".playback-speed"),
    speedOptions = container.querySelector(".speed-options"),
    qualityBtn = container.querySelector(".playback-quality span"),
    qualityOptions = container.querySelector(".quality-options"),
    pipBtn = container.querySelector(".pic-in-pic span"),
    fullScreenBtn = container.querySelector(".fullscreen i");
var timer;
var finifhCounter = 0;
var addPoint = 0;
// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
        setTimeout(stopVideo, 6000);
        done = true;
    }
}
function stopVideo() {
    player.stopVideo();
}

//       // Replace with your YouTube video ID
// const videoId = 'hiZkihneQLA';

// Initialize YouTube Iframe API
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '100%',
        width: '100%',
        videoId: videoId,
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        },
        playerVars: {
            controls: 0,
            disablekb: 1,
            rel: 0,
            modestbranding: 1,
            showinfo: 0
        }
    });

}

// // The API calls this function when the video player is ready
function onPlayerReady(event) {
    // Add event listeners for play, pause, seek, mute, and volume controls

    // Add event listeners for play, pause, seek, mute, and volume controls
    const playPauseButton = document.getElementById('play-pause');
    playPauseButton.addEventListener('click', togglePlayPause);

    const muteButton = document.getElementById('mute');
    muteButton.addEventListener('click', toggleMute);

    const volumeBar = document.getElementById('volume-bar');
    volumeBar.addEventListener('input', adjustVolume);

    // progressBar.addEventListener("timeupdate", function() {
    //     console.log('currentTime');
    //     let currentTime = player.getCurrentTime();
    //     let Duration = player.getDuration();
    //     let timelineWidth = videoTimeline.clientWidth;
    //     let NewLineWith = (currentTime / duration) * 100;
    //     progressBar.style.width = `${percent}%`;
    //     // currentVidTime.innerText = formatTime(currentTime);
    // });
    // Duration = player.getDuration();
    // Durat = Duration;
}

var formatTime = time => {
    let seconds = Math.floor(time % 60),
        minutes = Math.floor(time / 60) % 60,
        hours = Math.floor(time / 3600);

    seconds = seconds < 10 ? `0${seconds}` : seconds;
    minutes = minutes < 10 ? `0${minutes}` : minutes;
    hours = hours < 10 ? `0${hours}` : hours;

    if (hours == 0) {
        return `${minutes}:${seconds}`
    }
    return `${hours}:${minutes}:${seconds}`;
}

// mainVideo.addEventListener("timeupdate", e => {
// let NewLineWith = (player.getCurrentTime() / player.getDuration()) * 100;
// progressBar.style.width = `${NewLineWith}%`;
// currentVidTime.innerText = formatTime(currentTime);
// });



// The API calls this function when the player's state changes
function onPlayerStateChange(event) {
    if (player.getPlayerState() == 1) {
        // player.pauseVideo();
        playPauseBtn.classList.replace('fa-play', 'fa-pause');
    } else if (player.getPlayerState() == 0) {
        playPauseBtn.classList.replace('fa-pause', 'fa-play');
    }
    videoDuration.innerText = formatTime(player.getDuration());
    // You can add custom logic for state changes here
}

// // Play/Pause button click event
function togglePlayPause() {
    if (player.getPlayerState() === 1) {
        player.pauseVideo();
        playPauseBtn.classList.replace('fa-pause', 'fa-play');
    } else {
        player.playVideo();
        playPauseBtn.classList.replace("fa-play", "fa-pause");
    }
}

// Seek video
function seek() {
    let timelineWidth = videoTimeline.clientWidth;
    const seekBar = document.getElementById('seek-bar');
    const time = player.getDuration() * (seekBar.value / 100);
    player.seekTo(time);
}

// time line and time live update
function updateFunction() {
    let NewLineWith = (player.getCurrentTime() / player.getDuration()) * 100;
    progressBar.style.width = `${NewLineWith}%`;
    player.getPlayerState() === 1 && NewLineWith <= 99 ? finifhCounter++ : null;
    var finishprecentage = (finifhCounter / player.getDuration() * 100);
    if (finishprecentage > 10 && NewLineWith > 80 && addPoint == 0) {
        PassData = "manageActivity=" + "&type=" + 'votchRecoding' + "&data=" + Lesid;
        $.post("sql/process.php", PassData, function (data, status) { console.log("add activity " + data); });
        // console.log('mark as finished' + Lesid);
        addPoint++;
    }
    currentVidTime.innerText = formatTime(player.getCurrentTime());
    setTimeout
}
setInterval(updateFunction, 1000);


// Adjust volume
function adjustVolume() {
    const volumeBar = document.getElementById('volume-bar');
    if (volumeBar.value == 0) {
        volumeBtn.classList.replace("fa-volume-high", "fa-volume-xmark");
    } else {
        volumeBtn.classList.replace("fa-volume-xmark", "fa-volume-high");
    }
    player.setVolume(volumeBar.value);
}

// // Mute/Unmute video
function toggleMute() {
    if (player.isMuted()) {
        player.unMute();
        volumeBtn.classList.replace("fa-volume-xmark", "fa-volume-high");
        // document.getElementById('mute').textContent = 'Mute';
    } else {
        player.mute();
        volumeBtn.classList.replace("fa-volume-high", "fa-volume-xmark");
        // document.getElementById('mute').textContent = 'Unmute';
    }
}

// ******************************

var hideControls = () => {
    // if(player.getPlayerState() > 5) return;
    timer = setTimeout(() => {
        container.classList.remove("show-controls");
    }, 3000);
}
hideControls();

container.addEventListener("mousemove", () => {
    container.classList.add("show-controls");
    clearTimeout(timer);
    hideControls();
});

videoTimeline.addEventListener("mousemove", e => {
    let timelineWidth = videoTimeline.clientWidth;
    let offsetX = e.offsetX;
    let percent = Math.floor((offsetX / timelineWidth) * player.getDuration());
    const progressTime = videoTimeline.querySelector("span");
    offsetX = offsetX < 20 ? 20 : (offsetX > timelineWidth - 20) ? timelineWidth - 20 : offsetX;
    progressTime.style.left = `${offsetX}px`;
    progressTime.innerText = formatTime(percent);
});

videoTimeline.addEventListener("click", e => {
    let timelineWidth = videoTimeline.clientWidth;
    player.seekTo((e.offsetX / timelineWidth) * player.getDuration());
});

var draggableProgressBar = e => {
    let timelineWidth = videoTimeline.clientWidth;
    progressBar.style.width = `${e.offsetX}px`;
    mainVideo.currentTime = (e.offsetX / timelineWidth) * player.getDuration();
    player.seekTo(mainVideo.currentTime);
    ;  // currentVidTime.innerText = formatTime(mainVideo.currentTime);
}

speedOptions.querySelectorAll("li").forEach(option => {
    option.addEventListener("click", () => {
        var speed = option.dataset.speed;
        if (speed == 2.0) {
            player.setPlaybackRate(2.0);
        } else if (speed == 1.5) {
            player.setPlaybackRate(1.5);
        } else if (speed == 1.0) {
            player.setPlaybackRate(1.0);
        } else if (speed == 0.5) {
            player.setPlaybackRate(0.5);
        } else if (speed == 0.75) {
            player.setPlaybackRate(0.75);
        }
        speedOptions.querySelector(".active").classList.remove("active");
        option.classList.add("active");
    });
});

document.addEventListener("click", e => {
    if (e.target.tagName !== "SPAN" || e.target.className !== "material-symbols-outlined") {
        speedOptions.classList.remove("show");
    }
});

// qualityOptions.querySelectorAll("li").forEach(option => {
//     option.addEventListener("click", () => {
//         var quality = option.dataset.quality;
//        //  if (quality == 2.0) {
//        //      player.setPlaybackRate(2.0);
//        //  }else if(quality == 1.5){
//        //      player.setPlaybackRate(1.5);
//        // }else if(quality == 0.5){
//        //   }else if(quality == 1.0){
//        //      player.setPlaybackRate(1.0);
//        //      player.setPlaybackRate(0.5);
//        //  }else if(quality == 0.75){
//        //      player.setPlaybackRate(0.75);
//        //  }
//         player.setPlaybackQuality('highres');
//         qualityOptions.querySelector(".active").classList.remove("active");
//         option.classList.add("active");
//         qualityOptions.classList.remove("show");
//     });
// });

// document.addEventListener("click", e => {
//     if(e.target.tagName !== "SPAN" || e.target.className !== "material-symbols-outlined") {
//         qualityOptions.classList.remove("show");
//     }
// });


fullScreenBtn.addEventListener("click", () => {
    container.classList.toggle("fullscreen");
    if (document.fullscreenElement) {
        fullScreenBtn.classList.replace("fa-compress", "fa-expand");
        return document.exitFullscreen();
    }
    fullScreenBtn.classList.replace("fa-expand", "fa-compress");
    container.requestFullscreen();
});

// container.addEventListener("click", () => togglePlayPause());
speedBtn.addEventListener("click", () => speedOptions.classList.toggle("show"));
qualityBtn.addEventListener("click", () => qualityOptions.classList.toggle("show"));
pipBtn.addEventListener("click", () => mainVideo.requestPictureInPicture());
skipBackward.addEventListener("click", () => player.seekTo(player.getCurrentTime() - 5));
skipForward.addEventListener("click", () => player.seekTo(player.getCurrentTime() + 5));
videoTimeline.addEventListener("mousedown", () => videoTimeline.addEventListener("mousemove", draggableProgressBar));
document.addEventListener("mouseup", () => videoTimeline.removeEventListener("mousemove", draggableProgressBar));

// short cuts
document.addEventListener('keydown', function (event) {
    if (event.keyCode == 77) {
        toggleMute();
    }
    if (event.keyCode == 39) {
        player.seekTo(player.getCurrentTime() + 5);
    }
    if (event.keyCode == 37) {
        player.seekTo(player.getCurrentTime() - 5);
    }
    if (event.keyCode == 32) {
        togglePlayPause();
    }
    if (event.keyCode == 70) {
        container.classList.toggle("fullscreen");
        if (document.fullscreenElement) {
            fullScreenBtn.classList.replace("fa-compress", "fa-expand");
            return document.exitFullscreen();
        }
        fullScreenBtn.classList.replace("fa-expand", "fa-compress");
        container.requestFullscreen();
    }
});