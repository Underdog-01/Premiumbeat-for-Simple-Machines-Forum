<?php
// Version: 2.0; premiumbeat

/*     Main admin settings template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  

/* Premiumbeat mp3 display  */
function template_customMusic_page()
{
global $txt, $scripturl, $context, $boardurl, $settings;
isAllowedTo('premiumbeat_config'); 
/*   Display mp3 settings list  */	
	echo '<script type="text/javascript" src="my_music/js/premiumbeat-pages.js"></script>', $context['check_all'], $context['premiumbeat_confirm'], '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="mysongs">
		<h4 cclass="tborder titlebg catbg3" style="text-align:center;border-style:solid;border-width:2px;border-collapse:collapse;">' . $context['settings_title'] . '</h4>
		<br /><br />
		<table border="0" cellspacing="0" cellpadding="4" id="mp3List" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">
			<tr class="catbg" style="font-size:x-small;">
				<td class="catbg" style="border:0px;width:15%;text-align:left;">
					' . $txt['customMusic_trackid'] . '
				</td>
				<td class="catbg" style="border:0px;text-align:left;width:30%;" colspan="3">' . $txt['customMusic_playlist'] . '</td>
				<td class="catbg" style="border:0px;text-align:left;width:25%;">' . $txt['customMusic_trackname'] . '</td>					
				<td class="catbg" style="border:0px;text-align:center;width:20%;" colspan="2">' . $txt['customMusic_enable'] . '</td>					
					<td class="catbg" style="border:0px;text-align:center;height: 23px;width:10%;">' . $txt['customMusic_delete'] . '</td>						
			</tr>';

    $i = 1;
	$tag = 0;
	foreach ($context['tag'] as $song)
	{
		$tag = $context['tag'][$i];
		$descript = $context['description'][$i];
		if(!empty($context['playlist'][$i]))
    		$play = ($context['playlist'][$i]);
		else 
			$play = '1';
		
		$popupsong = '<a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong='.$context['url'][$i].';\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);"><img src="'.$boardurl.'/my_music/PlayButton.gif" alt="'.$txt['music_fileplay'].'" title="'.$txt['music_fileplay'].'" style="height:16px;width:16px;" /></a>';	
		$status = $context['status'][$i];	
		$title = ($context['title'][$i]) ? $context['title'][$i] : $txt['customMusic_playlist_num'] . $play;		
		$title = strlen($title) >= 35 ? $title = substr($title,0,34).'...': $title;
		$descript = strlen($descript) >= 35 ? $descript = substr($descript,0,34) . '...' : $descript;	
		if(empty($context['enable'][$i])) {$context['enable'][$i] = 0;}
		$hilight = 'windowbg';
		$enabling = false;
		$deleting = false;
		$link1 = $txt['customMusic_trackid'] . $i;
		$link2 = $descript;
		$hide = 'style="display:none;"';
		if (allowedTo('premiumbeat_settings'))
			$hide = '';
		if ((!empty($context['id_user'][$song])) && ((int)$context['id_user'][$song] == (int)$context['user']['id']) || allowedTo('premiumbeat_settings'))
		{
			$hilight = 'windowbg2';
			$enabling = true;
			$deleting = true;	
			$link1 = '<a href="' . $scripturl . '?action=custom_mp3;sa=EditPremiumbeat;' . 'tag=' . $tag . ';queryPlaylist=' . $play . ';">' . $txt['customMusic_trackid'] . $i . '</a>';
			$link2 = '<a href="' . $scripturl . '?action=custom_mp3;sa=EditPremiumbeat;' . 'tag=' . $tag . ';">'.$descript . '</a>';			
		}
		echo '
			<tr>
				<td style="text-align:left;border-bottom: 1px solid;width:15%;" class="'.$hilight.'">
					', $link1, '
				</td>
				<td style="text-align:left;border-bottom: 1px solid;white-space:nowrap;width:24%;" class="'.$hilight.'" >					
						<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat;' . 'queryPlaylist=' . $play . ';" title="' . $txt['customMusic_sort'] . $play .'">' . $title . '</a>
				</td>					
				<td class="'.$hilight.'" style="text-align:left;border-bottom: 1px solid;white-space:nowrap;width:3%;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;playlist=' . $play . ';" '.$hide.'><img src="'.$boardurl.'/my_music/view_playlist.gif" alt="'.$txt['customMusic_playlist_alt'].'" title="'.$txt['customMusicSortPlay'] . $play.'" /></a>
				</td>
				<td class="'.$hilight.'" style="text-align:left;border-bottom: 1px solid;white-space:nowrap;width:3%;">
				', $popupsong, ' 						
				</td>
				<td class="'.$hilight.'" style="border-bottom: 1px solid;text-indent:0.4%;white-space:nowrap;width:25%;">
					', $link2 ,'
				</td>
				<td class="'.$hilight.'" style="text-align:center;border-bottom: 1px solid;width:10%;text-indent:2%;">
					'. $status . '
				</td>
				<td class="'.$hilight.'" style="text-align:center;border-bottom: 1px solid;width:10%;">
					<input type="checkbox" name="enable[]" value="' . $tag . '" class="check" '. (allowedTo('premiumbeat_settings') || $enabling ? '' : ' disabled="disabled"'). ' />
				</td>					
				<td class="'.$hilight.'" style="text-align:center;border-bottom: 1px solid;width:10%;">
					<input type="checkbox" name="delete[]" value="' . $tag . '" class="check" '. (allowedTo('premiumbeat_showdelete') || $deleting ? '' : ' disabled="disabled"'). ' />
				</td>
			</tr>';
		$i++;
	}
	echo '
		</table>		
		<br /><div id="mp3PagePosition">&nbsp;</div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td colspan="8" style="font-size:xx-small;text-align:right;" >
					<input type="checkbox" name="reset[]" value="0" />&nbsp;',$txt['customMusicUpload_reset'],'&nbsp;&nbsp;
					<input type="checkbox" name="Check_All" value="Check All" onClick="Check(document.mysongs[';
				echo "'delete[]'";
				echo '])" />&nbsp;',$txt['premiumbeat_delete_all'],'
				</td>
			</tr>
		</table>			
		<table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8" align="left">
				<input type="button" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' onclick="var confirm = confirmSubmit(); if (confirm) {this.form.submit();}" />
				</td>
			</tr>
		</table>			
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>', $context['musicAdmin_pages'];
	
}
/*  END - Custom Music Main Template  */
?>