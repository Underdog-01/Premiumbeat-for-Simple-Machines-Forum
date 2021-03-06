<?php
// Version: 2.0.1; premiumbeat

/*     Main admin settings template file for the premiumbeat mp3 Mod    */
/*          c/o Underdog @ http://askusaquestion.net           */  

/* Premiumbeat forum settings display  */
function template_customMusic_settings_page()
{
	global $txt, $scripturl, $context, $settings;
	isAllowedTo('premiumbeat_settings');
	$sktype = 1;

	/* Onclick actions  */
	echo $context['toggle'];
 
	/*   Display forum settings */	
	echo '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="premiumbeat_settings">
		<div class="cat_bar"><h4 class="catbg" style="text-align:center;">' . $txt['premiumbeat_forum'] . '</h4></div>
		<br /><br />	
		<table border="0" cellspacing="0" cellpadding="4" width="100%">		
			<tr class="titlebg" style="font-size:x-small;">
				<td class="catbg" style="border:0px;border-bottom: thin outset;height: 23px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 0 -160px;">' . $txt['customMusic_general'] . '</td>
				<td class="catbg" style="border:0px;border-bottom: thin outset;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 100% -160px;height: 23px;line-height: 23px;" ></td>
			</tr>
			<tr>
				<td class="windowbg" style="text-align:left;">
					', $txt['premiumbeat_override'], '
				</td>
				<td class="windowbg" style="text-align:left;">
					<select name="override[]" style="width:10cm;" onchange="this.form.submit()">';
	if (count($context['playlist_data']) < 1)
		echo '<option disabled>','No Playlists Available','</option>';
	foreach ($context['playlist_data'] as $playlist)	
	{		
		if ((int)$playlist['id'] < 1)
			continue;
					
		$playlist['title'] = strlen($playlist['title']) >= 45 ? $playlist['title'] = substr($playlist['title'],0,42).'...': $playlist['title'];			
		
		if ((!empty($context['override'])) && (int)$context['override'] == (int)$playlist['id'])
		{
			$selected = 'selected="selected"';
			$context['playlist_file'] = !empty($playlist['file']) ? $playlist['file'] : false;
		}
		else
			$selected = false;			
				
		if ($playlist['title'] == false)
			echo '<option value="'.(int)$playlist['id'].'" ',$selected,'>',$txt['customMusic_playlist_num'],(int)$playlist['id'],'</option>';
		else
			echo '<option value="'.(int)$playlist['id'].'" ',$selected,'>',$playlist['title'],'</option>';		
		
	}
						
	echo '			</select>
				</td>
			</tr>';
		
	if ($context['mylink'] == $txt['premiumbeat_enabled'])		
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">', $txt['premiumbeat_link'], '</td>
				<td class="windowbg" style="text-align:left;"><input type="radio" id="playlinkD" name="forumlink[]" value="1" onclick="changeLinkD();" checked="checked" />
					<input type="radio" id="playlinkE" name="forumlink[]" value="2" onclick="changeLinkE();" />&nbsp;&nbsp;
					<span id="updateLink">',$context['mylink'],'</span>
				</td>
			</tr>';
	else
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">', $txt['premiumbeat_link'], '</td>
				<td class="windowbg" style="text-align:left;"><input type="radio" id="playlinkD" name="forumlink[]" value="1" onclick="changeLinkD();" />
					<input type="radio" id="playlinkE" name="forumlink[]" value="2" onclick="changeLinkE();" checked="checked" />&nbsp;&nbsp;
					<span id="updateLink">',$context['mylink'],'</span>
				</td>
			</tr>';
			
	if ($context['myFlink'] == $txt['premiumbeat_enabled'])
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">',$txt['premiumbeat_mainlink'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="radio" id="forumlinkD" name="mainlink[]" value="1" onclick="changeFLinkD();" checked="checked" />
					<input type="radio" id="forumlinkE" name="mainlink[]" value="2" onclick="changeFLinkE();" />&nbsp;&nbsp;
					<span id="updateFLink">',$context['myFlink'],'</span>
				</td>
			</tr>';
	else 
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">',$txt['premiumbeat_mainlink'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="radio" id="forumlinkD" name="mainlink[]" value="1" onclick="changeFLinkD();" />
					<input type="radio" id="forumlinkE" name="mainlink[]" value="2" onclick="changeFLinkE();" checked="checked" />&nbsp;&nbsp;
					<span id="updateFLink">',$context['myFlink'],'</span>
				</td>
			</tr>';
			
	if ($context['myFlinkDropdown'] == $txt['premiumbeat_enabled'])
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">',$txt['premiumbeat_mainlink_dropdown'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="radio" id="forumlinkDropdownD" name="forumlinkDrop[]" value="1" onclick="changeFLinkDropdownD();" checked="checked" />
					<input type="radio" id="forumlinkDropdownE" name="forumlinkDrop[]" value="2" onclick="changeFLinkDropdownE();" />&nbsp;&nbsp;
					<span id="updateFLinkDropdown">',$context['myFlinkDropdown'],'</span>
				</td>
			</tr>';
	else 
		echo '
			<tr>
				<td class="windowbg" style="text-align:left;">',$txt['premiumbeat_mainlink_dropdown'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="radio" id="forumlinkDropdownD" name="forumlinkDrop[]" value="1" onclick="changeFLinkDropdownD();" />
					<input type="radio" id="forumlinkDropdownE" name="forumlinkDrop[]" value="2" onclick="changeFLinkDropdownE();" checked="checked" />&nbsp;&nbsp;
					<span id="updateFLinkDropdown">',$context['myFlinkDropdown'],'</span>
				</td>
			</tr>';			
	echo '
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_axis'],'</td>
				<td class="windowbg" style="text-align:left;">
					<span>
						<input type="text" name="axisX[]" value="'.$context['axisX'].'" class="text" maxlength="4" size="5" />
					</span>
					<span style="position:relative;left:5px;">
						<input type="text" name="axisY[]" value="'.$context['axisY'].'" class="text" maxlength="4" size="5" />
					</span>
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="skin[]" value="'.$context['skin'].'" class="text" maxlength="60" size="40" />
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin_type'],'</td>
				<td class="windowbg" style="text-align:left;">
					<select name="skin_type[]">';
	while ($sktype < 6)
	{
		$select = false;
		if ((int)$context['skin_type'] == (int)$sktype)
			$select = 'selected="selected"';		
		
		echo '
						<option value="'.(int)$sktype.'" ',$select,'>',$txt['customMusic_skin_type_num'],$sktype,'</option>';
		$sktype++;			
	}				
	echo '
					</select>		
				</td>
			</tr>';
							
	echo '
			<tr>
				<td class="windowbg" style="text-align:left;">' . $txt['premiumbeat_file'] . '</td>		
				<td class="windowbg" style="text-align:left;">';
	if (!$context['playlist_file'])
		echo '			<input disabled type="text" name="filename[]" value="'.$context['filename'].'" class="text" maxlength="80" size="80" />';
	else
		echo '			<input disabled type="text" name="filename[]" value="'.$context['playlist_file'].'" class="text" maxlength="80" size="80" />';
	echo '				
				</td>						
			</tr>';				
	echo '
			<tr class="catbg3">
				<td style="border:0px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 1% -173px;height:10px;"></td>
				<td style="border:0px;background: url(',$settings['actual_theme_url'],'/images/theme/main_block.png) no-repeat 99% -173px;height:10px;"></td>
			</tr>
		</table>
		<br /><br />
		<h4 style="text-align:center;">
			<a href="' . $scripturl . '?action=admin;area=premiumbeat;sa=BrowsePremiumbeat;sesc=' . $context['session_id'] . ';">'.$txt['premiumbeat_return'] . '</a>
			<span style="float:right;">
				<input type="submit" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' />
			</span>
		</h4>				
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>';
}

/*  END - Custom Music Forum Settings Template  */
?>