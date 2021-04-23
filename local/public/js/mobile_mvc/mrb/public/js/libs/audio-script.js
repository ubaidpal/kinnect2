function audio() {


    var mytrack = document.getElementById('myTrack');
    var playbutton = document.getElementById('playButton');
    var muteButton = document.getElementById('muteButton');

    var currentTime = document.getElementById('currentTime');

    var duration = document.getElementById('fullDuration');


    var barSize = 520;
    var bar = document.getElementById('defaultBar');
    var progressBar = document.getElementById('progressBar');
    playbutton.addEventListener('click', playOrPause, false);//Event listener
    muteButton.addEventListener('click', muteOrUnmute, false);//Event listener
    bar.addEventListener('click', clickedBar, false);//Event listener
}
    function totalTime(track)//Total time duration
    {
        if (track.duration) {

            var minute = document.getElementById('fullDuration').innerHTML = Math.floor(parseInt(track.duration) / 60);
            var second = document.getElementById('fullDuration').innerHTML = Math.floor(parseInt(track.duration) % 60);

            duration.innerHTML = minute + ':' + second;

        }
    };




    function playOrPause() {
        if (!mytrack.paused && !mytrack.ended) {
            mytrack.pause();
            playbutton.style.backgroundImage = 'url(images/play.png)';
            window.clearInterval(updateTime);
        } else {
            mytrack.play();
            playbutton.style.backgroundImage = 'url(images/pause.png)';
            updateTime = setInterval(updatetime, 500);
        }
    }

    function muteOrUnmute() {
        if (mytrack.muted == true) {
            mytrack.muted = false;
            muteButton.style.backgroundImage = 'url(images/volume.png)';
        } else {
            mytrack.muted = true;
            muteButton.style.backgroundImage = 'url(images/volume-down.png)';
        }
    }

    function updatetime() {

        if (!mytrack.ended) {
            var playMinute = parseInt(mytrack.currentTime / 60);
            var playSeconds = pad(parseInt(mytrack.currentTime % 60));
            currentTime.innerHTML = playMinute + ':' + playSeconds;

            var size = parseInt(mytrack.currentTime * barSize / mytrack.duration)
            progressBar.style.width = size + "px";

        } else {
            currentTime.innerHTML = "0:00";
            playbutton.style.backgroundImage = 'url(images/play.png)';

            progressBar.style.width = "0px";
            window.clearInterval(updateTime);

        }
    }

    function clickedBar(e) {
        if (!mytrack.ended) {
            var mouseX = e.pageX - bar.offsetLeft;
            var newtime = mouseX * mytrack.duration / barSize;
            mytrack.currentTime = newtime;
            progressBar.style.width = mouseX + 'px';
        }
    }

    function pad(sec) {
        return (sec < 10) ? '0' + sec.toString() : sec.toString();
    }


