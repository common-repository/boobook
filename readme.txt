=== Boobook ===
Contributors: (xuxu.fr)
Donate link: http://goo.gl/SORljr
Tags: Facebook, Connect, API, User, Avatar, Widget
Requires at least: 4.6.1
Tested up to: 4.6.1
Stable tag: 1.21
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Another simple Facebook Connect for WordPress.

== Description ==

Boobook, another simple Facebook Connect for WordpPess.

Only the basic :

*   Login with a Facebook account
*   Create a WordPress user
*   Save locally on the server the Facebook picture's profile as an attachment
*   Shortcode or PHP Code to insert the Facebook Connect button wherever you want
*   Little widget for the sidebar

Page for the plugin : http://xuxu.fr/2013/12/26/boobook-un-autre-facebook-connect-minimal-pour-wordpress/

You can help me buy some diapers ^_^ : http://goo.gl/SORljr

== Installation ==

1. Extract and upload the directory `boobook` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create a Facebook Application to get an App ID and an App Secret.
4. Go to Settings -> Boobook Config to setup App ID and App Secret.
5. Insert a shortcode or the PHP code where you want the facebook connect button to appear.
6. Enjoy

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. Setting the App ID, App Secret and default role for the user connected.
2. Shortcode to put a Facebook Connect Button in a post or page content.
3. Example of button insert with a shortcode.
4. Widget Sidebar : Configuration.
5. Widget Sidebar : Not Connected.
6. Widget Sidebar : Connected.
7. The Facebook profile's picture is saved as a media file.
8. PHP code to insert the Facebook Connect Button where you want.

== Changelog ==

= 1.21 =
1. Fix bug on uninstall

= 1.20 =
1. Upgrade Facebook API v2.8
2. Translate ready !
3. Add French translations
4. Clean Boobook options on uninstall
5. Can change shortcode and widget button labels
6. Update avatar if needed
7. Refactoring code

= 1.13 =
1. Fix bug avatar creation

= 1.12 =
1. Fix Rewrite rule bug

= 1.11 =
1. Fix logout url bug 

= 1.10 =
1. Settings : can disable notification by email when a user was created
2. Settings : add redirect url after login
3. Settings : add redirect url after logout
4. add some hooks : boobook_fb_user_profile, boobook_user_created, boobook_user_before_update, boobook_user_avatar_updated, boobook_user_authenticated
5. Fix bug cookie when disconnected
6. Fix avatar size bug in the admin bar menu

= 1.01 =
1. Move flush rules

= 1.0 beta =
1. Hello world!

== Upgrade Notice ==

= 1.21 =
1. Error while processing uninstall fix

= 1.20 =
1. Upgrade The PHP Facebook SDK and change the plugin code
2. Settings, widget and shortcode labels can be translated
3. French language added
4. Delete options on plugin uninstall
5. You can change the label of the Facebook button connect in the widget and the shortcode
6. Update the avatar if this one changed since last visit
7. Miscellaneous updates


= 1.13 =
1. Fix a bug when you change the avatar on Twitter. The old image was replaced by the new one. Now both are saved correctly.

= 1.12 =
1. Fix Rewrite rule bug : add rule in hook init

= 1.11 =
1. Fix logout url bug : return empty string when no logout url filled 

= 1.10 =
1. You can choose to send a email or not when a user was created.
2. You can specify an url redirection after the user was logged.
3. You can specify an url redirection after the user was logout.
4. Add some hooks to manage more actions.
5. Fix a cookie bug : wasn't deleted correctly.
6. The avatar in the adminbar haven't got the good class.

= 1.01 =
1. Move flush rules from functions.blog.php to install.php

= 1.0 beta =
1. Welcome!