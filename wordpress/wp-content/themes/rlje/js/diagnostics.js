/* setting all form input with default text using title attribute  */
function setDefaultInputText() { $(':input[title]').each(function () { var $this = $(this); if ($this.val() === '') { $this.val($this.attr('title')); } $this.focus(function () { if ($this.val() === $this.attr('title')) { $this.val(''); } }); $this.blur(function () { if ($this.val() === '') { $this.val($this.attr('title')); } }); }); }

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

// Get the userAgent.
var uA = window.navigator.userAgent;

// Get the video param!
var video = getUrlVars()["video"];

function speedtest() {
	var time1;
    //var bytesLoaded = 373620; // speedtest5.jpg
    var bytesLoaded = 765954; // speedtest6.jpg
	var d = new Date;
	time1 = d.getTime();
	var connSpeed = 'Test Failed.'; // in case things do not come out

	$.ajax({
		url: '/content/themes/acorntv/img/speedtest6.jpg?s=' + time1, // this could be located on the same server as the wowza shizzle!
		// crossDomain: true, // This totally broke IE7 & 8 and Opera on both platforms!
		cache: false,
		complete: function (data) {
			var d = new Date;
			var time = Math.round((d.getTime() - time1) / 10) / 100; // math /100 for watch.acorn
			var connSpeed = Math.round(bytesLoaded / time / 66); // math /100 for watch.acorn	-- is this relative to the image size? this is 37XXXX bytes.
			if (connSpeed == 'null') {
				connSpeed = 'Test Failed.';
			}

			if (video != undefined) {
				$("#diagnostic .conSpeed").replaceWith('<li class="conSpeed"><strong>Connection Speed</strong> ' + connSpeed + '&nbsp;kbps</li>');
			}
			else {
				$("#diagnostic .conSpeed").replaceWith('<li class="conSpeed"><strong>Connection Speed</strong> ' + connSpeed + '&nbsp;kbps</li>');
			}
			$("input#connSpeed").attr('value', ' ' + connSpeed);

			/*
			$("#submitThisForm").click(function () {
				if (!/^([a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)+$/i.test($("#email").val())) { // updated per http://www.regular-expressions.info/email.html
					$("form#acornDiagnostic p.error").show();
					return false;
				}
				else {
					$("form#acornDiagnostic").submit();
				}
			}); */

            // should just disable instead
			$("#submitThisForm").css('opacity', '1');
		}
	}); // $.ajax [close]

}	// speedTest [close]

document.addEventListener("DOMContentLoaded", function () {


    jQuery.support.placeholder = (function () {
        var i = document.createElement('input');
        return 'placeholder' in i;
    })();

    if ($.support.placeholder) {
        $("label.email").css('display', 'none');
    }

    var playerVersion = swfobject.getFlashPlayerVersion(); // returns a JavaScript object (for Flash info!)
    /* Client-side I know, but we are not building an ultra sensitive beast. With email all should be cool */
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var date = new Date();
    var c_date = date.getUTCDate();
    var c_month = date.getUTCMonth();
    var c_year = date.getUTCFullYear();
    var c_hours = date.getUTCHours();
    var theM = '';
    var c_minutes = date.getUTCMinutes();
    if (c_minutes < 10) {
        c_minutes = '0' + c_minutes;
    }
    var allCookies = document.cookie;
    //var shtmlip = '173.8.124.110'; // page needs to have an .shtml or .shtm extension -or- AddHandler server-parsed .html -or- AddHandler server-parsed .jsp
    // others: REMOTE_ADDR, DATE_GMT, SERVER_NAME
    var connSpeed = '<span id=\"testing\">Testing...</span>';
    // var connSpeed = 'Testing...';

    var brwser =  $.browser.name;
    var brwserVersion =  $.browser.version;
    //if (($.browser.name == 'unknown' || $.browser.name == 'Unknown') && !!navigator.userAgent.match(/Trident\/7\./)) {
    //	brwser = 'Internet Explorer';
    //	brwserVersion = '11';
    //}

	// Write out the form on the page [can be removed if desired -- leaving since it's useful for debug sometimes. :) ]
    // $("#diagnostic #acornDiagnostic").after('<div id="reporting" class="clearfix"><ul><li class="customerHidden"><strong>Date:</strong> ' + months[c_month] + " " + c_date + ", " + c_year + '</li><li class="customerHidden"><strong>Time:</strong> ' + c_hours + ':' + c_minutes + theM + ' (UTC)</li><li class="conSpeed"><strong>Connection speed:</strong> ' + connSpeed + '</li><li><strong>Operating System:</strong> ' + navigator.platform + '</li><li><strong>Browser:</strong> ' + $.browser.name + ' ' + $.browser.version + '</li><li class="customerHidden"><strong>User Agent Header:</strong> ' + navigator.userAgent + '</li><li class="customerHidden"><strong>Screen Size:</strong> ' + screen.width + 'px x ' + screen.height + 'px</li><li class="customerHidden"><strong>Cookies Enabled:</strong> ' + navigator.cookieEnabled + '</li><li class="flashPlayer"><strong>Flash Player:</strong> ' + playerVersion.major + '.' + playerVersion.minor + '.' + playerVersion.release + '</li><li class="customerHidden"><strong>Acorn Online Cookies:</strong> ' +  allCookies + '</li></ul></div>');
    $("#stats").after('<div id="reporting" class="clearfix"><ul><li class="customerHidden"><strong>Date</strong> ' + months[c_month] + " " + c_date + ", " + c_year + '</li><li class="customerHidden"><strong>Time</strong> ' + c_hours + ':' + c_minutes + theM + ' (UTC)</li><li class="conSpeed"><strong>Connection speed</strong> ' + connSpeed + '</li><li><strong>Operating System</strong> ' + navigator.platform + '</li><li><strong>Browser</strong> ' + brwser + ' ' + brwserVersion + '</li><li class="customerHidden"><strong>User Agent Header</strong> ' + navigator.userAgent + '</li><li class="customerHidden"><strong>Screen Size</strong> ' + screen.width + 'px x ' + screen.height + 'px</li><li class="customerHidden"><strong>Cookies Enabled</strong> ' + navigator.cookieEnabled + '</li><li class="flashPlayer"><strong>Flash Player</strong> ' + playerVersion.major + '.' + playerVersion.minor + '.' + playerVersion.release + '</li><li class="customerHidden"><strong>Acorn Online Cookies</strong> ' +  allCookies + '</li></ul></div>');

    // Update all hidden inputs with values!
    $("input#subject").attr('value', 'Acorn TV Problem');
    $("input#date").attr('value', ' ' + months[c_month] + ' ' + c_date + ', ' + c_year);
    $("input#time").attr('value', ' ' + c_hours + ':' + c_minutes + theM + ' (UTC)');
    $("input#browser").attr('value', ' ' + brwser + ' ' + brwserVersion);
    $("input#userAgentHeader").attr('value', ' ' + navigator.userAgent);
    $("input#screenSize").attr('value', ' ' + screen.width + 'px x ' + screen.height + 'px');
    $("input#cookiesEnabled").attr('value', ' ' + navigator.cookieEnabled);
    $("input#acornOnlineCookies").attr('value', ' ' + allCookies);
    $("input#flashPlayer").attr('value', ' ' + playerVersion.major + '.' + playerVersion.minor + '.' + playerVersion.release);
    //$("input#ipAddress").attr('value', ' ' + shtmlip);
    $("input#referringUrl").attr('value', ' ' + document.referrer);

    //  var redirectUrl = "http://watch.acornonline.com/page/diagnostics-thank-you.jsp";
    //   $("input#redirectUrl").attr('value', redirectUrl);

    /*
    $("#submitThisForm").click(function () {
       return true; // turn off the submit action til test finished
    });
    */

    var issuePick = $("input[checked]").val();

    $("input[name='Issue']").click(function () {
        issuePick = $(this).val();
        $("input#subject").attr('value', 'Acorn TV Problem: ' + issuePick);
    });

    // Check for a back url param! //
    var back = getUrlVars()["back"];
    if (back != undefined) {
        // Work that back button!
        var refer = document.referrer;
        var redirect = $("#redirectUrl").attr('value');
        $("#redirectUrl").attr('value', redirect + '?back=yes&redir=' + refer);
        $(".backButt").show().click(function () {
            window.location.replace(refer); // works great for everything.
        });
    }

    speedtest();

}); // doc ready [close]