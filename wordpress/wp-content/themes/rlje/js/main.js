jQuery(document).ready(function($) {

// Navbar slide left
var sideslider = $('[data-toggle=collapse-side]');
var sel = sideslider.attr('data-target');
var sel2 = sideslider.attr('data-target-2');
sideslider.click(function(event){
$(sel).toggleClass('in');
$(sel2).toggleClass('out');});

//feature carousel state change
// $('#myCarousel').carousel({
//   interval: 40000
// });

// Carousel state change for various screen sizes

(function(){
  $('.carousel-respond-slide .item').each(function(){
    var itemToClone = $(this);

    for (var i=1;i<4;i++) {
      itemToClone = itemToClone.next();

      // wrap around if at end of item collection
      if (!itemToClone.length) {
        itemToClone = $(this).siblings(':first');
      }


      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo($(this));
    }
  });
}());

$('.overlay .carousel-button:last-child').on('click', function(){
     $('.item.active img').remove()
 });



// bcswipe.js mobile touch swipe for all carousels
(function($) {
  $.fn.bcSwipe = function(settings) {
    var config = { threshold: 50 };
    if (settings) {
      $.extend(config, settings);
    }

    this.each(function() {
      var stillMoving = false;
      var start;

      if ('ontouchstart' in document.documentElement) {
        this.addEventListener('touchstart', onTouchStart, false);
      }

      function onTouchStart(e) {
        if (e.touches.length == 1) {
          start = e.touches[0].pageX;
          stillMoving = true;
          this.addEventListener('touchmove', onTouchMove, false);
        }
      }

      function onTouchMove(e) {
        if (stillMoving) {
          var x = e.touches[0].pageX;
          var difference = start - x;
          if (Math.abs(difference) >= config.threshold) {
            cancelTouch();
            if (difference > 0) {
              $(this).carousel('next');
            }
            else {
              $(this).carousel('prev');
            }
          }
        }
      }

      function cancelTouch() {
        this.removeEventListener('touchmove', onTouchMove);
        start = null;
        stillMoving = false;
      }
    });

    return this;
  };
})(jQuery);

$('.carousel').bcSwipe({ threshold: 55,  maxTimeThreshold:10 });



//filter series dropdown

  $('#options').click(function(){
    if($('#drop-select').hasClass('closed')){
   $('#drop-select').slideDown(300).show();
    $('#drop-select').addClass('open');
      $('#drop-select').removeClass('closed');
    }
    else
    {
      $('#drop-select').slideUp(400).fadeOut();
      $('#drop-select').addClass('closed');
      $('#drop-select').removeClass('open');}
   });

//popout

(function (w, d, $) {
  function getConversation (elem) {
    return $(elem).closest('.conversation');
  }
  $('.conversation .icon.minimize').on('click', function (event) {
    event.preventDefault();
    getConversation(this).toggleClass('collapsed');
  });
  $('.conversation .icon.close').on('click', function (event) {
    event.preventDefault();
    getConversation(this).remove();
  });
})(window, document, jQuery);

$('.topnavbar').affix({
    offset: {
        top: $('#banner').height()
    }
});

$( ".close-hello-bar" ).on( "click", function() {
 $("#banner").css("display", "none");
});

///modals
$(".trailer-modal").fancybox({
    width: 640, // or whatever
    height: 320,
    titleShow : false,
    hideOnContentClick : false,
    transitionIn  : 'none',
    transitionOut : 'none',
    type: "swf"
});

});


///////WP-Plugin-JS///////
(function($) {
$content = $('body > div');
$playerSection = $('#player-section');

// var search = function(form) {
//     var $formSearchText = $(form['s']).val();
//     if($formSearchText !== '') {
//         var searchText = encodeURIComponent($formSearchText.replace(/\\\'/, "'").toLowerCase())
//                 .replace(/\!/g, '%21')
//                 .replace(/\'/g, '%27')
//                 .replace(/\(/g, '%28')
//                 .replace(/\)/g, '%29')
//                 .replace(/\*/g, '%2A')
//                 .replace(/\+/g, '%20');
//         window.location = '/search/' + searchText;
//     }
//     return false;
// };

// var addToWatchlist = function(franchise) {
//     $.post('/' + franchise, { franchise: franchise, action: "add" }, function() {
//         $('#watchlistActionButton').attr('onclick', 'removeFromWatchlist(\'' + franchise + '\')').html('Remove from Watchlist');
//     });
// };

// var removeFromWatchlist = function(franchise) {
//     $.post('/' + franchise, { franchise: franchise, action: "remove" }, function() {
//         $('#watchlistActionButton').attr('onclick', 'addToWatchlist(\'' + franchise + '\')').html('Add to Watchlist');
//     });
// };

//Episode Player
// var episodePlayer = function(episodeId, setTimePosition) {
//     var player = videojs('brightcove-episode-player'),
//         isPlayingSet = function(item) {
//             var isSet = false;
//             if(docCookies.hasItem('playerOption') && docCookies.getItem('playerOption') === item) {
//                 isSet = true;
//                 docCookies.removeItem('playerOption', '/');
//             }
//             return isSet;
//         },
//         fromStart = isPlayingSet('playFromStart'),
//         resume = isPlayingSet('playResume'),
//         initTime = setTimePosition,
//         strempositionInterval,
//         $continueWatchingBlock = $('.continueWatching'),
//         $playNextEpisodeBlock = $('.playNextEpisode'),
//         showingExtBtn = true,
//         showingNextEpisodePrompt = false,
//         isCapturingStreamPosition = false,
//         $overlay = $('#nextEpisodeOverlay'),
// 		$overlayCloned = $overlay.clone();
//     if(fromStart) {
//         initTime = 0;
//     }
//     if(resume || fromStart) {
//         player.autoplay(true);
//         $continueWatchingBlock.hide();
//         showingExtBtn = false;
//     }
//     player.on('loadedmetadata', function() {
//         //Set the current time once the initial duration is loaded.
//         if(setTimePosition) {
//             player.currentTime(setTimePosition);
//         }
//         if(fromStart) {
//             player.currentTime(initTime);
//             setStreamPosition('STOP');
//         }
//     });
//     player.on('play', function() {
//         if(isPlayingSet('playFromStart')) {
//             return;
//         }
//         ///Only fixes a Safari issue (sometimes the player doesn't load the current time).
//         if(initTime > 0) {
//             var isEnded = (initTime > (player.duration()-10)) ? true : false;
//             if(isEnded) { initTime = 0; } ///if the video ended, it plays from beginer.
//             if(player.currentTime() !== initTime) {
//                 player.currentTime(initTime);
//                 console.log('Player: Init time set.');
//             }
//             initTime=null;
//         }
//         ///End Safari fix.

//         //Checking if the current User is Active
//         var data = {
//                 'action': 'isUserActive',
//                 'token': atv_player_object.token
//             },
//             stopPlayer = function() {
//                 player.pause();
//             },
//             processData = function(response) {
//                 if(response.isActive !== true) {
//                     stopPlayer();
//                     console.error('User not active');
//                 }
//             };

//         $.post(atv_player_object.ajax_url, data, processData).fail(function() {
//             console.error('Error checking if the user is active');
//         });

//     }).on('playing', function(){
//         isPlayingSet('playFromStart');
//         if(!isCapturingStreamPosition) {
//             isCapturingStreamPosition = true;
//             strempositionInterval = setInterval(function() {
//                 setStreamPosition('PLAYING');
//             }, 60000);
//         }
//         $continueWatchingBlock.hide();
//     }).on('pause', function() {
//         clearInterval(strempositionInterval);
//         isCapturingStreamPosition = false;
//         setStreamPosition('STOP');
//         if(showingExtBtn) {
//             $continueWatchingBlock.show();
//             $playNextEpisodeBlock.hide();
//         }
//         else {
//             showingExtBtn = true;
//         }
//     }).on('ended', function() {
//         $continueWatchingBlock.hide();
//         $playNextEpisodeBlock.show();
//     }).on('timeupdate', function(){
//         if(!player.paused()) {
//             var showNextEpisode = (player.currentTime() >= player.duration() - 45);
//             if(showNextEpisode && !showingNextEpisodePrompt) {
//                 if(typeof $overlayCloned === 'undefined') {
//                     $overlayCloned = $overlay.clone();
//                 }
//                 if(typeof $overlayCloned[0] !== 'undefined') {
//                     $overlayCloned.show();
//                     player.el().appendChild($overlayCloned[0]);
//                     showingNextEpisodePrompt = true;
//                     console.log('showing nextEpisode prompt!');
//                 }
//             }
//             else if(!showNextEpisode && showingNextEpisodePrompt){
//                 player.el().removeChild($overlayCloned[0]);
//                 showingNextEpisodePrompt = false;
//                 console.log('removing nextEpisode prompt!');
//             }
//         }
//     });

//     var setStreamPosition = function(lastKnownAction) {
//         $.ajax({
//             type: 'POST',
//             data: "Position=" + parseInt(player.currentTime()) + "&EpisodeID=" + episodeId + "&LastKnownAction=" + lastKnownAction ,
//             url: '/streamposition',
//             dataType: "text"
//         });
//     };
// };

// var emailPlaceholder = function(el) {
//     var str = $(el).val();
//     if (!str.trim().length) {
//         $(el).val('Enter Your Email Address');
//     }
// };

// var clearPlaceholder = function(el) {
//     var str = $(el).val();
//     if (str == 'Enter Your Email Address') {
//         $(el).val('');
//     }
// };

// var signupNewsletter = function(el) {
//     var re = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,
//         $signupNewsletter = $(el).parent(),
//         $signupEmail = $signupNewsletter.find('#signupEmail').val();

//     if (re.test($signupEmail.trim())) {
//        $.ajax({
//             type: 'POST',
//             data: "SignupEmail=" + $signupEmail,
//             url: '/signupnewsletter',
//             dataType: "text",
//             success: function() {
//                 $signupNewsletter.find('#formMessage').html('<div class="alert alert-success">Thank you for subscribing!</div>');
//             },
//             error: function() {
//                 $signupNewsletter.find('#formMessage').html('<div class="alert alert-error">There was a problem with your submission, please try again.</div>');
//             }
//         });
//     }
//     else {
//         $signupNewsletter.find('#formMessage').html('<div class="alert alert-error">Invalid email address.</div>');
//     }
// };
})(jQuery);

jQuery(document).ready(function($) {

    //dropdown menu
    $('.menuOptions').click(function() {
        var $dropSelect = $(this).parent().find('.drop-select');
        if($dropSelect.hasClass('closed')) {
            $dropSelect.slideDown(300).show();
            $dropSelect.addClass('open');
            $dropSelect.removeClass('closed');
        }
        else
        {
            $dropSelect.slideUp(400).fadeOut();
            $dropSelect.addClass('closed');
            $dropSelect.removeClass('open');
        }
        return false;
    });

    $('.navbar .container').click(function(){
        var $dropSelect = $(this).find('.navbar-right .drop-select');
        if ($dropSelect.hasClass('open')) {
            $dropSelect.slideUp(400).fadeOut();
            $dropSelect.addClass('closed');
            $dropSelect.removeClass('open');
        }
    });

    //Fancybox
    $('.open-dialog').fancybox({
        padding : 10,
        autoSize: false
    });

    if($('#umc-about').length) {
      bc($('#umc-about')[0]);
    }

    // Continue Watching
    // var endCookie = new Date();
    // endCookie.setMinutes(endCookie.getMinutes() + 2);
    // $('#continueWatching, #brightcove-episode-player')
    //     .on('click', '.js-play-start', function() {
    //         docCookies.setItem('playerOption', 'playFromStart', endCookie);
    //     })
    //     .on('click', '.js-play-resume', function() {
    //         docCookies.setItem('playerOption', 'playResume', endCookie);
    //     });
    // $('.js-player-start').on('click', function(e) {
    //     e.preventDefault();
    //     scrollToPlayer(this);
    //     docCookies.setItem('playerOption', 'playFromStart', endCookie);
    //     videojs('brightcove-episode-player').currentTime(0).play();
    // });
    // $('.js-player-resume').on('click', function(e) {
    //     e.preventDefault();
    //     scrollToPlayer(this);
    //     videojs('brightcove-episode-player').play();
    // });
    // $('.js-play-next').on('click', function(e) {
    //     docCookies.setItem('playerOption', 'playResume', endCookie);
    //     window.location = $(this).data('next');
    // });
    // $('.js-go-to').on('click', function(e) {
    //     window.location = $(this).data('url');
    // });
    // $('#brightcove-episode-player').on('click', '.js-episode-loading', function(e) {
    //     $('.imgOverlayContainer #play-episodes').hide();
    //     $('.imgOverlayContainer .loading').show();
    //     setTimeout(function(){
    //         $('.imgOverlayContainer #play-episodes').show();
    //         $('.imgOverlayContainer .loading').hide();
    //     },60000);
    // });
    // var scrollToPlayer = function(elm){
    //     if(typeof $(elm).data('no-scroll') === 'undefined') {
    //         var position = $('#episode').position();
    //         window.scroll(0,position.top);
    //     }
    // };
});
