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


[color=green]Basic guidelines to get you started:[/color]
[list]
[li]Always have a playlist available (create one if necessary)[/li]
[li]Assign your playlists their file folder[/li]
[li]Assign your playlists to designated usergroups (each usergroup can only be assigned to one playlist)[/li]
[li]The popup will not work unless you assign membergroups to playlists (this includes Admins!)[/li]
[li]Experiment with other playlist settings until its appearance is satisfactory[/li] 
[li]Adjust Premiumbeat settings to have the override use an opted playlist[/li]
[li]Adjust Premiumbeat settings to enable/disable the playlink and/or drop-down buttons[/li]
[li]Adjust each membergroup its Premiumbeat permissions to allow access to various functions[/li]
[li]Files can be uploaded showing Anonymous and/or the username of the uploader[/li]
[li]Files that are manually uploaded to the playlist folder via ftp are anonymous with no n/a date[/li]
[li]Admins have permission to do all functions[/li]
[li]Users can only delete their own files if the permission is set[/li]
[li]Users can only change their own anonymous/name for files[/li]
[li]This player only accepts mp3 file types - no others for this version[/li]
[li]PHP 5.3 or more advanced is necessary on your server to use this modification[/li]
[li]Please read the recommended requirements shown below[/li]
[li]Please read the license and abide by its guidelines else do not use this modification[/li]
[/list]

[hr]

Changelog:

Version 2.0.1
! Fixed popup width/height (block and/or playlist settings)
! Fixed background issue for wide/tall popup player (beyond 250px)
+ Included multiple playlist popup code
+ X & Y coordinates for popup positioning (general setting applies to all playlists)


Version 2.0 
! Installation edited for SMF 1.1x & SMF 2.0x (+ subsequent updates)
! All check boxes now reflect their current setting on page load
! Character case for MP3 file type
! Folder name filter edited to allow spaces
! Increased max length for mp3 description
! Uninstall fixed for both SMF versions
! Popup player position corrected
! Hidden overflow for popup in all browsers
! Confirmation for any delete options
! Added cannot_ ... language variables
! isAllowedTo permission checks in templates only (allowedTo in source files where applicable) 
! Fixed playlist color code option
! Premiumbeat tables adjusted to common collation & table type using existing database settings
! Fixed uploader & date in upload/download template
! Fixed toggle of anonymous/user
! Fixed users only able to delete own files (Admin can delete all)
! Fixed page refreshing to update file list after adding/deleting files
! Opting playlist for override also auto adjusts file (if entered in playlist edit template)
! No longer uploads duplicate mp3 file name regardless of encrypted prefix (needs PHP 5.3+)
! Added warnings to add playlists where none exist
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
! Patched mp3 list deletion issue
! Removed url filter

Version 1.6 
! Access to the my_music folder via url is denied. 
! Access to the playlst file via url is denied.

Version 1.5 
! Edited for proper admin settings template

Version 1.4 
+ Debut public release
+ Added SMF 1.0 support
+ Added multiple playlsts - seperate blocks can play specific mp3's.
+ Added more adjustments for display in the settings menu (can be overidden in any block)
+ Playlist filtered through settings table
+ Reads mp3 and descriptions from database entries in admin-settings menu
+ Added ascending and shuffle
+ Changed xml file to php file

[hr]

Recommended requirements:

Server: PHP 5.3+, HTML5+, MYSQL 5.0+, json support enabled (v1.2+)
Browsers: IE 7+, Firefox 3.6+, Safari 5+, Chrome 5+, Opera 10.5+
Browser Add-ons: javascript 1.8+, Adobe Flash Player 10+

Recommended Portal for SMF:
SimplePortal (http://www.simpleportal.net)

[hr] 

PHP Portal Block Code:
Adjust $playlist_id = 0 to your opted playlist number
[code]
/* Set Playlist ID Number  - default is 1  */
/* MP3's using playlist number 99 will play on all blocks... Other mp3 playlist settings are block specific  */
$playlist_id = 1;

global $scripturl, $smcFunc, $boardurl;

/* Adjustable parameters  */
$width = 800;
$height = 215;
$skin = '0000FF';
$skinType = 5;
$autoplay = 'yes';   /*  yes = autoplay,    no = manual play  */

$columns_settings = array('height', 'width', 'autoplay', 'skin');
$a = check_block_playlist($playlist_id);
if ($a == true)
{
	$result = $smcFunc['db_query']('', "SELECT myplaylist, height, width, autoplay, type, skin, skin_type FROM {db_prefix}premiumbeat_settings WHERE (myplaylist = {$playlist_id}) LIMIT 1");
	while ($val = $smcFunc['db_fetch_assoc']($result))
	{	
		if ((empty($val['myplaylist'])) || (int)$val['myplaylist'] < 1)
			continue;	 
		
		foreach ($columns_settings as $sets)
		{
			if (empty($val[$sets])) 
				$val[$sets] = 0;					 	
		}
		$autoplay = 'no';
		if($val['autoplay'] == 1)
			$autoplay = 'yes';
	
		if (!$width)
			$width = $val['width'];
		if (!$height)	
			$height = $val['height'];
		if (!$skin)	
			$skin = $val['skin'];
		if (!$skinType)	
			$skinType = $val['skin_type'];		                       				
	}
	$smcFunc['db_free_result']($result);	

}

$_SESSION['premiumbeat_bbc'] = false;
$_SESSION['customMusic_checker'] = true;
$_SESSION['premiumbeat_new'] = true;
if($playlist_id < 0) {$playlist_id = 999;}
$_SESSION['playlist_id'] = $playlist_id;

echo'<div id="premiumbeat_player" style="position:relative;margin:auto;text-align:center;"><script type="text/javascript" src="my_music/swfobject.js"></script>
     
<div id="flashPlayer">
Mp3 Player Malfunction
</div>
<script type="text/javascript">
	var so = new SWFObject("my_music/playerMultipleList.swf", "premiumbeat-player", "'.$width.'", "'.$height.'", "9.0.0"); 
	so.addVariable("autoPlay","'.$autoplay.'") 
	so.addVariable("overColor","'.$skin.'")          
	so.addVariable("playerSkin", "'.$skinType.'")        
	so.addVariable("playlistPath","',$scripturl,'?action=customMusic", "SESSION")        
        so.addParam("bgcolor", "ffffff");        
        so.useExpressInstall("expressinstall.swf"); 
        so.addVariable("getURL","") 
	so.write("flashPlayer");
</script></div>';


function check_block_playlist($play)
{
	global $smcFunc;		
	$result2 = $smcFunc['db_query']('', "SELECT myplaylist FROM {db_prefix}premiumbeat_settings WHERE myplaylist = {$play} LIMIT 1");
	$result3 = $smcFunc['db_num_rows']($result2);	
	$smcFunc['db_free_result']($result2);		
	if ($result3 > 0) 
		return true;

	return false;
}
echo '<script type="text/javascript">
// when the document has loaded, start the premiumbeat player
window.onload = function () {
    (function () {
        var a = document.getElementById("premiumbeat_player");
        if (a) {
            // Player has loaded!
        }
        else {
            setTimeout(arguments.callee, 50);
        }
    }());
};
</script>';
[/code]

[hr]

PHP Block - Popup Player Code:
(Adjust playlist = 0; to your opted playlist number)
[code]
/* Adjustable variables: Set playlist ID + match width & height to the proper playlist settings */
/* Setting the playlist ID# to 0 or 999 will allow this block to use playlist permissions */

$playlist ='0';
$width = '220';
$height = '230';

/* Set $position to false to have the button appear in the block and set block body style accordingly  */
/* For $position = false put the following in your body style...  text-align:center;vertical-align:middle;overflow:hidden;  */
$position = 'fixed';

if ($position == 'fixed')
	$style = "position:fixed;top:0px;right:0px;";
else
	$style="text-align:center";


$url = 'index.php?action=customMusicPopup;playlist='.$playlist.';';
$icon = '<img src ="http://i272.photobucket.com/albums/jj187/ginger_face/mymusic.gif" style="'.$style.'" />';


/*  Do not edit below this line  */

echo '	<script type="text/javascript">
	<!--
	var WindowObjectReference = null; 	
	function PremiumbeatPopup(strURL,strWidth,strHeight)
	{
		
		var strOptions="";
		var strType = "console";
		if (strType=="console") strOptions="resizable,height="+strHeight+",width="+strWidth;
		if (strType=="fixed") strOptions="status,height="+strHeight+",width="+strWidth;
		if (strType=="elastic") strOptions="toolbar,menubar,scrollbars,resizable,location,height="+strHeight+",width="+strWidth;
		WindowObjectReference = window.open(strURL, "newWin", strOptions);
		WindowObjectReference.focus();
	}
	//-->
	</script>';

$pop = '<a href="javascript:void(0)" onclick="PremiumbeatPopup(\''.$url.'\',\''.$width.'\',\''.$height.'\')">'.$icon.'</a>';

$_SESSION['premiumbeat_bbc'] = false;
$_SESSION['customMusic_checker'] = true;
$_SESSION['premiumbeat_new'] = true;
echo $pop;

[/code]

[hr]

Multiple Playlist Popup Player PHP Code:
[code]
/* Premiumbeat PHP ~ popup block with multiple playlist dropdown option */

/* Edit your image location or make it false not to display it */
$image = false;
// $image = 'http://i272.photobucket.com/albums/jj187/ginger_face/mymusic.gif';
$width = '450';
$height = '230';
$autoplay = 'yes'; 
$title = 'Select Playlist';

/*
 *  Set $position to false to have the button appear in the block and set block body style accordingly  
 *  For $position = false put the following in your body style...  text-align:center;vertical-align:middle;overflow:hidden;
 */
 
$position = 'fixed';

/* 
 * Do not edit below this comment
*/

global $smcFunc, $scripturl, $settings;

$datum = array('myplaylist','title','equip');
$num = 0;
$drop = '';
$_SESSION['premiumbeat_bbc'] = false;
$_SESSION['customMusic_checker'] = true;
$_SESSION['premiumbeat_width'] = $width;
$_SESSION['premiumbeat_height'] = $height;
$_SESSION['premiumbeat_autoplay'] = $autoplay;
$_SESSION['premiumbeat_new'] = true;

if ($position == 'fixed')
	$style = "position:fixed;top:0px;right:2px;";
else
	$style="text-align:center;";

$result = $smcFunc['db_query']('', "SELECT myplaylist, title, equip						
                                    FROM {db_prefix}premiumbeat_settings
                                    WHERE equip > 0");
while ($val = $smcFunc['db_fetch_assoc']($result))
{
	foreach ($datum as $data)
		$playlists[$num][$data] = $val[$data];
	
	$num++;
}			
$smcFunc['db_free_result']($result);

if (empty($playlists))
	return false;

foreach ($playlists as $playlist)
	$drop .= '<option value="' . $playlist['myplaylist'] . '">' . $playlist['title'] . '</option>'; 

$playlist = $playlists[0]['myplaylist'];
$Xurl = 'index.php?action=customMusicPopup;playlist=989;playsong=playlist=';
$url = $Xurl . $playlist.';';

if (!$image)
	$icon = false;
elseif ($position == 'fixed')
	$icon = '<img src ="'.$image.'" alt="" style="position:relative;height:30px;width:59px;float:right;right:2px;" />';
else
	$icon = '<span style="display: block;margin-left: auto;margin-right: auto;"><img src ="'.$image.'" alt="" style="position:relative;height:30px;width:59px;" /></span>';
    
echo '
<script type="text/javascript"><!--
	var WindowObjectReference = null; 	
	function PremiumbeatPopup(strURL,strWidth,strHeight)
	{		
		var strOptions="";
		var strType = "console";
		if (strType=="console") strOptions="resizable,height="+strHeight+",width="+strWidth;
		if (strType=="fixed") strOptions="status,height="+strHeight+",width="+strWidth;
		if (strType=="elastic") strOptions="toolbar,menubar,scrollbars,resizable,location,height="+strHeight+",width="+strWidth;
		WindowObjectReference = window.open(strURL, "newWin", strOptions);
		WindowObjectReference.focus();
	}
//--></script>';

if ($icon)
	$pop = '<a href="javascript:void(0)" onclick="PremiumbeatPopup(\''.$url.'\',\''.$width.'\',\''.$height.'\')">'.$icon.'</a>';
else
	$pop = false;

echo '
<ul id="someID" style="list-style-type:none;'.$style.'">
	<li>'
		, $pop, '<br />
	</li>
	<li>
		<select id="C4" dir="rtl" onchange="PremiumbeatPopup(\''.$Xurl.'\' + this.options[this.selectedIndex].value,\''.$width.'\',\''.$height.'\')">
			<option disabled=true value="0" title="tooltip" selected=true>
				',$title,'
			</option>
			',$drop,'
		</select>
	</li>			
</ul>';
[/code]
[hr]

This version has been modified to work with SMF 2.0x Forums.

This SMF package falls under the [url=http://creativecommons.org/licenses/by-nd/3.0/]Creative Commons - Attribution No Derivatives License (by-nd) 3.0[/url].
All author contributions noted below fall under their own respected licenses. The above noted license allows such conditions, please click on its link for specifics. 

Development Credits:

[table]
[tr][td]Premiumbeat for SMF[/td][td]-[/td][td]Underdog (http://askusaquestion.net)[/td][td] [url=http://creativecommons.org/licenses/by-nd/3.0/]Creative Commons License - Attribution No Derivatives[/url][/td][/tr]
[tr][td]Premiumbeat Flash Music Player[/td][td]-[/td][td]Gilles & Francois Arbour (http://premiumbeat.com)[/td][td] [url=http://premiumbeat.com]Permission was granted directly from the authors[/url][/td][/tr]
[tr][td]jQuery AXuploader[/td][td]-[/td][td]Alban Xhaferllari (http://www.albanx.com)[/td][td] [url=http://jquery.org/license]Dual licensed under the MIT or GPL Version 2 licenses[/url][/td][/tr]
[tr][td]SMF Documentation[/td][td]-[/td][td]Skhilled (http://www.skhilled.com/smf/)[/td][td] [url=http://www.gnu.org/copyleft/fdl.html]GNU Free Documentation License[/url][/td][/tr]
[/table]

Disclaimers:

  This modification is intended to be used for legal purposes only! The *Premiumbeat for SMF* application package is intended for use with mp3 files under licenses that allow non-profit distribution of copyrighted or non-copyrighted works (ie. Creative Commons license) and their respected terms/conditions (ie. Attribution No Derivatives).  The author's involved in this project are in no way responsible for the end-user's possible abuse of copyright laws & do not condone such practices.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Please read all other license agreements contained within this package. 