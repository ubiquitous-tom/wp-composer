jQuery(document).ready(function ($) {
    bc(document.getElementById('umc-about'));
    videojs('umc-about').ready(function() {
        myPlayer = this;
        //myPlayer.pause();
    })
});
