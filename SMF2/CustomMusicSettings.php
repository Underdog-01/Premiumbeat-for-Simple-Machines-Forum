<?php
// Version: 2.0.1; premiumbeat

/*     Main admin settings file for the premiumbeat mp3 Mod     */
/*          c/o Underdog @ http://askusaquestion.net           */  
/*                     SMF 2 Version						*/

if (!defined('SMF'))
	die('Hacking attempt...');

function custom_mp3_forum_settings()
{
	global $scripturl, $txt, $context, $sourcedir;
	isAllowedTo('premiumbeat_settings');
	$context['robot_no_index'] = true;	
	require_once($sourcedir . '/ManageServer.php');	
	$context[$context['admin_menu_name']]['tab_data']['title'] = $txt['customMusic_tabtitle'];	
	$context['playlist_data'] = array();	
		
	/*  Fill $context with save data for the forum settings if they exist  */	
	$setting_types = array('override', 'forumlink', 'filename', 'autoplay', 'skin', 'skin_type', 'forumlinkDrop', 'axisX', 'axisY');	
	foreach ($setting_types as $setting_type)
	{			
		if (empty($_REQUEST[$setting_type][0]))
			$_REQUEST[$setting_type][0] = false;				
		$context[$setting_type] = $_REQUEST[$setting_type][0];			
	}
		 
	if (empty($_REQUEST['mainlink'][0]))
		$_REQUEST['mainlink'][0] = false;
		
	$context['mainlink'] = $_REQUEST['mainlink'][0];		
				
	$subActions = array('PremiumbeatSettings' => array('SettingsCustomMusic'),);
		
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'PremiumbeatSettings'; 

	if (isset($subActions[$_REQUEST['sa']][1]))
		isAllowedTo('premiumbeat_settings');

	$subActions[$_REQUEST['sa']][0]();
}

function SettingsCustomMusic()
{

	global $txt, $scripturl, $context, $smcFunc, $sourcedir, $db_prefix, $modSettings;
	loadLanguage('Modifications');
	require_once($sourcedir . '/Subs.php');	
	$context['tag'] = array();
	$context['robot_no_index'] = true;	
	isAllowedTo('premiumbeat_settings');
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['premiumbeat_forum'];
	$a = false;
	$count = 0;
	$context['playlist_file'] = false;
	$tableName = 'premiumbeat_forum_settings';	
	$setting_types = array('override', 'forumlink', 'filename', 'autoplay', 'skin', 'skin_type', 'forumlinkDrop', 'axisX', 'axisY');	
	if (empty($modSettings['premiumbeat_link'])) {$modSettings['premiumbeat_link'] = 0;}
	$context['myFlink'] = $txt['premiumbeat_disabled'];			
	if ((int)$modSettings['premiumbeat_link'] > 0) {$context['myFlink'] = $txt['premiumbeat_enabled'];}	
	$a = false;	
	
	/*  Check for new settings values and save to database if necessary */
	foreach ($setting_types as $setting_type)
	{		
		if (empty($context[$setting_type]))
		{
			$context[$setting_type] = false;
			continue;	
		}
				
		if ($context['forumlink'] == 2 && $setting_type == 'forumlink')	
		{	
			$columnName = 'forumlink';	
			$tag = 1;
			$value = 0;			
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`reference` = {$tag} LIMIT 1");
			continue;
		}
		if ($context['forumlinkDrop'] == 2 && $setting_type == 'forumlinkDrop')	
		{	
			$columnName = 'forumlinkDrop';	
			$tag = 1;
			$value = 0;			
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`reference` = {$tag} LIMIT 1");
			continue;
		}	
		elseif (($context['forumlinkDrop'] == 1 || $context['forumlinkDrop'] == 0) && $setting_type == 'forumlinkDrop')	
		{	
			$columnName = 'forumlinkDrop';	
			$tag = 1;
			$value = 2;			
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`reference` = {$tag} LIMIT 1");
			continue;
		}	
					
		if ($setting_type == 'override')	
		{	
			$columnName = $setting_type;					
			$value = premium_number_filter($context[$setting_type]);
			$tag = 1;	
			if((int)$value < 1 || (int)$value > 99)
				$value = 1;
								
			createpremiumval_setting($tableName, $setting_type, $tag, $value);		
			continue;
		}	
		if ($setting_type == 'autoplay')	
		{	
			$columnName = $setting_type;					
			$value = premium_number_filter($context[$setting_type]);
			$tag = 1;	
			if((int)$value < 1 || (int)$value > 99)
				$value = 1;
				
			createpremiumval_setting($tableName, $setting_type, $tag, $value);		
			continue;
		}	
		if ($setting_type == 'skin')	
		{	
			$columnName = $setting_type;					
			$value = premium_hex_filterx($context[$setting_type]);
			$tag = 1;																					
			createpremiumval_setting($tableName, $setting_type, $tag, $value);		
			continue;
		}
		if ($setting_type == 'axisX')	
		{
			if ((int)$context[$setting_type] < -1)
				$context[$setting_type] = 0;
			if ((int)$context[$setting_type] > 2000)
				$context[$setting_type] = 2000;
			$setArray['premiumbeat_axisX'] = (int)$context[$setting_type];
			updateSettings($setArray);			
			$modSettings['premiumbeat_axisX'] = (int)$context[$setting_type];		
			continue;			
		}
		if ($setting_type == 'axisY')	
		{
			if ((int)$context[$setting_type] < -1)
				$context[$setting_type] = 0;
			if ((int)$context[$setting_type] > 2000)
				$context[$setting_type] = 2000;																								
			$setArray['premiumbeat_axisY'] = (int)$context[$setting_type];
			updateSettings($setArray);			
			$modSettings['premiumbeat_axisY'] = (int)$context[$setting_type];		
			continue;
		}	
		if ($setting_type == 'skin_type')	
		{	
			$columnName = $setting_type;					
			$value = premium_number_filter($context[$setting_type]);
			
			if ((int)$value < 1 || (int)$value > 5)
				$value = 1;
						
			$tag = 1;																					
			createpremiumval_setting($tableName, $setting_type, $tag, $value);		
			continue;
		}							
		if ($context[$setting_type] != false)	
		{	
			$columnName = $setting_type;	
			$value = premium_folder_filter($context[$setting_type]);
			$tag = 1;					
			$value = substr($value, 0, 254);													
			createpremiumval_setting($tableName, $setting_type, $tag, $value);		
			continue;
		}			
	}

	/* Enable/Disable Premiumbeat link */					
	$mysetting = 'premiumbeat_link';		
	if ($context['mainlink'] == 2)	
	{
		$value = 0;
		$context['myFlink'] = $txt['premiumbeat_disabled'];	
		$premiumUpdate = array($mysetting => $value);
		updateSettings($premiumUpdate);
										
	}
	if ($context['mainlink'] == 1)	
	{					
		$value = 1;						
		$premiumUpdate = array($mysetting => $value);
		updateSettings($premiumUpdate);											
		$context['myFlink'] = $txt['premiumbeat_enabled'];													
	}					
				
	/* START - Read database entries for premiumbeat forum settings */
	$a = false;
	$a = checkFieldPremium3($tableName,'reference');
	if ($a == false) {fatal_lang_error('customMusic_error', false);}	
	$result1 = array();
	$val = false;	
	$query = 'reference = 1';	
	$result1 = $smcFunc['db_query']('', "SELECT reference, override, forumlink, filename, autoplay, skin, skin_type, forumlinkDrop FROM {db_prefix}premiumbeat_forum_settings WHERE {$query}");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
	{
		foreach ($setting_types as $setting_type)
		{					
			if (empty($val[$setting_type])) {$val[$setting_type] = false;}
				$context[$setting_type] = $val[$setting_type];
		}			
	}
	$smcFunc['db_free_result']($result1);	
	
	/* Query all available playlists */
	$result = $smcFunc['db_query']('', "SELECT myplaylist, title, autofile FROM {db_prefix}premiumbeat_settings WHERE (myplaylist > 0)");
	while ($val = $smcFunc['db_fetch_assoc']($result))
	{	
		if ((int)$val['myplaylist'] == 0)
			continue;
		
		foreach (array('myplaylist', 'title', 'autofile') as $column)
		{
			if (empty($column))
				$column = false;
		}		
				
		$context['playlist_data'][] = array('id' => (int)$val['myplaylist'], 'title' => $val['title'], 'file' => $val['autofile']);
				
	}
	$smcFunc['db_free_result']($result);
	
	if ($context['playlist_file'] && $context['playlist_file'] != $context['filename'])
	{
		$value = $context['playlist_file'];
		$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_forum_settings SET filename = '{$value}' WHERE reference = 1 LIMIT 1");
	}
	
	/* END - Read databse entries for premiumbeat forum settings */		
	
	/*  Set the $context for the tabtitle, post url and all mp3 tags for the display template */
	$context['axisX'] = !empty($modSettings['premiumbeat_axisX']) ? (int)$modSettings['premiumbeat_axisX'] : 0;
	$context['axisY'] = !empty($modSettings['premiumbeat_axisY']) ? (int)$modSettings['premiumbeat_axisY'] : 0;
	$context['playlist_file'] = false;
	$context['settings_title'] = $txt['premiumbeat_forum'];
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat_settings;sa=PremiumbeatSettings;' . $context['session_var'] . '=' . $context['session_id'] . ';save';			
	/*  Display the template and all the tags.  */
	$context['sub_template'] = 'customMusic_settings_page';	
	$context['mylink'] = $txt['premiumbeat_disabled'];		
	if ($context['forumlink'] == 1) 
		$context['mylink'] = $txt['premiumbeat_enabled'];
	
	$context['myFlink'] = $txt['premiumbeat_disabled'];			
	if ((int)$modSettings['premiumbeat_link'] > 0) 
		$context['myFlink'] = $txt['premiumbeat_enabled'];
	
	$context['myFlinkDropdown'] = $txt['premiumbeat_disabled'];			
	if ((int)$context['forumlinkDrop'] > 0)
		$context['myFlinkDropdown'] = $txt['premiumbeat_enabled'];
		
	$context['toggle'] = '<script type="text/javascript">	
	function changeLinkD(){
	   document.getElementById("updateLink").innerHTML = "Enabled";
	}
	function changeLinkE(){
 	  document.getElementById("updateLink").innerHTML = "Disabled";
	}
	function changeFLinkD(){
	   document.getElementById("updateFLink").innerHTML = "Enabled";
	}
	function changeFLinkE(){
 	  document.getElementById("updateFLink").innerHTML = "Disabled";
	}	
	function changeFLinkDropdownD(){
	   document.getElementById("updateFLinkDropdown").innerHTML = "Enabled";
	}
	function changeFLinkDropdownE(){
 	  document.getElementById("updateFLinkDropdown").innerHTML = "Disabled";
	}	
	</script>'; 	
	loadTemplate('CustomMusicForum');	
}
	  
/* Check if the column exists */
function checkFieldPremium3($tableName,$columnName)
{
	$checkTable = false;
	$checkTable = check_table_existsPremium3($tableName);
	if ($checkTable == true)
	{
		global $db_prefix, $smcFunc;
		$check = false;
		$checkval = false;
		$check = $smcFunc['db_query']('', "DESCRIBE {$db_prefix}$tableName $columnName");
		$checkval = $smcFunc['db_num_rows']($check);
		$smcFunc['db_free_result']($check);
		if ($checkval > 0) {return true;}
	}
	return false;
} 

/*  Check if table exists  */
function check_table_existsPremium3($table)
{
	global $db_prefix, $smcFunc;
	$check = false;
	$checkval = false;
	$check = $smcFunc['db_query']('', "SHOW TABLES LIKE '{$db_prefix}$table'");
	$checkval = $smcFunc['db_num_rows']($check);
	$smcFunc['db_free_result']($check);
	if ($checkval >0) {return true;}
	return false;
}

/*  Update table -> column -> value for premiumbeat forum settings */
function createpremiumval_setting($tableName, $columnName, $tag, $valuex) 
{
	global $smcFunc, $db_prefix;
	$value = cleanPremiumbeatQuery($valuex);
	$i = 0;
	$request = false;   
	if (empty($tableName) || empty($columnName) || empty($tag)) {return;}
	if (empty($value)) {$value = false;}
	$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`reference` = {$tag} LIMIT 1");
							 
}	
	
/* Foldername filter */	
function premium_folder_filter($folder)
{	
	$filtered_folder = "";
	$patterns = array("/\&/","/\+/","/delete/i", "/update/i","/union/i","/insert/i","/drop/i","/http/i","/--/i");  
	$folder = preg_replace($patterns, "_" , $folder);
	for ($i=0;$i<strlen($folder);$i++)
	{
		$current_char = substr($folder,$i,1);
		if (ctype_alnum($current_char) == TRUE || $current_char == "_" || $current_char == "/" || $current_char == "-" || $current_char == " ")
			$filtered_folder .= $current_char;
	}  
	$filtered_folder = rtrim($filtered_folder, ' ');
	$filtered_folder = ltrim($filtered_folder, ' ');	   
	return $filtered_folder;
}	

/* Playlist number filter */	
function premium_number_filter($folder)
{	
	$filtered_folder = "";
	$patterns = array("/\s/","/\&/","/\+/","/\-/","/delete/i", "/update/i","/union/i","/insert/i","/drop/i","/http/i","/--/i");  
	$folder = preg_replace($patterns, "" , $folder);
	for ($i=0;$i<strlen($folder);$i++)
	{
		$current_char = substr($folder,$i,1);
		if (ctype_alnum($current_char) == TRUE)
			$filtered_folder .= $current_char;
	}     
	return $filtered_folder;
}	

/* mysql query filter */
function cleanPremiumbeatQuery($string)
{
	if(get_magic_quotes_gpc())  
		$string = stripslashes($string);
 
	if (phpversion() >= '4.3.0')
		$string = mysql_real_escape_string($string);
	else
		$string = mysql_escape_string($string);
  
	return $string;
}

/* Filter input for hex color */
function premium_hex_filterx($color)
{
	if ($color[0] == '#')
		$color = substr($color, 1);

	if (strlen($color) == 6)
		list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
	elseif (strlen($color) == 3)
		list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	else
		return false;

	$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	if (is_array($r) && sizeof($r) == 3)
		list($r, $g, $b) = $r;

	$r = intval($r); $g = intval($g);
	$b = intval($b);
	$r = dechex($r<0?0:($r>255?255:$r));
	$g = dechex($g<0?0:($g>255?255:$g));
	$b = dechex($b<0?0:($b>255?255:$b));

	$color = (strlen($r) < 2?'0':'').$r;
	$color .= (strlen($g) < 2?'0':'').$g;
	$color .= (strlen($b) < 2?'0':'').$b;
	return $color;
}	
?>