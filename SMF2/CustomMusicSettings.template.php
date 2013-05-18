<?php
// Version: 2.0.1; premiumbeat

/*     Main admin settings template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  

/* Premiumbeat main settings display  */
function template_customMusic_settings()
{
	global $txt, $scripturl, $context, $boardurl, $settings;
	isAllowedTo('premiumbeat_settings'); 
	
	echo '<script type="text/javascript" src="my_music/js/premiumbeat-pages.js"></script>
	<script type="text/javascript">
		<!--
			window.onload=regenerate2
		//-->
	</script>',
	$context['premiumbeat_confirm'], '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="playlistselect" id="playlistselect">		
		<div class="cat_bar"><h4 class="catbg" style="text-align:center;">' . $txt['customMusic_tabtitle3'] . '</h4></div>
		<br /><br />';
	/*  Fill the javascript array prior to the table  */ 
	foreach ($context['sets'] as $myval)
	{		
		$i = $myval['myplaylist'];
		$title = ($myval['title']) ? $myval['title'] : $txt['customMusic_playlist_num'] . $i;
		$title = strlen($title) >= 30 ? $title = substr($title,0,27).'...': $title;		
		if ($myval['autofile'] != false)
			echo '<script type="text/javascript">
					content_'.$i.' = "',$title, '&nbsp;' , $txt['premiumbeat_playlist_perm1'],'<strong>',$context['id_group'][$i],'</strong><br />',$txt['premiumbeat_playlist_file'], '&nbsp;<strong>', $myval['autofile'],'</strong>";
					nocontent = "&nbsp;";	
				</script>';	
		else
			echo '<script type="text/javascript">
					content_'.$i.' = "',$title, '&nbsp;' , $txt['premiumbeat_playlist_perm1'],'<strong>',$context['id_group'][$i],'</strong>";
					nocontent = "&nbsp;";
				</script>';				
	} 
	/*  Main settings -  hyperlinks  */ 
	echo '
		<table border="0" cellpadding="6" cellspacing="0" width="100%" id="mp3List2">
			<tr class="titlebg" style="font-size:x-small;">
				<td class="catbg" style="text-align:center;border:0px;border-bottom: thin outset;height: 23px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 0 -160px;width:6%;">&nbsp;</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;text-align:left;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;width:20%;">' . $txt['customMusic_playlist'] . '</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;text-align:center;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;width:12%;">' . $txt['premiumbeat_height'] . '</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;text-align:center;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;width:12%;">' . $txt['premiumbeat_width'] . '</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;text-align:center;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;width:15%;">' . $txt['premiumbeat_autoplay'] . '</td>					
				<td class="catbg" style="border:0px;border-bottom: thin outset;text-align:center;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;width:15%;">' . $txt['premiumbeat_type'] . '</td>
				<td class="catbg" style="text-align:center;border:0px;border-bottom: thin outset;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 50% -160px;height: 23px;line-height: 23px;width:15%;">' . $txt['premiumbeat_playlist_equip'] . '</td>
				<td class="catbg" style="text-align:center;border:0px;border-bottom: thin outset;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 100% -160px;height: 23px;line-height: 23px;width:5%;">' . $txt['customMusic_delete'] . '</td>						
			</tr>';

	foreach ($context['sets'] as $myval)
	{		
		$i = $myval['myplaylist'];	
		$type = $txt['premiumbeat_shuffle'];
		$autoplay = $txt['premiumbeat_disabled'];
		$equip = $txt['premiumbeat_disabled'];
		if ($myval['type'] == 1) {$type = $txt['premiumbeat_asc'];}	
		if ($myval['autoplay'] == 1) {$autoplay = $txt['premiumbeat_enabled'];}
		if ($myval['equip'] == 1) {$equip = $txt['premiumbeat_enabled'];}
		$title = ($myval['title']) ? $myval['title'] : $txt['customMusic_playlist_num'] . $i;			
		$title = strlen($title) >= 30 ? $title = substr($title,0,27).'...': $title;			
		$popuplist = '<a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong=http://playlist='.$i.';\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);"><img src="'.$boardurl.'/my_music/PlayButton.gif" alt="'.$txt['music_fileplay'].'" title="'.$txt['premiumbeat_playlist_test'].'" style="height:16px;width:16px;" /></a>';										
		echo '
			<tr>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;white-space:nowrap;width:6%;">
					<span style="float:left;">
						<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=BrowsePremiumbeat;' . 'queryPlaylist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . ';' . '" title="' . $txt['customMusic_sort'] . $i .'"><img src="'.$boardurl.'/my_music/playlist.png" alt="#" /></a>
					</span>	
					<span style="float:right;">		
						', $popuplist,'
					</span>	
				</td>
				<td class="windowbg" style="text-align:left;vertical-align:text-top;border-bottom: 1px solid;white-space:nowrap;width:20%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">' . $title . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:12%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">' . $myval['height'] . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:12%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$myval['width'] . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:15%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$autoplay . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:15%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$type . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:14%;">
					<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$equip . '</a>
				</td>	
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:5%;">
					<input type="checkbox" name="deletePL[]" value="' . $i . '" class="check" '. (allowedTo('premiumbeat_config') ? '' : ' disabled="disabled"'). ' />
				</td>			
			</tr>';						
	}				
	echo'
		</table>						
		<br />
		<span id="mp3PagePosition2" style="float:left;"></span><span style="float:right;">
			<input type="button" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' onclick="var confirm = confirmSubmit(); if (confirm) {this.form.submit();}" />
		</span>			
		<br /><br />
		<h4 style="text-align:center;">
			<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=BrowsePremiumbeat;sesc=' . $context['session_id'] . ';">'.$txt['premiumbeat_return'] . '</a>
		</h4>			
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />		
	</form>', $context['musicAdmin_pages2'];	
	
	echo '
	<div id="d1" style="width:200;height:200;visibility:hide;">
		<div id="d2" style="width:200;height:200;">
			<div id="descriptions" style="text-align:center;font-face:verdana;font-size:small;">&nbsp;</div>
		</div>
	</div>';
}
/*  END - Main Custom Music Settings Template  */
?>