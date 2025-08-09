=== Unbranded Portal Connector ===
Contributors: @unbrandedmanchester
Donate link: https://www.unbrandedmanchester.com/
Tags: Activity Log, event log, history, logger, user tracking
Requires at least: 6.8
Tested up to: 6.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Log all of your user activity and report directly into your Unbranded Portal, without bloating your database. 

== Description ==

Once you've signed up for your free Unbranded Portal account you can use this plugin to report all website activity log data straight into your account. 

By bypassing the Wordpress database we're able to ensure that your site size remains small whilst collecting all critical data from how the admin side of your site is being used.

== Upcoming Features ==

In our next update we'll be adding an plugin report to the Unbranded Portal to check if there any available security vulnerabilities in your installed plugins. 

== Installation ==

How to install the Activity Log to API Plugin

1. Upload `activity-log-api.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Log in to your Unbranded Portal account and get your API key and Project ID 
1. Navigate to the Activity Log API menu in wordpress
1. Copy and paste your API Key and Project ID into the fields
1. That's it, you're good to go, you will now see activity logs in your portal account

== Frequently Asked Questions ==

= Do I need to pay for this plugin? =

No, this is a free service that works alongside your portal account

= Can I restrict users from seeing the logs in my portal account =

Yes, just set the user permissions of each user as you wish

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Initial commit of plugin

== External services ==
This plugin connects to an API to to submit your updates to the Unbranded Portal for all logged in users.
The following information is sent with each post to the API:
- Current Username and Email address
- API Key and Portal Project ID
- Your Website URL
- The event that has taken place, and details of what that even was. Where a change was made, it will show the previous and new values.

The following actions are currently tracked by the plugin:
- When a plugin is added, updated, activated or deactivated
- When user is added, changed or deleted
- When a new post is created, updated or deleted
- When site options like, permalinks, site name, discourage search engines are changed

[Terms of Use](https://www.unbrandedmanchester.com/portal-connector/terms-of-service/)
[Privacy Policy](https://www.unbrandedmanchester.com/portal-connector/privacy/)
