// JavaScript Document

//
jQuery.noConflict();
//
jQuery(document).ready(function() {
	//
	jQuery('.boobook-btn').click(function() {
		return fbconnect_js();
	});
});

// ----------------------------------------------------------------------------------------------------
// Vars
// ----------------------------------------------------------------------------------------------------
var fb_user_id, fb_access_token;

// ----------------------------------------------------------------------------------------------------
// FB init
// ----------------------------------------------------------------------------------------------------
window.fbAsyncInit = function() {
    FB.init({
        'appId': boobook_fb_app_id,
        'status': true,
        'cookie': true,
        'xfbml': true,
        'oauth': true,
	    'version': 'v2.8',
    });
};

// ----------------------------------------------------------------------------------------------------
// FB init
// ----------------------------------------------------------------------------------------------------
(function(d) {
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) { return; }
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/fr_FR/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
} (document));

// -------------------------------------------------------------------------------------
// fbconnect JS
// -------------------------------------------------------------------------------------
var fbconnect_js = function() {
	FB.login(function(response) {
		if (response.authResponse) {
			FB.api('/me', function(response) {
				fb_access_token = FB.getAccessToken();
				createCookie('fb_user_id', response.id, 7);
				createCookie('fb_access_token', fb_access_token, 7);

				document.location = '/boobook/connect/?code='+fb_access_token;
			});
		}
		else {
			console.log('User cancelled login or did not fully authorize.');
		}
	}, {
		'scope': all_scope_comma,
	});

	return false;
}

// -----------------------------------------------------------------------------------
// via http://www.quirksmode.org/js/cookies.html
// -----------------------------------------------------------------------------------
function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name, "", -1);
}
