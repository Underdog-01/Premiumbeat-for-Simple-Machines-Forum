<?php
// Version: 2.0; premiumbeat

/*     Main mp3 popup template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  


/* Premiumbeat mp3 display  */
// The main sub template above the content.
if (!defined('SMF'))
	die('Hacking attempt...');
	
function template_mp3_popup($height, $width, $skin, $skinType, $autoplay, $playlist_id, $title)
{
	global $context, $settings, $options, $scripturl, $txt, $boardurl;	
	$skin = ltrim($skin, '#');
	if ((int)$skinType < 1 || (int)$skinType > 5)
		$skinType = 1;
			
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
		<head>
			<style type="text/css"> html {overflow:hidden;}</style>
			<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
			<meta name="description" content="Premiumbeat for SMF Forums" />', empty($context['robot_no_index']) ? '' : '
			<meta name="robots" content="noindex" />', '
			<meta name="keywords" content="PHP, MySQL, bulletin, board, free, open, source, smf, simple, machines, forum" />
			<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js?fin11"></script>
			<title>',$title,'</title>			
			<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css?fin11" />
			<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/print.css?fin11" media="print" />';
	if ($context['browser']['needs_size_fix'])
		echo '
			<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/fonts-compat.css" />';
	
	// Robots can not index
	if (!empty($context['robot_no_index']))
		echo '
			<meta name="robots" content="noindex" />';	
	
	echo '
		</head>';

	echo '
		<body>
			<table border="0">
				<tr>
					<td style="border:0px;position:absolute;left:0px;top:0px;">
						<script type="text/javascript" src="my_music/swfobject.js"></script>     
						<div id="flashPlayer">Mp3 Player Malfunction</div>
						<script type="text/javascript">
   							var so = new SWFObject("',$boardurl,'/my_music/playerMultipleList.swf", "mymovie", "'.$width.'", "'.$height.'", "7", "'.$skin.'");
   							so.addVariable("autoPlay","'.$autoplay.'")
							so.addVariable("overColor","'.$skin.'")
							so.addVariable("playerSkin", "'.$skinType.'")
   							so.addVariable("playlistPath","',$scripturl,'?action=customMusic", "SESSION")
   							so.write("flashPlayer");
						</script>
					</td>
				</tr>
			</table>
		</body>
	</html>';	
}
?>