<?php
// Version: 2.0; premiumbeat

/*        MP3 files template file for the premiumbeat mp3 Mod 	    	*/
/*        This template is user input for uploading mp3 files			*/
/*                 c/o Underdog @ http://askusaquestion.net				*/  

/* Premiumbeat mp3 files template  */
function template_customMusic_upload()
{
	global $txt, $scripturl, $context, $boarddir, $boardurl, $settings;
	if (!allowedTo('premiumbeat_showupload') && !allowedTo('premiumbeat_showdownload'))
		fatal_lang_error('cannot_premiumbeat_showupload', false);
		
	$count = 0;
	$dcount = 0;
	$listplay = $txt['music_nofolder'];
	$playlist = $txt['premiumbeat_playlist_test'];
	$popup = 'javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong=http://playlist='.$context['playlist'].';\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);';
	$showdl = false;
	if (!allowedTo('premiumbeat_showdownload'))
		$showdl = 'style="visibility:hidden;">';
	
	if ((int)$context['playlist'] > 0)
	{
		$listplay = '<a href="'.$popup.'" title="'.$txt['premiumbeat_playlist_test'].'"><img src="'.$boardurl.'/my_music/PlayButton.gif" alt="'.$txt['music_fileplay'].'" title="'.$txt['premiumbeat_playlist_test'].'" style="height:16px;width:16px;" /></a>';
		$playlist = '<a href="'.$scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;playlist=' . $context['playlist'] . '" title="'.$txt['customMusic_sort'].$context['playlist'].'">'.$txt['premiumbeat_playlist_test'].'&nbsp;&#035;'. $context['playlist'].'</a>';
	}
	/*  Upload MP3 Files Input  */  
	echo '<script src="',$boardurl,'/my_music/js/jquery.js" type="text/javascript"></script>
			<script type="text/javascript" src="my_music/js/premiumbeat-pages.js"></script>';
	
	/* define language variables for the javascript */    
	echo '<script type="text/javascript">';
	if ($context['browser']['is_firefox'])
		 {echo 'var allactive = "button"';}
	else
		{echo 'var allactive = "hidden"';}
	
	echo '				
			var uploadall = ', json_encode($txt["customMusic_ulall"]),'
			var uploading = ', json_encode($txt["customMusic_ul"]),'
			var clearbutton = ', json_encode($txt["customMusic_clear"]),'
			var filebutton = ', json_encode($txt["customMusic_file"]),'
			var sizebutton = ', json_encode($txt["customMusic_size"]),'
			var progressbutton = ', json_encode($txt["customMusic_progress"]),'
			var removebutton = ', json_encode($txt["customMusic_remove"]),'
			var uploadbutton = ', json_encode($txt["customMusic_upload"]),'
			var finishtext = ', json_encode($txt["customMusic_finish"]),'
		</script>';
			
	echo '<script src="',$boardurl,'/my_music/js/axuploader.js" type="text/javascript"></script>
		<script type="text/javascript">',$context['downlimit'],$context['premiumbeat_confirm'],'</script>
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="uploadselect" id="uploadselect">
		<table border="0" cellspacing="0" cellpadding="4" style="border-style:solid;border-width:2px;border-collapse:collapse;width:100%;">
			<tr class="catbg" style="font-size:x-small;">
				<td class="catbg" style="border:0px;height: 23px;width:5%;"></td>
				<td class="catbg" colspan="5" style="border:0px;text-align:center;width:90%;height: 23px;">' . $txt['premiumbeat_upload_files'] . '</td>
				<td class="catbg" style="border:0px;height: 23px;width:5%;"></td>
			</tr>						
		</table>
		<table border="0" cellspacing="0" cellpadding="4" class="windowbg" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">';

	if (is_array($context['files_list']))
	{	
		echo '
			<tr>
				<td class="titlebg" style="text-align:left;">',$txt['premiumbeat_playlist_title'],'</td>
				<td class="titlebg" style="text-align:left;" colspan="5">',$txt['premiumbeat_playlist_filez'],'</td>
				<td class="titlebg" style="text-align:center;">',$playlist,'</td>
			</tr>
			<tr>
				<td style="text-align:left;">
					<a href="'.$scripturl . '?action=custom_mp3;sa=PlaylistPremiumbeat;playlist=', $context['playlist'], '" title="'.$txt['customMusic_sort'].$context['playlist'].'">',$context['sets']['title'],'</a>
				</td>
				<td style="text-align:left;" colspan="5">', $context['audiofile'],'</td>
				<td style="text-align:center;">',$listplay,'</td>
			</tr>	
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
		</table>';	
		echo '
		<table border="0" cellspacing="0" cellpadding="4" class="windowbg" style="border-style:inset;border-left-width:2px;border-right-width:2px;border-collapse:collapse;width:100%;" id="premFiles">
			<tr>
				<td class="titlebg" style="text-align:right;width:1%;">&nbsp;</td>
				<td class="titlebg" style="text-align:left;width:55%;">',$txt['music_filename'],'</td>
				<td class="titlebg" style="text-align:left;width:15%;">',$txt['musicfile_author'],'</td>
				<td class="titlebg" style="width:19%;text-align:right;">',$txt['music_filedate'],'</td>
				<td class="titlebg" style="width:3%;text-align:center;">',$txt['customMusic_visability'],'</td>	
				<td class="titlebg" style="width:3%;text-align:center;"><span ',$showdl,'>',$txt['customMusic_download'],'</span></td>			
				<td class="titlebg" style="width:4%;text-align:center;">',$txt['customMusic_delete'],'</td>
			</tr>';
		foreach ($context['music_files'] as $files => $file)
		{	
			$premfilex = explode('-_-', $file['filex']);	
			$premfile = str_replace($premfilex[0] . '-_-', '', $file['filex']);		
			$popupsong = '<a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong='.$boardurl.'/'.$context['destinationz'].'/'.$file['filex'].';\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);"><img src="'.$boardurl.'/my_music/PlayButton.gif" alt="'.$txt['music_fileplay'].'" title="'.$txt['music_fileplay'].'" style="height:16px;width:16px;" /></a>';
			echo '
			<tr class="'. ($context['user']['id'] == (int)$file['user_id'] ? 'windowbg2' : 'windowbg'). '">
				<td style="text-align:right;width:1%;">',$popupsong,'</td>
				<td style="text-align:left;width:55%;text-indent:0.5%;">',$premfile,'</td>				
				<td style="text-align:left;width:15%;text-indent:0.5%;">'. ((int)$file['author'] == 1 ? $file['name'] : $txt['customMusicUpload_noname']). '</td>	
				<td style="width:19%;text-align:right;">',$file['date'],'&nbsp;</td>
				<td style="width:3%;text-align:center;"><input type="checkbox" name="toggle[]" value="' . $file['filex'] . '" class="check" '. ($context['user']['id'] == (int)$file['user_id'] || allowedTo('premiumbeat_config') ? '' : ' disabled="disabled"'). ' /></td>	
				<td style="width:3%;text-align:center;">
					<span ',$showdl,'><input type="checkbox" id="download" name="download[]" value="' . $file['filex'] . '" class="check" '. (allowedTo('premiumbeat_download') || allowedTo('premiumbeat_config') ? '' : ' disabled="disabled"'). ' onclick="downcontrol('.(int)$dcount.')" /></span>
				</td>				
				<td style="width:4%;text-align:center;">
					<input type="checkbox" name="delete[]" value="' . $file['filex'] . '" class="check" '. ($context['user']['id'] == (int)$file['user_id'] || allowedTo('premiumbeat_showdelete') ? '' : ' disabled="disabled"'). ' />
				</td>
			</tr>';	
			$dcount++;	
		}
		echo '
		</table>		
		<table border="0" cellspacing="0" cellpadding="4" class="windowbg" style="border-style:inset;border-left-width:2px;border-right-width:2px;border-collapse:collapse;width:100%;">
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>';
	}

	if ((int)$context['playlists_total'] > 0)
	{
		echo '
			<tr>
				<td style="text-align:left;">' , $txt['premiumbeat_playlist_edit'] , '</td>
				<td style="text-align:left;" colspan="5">
					<select name="queryPlaylist" onchange="this.form.submit()">';
					while ($count < (int)$context['playlists_total'])
					{
						if ($count == 0)
						{
							if ($context['title_playlists'][$count] && ((int)$context['playlist'] == (int)$context['playlists'][$count]))
								echo'<option value="-1" selected="selected">',$context['title_playlists'][$count],'</option>';
							else
								echo'<option value="-1">',$context['title_playlists'][$count],'</option>';	
						}	
						elseif ($context['title_playlists'][$count] && ((int)$context['playlist'] == (int)$context['playlists'][$count]))
							echo'<option value="'.$context['playlists'][$count].'" selected="selected">',$context['title_playlists'][$count],'</option>';
						elseif ($context['title_playlists'][$count])
							echo'<option value="'.$context['playlists'][$count].'">',$context['title_playlists'][$count],'</option>';	
						elseif ((int)$context['playlist'] == (int)$context['playlists'][$count])
							echo'<option value="'.$context['playlists'][$count].'" selected="selected">',$txt['customMusic_playlist_num'], $context['playlists'][$count],'</option>';	
						else
							echo'<option value="'.$context['playlists'][$count].'">',$txt['customMusic_playlist_num'], $context['playlists'][$count],'</option>';
								
						$count++;
					}
				echo '
					</select>	
				</td>';
				if (allowedTo('premiumbeat_showupload') || allowedTo('premiumbeat_showdownload'))
				{				
					echo '
				<td style="float:right;">					
					<select name="compatFiles" onchange="var confirm = confirmSubmit(); if (confirm) {this.form.submit();}">
						<option value="0">',$txt['customMusicUpload_compat'],'</option>';					
					if (allowedTo('premiumbeat_config')) 	
						echo '<option value="1">',$txt['customMusicUpload_rename'],'</option>';
					else
						echo '<option value="1" disabled="disabled">',$txt['customMusicUpload_rename'],'</option>';
						
					echo '
						<option value="2">',$txt['customMusic_execute'],'</option>
						<option value="3">',$txt['customMusicUpload_reset'],'</option>
					</select>					
				</td>';
				}		
				else
					echo '
				<td>&nbsp;</td>';
								
				echo '			
			</tr>';
	}
	echo'
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:left;">',$txt['premiumbeat_upload_file'],'</td>
				<td style="text-align:left;" colspan="6">
					<div id="upload_files"></div>
					<div style="float:left;">
						<input name="music_submit" type="submit" value="'. $txt['customMusicUpload_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' />
					</div>
				</td>
			</tr>
			<tr class="catbg3">
				<td colspan="6" style="border:0px;height:5px;"></td>
				<td style="border:0px;height:5px;"></td>
			</tr>	
		</table>
		<script type="text/javascript">';
	echo "$('#upload_files').axuploader({
    		url:'",$context['post_url'],"',    
    		maxFiles:5,
    		allowExt:['mp3'],
    		onFinish:function(txt,files){
			alert('All files uploaded server return:'+txt);}});
		</script>";	
	echo '
		<br />
		<span id="FilesPagePosition" style="float:left;">&nbsp;</span>
		<h4 style="text-align:center;">
			<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat;sesc=' . $context['session_id'] . ';">'.$txt['premiumbeat_return'] . '</a>
		</h4>				
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>', $context['musicAdmin_pages3'];
}

/*  END - MP3 Files Template  */
?>