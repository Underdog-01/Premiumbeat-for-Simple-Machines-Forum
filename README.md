Premiumbeat-for-Simple-Machines-Forums
======================================

SMF 2.0x - Premiumbeat Mp3 Player
Mp3 Platform for SMF forums c/o Underdog @ http://askusaquestion.net 
		
This will install a flash mp3 player onto your SMF forum & will work with a php block/module and/or individually
from a permission based forum link producing a popup.
For an invisible player - set height and width to 0 in settings (or your block/module code)
as well as disabling the title and body of your block/module.
Options include local or outside sourced url inputs, auto-loading folders, upload/download and various membergroup permissions. 
		
Mp3's using Playlist #99 will play for every playlist and all other mp3 playlists are block/popup specific.

Recently tested on SMF 2.0.4 with no issues.
The Premiumbeat v2.0 stable version is only available for the SMF2.0x branch.
Support for SMF 1.1x has now been dropped although the beta version will be left available for it.
Thank you for opting to use this software package & enjoy.

Basic guidelines to get you started:

+ Always have a playlist available (create one if necessary)
+ Assign your playlists their file folder
+ Assign your playlists to designated usergroups (each usergroup can only be assigned to one playlist)
+ The popup will not work unless you assign membergroups to playlists (this includes Admins!)
+ Experiment with other playlist settings until its appearance is satisfactory
+ Adjust Premiumbeat settings to have the override use an opted playlist
+ Adjust Premiumbeat settings to enable/disable the playlink and/or drop-down buttons
+ Adjust each membergroup its Premiumbeat permissions to allow access to various functions
+ Files can be uploaded showing Anonymous and/or the username of the uploader
+ Files that are manually uploaded to the playlist folder via ftp are anonymous with no n/a date
+ Admins have permission to do all functions
+ Users can only delete their own files if the permission is set
+ Users can only change their own anonymous/name for files
+ This player only accepts mp3 file types - no others for this version
+ PHP 5.3 or more advanced is necessary on your server to use this modification
+ Please read the recommended requirements shown below
+ Please read the license and abide by its guidelines else do not use this modification




Changelog:

Version 2.0.1
+ Fixed popup width/height (block and/or playlist settings)
+ Fixed background issue for wide/tall popup player (beyond 250px)
+ Included multiple playlist popup code
+ X & Y coordinates for popup positioning (general setting applies to all playlists)

Version 2.0 
+ Installation edited for SMF 1.1x & SMF 2.0x (+ subsequent updates)
+ All check boxes now reflect their current setting on page load
+ Character case for MP3 file type
+ Folder name filter edited to allow spaces
+ Increased max length for mp3 description
+ Uninstall fixed for both SMF versions
+ Popup player position corrected
+ Hidden overflow for popup in all browsers
+ Confirmation for any delete options
+ Added cannot_ ... language variables
+ isAllowedTo permission checks in templates only (allowedTo in source files where applicable) 
+ Fixed playlist color code option
+ Premiumbeat tables adjusted to common collation & table type using existing database settings
+ Fixed uploader & date in upload/download template
+ Fixed toggle of anonymous/user
+ Fixed users only able to delete own files (Admin can delete all)
+ Fixed page refreshing to update file list after adding/deleting files
+ Opting playlist for override also auto adjusts file (if entered in playlist edit template)
+ No longer uploads duplicate mp3 file name regardless of encrypted prefix (needs PHP 5.3+)
+ Added warnings to add playlists where none exist
+ Changed bbc image to 20x20 plain graphic
+ Options are locked when no playlists are available
+ Option to manipulate local mp3 filenames (player does not allow some characters)
+ Play/test button for playlists and mp3 entries
+ Download mp3 files option (as compressed zip archive) 
+ Upload mp3 files function & template 
+ Common playlist now set to Playlist#99 (previously Playlist#1)
+ BBC execute playlist (if permission to use playlist or if playlist is available for upload)
+ Permissions to enable the upload function
+ Option to allow playlist upload/download access 
+ Auto-Load folder per playlist (changed from single input setting)
+ Edited display of all Premiumbeat admin templates
+ Pages (javascript) for the templates
+ Playlist titles
+ All playlist settings in configuration now under premiumbeat_settings permission
+ Added playlist type option
+ Mp3 list now ordered by Playlist#
+ Mp3 list enable & delete only permitted by user who entered it and/or premiumbeat settings permission
+ Drop-down (playlist names) in mp3 creation/editing for existing playlists and Premiumbeat Settings
+ Playlists can now be manually added
+ Encrypted mp3 filenames for added security 
+ Added premiumbeat button to access premiumbeat settings/mp3 db directly
+ License information available from the mp3 db menu 
+ Added disclaimer notices  
+ Documentation/intructions now falls under the NU Free Documentation License 
+ Premiumbeat for SMF now falls under the Creative Commons - Attribution No Derivatives License (by-nd) 3.0 

Version 1.9 
+ Filters for all user inputs and mysql queries
+ Unique permission settings for forum link
+ Permissions to view/use popup link and/or bbcode
+ Permissions to moderate configuration and/or settings
+ Auto-load folder playlist setting 
+ Added bbcode to play mp3 url(s)
+ Added button/link in forum for a popup
+ Added forum settings source code + template

Version 1.8 
+ Edited for SMF 1.1.13 installation 
+ HTML block codes for popup player
+ PHP Portal block code has been edited for this version
+ Specific settings for each playlist
	(old versions used settings table - this installation will transfer all values)
+ Uses own mysql db tables/columns 
+ Major rewrite of code

Version 1.71 
+ Updated install file for SMF 1.1.12

Version 1.7 
+ Patched mp3 list deletion issue
+ Removed url filter

Version 1.6 
+ Access to the my_music folder via url is denied. 
+ Access to the playlst file via url is denied.

Version 1.5 
+ Edited for proper admin settings template

Version 1.4 
+ Debut public release
+ Added SMF 1.0 support
+ Added multiple playlsts - seperate blocks can play specific mp3's.
+ Added more adjustments for display in the settings menu (can be overidden in any block)
+ Playlist filtered through settings table
+ Reads mp3 and descriptions from database entries in admin-settings menu
+ Added ascending and shuffle
+ Changed xml file to php file
 

Recommended requirements:

Server: PHP 5.3+, MYSQL 5.0+, json support enabled (v1.2+)
Browsers: IE 7+, Firefox 3.6+, Safari 5+, Chrome 5+, Opera 10.5+
Browser Add-ons: javascript 1.8+, Adobe Flash Player 10+, HTML5+ Capability

Recommended Portal for SMF:
SimplePortal (http://www.simpleportal.net)

This version has been modified to work with SMF 2.0x Forums.

This SMF package falls under the Creative Commons - Attribution No Derivatives License (by-nd) 3.0 (http://creativecommons.org/licenses/by-nd/3.0/).
All author contributions noted below fall under their own respected licenses. The above noted license allows such conditions, please click on its link for specifics. 

Development Credits:

Premiumbeat for SMF - Underdog (http://askusaquestion.net) Creative Commons License - Attribution No Derivatives
Premiumbeat Flash Music Player - Gilles & Francois Arbour (http://premiumbeat.com) Permission was granted directly from the authors
jQuery AXuploader - Alban Xhaferllari (http://www.albanx.com) - Dual licensed under the MIT or GPL Version 2 licenses
SMF Documentation - Skhilled (http://docskhillz.com/docs/) GNU Free Documentation License


Disclaimers:

  This modification is intended to be used for legal purposes only! The *Premiumbeat for SMF* application package is intended for use with mp3 files under licenses that allow non-profit distribution of copyrighted or non-copyrighted works (ie. Creative Commons license) and their respected terms/conditions (ie. Attribution No Derivatives).  The author's involved in this project are in no way responsible for the end-user's possible abuse of copyright laws & do not condone such practices.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Please read all other license agreements contained within this package. 
