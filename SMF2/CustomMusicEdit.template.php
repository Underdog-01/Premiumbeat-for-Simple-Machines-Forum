<?php
// Version: 2.0.1; premiumbeat

/*     Main admin Add/Edit Mp3 template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  

/* Premiumbeat mp3 display  */
function template_customMusic_edit()
{
	global $txt, $scripturl, $context, $settings;
	isAllowedTo('premiumbeat_config');
	$track = $context['track_id'];
	$count = 0;
	$disabled = '';
	$context['queryPlaylist'] = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 0;	
	if ($context['queryPlaylist'] > 0)
		$context['playlist'][$track] = (int)$context['queryPlaylist'];
	
	/*   Display add/edit settings  */	
	echo $context['toggle']; 
	echo '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '">
		<div class="cat_bar"><h4 class="catbg" style="text-align:center;">' . $context['premiumbeat_edit'] . '</h4></div>
		<br />
		<table border="0" cellspacing="0" cellpadding="4" width="100%" class="windowbg">
			<tr class="titlebg" style="font-size:x-small;">
				<td class="catbg" style="border:0px;border-bottom: thin outset;height: 23px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 0 -160px;">
					&nbsp;',$txt['customMusic_mp3id'],$context['number_tag'],'
				</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 100% -160px;height: 23px;line-height: 23px;" >
				</td>
			</tr>			
			<tr>
				<td style="text-align:left">', $txt['customMusic_playlist'], '</td>
				<td style="text-align:left;">
					<select name="playlist" style="width:10cm;">';
	if (count($context['playlist_data']) < 1)
	{
		echo '<option disabled>',$txt['customMusic_NoneAvailable'],'</option>';
		$disabled = 'disabled ';
	}
	foreach ($context['playlist_data'] as $playlist)	
	{		
		if ((int)$playlist['id'] < 1)
			continue;
					
		$playlist['title'] = strlen($playlist['title']) >= 45 ? $playlist['title'] = substr($playlist['title'],0,42).'...': $playlist['title'];			
		
		if ((!empty($context['playlist_id'][$track])) && (int)$context['playlist_id'][$track] == (int)$playlist['id'])
			$selected = 'selected="selected"';		
		elseif ((int)$context['queryPlaylist'] == (int)$playlist['id'])
			$selected = 'selected="selected"';	
		else
			$selected = false;			
				
		if ($playlist['title'] == false)
			echo '<option value="'.(int)$playlist['id'].'" ',$selected,'>',$txt['customMusic_playlist_num'],(int)$playlist['id'],'</option>';
		else
			echo '<option value="'.(int)$playlist['id'].'" ',$selected,'>',$playlist['title'],'</option>';		
		
	}	
						
	echo '			</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:left">
					', $txt['customMusic_url'], '
				</td>
				<td style="text-align:left;">
					<textarea '.$disabled.'id="url" name="url" rows="3" cols="50" tabindex="5">',$context['url'][$track],'</textarea>		
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:left">
					', $txt['customMusic_description'], '
				</td>
				<td style="text-align:left">
					<input '.$disabled.'type="text" name="description" value="'.$context['description'][$track].'" maxlength="100" size="100" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:left;">
					',$txt['customMusic_status'],'&#58;
				</td>
				<td style="text-align:left;">
					<span id="updateEnable">',$context['xenable'],'</span>
				</td>
			</tr>
			<tr>
				<td style="text-align:left">
					', $txt['customMusic_enable'], '&#58;
				</td>
				<td style="text-align:left">';
				
	if ($context['xenable'] == $txt['premiumbeat_enabled'])
		echo '
					<input '.$disabled.'type="radio" id="enableE" name="enable" value="1" onclick="changeEnableE();" checked="checked" />&nbsp;
					<input '.$disabled.'type="radio" id="enable" name="enable" value="2" onclick="changeEnableD();" />';
	else
		echo '
					<input '.$disabled.'type="radio" id="enableE" name="enable" value="1" onclick="changeEnableE();" />&nbsp;
					<input '.$disabled.'type="radio" id="enable" name="enable" value="2" onclick="changeEnableD();" checked="checked" />';
					
		echo '			
				</td>
			</tr>
			<tr class="catbg3">
				<td style="border:0px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 1% -173px;height:10px;"></td>
				<td style="border:0px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 99% -173px;height:10px;"></td>
			</tr>				
		</table>
		<table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">
					<input '.$disabled.'type="submit" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' />
				</td>
			</tr>			
		</table>				
		<input '.$disabled.'type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>';
}
/*  END - Custom Music add/edit Template  */
?>