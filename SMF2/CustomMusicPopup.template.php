<?php
// Version: 2.0.1; premiumbeat

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
			<style type="text/css">
				html {overflow:hidden;}				
			</style>
			<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?rc3" />';	
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
	{
		if ($context['browser']['is_' . $cssfix])
			echo '
			<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';
	}	
	
	if ($context['right_to_left'])
		echo '
			<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';	
	echo '
			<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?rc3"></script>';
	if (@file_exists($settings['theme_url']. '/scripts/theme.js'))
		echo '
			<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?rc3"></script>';	
				
	echo '	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
			<meta name="description" content="Premiumbeat for SMF Forums" />
			<meta name="keywords" content="premiumbeat, smf" />
			<title>',$title,'</title>';

	// No indexing by robots
	if (!empty($context['robot_no_index']))
		echo '
			<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
			<link rel="canonical" href="', $context['canonical_url'], '" />';
	echo '		
			<script type="text/javascript">
				function premiumbeat_resize()
				{
					var innerWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
					var innerHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
					var targetWidth = ',$width,';
					var targetHeight = ',$height,';
					var targetX = ',$context['premiumbeat_axisX'],';
					var targetY = ',$context['premiumbeat_axisY'],';
					if (targetX == -1) {targetX = (screen.width / 2)-(targetWidth/2);}
					if (targetY == -1) {targetY = (screen.width / 2)-(targetWidth/2);}					
					window.resizeBy(targetWidth-innerWidth, targetHeight-innerHeight);					
					window.moveTo(targetX,targetY);
					window.top.focus;					
				}
				function premiumbeat_fitPlayer()
				{ 
					if(navigator.userAgent.toLowerCase().indexOf("chrome") > -1)
						var t = setTimeout("premiumbeat_resize()", 200);
					else
						premiumbeat_resize();
				}; 
			</script> 
		</head>';

	echo '
		<body onload="premiumbeat_fitPlayer();" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">';
	echo '
			<table border="0">
				<tr>
					<td style="border:0px;position:absolute;left:0px;top:0px;">						
						<script type="text/javascript" src="my_music/swfobject.js"></script>     
						<div id="flashPlayer">Mp3 Player Malfunction</div>
						<script type="text/javascript">
							var so = new SWFObject("my_music/playerMultipleList.swf", "premiumbeat-player", "'.$width.'", "'.$height.'", "9.0.0"); 
							so.addVariable("autoPlay","'.$autoplay.'") 
							so.addVariable("overColor","'.$skin.'")          
							so.addVariable("playerSkin", "'.$skinType.'")        
							so.addVariable("playlistPath","',$scripturl,'?action=customMusic", "SESSION")							  
							so.addParam("bgcolor", "ffffff");							
							so.useExpressInstall("expressinstall.swf"); 
							so.addVariable("getURL","javascript:window.close()") 
							so.write("flashPlayer");
							window.history.pushState("object or string", "Title", "premiumbeat");							
						</script>						
					</td>
				</tr>
			</table>
		</body>
	</html>';	
}
?>