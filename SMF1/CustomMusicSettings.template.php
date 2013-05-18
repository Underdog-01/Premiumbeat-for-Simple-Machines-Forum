<?php
// Version: 2.0; premiumbeat

/*     Main admin settings template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  

/* Premiumbeat main settings display  */
function template_customMusic_settings()
{
	global $txt, $scripturl, $context, $boardurl, $settings;
 	
 	isAllowedTo('premiumbeat_settings');
	/*   Display mp3 settings  */	
	echo '<script type="text/javascript" src="my_music/js/premiumbeat-pages.js"></script>
	<script type="text/javascript">
		<!--
			window.onload=regenerate2
		//-->
	</script>';
	echo $context['premiumbeat_confirm'], '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="playlistselect" id="playlistselect">
		<h4 class="tborder titlebg catbg3" style="text-align:center;border-style:solid;border-width:2px;border-collapse:collapse;">' . $txt['customMusic_tabtitle3'] . '</h4>
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
		<table border="0" cellpadding="6" cellspacing="0" id="mp3List2" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">
			<tr class="catbg" style="font-size:x-small;">
					<td class="catbg" style="text-align:center;border:0px;height: 23px;width:6%;">&nbsp;</td>
					<td class="catbg" style="border:0px;text-align:left;width:20%;">' . $txt['customMusic_playlist'] . '</td>
					<td class="catbg" style="border:0px;text-align:center;width:12%;">' . $txt['premiumbeat_height'] . '</td>
					<td class="catbg" style="border:0px;text-align:center;width:12%;">' . $txt['premiumbeat_width'] . '</td>
					<td class="catbg" style="border:0px;text-align:center;width:15%;">' . $txt['premiumbeat_autoplay'] . '</td>					
					<td class="catbg" style="border:0px;text-align:center;width:15%;">' . $txt['premiumbeat_type'] . '</td>
					<td class="catbg" style="text-align:center;border:0px;height: 23px;line-height: 23px;width:15%;">' . $txt['premiumbeat_playlist_equip'] . '</td>	
					<td class="catbg" style="text-align:center;border:0px;height: 23px;line-height: 23px;width:5%;">' . $txt['customMusic_delete'] . '</td>						
			</tr>';
		
	foreach ($context['sets'] as $myval)
	{		
		$i = $myval['myplaylist'];	
		$type = $txt['premiumbeat_shuffle'];
		$autoplay = $txt['premiumbeat_disabled'];
		$equip = $txt['premiumbeat_disabled'];
		if ($myval['type'] == 1) 
			$type = $txt['premiumbeat_asc'];
			
		if ($myval['autoplay'] == 1)
			$autoplay = $txt['premiumbeat_enabled'];
			
		if ($myval['equip'] == 1)
			$equip = $txt['premiumbeat_enabled'] . '&nbsp;';
			
		$title = ($myval['title']) ? $myval['title'] : $txt['customMusic_playlist_num'] . $i;
		$title = strlen($title) >= 30 ? $title = substr($title,0,27).'...': $title;	
		$popuplist = '<a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong=http://playlist='.$i.';\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);"><img src="'.$boardurl.'/my_music/PlayButton.gif" alt="'.$txt['music_fileplay'].'" title="'.$txt['premiumbeat_playlist_test'].'" style="height:16px;width:16px;" /></a>';			
		echo '
			<tr>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;white-space:nowrap;width:6%;">
					<span style="float:left;">
						<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat;queryPlaylist=' . $i . ';" title="' . $txt['customMusic_sort'] . $i .'">
							<img src="'.$boardurl.'/my_music/playlist.png" alt="#" />
						</a>
					</span>	
					<span style="float:right;">		
						', $popuplist,'
					</span>	
				</td>			
				<td class="windowbg" style="text-align:left;vertical-align:text-top;border-bottom: 1px solid;white-space:nowrap;width:20%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">' . $title . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:12%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">' . $myval['height'] . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:12%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$myval['width'] . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:15%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$autoplay . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:15%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$type . '</a>
				</td>
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:14%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;' . 'playlist=' . $i . ';sesc=' . $context['session_id'] . '" onMouseover="changetext(content_'.$i.')" onmouseout="changetext(nocontent)" title="'.$txt['customMusic_playlist_num'] . $i .'">'.$equip . '</a>
				</td>	
				<td class="windowbg" style="text-align:center;vertical-align:text-top;border-bottom: 1px solid;width:5%;">
					<input type="checkbox" name="deletePL[]" value="' . $i . '" class="check" '. (allowedTo('premiumbeat_config') ? '' : ' disabled="disabled"'). ' />
				</td>				
			</tr>';
	}							
	echo'
		</table>			
		<br />
		<span style="float:left;" id="mp3PagePosition2">&nbsp;</span>
		<span style="float:right;">
			<input type="button" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' onclick="var confirm = confirmSubmit();	if (confirm) {this.form.submit();}" />
		</span>						
		<br /><br />
		<h4 style="text-align:center;">
			<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat;sesc=' . $context['session_id'] . ';">'.$txt['premiumbeat_return'] . '</a>
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