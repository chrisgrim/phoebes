document.addEventListener('DOMContentLoaded', function() {
    var video = document.getElementById('myVideo');

    document.getElementById('openVideo').addEventListener('click', function() {
        // Remove 'hidden' class and adjust CSS for visibility
        video.classList.remove('hidden'); // Assuming this was your method to hide it
        video.style.visibility = 'visible';
        video.style.opacity = '1';
        video.style.position = 'initial'; // Reset any positioning used to hide the video

        video.play().then(() => {
            // Fullscreen request logic remains unchanged
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitEnterFullscreen) { // For iOS Safari
                video.webkitEnterFullscreen();
            } else if (video.mozRequestFullScreen) { // For Firefox
                video.mozRequestFullScreen();
            } else if (video.webkitRequestFullscreen) { // For Chrome, Safari, and Opera
                video.webkitRequestFullscreen();
            } else if (video.msRequestFullscreen) { // For IE/Edge
                video.msRequestFullscreen();
            }
        }).catch(err => {
            console.error("Error attempting to play video: ", err);
        });
    });

    // Exiting fullscreen and hiding the video logic remains unchanged
    function exitFullscreenHandler() {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
            hideAndResetVideo();
        }
    }

    document.addEventListener('fullscreenchange', exitFullscreenHandler);
    document.addEventListener('webkitfullscreenchange', exitFullscreenHandler);
    document.addEventListener('mozfullscreenchange', exitFullscreenHandler);
    document.addEventListener('MSFullscreenChange', exitFullscreenHandler);

    video.addEventListener('webkitendfullscreen', hideAndResetVideo); // For iOS

    function hideAndResetVideo() {
        video.pause();
        video.classList.add('hidden'); // Consider using CSS visibility and opacity here too if needed
        video.currentTime = 0;
    }
});
