# Jewel CMS changelog

## 1.0.0

Initial fork of Injader 2.5.0.

Changes since then:

* New: Revamped installer
* New: Revamped Control Panel and navigation
* New: Manual editing of content URLs
* New: Autogeneration of URLs
* New: Block duplicate URLs
* New: Allow articles with no category
* New: Option to use Disqus comments
* New: Themes now use Twig
* New: Archives page can now be customised
* New: Login with email instead of username
* New: Introduced Bootstrap for public-facing themes and for the Control Panel
* New: Introduced many new helper functions for themes
* New: Replaced TinyMCE with CKEditor
* New: Ability to theme the Control Panel (work-in-progress)
* Dependencies: Added Composer
* Dependencies: Removed Twig from repo; now included via Composer
* Dependencies: Added Doctrine via Composer
* Security: Replacing MD5 with BCRYPT
* SEO: Added canonical URL to category and article pages
* SEO: Added prev/next URLs to category pages
* Maintenance: Moved sitemap URL to the Control Panel dashboard
* Maintenance: Removed standard comments - use Disqus instead
* Maintenance: Removed navigation types - areas now have one level only
* Maintenance: Removed ?loggedin=1 from URLs to avoid multiple URLs from being shared
* Maintenance: Removed setting: Allow password changes
* Maintenance: Removed setting: Allow password resets
* Maintenance: Removed setting: Lock system (this will be reworked and added at a later date)
* Maintenance: Removed setting: Feedburner URL
* Maintenance: Removed setting: Favicon (the custom header can be used for this)
* Maintenance: Allowed site description field to be left blank
* Maintenance: Moved setting from Control Panel to config file: Log file row limit
* Maintenance: Moved setting from Control Panel to config file: Control Panel page count
* Maintenance: Cleaned up path constants and removed SystemDirs.php
* Maintenance: Fixed various fatal errors so you get a nicer message if things go wrong
* Code: Major framework changes and code cleanup (ongoing)
* Code: RSS feeds now use full headers
* Code: Renamed all database tables
