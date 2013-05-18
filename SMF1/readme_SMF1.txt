Premiumbeat Flash Music Player @ http://premiumbeat.com
SMF 1.1x - Premiumbeat Mp3 Player
Modified to work with SMF forums c/o Underdog @ http://askusaquestion.net 
		
This will install a flash mp3 player onto your SMF forum & will work with a php block/module and/or individually from a permission based forum link producing a popup.
For an invisible player - set height and width to 0 in settings (or your block/module code) as well as disabling the title and body of your block/module.
Options include local or outside sourced url inputs, auto-loading folders, upload/download and various membergroup permissions. 
		
Mp3's using Playlist #99 will play for every playlist and all other mp3 playlists are block/popup specific.

Changelog:

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
! Premiumbeat tables adjusted to MyISAM type & common collation
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

Server: PHP 5.2+, HTML5+, MYSQL 5.0+, json support enabled (v1.2+)
Browsers: IE 7+, Firefox 3.6+, Safari 5+, Chrome 5+, Opera 10.5+
Browser Add-ons: javascript 1.8+, Adobe Flash Player 10+

Recommended Portal for SMF:
SimplePortal (http://www.simpleportal.net)

[hr] 

PHP Portal Block Code:
Adjust $playlist_id = 0 to your opted playlist number
[code]
/* Set Playlist ID Number  - default is 1  */
/* MP3's using playlist number 1 will play on all blocks... Other mp3 playlist settings are block specific  */
$playlist_id = 0;

global $scripturl, $db_prefix;

/* Adjustable parameters  */
$width = 190;
$height = 210;
$skin = '000000';
$skinType = 5;
$autoplay = 'yes';   /*  yes = autoplay,    no = manual play  */

$columns_settings = array('height', 'width', 'autoplay', 'skin', 'skin_type');
$a = check_block_playlist($playlist_id);
if ($a == true)
{
	$result = db_query("SELECT myplaylist, height, width, autoplay, type, skin, skin_type FROM {$db_prefix}premiumbeat_settings WHERE (myplaylist = {$playlist_id}) LIMIT 1",__FILE__, __LINE__);
	while ($val = mysql_fetch_assoc($result))
	{	
		if (empty($val['myplaylist']))
			continue;
	 
		if ((int)$val['myplaylist'] < 1)
			continue;
		foreach ($columns_settings as $sets)
		{
			if (empty($val[$sets])) 
				$val[$sets] = 0;					 	
		}

		$autoplay = 'no';
		if($val['autoplay'] == 1)
			$autoplay = 'yes';
	
		$width = $val['width'];
		$height = $val['height'];
		$skin = $val['skin'];   
		$skinType = (int)$val['skin_type'];                    				
	}
	mysql_free_result($result);	
}

$_SESSION['premiumbeat_bbc'] = false;
$_SESSION['customMusic_checker'] = true;
if($playlist_id < 0) 
	$playlist_id = 999;

$_SESSION['playlist_id'] = $playlist_id;

echo'<script type="text/javascript" src="my_music/swfobject.js"></script>
     
<div id="flashPlayer">
Mp3 Player Malfunction
</div>

<script type="text/javascript">
	var so = new SWFObject("my_music/playerMultipleList.swf", "mymovie", "'.$width.'", "'.$height.'", "7", "'.$skin.'"); 
	so.addVariable("autoPlay","'.$autoplay.'") 
	so.addVariable("overColor","'.$skin.'")
	so.addVariable("playerSkin", "'.$skinType.'")
	so.addVariable("playlistPath","',$scripturl,'?action=customMusic", "SESSION")
	so.write("flashPlayer");
</script>';

function check_block_playlist($play)
{
	global $db_prefix;		
	$result2 = db_query("SELECT myplaylist FROM {$db_prefix}premiumbeat_settings WHERE myplaylist = {$play} LIMIT 1", __FILE__, __LINE__);
	$result3 = mysql_num_rows($result2);	
	mysql_free_result($result2);		
	if ($result3 > 0)
		return true;

	return false;
}		
[/code]

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
if ((int)$playlist < 0)
	$playlist = 999;

echo '
<script type="text/javascript">
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
echo $pop;
[/code]

[hr]

This version has been modified to work with SMF 1.x Forums.

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

  This mod is intended to be used for legal purposes only! The *Premiumbeat for SMF* application package is intended for use with mp3 files under licenses that allow non-profit distribution of copyrighted or non-copyrighted works (ie. Creative Commons license) and their respected terms/conditions (ie. Attribution No Derivatives).  The author's involved in this project are in no way responsible for the end-user's possible abuse of copyright laws & do not condone such practices.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Please read all other license agreements contained within this package. 