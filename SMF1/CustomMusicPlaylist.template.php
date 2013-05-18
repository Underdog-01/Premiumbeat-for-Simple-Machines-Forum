<?php
// Version: 2.0; premiumbeat

/*     Secondary admin settings template file for the premiumbeat mp3 Mod    */
/*         This template is for editing a specific playlist's settings          */
/*                 c/o Underdog @ http://askusaquestion.net                  */  

/* Premiumbeat playlist settings display  */
function template_customMusic_edit_playlist()
{
	global $txt, $scripturl, $context, $settings;
	isAllowedTo('premiumbeat_settings');
	$z = 0;	
	$sktype = 1;
	
	/* Onclick actions  */
	echo $context['toggle'];
 
	/*   Display playlist settings   */	
	echo '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="myselect" id="myselect">
		<h4 class="tborder titlebg catbg3" style="text-align:center;border-style:solid;border-width:2px;border-collapse:collapse;">' . $txt['premiumbeat_edit_list']. $context['mysets']['myplaylist'] . '</h4>
		<br /><br />		
		<table border="0" cellspacing="0" cellpadding="4" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">
			<tr class="catbg" style="font-size:x-small;">
				<td class="catbg" style="border:0px;height: 23px;">' . $txt['customMusic_playlist_data'] . '</td>
				<td class="catbg" style="border:0px;height: 23px;line-height: 23px;" ></td>
			</tr>
			<tr>
				<td class="windowbg" colspan="2" style="text-align:left;">', $txt['customMusic_playlist_num'], $context['mysets']['myplaylist'], '</td>
			</tr>
			<tr>
				<td class="windowbg" width="15%" style="text-align:top left;">',$txt['premiumbeat_playlist_title'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="title[]" value="'.$context['mysets']['title'].'" class="text" maxlength="60" size="40" /></td>
			</tr>		
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_height'], '</td>
				<td class="windowbg" width="15%" style="text-align:left;">
					<input type="text" name="height[]" value="'.$context['mysets']['height'].'" class="text" maxlength="4" size="4" />
				</td>
			</tr>		
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_width'], '</td>
				<td class="windowbg" width="15%" style="text-align:left;">
					<input type="text" name="width[]" value="'.$context['mysets']['width'].'" class="text" maxlength="4" size="4" />
				</td>
			</tr>';
		
		if ($context['xauto'] == $txt['premiumbeat_enabled'])
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_toggle_autoplay'], '</td>
				<td class="windowbg" width="19%" style="text-align:left;">
					<input type="radio" id="autoplayD" name="autoplay[]" value="1" onclick="changeAutoD();" checked="checked" />
					<input type="radio" id="autoplay" name="autoplay[]" value="2" onclick="changeAutoE();" />
						<span id="updateAuto">',$context['xauto'],'</span>
				</td>
			</tr>';
		else
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_toggle_autoplay'], '</td>
				<td class="windowbg" width="19%" style="text-align:left;">
					<input type="radio" id="autoplayD" name="autoplay[]" value="1" onclick="changeAutoD();" />
					<input type="radio" id="autoplay" name="autoplay[]" value="2" onclick="changeAutoE();" checked="checked" />
					<span id="updateAuto">',$context['xauto'],'</span>
				</td>
			</tr>';
		if ($context['xtype'] == $txt['premiumbeat_asc'])	
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_toggle_type'], '</td>
				<td class="windowbg" width="19%" style="text-align:left;">
					<input type="radio" id="typeA" name="type[]" value="1" onclick="changeTypeA();" checked="checked" />
					<input type="radio" id="type" name="type[]" value="2" onclick="changeTypeS();" />
						<span id="updateDir">',$context['xtype'],'</span>
				</td>
			</tr>';
		else	
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_toggle_type'], '</td>
				<td class="windowbg" width="19%" style="text-align:left;">
					<input type="radio" id="typeA" name="type[]" value="1" onclick="changeTypeA();" />
					<input type="radio" id="type" name="type[]" value="2" onclick="changeTypeS();" checked="checked" />
					<span id="updateDir">',$context['xtype'],'</span>
				</td>
			</tr>';
		if ($context['equip'] == $txt['premiumbeat_enabled'])
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_playlist_equip'], '</td>
				<td class="windowbg" width="17%" style="text-align:left;">
					<input type="radio" id="equipX" name="equip[]" value="1" onclick="changeTypeX();" checked="checked" />
					<input type="radio" id="equip" name="equip[]" value="2" onclick="changeTypeY();" />
					<span id="updateEquip">',$context['equip'],'</span>
				</td>
			</tr>';	
		else
			echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:left;">', $txt['premiumbeat_playlist_equip'], '</td>
				<td class="windowbg" width="17%" style="text-align:left;">
					<input type="radio" id="equip" name="equip[]" value="1" onclick="changeTypeX();" />
					<input type="radio" id="equip" name="equip[]" value="2" onclick="changeTypeY();" checked="checked" />
					<span id="updateEquip">',$context['equip'],'</span>
				</td>
			</tr>';	
						
		echo '
			<tr>
				<td class="windowbg" width="15%" style="text-align:top left;">
					',$txt['premiumbeat_file_title'],'
				</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="autofile[]" value="'.$context['mysets']['autofile'].'" class="text" maxlength="60" size="40" />
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="skin[]" value="'.$context['mysets']['skin'].'" class="text" maxlength="60" size="40" />
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin_type'],'</td>
				<td class="windowbg" style="text-align:left;">
					<select name="skin_type[]">';
	while ($sktype < 6)
	{
		$select = false;
		if ((int)$context['mysets']['skin_type'] == (int)$sktype)
			$select = 'selected="selected"';
			
		echo '
						<option value="'.(int)$sktype.'" ',$select,'>',$txt['customMusic_skin_type_num'],$sktype,'</option>';
		$sktype++;			
	}				
	echo '
					</select>		
				</td>
			</tr>';		
		if ($context['id_group'] != $txt['premiumbeat_na'])
		{
			echo '
			<tr>
				<td class="windowbg" colspan="2">
					<div style="text-align:top;">
						<div style="float:left;">',$txt['premiumbeat_playlist_perm1'],'
							<strong>',$context['id_group'],'</strong>
						</div>
					</div>
				</td>
			</tr>';
		}									
		echo '
			<tr class="catbg3">
				<td style="border:0px;height:5px;"></td>
				<td style="border:0px;height:5px;"></td>
			</tr>
		</table>
		<br /><br />
		<div>
			<div style="float:left;">
				<input type="button" onclick="showPermissions()" value="',$txt['premiumbeat_open_perm'],'" />
			</div>
			<div style="float:right;">
				<input type="submit" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' />
			</div>
		</div>
		<table border="0" cellspacing="0" cellpadding="4" width="100%" style="visibility:hidden;" id="perm1">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>',$txt['premiumbeat_help_perm'],'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>';
	echo '
			<tr>
				<td>',$txt['premiumbeat_playlist_perm2'],'</td></tr><tr><td>&nbsp;</td>
			</tr>';
	
	/* Start - membergroups list */		
	foreach ($context['groups'] as $group)
	{
		echo '
			<tr>
				<td>
					<input type="checkbox" value="', $context['group_id'][$z], '" id="perms_',$context['group_id'][$z],'" name="perms[]" />&nbsp;', $group, '
				</td>
			</tr>';
	
			$z++;				
	}
	
	echo '
			<tr>
				<td>
					<input type="checkbox" value="-1" id="perms_guest" name="perms[]" />&nbsp;', $txt[28], '
				</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" value="-999" id="perms_admin" name="perms[]" />&nbsp;', $txt['premiumbeat_na'], '</td>
			</tr>
			<tr>
				<td style="font-size:xx-small;">
					<input type="checkbox" name="Check_All" value="Check All" onClick="Check(document.myselect[';
				echo "'perms[]'";
				echo '])" />&nbsp;',$txt['premiumbeat_checkall'],'
				</td>
			</tr>
		</table>';		
	/*  End membergroups list  */
	echo '
		<table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr>
				<td>&nbsp;</td>
			</tr>';
	echo '
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="windowbg" colspan="6" style="text-align:center;">
					<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat">' ,$txt['premiumbeat_return'] , '</a>
				</td>
			</tr>
		</table>			
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>';
}

/*  END - Custom Music Playlist Settings Template  */
?>