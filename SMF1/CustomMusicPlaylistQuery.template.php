<?php
// Version: 2.0; premiumbeat

/*     Specific playlist template file for the premiumbeat mp3 Mod    */
/*  This template is user input for querying a specific playlist id#  */
/*                 c/o Underdog @ http://askusaquestion.net      */  

/* Premiumbeat query playlist display  */
function template_customMusic_query_playlist()
{
	global $txt, $scripturl, $context, $settings;
	isAllowedTo('premiumbeat_settings');	
	$sktype = 1;
	/*  Create New Playlist */  
	echo $context['toggle'];	 
	echo '
	<form action="'. $context['post_url']. '" method="post" accept-charset="'. $context['character_set']. '" name="myselect" id="myselect">
		<h4 class="tborder titlebg catbg3" style="text-align:center;border-style:solid;border-width:2px;border-collapse:collapse;">' . $txt['premiumbeat_playlist_create'] . '</h4>
		<br /><br />
		<table border="0" cellspacing="0" cellpadding="4" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">
			<tr class="catbg" style="font-size:x-small;">				
				<td class="catbg" style="border:0px;height: 23px;width:5%;"></td>
				<td class="catbg" style="text-align:center;border:0px;width:90%;">' . $txt['premiumbeat_settings'] . '</td>
				<td class="catbg" style="border:0px;height: 23px;line-height: 23px;width:5%;"></td>
			</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="4" style="border-style:inset;border-width:2px;border-collapse:collapse;width:100%;">
			<tr>
				<td width="50%" style="text-align:left;" class="windowbg">' . $txt['premiumbeat_playlist_newname'] . '</td>
				<td width="50%" style="text-align:left;" class="windowbg">
					<input type="text" name="title[]" value="" class="text" />
				</td>											
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">', $txt['premiumbeat_height'], '</td>
				<td class="windowbg" width="50%" style="text-align:left;">
					<input type="text" name="height[]" value="215" class="text" maxlength="4" size="4" />
				</td>
			</tr>		
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">', $txt['premiumbeat_width'], '</td>
				<td class="windowbg" width="50%" style="text-align:left;">
					<input type="text" name="width[]" value="200" class="text" maxlength="4" size="4" />
				</td>
			</tr>		
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">', $txt['premiumbeat_toggle_autoplay'], '</td>
				<td class="windowbg" width="50%" style="text-align:left;">
					<input type="radio" id="autoplayD" name="autoplay[]" value="1" onclick="changeAutoD();" />
					<input type="radio" id="autoplay" name="autoplay[]" value="2" onclick="changeAutoE();" checked="checked" />
					<span id="updateAuto">',$txt['premiumbeat_disabled'],'</span>
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">', $txt['premiumbeat_toggle_type'], '</td>
				<td class="windowbg" width="50%" style="text-align:left;">
					<input type="radio" id="typeA" name="type[]" value="1" onclick="changeTypeA();" checked="checked" />
					<input type="radio" id="type" name="type[]" value="2" onclick="changeTypeS();" />
					<span id="updateDir">',$txt['premiumbeat_asc'],'</span>
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">', $txt['premiumbeat_playlist_equip'], '</td>
				<td class="windowbg" width="50%" style="text-align:left;">
					<input type="radio" id="equipX" name="equip[]" value="1" onclick="changeTypeX();" />
					<input type="radio" id="equip" name="equip[]" value="2" onclick="changeTypeY();" checked="checked" />
					<span id="updateEquip">',$txt['premiumbeat_disabled'],'</span>
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['premiumbeat_file_title'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="autofile[]" value="" class="text" maxlength="60" size="40" />
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin'],'</td>
				<td class="windowbg" style="text-align:left;">
					<input type="text" name="skin[]" value="000000" class="text" maxlength="60" size="40" />
				</td>
			</tr>
			<tr>
				<td class="windowbg" width="50%" style="text-align:left;">',$txt['customMusic_skin_type'],'</td>
				<td class="windowbg" style="text-align:left;">
					<select name="skin_type[]">';
	while ($sktype < 6)
	{
		echo '
						<option value="'.(int)$sktype.'" />',$txt['customMusic_skin_type_num'],$sktype,'</option>';
		$sktype++;			
	}				
	echo '
					</select>		
				</td>
			</tr>		
			<tr class="catbg2">
				<td class="catbg" colspan="2" style="border:0px;height:5px;"></td>			
			</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="4" width="100%">		
			<tr>
				<td colspan="6" align="left"><input type="submit" value="'. $txt['customMusic_submit']. '"'. (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''). ' /></td>
			</tr>
		</table>
		<br /><br />
		<h4 style="text-align:center;">
			<a href="' . $scripturl . '?action=custom_mp3;sa=BrowsePremiumbeat;sesc=' . $context['session_id'] . ';">'.$txt['premiumbeat_return'] . '</a>
		</h4>					
		<input type="hidden" name="sc" value="'. $context['session_id']. '" />
	</form>';
}

/*  END - Custom Music View Specific Playlist Template  */
?>