<?php
// Version: 2.0.1; premiumbeat

/*     Main admin settings file for the premiumbeat mp3 Mod     */
/*          c/o Underdog @ http://askusaquestion.net           */  
/*                     SMF 2 Version						*/

if (!defined('SMF'))
	die('Hacking attempt...');

function custom_mp3()
{
	global $scripturl, $txt, $context, $sourcedir, $boarddir;
		
	$context['robot_no_index'] = true;	
	require_once($sourcedir . '/ManageServer.php');	
	$context[$context['admin_menu_name']]['tab_data']['title'] = $txt['customMusic_tabtitle'];	
	
	/*  Request needed data from the url  */
	$context['lists']['mylist'] = !empty($_REQUEST['playlist']) ? (int) $_REQUEST['playlist'] : 0;	
	$context['edit_tag'] = !empty($_REQUEST['tag']) ? (int) $_REQUEST['tag'] : 0;	
	$context['xtag']['tag'] = !empty($_REQUEST['edit']) ? (int) $_REQUEST['edit'] : 0;	
	$context['queryPlaylist'] = !empty($_REQUEST['queryPlaylist']) ? (int) $_REQUEST['queryPlaylist'] : 0;	
	$context['compatFiles'] = !empty($_REQUEST['compatFiles']) ? (int) $_REQUEST['compatFiles'] : 0;
	$context['songs'] = !empty($_FILES['songs']) ? $_FILES['songs'] : array();	
	$context['delete_songs'] = !empty($_REQUEST['delete']) ? $_REQUEST['delete'] : array();	
	$context['toggle_songs'] = !empty($_REQUEST['toggle']) ? $_REQUEST['toggle'] : array();	
	$context['deletePL'] = !empty($_REQUEST['deletePL']) ? $_REQUEST['deletePL'] : array();	
	$context['download_songs'] = !empty($_REQUEST['download']) ? $_REQUEST['download'] : array();		
	$context['perm_id_group'] = !empty($_REQUEST['perms']) ? (int) $_REQUEST['perms'] : 0;
	$context['ax-file-path'] = !empty($_REQUEST['ax-file-path']) ? $_REQUEST['ax-file-path'] : false;	
	
	/* Delete outstanding temporary zip archives */	
	$dir_contents = array();
	$dir_contents = ReadMusicDirectory2($boarddir . '/my_music');
	foreach ($dir_contents as $file)
	{
		if (substr($file, -4) == '.zip')
			@unlink($boarddir . '/my_music/' . $file);	
	}
		
	/*  Fill $context with save data for the playlist if it exists  */
	$list_types = array('height', 'width', 'autoplay', 'type', 'skin', 'skin_type', 'autofile', 'title', 'equip', 'perms');	
	foreach ($list_types as $list_type)
		{						
			if (empty($_REQUEST[$list_type]))
				$_REQUEST[$list_type] = false;				
			
			$context['lists'][$list_type] = $_REQUEST[$list_type];					
			if (empty($context['lists']['width'][0])) {$context['lists']['width'][0] = -1;} 
			if (empty($context['lists']['height'][0])) {$context['lists']['height'][0] = -1;} 
			if ($context['lists']['width'][0] == false) {$context['lists']['width'][0] = -1;} 
			if ($context['lists']['height'][0] == false) {$context['lists']['height'][0] = -1;}					
		} 
	
	/*  Fill $context with save data for the new or edited tag if it exists  */	
	$edit_types = array('playlist', 'description', 'url', 'enable', 'id_user');	
	foreach ($edit_types as $edit_type)
		{			
			if (empty($_REQUEST[$edit_type])) {$_REQUEST[$edit_type] = false;}				
			$context['xtag'][$edit_type] = $_REQUEST[$edit_type];			
		}	
				
	$subActions = array(
					'BrowsePremiumbeat' => array('BrowseCustomMusic'),					
					'SettingsPremiumbeat' => array('SettingsCustomMusic'),
					'PlaylistPremiumbeat' => array('PlaylistCustomMusic'),
					'EditPremiumbeat' => array('EditCustomMusic'),
					'UploadPremiumbeat' => array('UploadCustomMusic'),	
					'LicensePremiumbeat' => array('LicensePremiumbeatPlayer'),		
	);
		
	if (empty($_REQUEST['sa']))
		$context['queryPlaylist'] = -1;	
	if (allowedTo('premiumbeat_config'))
		$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : "BrowsePremiumbeat"; 
	elseif (allowedTo('premiumbeatSettings'))
		$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : "SettingsPremiumbeat"; 
	elseif (allowedTo('premiumbeat_showupload') || allowedTo('premiumbeat_showdownload'))
		$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : "UploadPremiumbeat";
	else	 				
		$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : "LicensePremiumbeat"; 
		
	$subActions[$_REQUEST['sa']][0]();
}

function BrowseCustomMusic()
{

	global $txt, $scripturl, $context, $smcFunc, $sourcedir, $db_prefix, $user_info;
	loadLanguage('Modifications');	
	$context['tag'] = array();
	$context['robot_no_index'] = true;		
	$datum = array('id', 'user_id', 'my_playlist', 'file', 'author', 'date', 'downloads');
	foreach($datum as $data)
		$browse_data[$data] = false;
	
	$playlist = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 0;	
	$user = (int)$user_info['id'];
	
	/* Check if the user has a playlist preference */
	if (((int)$user_info['id']) > 0 && check_user($user))
	{	
		$result1 = $smcFunc['db_query']('', "SELECT user_id, last_playlist FROM {db_prefix}premiumbeat_users WHERE (user_id = '{$user}' AND last_playlist) LIMIT 1");
		while ($val = $smcFunc['db_fetch_assoc']($result1))
		{			
			if ((int)$val['last_playlist'] > 0 && $context['queryPlaylist'] == 0 && $playlist > -1)	
				$playlist = (int)$val['last_playlist'];
		}
		$smcFunc['db_free_result']($result1);	
	}
		
	if ($playlist < 0)
		$playlist = 0;
			
	if ((int)$context['queryPlaylist'] > 0)
	{	
		if (!check_user($user))
			$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`, `visibility`) 
												VALUES ('{$user}', '{$context['queryPlaylist']}', '0', '0', '0', '0')");
		else															
			$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = '{$context['queryPlaylist']}'
												 WHERE (user_id = '{$user}')");		
	}	
	elseif ($playlist == 0)
	{	
		if (!check_user($user))
			$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`, `visibility`) 
												VALUES ('{$user}', '{$playlist}', '0', '0', '0', '0')");
		else															
			$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = '{$playlist}'
												 WHERE (user_id = '{$user}')");		
	}		
		
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['customMusic_tabtitle2'];
	$a = false;
	$count = 0;
	$tableName = 'premiumbeat_mp3s';
	$columns_mp3s = array('tag', 'playlist', 'enable', 'description', 'url', 'id_user');
	$edit_types = array('playlist', 'enable', 'description', 'url');	
	if (empty($context['xtag']['tag'])) {$context['xtag']['tag'] = 0;}
	$a = false;
	$mytag = $context['xtag']['tag'];
	/*  Insert bogus values for a new tag - the actual data can be updated in the next query  */
	if ($mytag > 0)
		{
			$a = check_mp3($mytag);
			if ($a == false)
				{
					$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_mp3s WHERE tag LIKE {string:mytag}',
														array('mytag' => $mytag));
					$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_mp3s (`tag`, `playlist`, `enable`, `description`, `url`, `id_user`) 
															VALUES ({int:mytagx} , '1', '1', false, false, {int:userx})",array('mytagx' => $mytag, 'userx' => $user,));
				}
		}
	/*  Check for new/edit mp3 values and save to database if necessary */
	foreach ($edit_types as $edit_type)
		{	
			if ($edit_type == 'playlist' && (int)$context['xtag'][$edit_type] > 0)
			{
				$plist = (int)$context['xtag'][$edit_type];
				if (!check_user($user))
					$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`) 
											VALUES ('{$user}', '{$plist}', '0', '0', '0')");
				else															
					$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = '{$plist}'
											 WHERE (user_id = '{$user}')");			
			}	
			
			if (empty($context['xtag'][$edit_type])) {$context['xtag'][$edit_type] = false; continue;}	
			if ($context['xtag']['tag'] > 0 && (int)$context['xtag']['enable'] > 1 && $edit_type == 'enable')	
				{	
					$columnName = 'enable';	
					$tag = $context['xtag']['tag'];
					$value = 0;			
					$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`tag` = {$tag} LIMIT 1");
					continue;
				}		
			if ($context['xtag'][$edit_type] == true && $context['xtag']['tag'] > 0) 
				{									 					
					$tag = $context['xtag']['tag'];
					$value = $context['xtag'][$edit_type];
					if ($edit_type == 'url' && $value == true)
						{$value = substr($value, 0, 254);}																		
					createpremiumval_mp3('premiumbeat_mp3s', $edit_type, $tag, $value);				
				}		
		} 
	
	
	/* START - Read database entries for mp3's */
	$a = false;
	$a = checkFieldPremium2('premiumbeat_mp3s','playlist');
	if ($a == false) {fatal_lang_error('customMusic_error', false);}
	$list = array();
	$result1 = array();
	$val = false;
	$song = 1;	
	$check_playlist = array();
	if ((!empty($playlist)) && (int)$playlist > 0)
		$query = 'playlist=' . (int)$playlist;		
	else
		$query = 'tag > 0';
			
	$result1 = $smcFunc['db_query']('', "SELECT tag, playlist, enable, description, url, id_user, settings.title FROM {db_prefix}premiumbeat_mp3s
										 LEFT JOIN {db_prefix}premiumbeat_settings AS settings ON (settings.myplaylist = playlist)	
										 WHERE {$query} ORDER BY playlist ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
		{	
			if ((empty($val['url'])) || (empty($val['tag']))) {continue;}	 
			if ($val['tag'] == 0) {continue;}	
			$check_playlist[$song-1] = $val['playlist'];
			if (empty($val['description'])) {$val['description'] = $txt['none'];}                          
			$context['tag'][$song] = $val['tag'];
			$context['playlist'][$song] = $val['playlist'];
			$context['playlist_id'][$song] = $context['playlist'][$song];
			$context['enable'][$song] = $val['enable'];
			$context['description'][$song] = $val['description'];
			$context['url'][$song] = $val['url'];	
			$context['id_user'][$song] = $val['id_user'];
			$context['title'][$song] = !empty($val['title']) ? $val['title'] : false;
			if ($context['enable'][$song] == 1) {$context['status'][$song] = $txt['premiumbeat_enabled'];}
			else {$context['status'][$song] = $txt['premiumbeat_disabled'];}		
			$song++;
					
		}
	$smcFunc['db_free_result']($result1);
	$context['premiumbeat_count'] = $song;
	$count = $song -1;		
	/* END - Read databse entries for mp3's */
	
	/* Fill playlist with default data if the needed playlist data does not exist */
	foreach ($check_playlist as $play)
		{
			if(empty($play)) {continue;}
			$a = false;
			$a = check_playlist($play);
			if ($a == false)
				{
					$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_settings WHERE myplaylist LIKE {string:list}',
														array('list' => $play));
					$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_settings (`myplaylist`, `height`, `width`, `autoplay`, `type`, `skin`, `skin_type`, `autofile`, `title`, `equip`) 
															VALUES ({int:val} , '215', '200', '1', '0', '000000', '1', false, false, '0')",array('val' => $play,));	
				}
		
		}
		
	/*   Saving?   */
	if (isset($_REQUEST['save']))
		{

			/*  Mark the ones to delete.  */
			if (isset($_POST['delete']) && is_array($_POST['delete']))
				{
					$delete_tags = $_POST['delete'];								
					$songs = 1;									
					foreach ($context['tag'] as $song)
						{							
							$i = (int)$song;							
							if ($user != $context['id_user'][$i] && !allowedTo('premiumbeat_settings')) 
								continue;
																									
							/* Delete the mp3 if it was opted */
							foreach ($delete_tags as $check_tag)
								{
									if (!allowedTo('premiumbeat_showdelete'))
										continue;
										
									if ((int)$check_tag == $i)
										{																			
											$tableName = 'premiumbeat_mp3s';											
											$request = $smcFunc['db_query']('', "DELETE FROM `{db_prefix}$tableName` WHERE `{db_prefix}$tableName`.`tag` = '$i'");														
											
										}			
								}
									
						}						
				}				
			elseif (isset($_POST['enable']) && is_array($_POST['enable']))
				{					
					$enable_tags = $_POST['enable'];					
					$songs = 1;	
					$toggle = 0;						
					foreach ($context['tag'] as $song)
						{							
							$i = (int)$song;
							if ($user != $context['id_user'][$i] && !allowedTo('premiumbeat_settings')) 
								continue;
															
							foreach ($enable_tags as $check_mp3x)
								{									
									if ((int)$check_mp3x == $i)								
										{																				
											$request = $smcFunc['db_query']('', 'UPDATE {db_prefix}premiumbeat_mp3s SET enable = IF(enable=1, 0, 1)
																				 WHERE tag = {int:mp3}',array('mp3' => $i,'change' => $toggle,));									 
										}	
											
								}								
						}							
				}	
			elseif (isset($_POST['reset']) && is_array($_POST['reset']))
				{
					$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = 0 WHERE (user_id = '{$user_info['id']}') LIMIT 1");					
					redirectexit('action=admin;area=premiumbeat;sa=BrowsePremiumbeat;queryPlaylist=0;' . $context['session_var'] . '=' . $context['session_id'] . ';');	
				}			
			else 
				{$delete_tags = array(); $enable_tags = array(); $track = 0;}		
		 
			redirectexit('action=admin;area=premiumbeat;sa=BrowsePremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';');
		}

	/*  Set the $context for the tabtitle, post url and all mp3 tags for the display template */
	$context['settings_title'] = $txt['customMusic_tabtitle2'];
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=BrowsePremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';save';			
	/*  Display the template and all the tags.  */
	$context['sub_template'] = 'customMusic_page';		
	$context['check_all'] = '<script type="text/javascript">
	function Check(chk)
	{		
		if(document.mysongs.Check_All.value=="Check All")
		{
			for (i = 0; i < chk.length; i++)
			chk[i].checked = true ;
			document.mysongs.Check_All.value="UnCheck All";
		}
		else
		{
			for (i = 0; i < chk.length; i++)
			chk[i].checked = false ;
			document.mysongs.Check_All.value="Check All";
		}
	}
	</script>'; 
	$context['musicAdmin_pages'] = '<script type="text/javascript"><!--
        var pager = new Pager("mp3List", 15); 
        pager.init(); 
        pager.showPageNav("pager", "mp3PagePosition"); 
        pager.showPage(1);
    //--></script>';	
	$context['premiumbeat_confirm'] = '<script type="text/javascript">
					function confirmSubmit()
					{
						var agree=confirm("'.$txt['premiumbeat_confirm'].'");
						if (agree)
							return true ;
						else
							return false ;
					}
					</script>';		
	loadTemplate('CustomMusic');	
}

function EditCustomMusic()
{
	
	global $txt, $scripturl, $context, $smcFunc, $user_info;
	loadLanguage('Modifications');	
	$context['robot_no_index'] = true;		
	$context['premiumbeat_edit'] = $txt['premiumbeat_edit'];
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['customMusic_tabtitle5'];
	$context['queryPlaylist'] = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 0;
	$edit_types = array('playlist', 'enable', 'description', 'url', 'id_user');	
	$a = false;
	$a = checkFieldPremium2('premiumbeat_mp3s','tag');
	if ($a == false) {fatal_lang_error('customMusic_error', false);}	
	$edit_tag = !empty($_REQUEST['tag']) ? (int) $_REQUEST['tag'] : 0;		
	$result1 = array();
	$val = false;
	$song = 1;
	$count = 0;
	$context['playlist_id'] = array();
	$context['playlist_data'] = array();
	$result1 = $smcFunc['db_query']('', "SELECT tag, playlist, enable, description, url, id_user FROM {db_prefix}premiumbeat_mp3s WHERE (tag > 0) ORDER BY tag ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
	{	
		if ((empty($val['url'])) || (empty($val['tag']))) {continue;}	 
		if ($val['tag'] == 0) {continue;}	
		if (empty($val['description'])) {$val['description'] = $txt['none'];}                          
		$context['tag'][$song] = $val['tag'];
		$context['playlist'][$song] = $val['playlist'];
		$context['playlist_id'][$song] = $context['playlist'][$song];
		$context['enable'][$song] = $val['enable'];
		$context['description'][$song] = $val['description'];
		$context['url'][$song] = $val['url'];	
		$context['id_user'][$song] = $val['id_user'];	
		if ($context['enable'][$song] == 1) {$context['status'][$song] = $txt['premiumbeat_enabled'];}
		else {$context['status'][$song] = $txt['premiumbeat_disabled'];}		
		$song++;		
	}
	$smcFunc['db_free_result']($result1);
	$context['premiumbeat_count'] = $song;
	$count = $song;		
	/* END - Read databse entries for mp3's */
	
	/* Check if the user has a playlist preference */
	if (((int)$user_info['id'] > 0) && check_user($user_info['id']))
	{	
		$result1 = $smcFunc['db_query']('', "SELECT user_id, last_playlist FROM {db_prefix}premiumbeat_users WHERE (user_id = '{$user_info['id']}' AND last_playlist) LIMIT 1");
		while ($val = $smcFunc['db_fetch_assoc']($result1))
		{			
			if ((int)$val['last_playlist'] > 0)	
				$context['queryPlaylist'] = (int)$val['last_playlist'];
		}
		$smcFunc['db_free_result']($result1);	
		
		if ($context['queryPlaylist'] < 1)
			$context['queryPlaylist'] = 1;
	
	}
	
	/* Query all available playlists */
	$result = $smcFunc['db_query']('', "SELECT myplaylist, title FROM {db_prefix}premiumbeat_settings WHERE (myplaylist > 0)");
	while ($val = $smcFunc['db_fetch_assoc']($result))
	{	
		if ((int)$val['myplaylist'] == 0)
			continue;
		
		$title = false;
		$playlistid = (int)$val['myplaylist'];	
		if (!empty($val['title']))
			$title = $val['title'];		
							
		$context['playlist_data'][] = array('id' => $playlistid, 'title' => $title);
				
	}
	$smcFunc['db_free_result']($result);	
	        
	/*  Determine the next available numerical tag reference or if the tag being edited exists */
	$context['number_tag'] = 1;
	$context['track_id'] = 0;
	$i = 1;	
	$mycheck = false;
	$edit = false;	
	if ($count > 0)
		{			
			while ($i < ($count + 1))
				{	
					if (empty($context['tag'][$i])) {$context['tag'][$i] = $i;}	
					if ($context['tag'][$i] == (int)$edit_tag)
						{
							$edit = true;
							$mycheck = true;
							$context['number_tag'] = (int)$edit_tag;
							$tag = $context['number_tag'];
							$context['track_id'] = $i;	
							break;												
						}	
											
					if (($context['tag'][$i] != $i) && $mycheck == false)
						{																										
							$mycheck = true;
							$context['premiumbeat_edit'] = $txt['premiumbeat_edit_add'];
							$context['number_tag'] = $i;
							$tag = $context['number_tag'];
							$context['track_id'] = $i;	
							foreach ($edit_types as $mytype)
								{$context[$mytype][$i] = false;}
							$context['playlist'][$i] = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 1;
							$context['enable'][$i] = 1;																				
						}					
					
					$i++;						
				}			
		}		
	if ($context['track_id'] == 0) 
		{
			$context['premiumbeat_edit'] = $txt['premiumbeat_edit_add'];
			$context['track_id'] = $count;
			$context['number_tag'] = $count; 
			$context['playlist'][$count] = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 1;
			$context['enable'][$count] = 1;
			$context['description'][$count] = false;
			$context['url'][$count] = false;
			$context['id_user'][$count] = 0;
		}		
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=BrowsePremiumbeat;edit=' . $context['number_tag'] . ';' . $context['session_var'] . '=' . $context['session_id'] . ';save;';
	$context['settings_title'] = $txt['customMusic_tabtitle'];	
	/*  Display the edit mp3 template.  */
    $context['sub_template'] = 'customMusic_edit';
	$context['xenable'] = $txt['premiumbeat_disabled'];	
	$track = $context['track_id'];	
	if ($context['enable'][$track] == 1) {$context['xenable'] = $txt['premiumbeat_enabled'];}	
	$context['toggle'] = '<script type="text/javascript">
	function changeEnableD(){
 	  document.getElementById("updateEnable").innerHTML = "'.$txt['premiumbeat_disabled'].'";
	}
	function changeEnableE(){
 	  document.getElementById("updateEnable").innerHTML = "'.$txt['premiumbeat_enabled'].'";
	}
	</script>'; 	
	loadTemplate('CustomMusicEdit');	
}

function SettingsCustomMusic()
{
	global $txt, $scripturl, $context, $smcFunc, $db_prefix, $sourcedir;
	loadLanguage('Modifications');	
	$context['sets'] = array();
	$context['robot_no_index'] = true;		
	require_once($sourcedir . '/Subs-Members.php');
	if (!empty($context['queryPlaylist']))
		{			
			if ((int)$context['queryPlaylist'] > 0)
				{
					redirectexit('action=admin;area=premiumbeat;sa=BrowsePremiumbeat;queryPlaylist=' . (int)$context['queryPlaylist'] . ';' . $context['session_var'] . '=' . $context['session_id'] . ';');
				}
			
		}	
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['customMusic_tabtitle3'];
	$a = false;
	$b = false;
	$count = 0;
	$columns_settings = array('myplaylist', 'type', 'height', 'width', 'autoplay', 'skin', 'skin_type', 'autofile', 'title', 'equip');
	$permission_sets = array('id_group', 'permission', 'add_deny');
	$a = checkFieldPremium2('premiumbeat_settings','myplaylist');
	$b = checkFieldPremium2('premiumbeat_mp3s','tag');
	if ($a == false || $b == false) {fatal_lang_error('customMusic_error', false);}	
	$result1 = array();
	$playlists = 1;
	$track = 0;
	$context['all_playlists'] = array();
	$checkit = false;
	
	/* Are we creating a new playlist? */
	if ($context['lists']['title'] && (int)$context['lists']['mylist'] == 0)
		createPlaylist($context['lists']);
		
	foreach ($context['deletePL'] as $delete)
	{
		if ((int)$delete == 0 || !allowedTo('premiumbeat_showdelete'))
			continue;
		
		$delete = (int)$delete;	
		$request = $smcFunc['db_query']('', "DELETE FROM {db_prefix}premiumbeat_settings WHERE myplaylist LIKE '{$delete}'");	
		$request = $smcFunc['db_query']('', "DELETE FROM {db_prefix}premiumbeat_mp3s WHERE playlist LIKE '{$delete}'");	
		$request = $smcFunc['db_query']('', "DELETE FROM {db_prefix}premiumbeat_data WHERE my_playlist LIKE '{$delete}'");	
		
	}
	
	$check_list = 0;
	if (!empty($context['lists']['mylist'])) {$check_list = $context['lists']['mylist'];}
	$list_types = array('height', 'width', 'autoplay', 'type', 'skin', 'skin_type', 'autofile', 'title', 'equip', 'perms');	
	foreach ($list_types as $list_type)
		{
			if (empty($context['lists'][$list_type][0])) {$context['lists'][$list_type][0] = false;}			
			if ($context['lists'][$list_type][0] == true && $check_list > 0) 
				{					
					$value = $context['lists'][$list_type][0];	
					if ((int)$value < 0) {$value = 0;}				 			
					if ($list_type == 'autoplay')
						{
							if ((int)$value > 1) {$value = 0;}
							$request = $smcFunc['db_query']('', 'UPDATE {db_prefix}premiumbeat_settings SET autoplay = {int:autox}
																				 WHERE myplaylist = {int:listx}',array('listx' => $check_list, 'autox' => $value));
							continue;
						}
					elseif ($list_type == 'equip')
						{
							if ((int)$value > 1) {$value = 0;}
							$request = $smcFunc['db_query']('', 'UPDATE {db_prefix}premiumbeat_settings SET equip = {int:equipx}
																				 WHERE myplaylist = {int:listx}',array('listx' => $check_list, 'equipx' => $value,));
							continue;
						}			
					elseif ($list_type == 'type')
						{
							if ((int)$value > 1) {$value = 0;}
							$request = $smcFunc['db_query']('', 'UPDATE {db_prefix}premiumbeat_settings SET type = {int:typex}
																				 WHERE myplaylist = {int:listx}',array('listx' => $check_list, 'typex' => $value,));
							continue;
						}					
					elseif ($list_type == 'skin')
						{
							$value2 = premium_hex_filter($value);
							createpremiumval_settings('premiumbeat_settings', $list_type, $value2, $check_list);	
						}
					elseif ($list_type == 'skin_type')
						{
							if ((int)$value < 1 || (int)$value > 5)
								$value = 1;
								
							$value5 = (int)$value;
							createpremiumval_settings('premiumbeat_settings', $list_type, $value5, $check_list);	
						}	
					elseif ($list_type == 'autofile')
						{							
							$value3 = premium_folder_filter2($value);
							createpremiumval_settings('premiumbeat_settings', $list_type, $value3, $check_list);	
						}	
					elseif ($list_type == 'title')
						{
							$value4 = premium_folder_filter2(formspecialcharsformeagain3($value, false));
							createpremiumval_settings('premiumbeat_settings', $list_type, $value4, $check_list);	
						}		
					elseif ($list_type == 'perms' && $context['lists']['perms'][0] == true)
					{
						$columnName1 = 'premiumbeat_playlist_'. $context['lists']['mylist'];
						$columnName2 = 'premiumbeat_playlist_*';
						$tableName2 = 'permissions';
						if ($context['lists']['perms'][0] == true)
							$request = $smcFunc['db_query']('', "DELETE FROM `{db_prefix}$tableName2` WHERE `{db_prefix}$tableName2`.`permission` = '$columnName1'");
						foreach ($context['lists']['perms'] as $value)
						{
							$value = (int)$value;									
							if ($value == 1) {$value = 999;}	
							$request = $smcFunc['db_query']('', "DELETE FROM `{db_prefix}$tableName2` 
							WHERE (`{db_prefix}$tableName2`.`id_group` LIKE '$value') AND (`{db_prefix}$tableName2`.`permission` RLIKE '$columnName2')");																		
							if ($value != -999)
							{						
								$request = $smcFunc['db_query']('', "INSERT INTO `{db_prefix}$tableName2` (`id_group`, `permission`, `add_deny`)
								VALUES ('$value' , '$columnName1', '1')");
							}		
						}	
						continue;	
					} 		
					else
						createpremiumval_settings('premiumbeat_settings', $list_type, $value, $check_list);	
						
					
				}
			elseif ($context['lists'][$list_type][0] == false && $check_list > 0 && $list_type == 'autofile') 
				createpremiumval_settings('premiumbeat_settings', $list_type, false, $check_list);	
			elseif ($context['lists'][$list_type][0] == false && $check_list > 0 && $list_type == 'title') 
				createpremiumval_settings('premiumbeat_settings', $list_type, false, $check_list);					
		}		

	/*  Retrieve all the different playlists from the mp3 list  */
	$result1 = $smcFunc['db_query']('', "SELECT playlist FROM {db_prefix}premiumbeat_mp3s WHERE (tag > 0) ORDER BY tag ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
		{	
			if (empty($val['playlist'])) {continue;}
			$tracking[$track] = $val['playlist'];
			foreach ($tracking as $tracks)
				{					
					if (!in_array($tracks, $context['all_playlists']))
					 	{   							
							$context['all_playlists'][$playlists] = $val['playlist'];	
							$playlists++;
						}	
				}			
			
			$track++;	
		}
	$smcFunc['db_free_result']($result1);	
	asort($context['all_playlists']);
	$check_playlists = array();
	$result1 = array();
	$request = array();
	$myval = false;
	$a = false;
	
	foreach ($context['all_playlists'] as $myval)
		{			
			$a = check_playlist($myval);
			if ($a == false)
				{					
					$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_settings WHERE myplaylist LIKE {string:list}',
														array('list' => $myval));
					$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_settings (`myplaylist`, `height`, `width`, `autoplay`, `type`, `skin`, `skin_type`, `autofile`, `title`, `equip`) 
															VALUES ({int:val} , '215', '200', '1', '0', '000000', '1', false, false, '0')",array('val' => $myval,));
				}	
		}		
	$i = 0;	
	$result1 = $smcFunc['db_query']('', "SELECT myplaylist, height, width, autoplay, type, skin, autofile, title, equip FROM {db_prefix}premiumbeat_settings																				
										WHERE (myplaylist > 0) ORDER BY myplaylist ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
		{	
			if ((empty($val['myplaylist']))) {continue;}	 
			if ((int)$val['myplaylist'] == 0) {continue;}					
			foreach ($columns_settings as $sets)
				{	
					if (empty($val[$sets])) {$val[$sets] = 0;} 				
					if ($sets == 'autofile' && (empty($val['autofile']) || $val['autofile'] == '0'))
						$val['autofile'] = false;				
					elseif ($sets == 'title' && (empty($val['title']) || $val['title'] == '0'))
						$val['title'] = false;
												
					$context['sets'][$i][$sets] = $val[$sets]; 	
				}				
			$i++;		
		}
	$smcFunc['db_free_result']($result1);	
	
	/* Double check to make sure the playlist exists in the main mp3 list - if not then delete it */	
	$reset_page = false;	
	foreach ($context['sets'] as $check)
	{
		$checkit2 = false;
		$checkit = $check['myplaylist'];
		if ((int)$checkit > 99)
			$checkit2 = true;
				
		$perm1 = 'premiumbeat_playlist_' . $checkit;
		$mytableName2 = 'permissions';		
		
		if ($checkit2)
			{
				$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_settings WHERE myplaylist LIKE {string:list}',
														array('list' => $checkit));
				$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_data WHERE my_playlist LIKE {string:list}',
														array('list' => $checkit));										
				$request = $smcFunc['db_query']('', "DELETE FROM `{db_prefix}$mytableName2` WHERE `{db_prefix}$mytableName2`.`permission` = '$perm1'");								
				$reset_page = true;
				continue;										
			}				
	}		
	
	if ($reset_page == true) {redirectexit('action=admin;area=premiumbeat;sa=SettingsPremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';');}	
	
	/* Query playlist permissions */
	$result2 = array();	
	$context['group_names'][] = array();	
	$z = 0;
	$result2 = $smcFunc['db_query']('', 'SELECT	id_group, group_name FROM {db_prefix}membergroups WHERE id_group > 0 ORDER BY id_group');
	while ($val = $smcFunc['db_fetch_assoc']($result2))
			{
				if ((int)$val['id_group'] < 1) {continue;}
				$id = $val['id_group'];
				if (empty($val['group_name'])) {$val['group_name'] = false;}				
				$context['group_names'][$id] = substr($val['group_name'], 0, 60);
				$context['groups'][$z] = substr($val['group_name'], 0, 60);
				$context['group_id'][$z] = $id;
				$z++;
			}
	$smcFunc['db_free_result']($result2);
	$z = 0;
	foreach ($context['sets'] as $check)
	{
		$playlist = $check['myplaylist'];
		$context['id_group'][$playlist] = false;		
		$allow = 'premiumbeat_playlist_'. $playlist;
		$groups = groupsAllowedTo($allow);		
		$check_group = array_unique($groups['allowed']);
		if (empty($check_group[1])) {$check_group[1] = 0;}	
		if ($check_group[1] == false) {$context['id_group'][$playlist] = $txt['premiumbeat_na'];continue;}		
		foreach ($check_group as $groupid)
		{
			if ((int)$groupid == 0 || (int)$groupid == 1) {continue;}
			if ((int)$groupid == 999) {$context['id_group'][$playlist] .= $context['groups'][0] . ', '; continue;}
			if ((int)$groupid == -1) {$context['id_group'][$playlist] .= $txt['guest_title'] . ', '; continue;}			
			$group = $context['group_names'][$groupid];	
			if ($group == false) {$context['id_group'][$playlist] = $txt['premiumbeat_na'];continue;}								
			$context['id_group'][$playlist] .= $group . ', ';
			$context['premium_groups'][$z] = $group;			
		}
		$context['id_group'][$playlist] = rtrim($context['id_group'][$playlist], ', ');
		$z++;	
	}									
	/*  Display all the mp3 playlist settings  */	
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=SettingsPremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';';
	$context['sub_template'] = 'customMusic_settings';	
	$context['musicAdmin_pages2'] = '<script type="text/javascript"><!--
        var pager = new Pager("mp3List2", 10); 
        pager.init(); 
        pager.showPageNav("pager", "mp3PagePosition2"); 
        pager.showPage(1);
    //--></script>';
	$context['toggle'] = '<script type="text/javascript">
	function changeTypeA(){
   	document.getElementById("updateDir").innerHTML = "'.$txt['premiumbeat_asc'].'";
	}
	function changeTypeS(){
   	document.getElementById("updateDir").innerHTML = "'.$txt['premiumbeat_shuffle'].'";
	}
	function changeAutoD(){
   	document.getElementById("updateAuto").innerHTML = "'.$txt['premiumbeat_enabled'].'";
	}
	function changeAutoE(){
   	document.getElementById("updateAuto").innerHTML = "'.$txt['premiumbeat_disabled'].'";
	}
	function changeTypeX(){
 	  document.getElementById("updateEquip").innerHTML = "'.$txt['premiumbeat_enabled'].'";
	}
	function changeTypeY(){
 	  document.getElementById("updateEquip").innerHTML = "'.$txt['premiumbeat_disabled'].'";
	}	
	</script>'; 	
	$context['premiumbeat_confirm'] = '<script type="text/javascript">
					function confirmSubmit()
					{
						var agree=confirm("'.$txt['premiumbeat_confirm'].'");
						if (agree)
							return true ;
						else
							return false ;
					}
					</script>';			
	loadTemplate('CustomMusicSettings');	
}
 
function PlaylistCustomMusic()
{
	global $txt, $scripturl, $context, $smcFunc, $sourcedir;
	loadLanguage('Modifications');	
	$context['robot_no_index'] = true;		
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['customMusic_tabtitle4'];
	$a = false;		
	$count = 0;
	$context['mysets'] = array();
	$result1 = array();
	$checkit = false;
	$columns_settings = array('myplaylist', 'type', 'height', 'width', 'autoplay', 'skin', 'skin_type', 'autofile', 'title', 'equip');
	$context['template'] = 'CustomMusicPlaylist';
	$context['sub_template'] = 'customMusic_edit_playlist';
	$check_track = !empty($_REQUEST['playlist']) ? (int) $_REQUEST['playlist'] : 0;
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=SettingsPremiumbeat;' . 'playlist=' . (int)$check_track . ';' . $context['session_var'] . '=' . $context['session_id'] . ';save'; 
	require_once($sourcedir . '/Subs-Members.php');
	/* Query playlist permissions */
	$result2 = array();	
	$context['group_names'][] = array();	
	$z = 0;
	$result2 = $smcFunc['db_query']('', 'SELECT	id_group, group_name FROM {db_prefix}membergroups WHERE id_group > 0 ORDER BY id_group');
	while ($val = $smcFunc['db_fetch_assoc']($result2))
	{
		if ((int)$val['id_group'] < 1) {continue;}
		$id = $val['id_group'];
		if (empty($val['group_name'])) {$val['group_name'] = false;}				
		$context['group_names'][$id] = substr($val['group_name'], 0, 60);
		$context['groups'][$z] = substr($val['group_name'], 0, 60);
		$context['group_id'][$z] = $id;
		if ($id == 0) {$context['group_id'][$z] = 999;}
		$z++;
	}
	$smcFunc['db_free_result']($result2);	
	
	$allow = 'premiumbeat_playlist_'. $check_track;
	$groups = groupsAllowedTo($allow);		
	$check_group = array_unique($groups['allowed']);
	$context['id_group'] = false;
	if (empty($check_group[1])) 
		$check_group[1] = 0;
		
	if ($check_group[1] == false)
		$context['id_group'] = $txt['premiumbeat_na'];
				
	foreach ($check_group as $groupid)
	{
		if ((int)$groupid == 0 || (int)$groupid == 1) 
			continue;
			
		if ((int)$groupid == 999) 
			$context['id_group'] .= $context['groups'][0] . ', '; continue;
			
		if ((int)$groupid == -1) 
			$context['id_group'] .= $txt['guest_title'] . ', '; continue;
						
		$group = $context['group_names'][$groupid];	
		if ($group == false)
			$context['id_group'] = $txt['premiumbeat_na'];continue;
											
		$context['id_group'] .= $group . ', ';
		$context['premium_groups'] = $group;			
	}
	
	$context['id_group'] = rtrim($context['id_group'], ', ');
					
	/*  Check if we're editing a specific playlist  */		
	if ($check_track > 0) 
		$checkit = check_playlist($check_track);
		 		
	$a = checkFieldPremium2('premiumbeat_settings','myplaylist');		
	if ($a == false)
		fatal_lang_error('customMusic_error2', false);
			
	/*  Check if viewing query specific playlist template  */
	if ($checkit == false)
	{
		$context['template'] = 'CustomMusicPlaylistQuery';
		$context['sub_template'] = 'customMusic_query_playlist';
		$context['mysets']['type'] = false;
		$context['mysets']['autoplay'] = false;				
		$context['post_url'] = $scripturl . '?action=custom_mp3;sa=SettingsPremiumbeat;save'; 
		$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=SettingsPremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';save'; 
	}
		
	$i = 0;
	$result1 = $smcFunc['db_query']('', "SELECT myplaylist, height, width, autoplay, type, skin, skin_type, autofile, title, equip FROM {db_prefix}premiumbeat_settings 
												WHERE (myplaylist = {$check_track}) LIMIT 1");
	while ($val = $smcFunc['db_fetch_assoc']($result1))
	{	
		if ((empty($val['myplaylist']))) 
			continue;
				 
		if ((int)$val['myplaylist'] < 1)
			continue;
			
		foreach ($columns_settings as $sets)
		{
			if (empty($val[$sets])) 
				$val[$sets] = 0;
				 				
			if ($sets == 'autofile' && (empty($val['autofile']) || $val['autofile'] == '0'))
				$val['autofile'] = false;
			elseif ($sets == 'title' && (empty($val['title']) || $val['title'] == '0'))
				$val['title'] = false;
			elseif ($sets == 'skin' && (empty($val['skin']) || $val['skin'] == '0'))
				$val['skin'] = '000000';
			elseif ($sets == 'skin_type' && (empty($val['skin_type']) || ((int)$val['skin_type'] < 1 || (int)$val['skin_type'] > 5)))
				$val['skin_type'] = 1;	
								
			$context['mysets'][$sets] = $val[$sets]; 	
		}				
		$i++;		
	}
	$smcFunc['db_free_result']($result1);			
	
	/*  Display the edit template for the opted playlist settings */    
	$context['xtype'] = $txt['premiumbeat_shuffle'];
	$context['xauto'] = $txt['premiumbeat_disabled'];
	$context['equip'] = $txt['premiumbeat_disabled'];
	if ($context['mysets']['type'] == 1) 
		$context['xtype'] = $txt['premiumbeat_asc'];
					
	if ($context['mysets']['autoplay'] == 1)
		$context['xauto'] = $txt['premiumbeat_enabled'];
			
	if ((!empty($context['mysets']['equip'])) && $context['mysets']['equip'] == 1)
		$context['equip'] = $txt['premiumbeat_enabled'];
		
	$context['toggle'] = '<script type="text/javascript">
	function changeTypeA(){
   	document.getElementById("updateDir").innerHTML = "'.$txt['premiumbeat_asc'].'";
	}
	function changeTypeS(){
   	document.getElementById("updateDir").innerHTML = "'.$txt['premiumbeat_shuffle'].'";
	}
	function changeAutoD(){
   	document.getElementById("updateAuto").innerHTML = "'.$txt['premiumbeat_enabled'].'";
	}
	function changeAutoE(){
   	document.getElementById("updateAuto").innerHTML = "'.$txt['premiumbeat_disabled'].'";
	}
	function changeTypeX(){
 	  document.getElementById("updateEquip").innerHTML = "'.$txt['premiumbeat_enabled'].'";
	}
	function changeTypeY(){
 	  document.getElementById("updateEquip").innerHTML = "'.$txt['premiumbeat_disabled'].'";
	}
	function showPermissions()
	{
		document.getElementById("perm1").style.visibility="visible";
	}
	function Check(chk)
	{
		if(document.myselect.Check_All.value=="Check All")
		{
			for (i = 0; i < (chk.length - 1); i++)
			chk[i].checked = true ;
			document.myselect.Check_All.value="UnCheck All";
		}
		else
		{
			for (i = 0; i < (chk.length - 1); i++)
			chk[i].checked = false ;
			document.myselect.Check_All.value="Check All";
		}
	}
	</script>'; 	
	loadTemplate($context['template']);
}
	 
/* Upload/Download mp3 files */	 
function UploadCustomMusic()
{

	global $txt, $scripturl, $context, $smcFunc, $sourcedir, $boarddir, $user_info;
	loadLanguage('Modifications');		
	$context['robot_no_index'] = true;		
	$tempDir = $boarddir . '/mp3_music/';
	$count = 1;	
	$musicfolder = false;
	$fcount = 0;
	$y = 0;
	$refreshPage = false;
	$context['check'] = !empty($context['check']) ? $context['check'] : false;	
	$files = array();	
	$checkfiles = array();
	$upfiles = array();
	$context['music_files'] = array();
	$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['premiumbeat_upload'];
	$context['template'] = 'CustomMusicUpload';
	$context['sub_template'] = 'customMusic_upload';	
	$context['post_url'] = $scripturl . '?action=admin;area=premiumbeat;sa=UploadPremiumbeat;' . $context['session_var'] . '=' . $context['session_id'] . ';save'; 
	$context['files_list'] = array();
	$context['title_playlists'] = array();
	$context['playlists'] = array();
	$context['sets']['filename'] = false;
	$context['sets']['title'] = $txt['customMusic_overdir'];
	$columns_settings = array('myplaylist', 'autofile', 'title', 'equip', 'filename');
	$columns_data = array('id', 'user_id', 'my_playlist', 'file', 'author', 'date', 'downloads');
	$compat = !empty($context['compatFiles']) ? (int) $context['compatFiles'] : 0;	
	$context['up_visibility'] = !empty($context['up_visibility']) ? (int) $context['up_visibility'] : 1;	
	
	foreach ($columns_data as $data)	
		$checkfiles[$data] = array();
		
	if (!$context['check'] && (int)$context['queryPlaylist'] > 0)
	{
		if (!check_user($user_info['id']))
			$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`) 
												VALUES ('{$user_info['id']}', '{$context['queryPlaylist']}', '0', '0', '0')");
		else										
			$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = '{$context['queryPlaylist']}' WHERE (user_id = '{$user_info['id']}') LIMIT 1");	
				
		$context['check'] = false;		
			
	} 
	elseif (!$context['check'] && (int)$context['queryPlaylist'] < 0)
	{
		if (!check_user($user_info['id']))
			$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`) 
												VALUES ('{$user_info['id']}', '0', '0', '0', '0')");
		else										
			$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = '0' WHERE (user_id = '{$user_info['id']}') LIMIT 1");	
				
		$context['check'] = false;		
			
	} 
		
	/* Query extra data */
	$result1 = $smcFunc['db_query']('', "SELECT data.id, data.user_id, data.my_playlist, data.file, data.author, data.date, data.downloads, mem.id_member,
										 mem.real_name, sets.myplaylist, sets.equip, user.last_playlist, user.visibility
										 FROM {db_prefix}premiumbeat_data AS data
										 LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = data.user_id)
										 LEFT JOIN {db_prefix}premiumbeat_users AS user ON (user.user_id = '{$user_info['id']}')
										 LEFT JOIN {db_prefix}premiumbeat_settings AS sets ON (sets.myplaylist = data.my_playlist)
										 ORDER BY data.id ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result1))	
	{		
		foreach ($columns_data as $data)	
			$checkfiles[$data][$y] = $val[$data];			
		
		if ((int)$val['last_playlist'] > 0)
		{		
			$context['queryPlaylist'] = (int)$val['last_playlist'];
			$context['check'] = true;
		}	
		$context['up_visibility'] = !empty($val['visibility']) ? (int)$val['visibility'] : 0;				
		$checkname[$y] = $val['real_name'];	
		$checkdate[$y] = $val['date'];		
		$y++;
	}										
		
	$smcFunc['db_free_result']($result1);
				
	/* Does the assigned playlist have a designated folder?  If not.. the override will be used else the default directory (false=$boarddir/mp3_music) */
	$playlist = !empty($context['queryPlaylist']) ? (int) $context['queryPlaylist'] : 0;	
	$context['playlist'] = !empty($playlist) ? (int) $playlist : 0;
	$context['playlists'][0] = 0;	
	$context['title_playlists'][0] = $txt['customMusic_overdir'];		
	
	if (((int)$playlist != 0) && check_playlist($playlist))
	{
		$result1 = $smcFunc['db_query']('', "SELECT myplaylist, autofile, title, equip, settings.filename FROM {db_prefix}premiumbeat_settings	
											LEFT JOIN {db_prefix}premiumbeat_forum_settings AS settings ON (settings.reference = 1)																			
											WHERE (myplaylist > 0 AND equip = 1) ORDER BY myplaylist ASC");
		while ($val = $smcFunc['db_fetch_assoc']($result1))
		{	
			if ((empty($val['myplaylist'])) || $val['myplaylist'] == 0) {continue;}	 
			if ((int)$val['myplaylist'] == (int)$playlist)
			{		
				$context['playlists'][$count] = $val['myplaylist'];	
				$context['title_playlists'][$count] = !empty($val['title']) ? $val['title'] : false;	
				foreach ($columns_settings as $sets)
					{	
						if (empty($val[$sets])) 
							$val[$sets] = 0; 
										
						if ($sets == 'autofile' && (empty($val['autofile']) || $val['autofile'] == '0' || $val['autofile'] == false))
							$val['autofile'] = 'mp3_music';  /* <--- Default upload folder   */				
						elseif ($sets == 'title' && (empty($val['title']) || $val['title'] == '0'))
							$val['title'] = false;
						elseif ($sets == 'filename' && (empty($val['filename']) || $val['filename'] == '0'))
							$val['filename'] = false;							
																		
						$context['sets'][$sets] = html_entity_decode($val[$sets]); 	
					}
				if (!$context['sets']['title'])
						$context['sets']['title'] = $txt['customMusic_playlist_num'] . $playlist;	
			}
			else
			{
				$context['playlists'][$count] = $val['myplaylist'];	
				$context['title_playlists'][$count] = !empty($val['title']) ? $val['title'] : false;
			}	
			$count++;							
		}
		$smcFunc['db_free_result']($result1);
	}
	else
	{
		$result1 = $smcFunc['db_query']('', "SELECT myplaylist, title, settings.filename FROM {db_prefix}premiumbeat_settings	
											LEFT JOIN {db_prefix}premiumbeat_forum_settings AS settings ON (settings.reference = 1)																			
											WHERE (myplaylist > 0 AND equip = 1) ORDER BY myplaylist ASC");
		while ($val = $smcFunc['db_fetch_assoc']($result1))	
		{		
			$context['sets']['filename'] = !empty($val['filename']) ? $val['filename'] : false;				 		
			$context['playlists'][$count] = !empty($val['myplaylist']) ? (int) $val['myplaylist'] : 0;
			$context['title_playlists'][$count] = !empty($val['title']) ? $val['title'] : false;
			$context['sets']['equip'] = 1;
			$count++;
		}										
		
		$smcFunc['db_free_result']($result1);
	}		
	
	if (!$context['sets']['filename'])
		$context['title_playlists'][0] = $txt['customMusic_overdir'];		
		
	$context['playlists_total'] = $count;
	$context['destinationz'] = !empty($context['sets']['autofile']) ? $context['sets']['autofile'] : $context['sets']['filename'];		
	$context['destination'] = ltrim($context['destinationz'], '/');
	$context['destination'] = rtrim($context['destination'], '/');
	$count = 0;
	
	 if ($context['destinationz'] == $context['sets']['filename'] && ($context['sets']['filename'] != false) && (int)$playlist == 0)
		$context['sets']['title'] = $txt['customMusic_overdir_select']; 
		 
	if ($context['destination'])							
		$context['destination'] = '/' . $context['destination'] . '/';	
	else
		$musicfolder = '/mp3_music/';	 /* <---- Default directory  */
	
	$context['audiofile'] = $context['destination'] . $musicfolder;
	if (allowedTo('premiumbeat_config'))
		$context['audiofile'] = $boarddir . $context['destination'] . $musicfolder;
		
	if (($context['destinationz']) && !is_dir($boarddir . '/' . $context['destinationz']))
		@mkdir($boarddir . '/' . $context['destinationz'], 0755);	 
	
	if (!@is_dir($boarddir . '/my_music/temp' . $user_info['id']))
			@mkdir($boarddir . '/my_music/temp' . $user_info['id'], 0755);		
	
	if (empty($context['sets']['eqip']))
		$context['sets']['eqip'] = 0;		
		
		/* Execute upload routine else copy files from temp directory (if it exists) to the opted auto-load folder */	
		if ($context['ax-file-path'])
		{				
			$up = new FileUploader();
			$path = $context['ax-file-path'];
			$ext = 'mp3,mP3,Mp3,MP3';
			$res=$up->uploadfile('my_music/temp' . $user_info['id'],$ext);					
		}	
		else
		{
			if ($context['destinationz'] == false || (trim($context['destinationz'], ' ') == false))
				$context['destinationz'] = 'mp3_music';    /* <-----  Default directory   */
				
			$upfiles = ReadMusicDirectory2($boarddir . '/my_music/temp'. $user_info['id']);	
			copyMusicDirectory($boarddir . '/my_music/temp'. $user_info['id'], $boarddir . '/' . $context['destinationz']);			
			deleteTempArchives($boarddir . '/my_music/temp'. $user_info['id']); 			
			
			/* Update data fields */			
			foreach ($upfiles as $file)
			{
				/* This will thwart odd time() behavior? */
				$date = 'time:' . time(); 
				$date = str_replace('time:', '', $date);	
				$author = (int)$context['up_visibility'];				
				$fileArray = explode('-_-', $file);
				$unfiltered_file = $context['destinationz'] . '/' . cleanPremiumbeatQuery2($file);
				if (count($fileArray) > 1)
					$unfiltered_file = $context['destinationz'] . '/' . cleanPremiumbeatQuery2($fileArray[1]);				
				
				$file = $context['destinationz'] . '/' . cleanPremiumbeatQuery2($file);
				$check = false;
				foreach ($checkfiles['file'] as $checkfile)
				{					
					if (cleanPremiumbeatQuery2($checkfile) == $file || cleanPremiumbeatQuery2($checkfile) == $unfiltered_file)
						$check = true;					
				}
				
				if (!$check)
				{
					$playlist_check = !empty($playlist) ? (int)$playlist : 0;
					if ($playlist_check < 0 || $playlist_check > 99)
						$playlist_check = 0;
					$request = $smcFunc['db_query']('', "DELETE FROM {db_prefix}premiumbeat_data WHERE (file LIKE '{$file}')");
					$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_data (`user_id`, `my_playlist`, `file`, `author`, `date`, `downloads`) 
																VALUES ('{$user_info['id']}', '{$playlist_check}', '{$file}', '{$author}', '{$date}', '0')");	
					$refreshPage = true;
				}			
						
			}
			
			/* Make sure the directory is protected */
			if (!@file_exists($boarddir . '/' . $context['destinationz'] . '/index.php') && @file_exists($boarddir . '/my_music/index.php'))
				@copy($boarddir . '/my_music/index.php', $boarddir . '/' . $context['destinationz'] . '/index.php');				
				
		}			
		
		/* Gather filenames from the opted music directory */	
		if ($context['destinationz'])
			$files = ReadMusicDirectory2($boarddir . '/' . $context['destinationz']);
		elseif ($playlist == 0)
			 $files = ReadMusicDirectory2($boarddir . '/mp3_music');	
			 	
		if ((is_array($files)) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				if (strtolower(substr($file, -4, 4)) == '.mp3')
				{
					$fileArray = explode('-_-', $file);
					if (count($fileArray) > 1)
						$unfiltered_file = $fileArray[1];
						
					$context['music_files'][$fcount]['filex'] = $file;
					$context['music_files'][$fcount]['name'] = $txt['customMusicUpload_noname'];
					
					foreach ($columns_data as $data)	
						$context['music_files'][$fcount][$data] = $txt['music_nofolder'];
						
					$y = 0;					
					foreach ($checkfiles['file'] as $checkfile)
					{
						
						if ($context['destinationz'] . '/' . formspecialcharsformeagain3($unfiltered_file, true) == formspecialcharsformeagain3($checkfile, true) && strpos($file, '-_-', 1) !== false)
						{							
							foreach ($columns_data as $data)
								$context['music_files'][$fcount][$data] = $checkfiles[$data][$y];							
							
								$context['music_files'][$fcount]['date'] = date("F j, Y", $checkfiles['date'][$y]);								
								if (allowedTo('premiumbeat_config') || (int)$checkfiles['author'][$y] > 0)
									$context['music_files'][$fcount]['name'] = '<a href="'. $scripturl . '?action=profile;u=' . $checkfiles['user_id'][$y] . '">' . $checkname[$y] . '</a>';		
							
						}						
						$y++;
					}
				}	 
									
				$fcount++;
			}
		}	
	
	/* Adjust for compatible filenames */
	if (($compat == 1 && $context['destinationz'] && AllowedTo('premiumbeat_config')) && is_array($files) && is_dir($boarddir . '/' . $context['destinationz']))
	{
		$context['compatFiles'] = 0;
		foreach ($files as $file)
		{
			$newfile = formspecialcharsformeagain3($file, true);
			if (substr_count($newfile, '-_-') == 0 && $newfile != 'index.php')
				$newfile = premium_coded_filename() . '-_-' . $newfile;	
				
			if ($newfile != $file)
			{
				@copy($boarddir . '/' . $context['destinationz'] . '/' . $file, $boarddir . '/' . $context['destinationz'] . '/' . $newfile);
				@unlink($boarddir . '/' . $context['destinationz'] . '/' . $file);					
			}
		}			
		redirectexit('action=admin;area=premiumbeat;sa=UploadPremiumbeat;queryPlaylist=' . $context['playlist']); 
	}
	/* Execute checkbox commands */
	elseif (($compat == 2 && allowedTo('premiumbeat_showdelete')) && (isset($context['delete_songs']) && is_array($context['delete_songs'])))
	{
		/* Delete opted files */
		foreach ($context['delete_songs'] as $delete_song)
		{
			$myfile = $context['destinationz'] . '/' . $delete_song;
			$checkMember = 0;
			foreach ($context['music_files'] as $checkFile)
			{
				if (strpos($delete_song, $checkFile['filex']) !== false)
				{
					$checkMember = $checkFile['user_id'];
					break;	
				}	
					
			}
			
			if ((int)$checkMember == (int)$user_info['id'] || allowedTo('admin_forum'))
			{
				if (@file_exists($boarddir . '/' . $myfile))
					@unlink($boarddir . '/' . $myfile);
				
				$request = $smcFunc['db_query']('', "DELETE FROM {db_prefix}premiumbeat_data WHERE (file LIKE '{$myfile}')");	
			}
			$refreshPage = true;
			
							
		} 
	}
	if(($compat == 2) && (isset($context['toggle_songs']) && is_array($context['toggle_songs'])))
	{		
		/* Toggle uploader visibilty for opted files */
		foreach ($context['toggle_songs'] as $toggle_song)
		{
			$songx = $toggle_song;
			$songArray = explode('-_-', $toggle_song);
			if (count($songArray) > 1)
				$toggle_song = $songArray[1];
			
			$checkMember = 0;
			foreach ($context['music_files'] as $checkFile)
			{
				if (strpos(formspecialcharsformeagain3($songx, true), $checkFile['filex']) !== false)
				{
					$checkMember = $checkFile['user_id'];					
					break;
				}	
					
			}
			
			$toggle = $context['destinationz'] . '/' . cleanPremiumbeatQuery2(str_replace('`', '&#039;', $toggle_song));			
			$visible = (int)$context['up_visibility'];
			if ((int)$checkMember == (int)$user_info['id'] || allowedTo('admin_forum'))
			{
				$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_data SET author = MOD(author+1,2) WHERE (file = '{$toggle}') LIMIT 1");
				$result = $smcFunc['db_query']('', "SELECT user_id, file, author FROM {db_prefix}premiumbeat_data WHERE (file = '{$toggle}' AND user_id = '{$user_info['id']}') LIMIT 1");
				while ($val = $smcFunc['db_fetch_assoc']($result))	
				{		
					$visible = $val['author'];
					$context['up_visibility'] = $visible;
				}					
				$smcFunc['db_free_result']($result);
			
				$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET visibility = '{$visible}' WHERE (user_id = '{$user_info['id']}') LIMIT 1");
				$refreshPage = true;
			}	
		} 
		
		/* Download opted files as a compressed zip archive */
		if(count($context['download_songs']) > 0 && allowedTo('premiumbeat_showdownload')) 
		{
			$tempdir = 'download_temp_' . $user_info['id'] . '-' . time();
			$zipfile = 'mp3_music_' . $user_info['id'] . '-' . time() . '.zip';
			
			if (@is_dir($boarddir . '/' . 'my_music/' . $tempdir))
				deleteTempArchives($boarddir . '/' . 'my_music/' . $tempdir);
				
			@mkdir($boarddir . '/' . 'my_music/' . $tempdir, 0755);
					 
			foreach ($context['download_songs'] as $download)
			{
				$file = $boarddir . '/' . $context['destinationz'] . '/' . $download;
				$update = $context['destinationz'] . '/' . cleanPremiumbeatQuery2($download);
				/* This will thwart odd time() behavior? */
				$mydate = 'time:' . time(); 
				$mydate = str_replace('time:', '', $mydate);
				$premfilex = explode('-_-', $download);	
				$download = str_replace($premfilex[0] . '-_-', '', $download);	
									
				if (@file_exists($file) && !@file_exists($boarddir . '/' . 'my_music/' . $tempdir . '/' . $download))
				{
					@copy($file, $boarddir . '/' . 'my_music/' . $tempdir . '/' . $download); 
					$playlist_check = !empty($context['playlist']) ? (int)$context['playlist'] : 0;
					if ($playlist_check < 0 || $playlist_check > 99)
						$playlist_check = 0;
						
					if (!check_file($update))	
						$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_data (`user_id`, `my_playlist`, `file`, `author`, `date`, `downloads`) 
															VALUES ('0', '{$playlist_check}', '{$update}', 0, '{$mydate}', '1')");							
					else	
						$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_data SET downloads = downloads + 1 WHERE (file = '{$update}') LIMIT 1");	
					
					if (!check_user($user_info['id']))
						$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_users (`user_id`, `last_playlist`, `pref_playlist`, `downloads`, `autoplay`, `visibility`) 
															VALUES ('{$user_info['id']}', '{$playlist_check}', 0, 1, 0, '{$context['up_visibility']}')");				
					else
						$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET downloads = downloads + 1 WHERE (user_id = '{$user_info['id']}') LIMIT 1");		
						
				}	
			} 
			
			@include_once($sourcedir . "/CustomMusicZip.php");			
			$createZipFile=new CreateZipFile;
			$createZipFile->zipDirectory('my_music/' . $tempdir, '');
			$fd=@fopen('my_music/' . $zipfile, "wb");
			$out=@fwrite($fd,$createZipFile->getZippedfile()); 
			@fclose($fd);			
			deleteTempArchives($boarddir . '/' . 'my_music/' . $tempdir);	
			
			if (@file_exists($boarddir . '/my_music/' . $zipfile))
			{
				ob_start();
				@header("Content-type: application/zip");
				@header('Location: ' . 'my_music/' . $zipfile);
				@header("Pragma: no-cache");
				@header("Expires: 0");
				@readfile("$zipfile");
				ob_end_flush();							
				exit();
			} 
		}
		
		redirectexit('action=admin;area=premiumbeat;sa=UploadPremiumbeat;queryPlaylist=' . $context['playlist']);	
	}
	
	/* Reset playlist id */
	elseif ($compat == 3)
	{
		$request = $smcFunc['db_query']('', "UPDATE {db_prefix}premiumbeat_users SET last_playlist = 0 WHERE (user_id = '{$user_info['id']}') LIMIT 1");	
		redirectexit('action=admin;area=premiumbeat;sa=UploadPremiumbeat;queryPlaylist=-1'); 
	}	
	
	/* Limit 3 file downloads per session */
	$context['downlimit'] = '	function downcontrol(j)
					{
						var total=0;
						for(var i=0; i < document.uploadselect.download.length; i++)
						{
							if(document.uploadselect.download[i].checked)
							   {total = total +1;}
							if(total > 3)
							{
								alert("'.$txt['customMusicDownloadAlert'].'")
								document.uploadselect.download[j].checked = false;
								return false;
							}
						}
					}';
	$context['premiumbeat_confirm'] = '		function confirmSubmit()
							{
								var agree=confirm("'.$txt['premiumbeat_confirm'].'");
								if (agree)
									return true ;
								else
									return false ;
							}';		
	$context['musicAdmin_pages3'] = '<script type="text/javascript"><!--
        var pager = new Pager("premFiles", 10); 
        pager.init(); 
        pager.showPageNav("pager", "FilesPagePosition"); 
        pager.showPage(1);
  	  //--></script>';										
	
	if ($refreshPage)
		redirectexit('action=admin;area=premiumbeat;sa=UploadPremiumbeat;queryPlaylist=' . $context['playlist']);
		
	/* Load the template */
	loadTemplate($context['template']);
	
}

/* Show this modifications license */
function LicensePremiumbeatPlayer()
{
	global $boardurl;	
	redirectexit($boardurl.'/my_music/Premiumbeat_for_SMF-license.pdf'); 
	return;
}

/* Create new playlist */
function createPlaylist($playlists=array())
{
	global $smcFunc, $context;	
	$list = 1;	
	$mylist = 0;	
	$list_types = array('height', 'width', 'autoplay', 'type', 'skin', 'skin_type', 'autofile', 'title', 'equip');
	
	foreach ($list_types as $list_type)
		$playlist[] = cleanPremiumbeatQuery2($playlists[$list_type][0]);	
	
	list($height, $width, $autoplay, $type, $skin, $skin_type, $autofile, $title, $equip) =  $playlist;		
	if ((int)$height == -1)
		$height = 0;
	if ((int)$width == -1)
		$width = 0;	
	$skin = premium_hex_filter($skin);
	if ((int)$skin_type < 1 || (int)$skin_type > 5)
		$skin_type = 1;
		
	/* Determine the next available playlist id else it will auto increment */	
	$result = $smcFunc['db_query']('', "SELECT myplaylist FROM {db_prefix}premiumbeat_settings WHERE (myplaylist > 0) ORDER BY myplaylist ASC");
	while ($val = $smcFunc['db_fetch_assoc']($result))
		{	
			if (empty($val['myplaylist'])) {continue;}
			if((int)$val['myplaylist'] != $list)
			{					
				$mylist = $list;
				break;
			}	
			$list++;	
		}
	$smcFunc['db_free_result']($result);	
	
	/* Create the new playlist */
	if ((int)$mylist == 0)
		$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_settings (`height`, `width`, `autoplay`, `type`, `skin`, `skin_type`, `autofile`, `title`, `equip`) 
															VALUES ('{$height}', '{$width}', '{$autoplay}', '{$type}', '{$skin}', '{$skin_type}', '{$autofile}', '{$title}', '{$equip}')");	
	else
		$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_settings (`myplaylist`, `height`, `width`, `autoplay`, `type`, `skin`, `skin_type`, `autofile`, `title`, `equip`) 
															VALUES ('{$mylist}', '{$height}', '{$width}', '{$autoplay}', '{$type}', '{$skin}', '{$skin_type}', '{$autofile}', '{$title}', '{$equip}')");															
	redirectexit('action=admin;area=premiumbeat;sa=SettingsPremiumbeat;'. $context['session_var'] . '=' . $context['session_id'] . ';'); 														
															
	
}		
  
/* Check if the column exists */
function checkFieldPremium2($tableName,$columnName)
	{
		$checkTable = false;
		$checkTable = check_table_existsPremium2($tableName);
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
function check_table_existsPremium2($table)
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

function check_playlist($play)
	{
		global $db_prefix, $smcFunc;		
		$result2 = $smcFunc['db_query']('', "SELECT myplaylist FROM {db_prefix}premiumbeat_settings WHERE myplaylist = {$play} LIMIT 1");
		$result3 = $smcFunc['db_num_rows']($result2);	
		$smcFunc['db_free_result']($result2);		
		if ($result3 > 0) {return true;}
		return false;
	}	
	
function check_mp3($tag)
	{
		global $db_prefix, $smcFunc;		
		$result2 = $smcFunc['db_query']('', "SELECT tag FROM {db_prefix}premiumbeat_mp3s WHERE tag = {$tag} LIMIT 1");
		$result3 = $smcFunc['db_num_rows']($result2);	
		$smcFunc['db_free_result']($result2);		
		if ($result3 > 0) {return true;}
		return false;
	}			

function check_file($file)
	{
		global $db_prefix, $smcFunc;		
		$result2 = $smcFunc['db_query']('', "SELECT file FROM {db_prefix}premiumbeat_data WHERE file = '{$file}' LIMIT 1");
		$result3 = $smcFunc['db_num_rows']($result2);	
		$smcFunc['db_free_result']($result2);		
		if ($result3 > 0) {return true;}
		return false;
	}	
	
function check_user($user)
	{
		global $db_prefix, $smcFunc;		
		$result2 = $smcFunc['db_query']('', "SELECT user_id FROM {db_prefix}premiumbeat_users WHERE user_id = '{$user}' LIMIT 1");
		$result3 = $smcFunc['db_num_rows']($result2);	
		$smcFunc['db_free_result']($result2);		
		if ($result3 > 0) {return true;}
		return false;
	}				
	
/*  Update table -> column -> value for premiumbeat settings  */
function createpremiumval_settings($tableName, $columnName, $value, $playlist) 
	{
		global $smcFunc, $db_prefix;
		$i = 0;
		$request = false;   
		if (empty($tableName) || empty($columnName) || empty($playlist)) {return;}
		if (empty($value)) {$value = false;}
		$value2 = cleanPremiumbeatQuery2($value);
		$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value2}' WHERE `{$db_prefix}$tableName`.`myplaylist` = {$playlist} LIMIT 1");
								 
	}

/*  Update table -> column -> value for premiumbeat mp3 list  */
function createpremiumval_mp3($tableName, $columnName, $tag, $value) 
	{
		global $smcFunc, $db_prefix;
		$i = 0;
		$request = false;   
		if (empty($tableName) || empty($columnName) || empty($tag)) {return;}
		if (empty($value)) {$value = false;}
		$value2 = cleanPremiumbeatQuery2($value);
		$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value2}' WHERE `{$db_prefix}$tableName`.`tag` = {$tag} LIMIT 1");
								 
	}	
	
/* mysql query filter */
function cleanPremiumbeatQuery2($string)
{
  if(get_magic_quotes_gpc())  
  {
    $string = stripslashes($string);
  }
  if (phpversion() >= '4.3.0')
  {
    $string = mysql_real_escape_string($string);
  }
  else
  {
    $string = mysql_escape_string($string);
  }
  return $string;
}	

/* Filter input for hex color */
function premium_hex_filter($color)
{
	    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
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

/* Foldername filter */	
function premium_folder_filter2($folder)
{	
	$filtered_folder = "";
	$patterns = array("/\&/","/\+/","/delete/i", "/update/i","/union/i","/insert/i","/drop/i","/http/i","/--/i");  
	$folder = preg_replace($patterns, "_" , $folder);
	for ($i=0;$i<strlen($folder);$i++)
		{
			$current_char = substr($folder,$i,1);
			if (ctype_alnum($current_char) == TRUE || $current_char == "_" || $current_char == "/" || $current_char == "-" || $current_char == " " || $current_char == "`")
				{$filtered_folder .= $current_char;}
		}  
	$filtered_folder = rtrim($filtered_folder, ' ');
	$filtered_folder = ltrim($filtered_folder, ' ');	   
	return $filtered_folder;
}	

/* Upload class for handling upload files */
class AsyncUpload
{
    function save($remotePath,$allowext,$add) 
	{    
	    $file_name=$_REQUEST['ax-file-name'];
    
	    $file_info=pathinfo($file_name);	    
		if (empty($file_info['extension']))
			$file_info['extension'] = false;
			
	    if(strpos($allowext, $file_info['extension'])!==false || $allowext=='all')
	    {
	    	$flag =($_REQUEST['start']==0) ? 0:FILE_APPEND;
	    	$file_part=file_get_contents('php://input'); 
	    	while(@file_put_contents($remotePath.$add.$file_name, $file_part,$flag)===FALSE) 
	    	{
	    		usleep(50);
	    	}
	        return true;
	    }
	    return $file_info['extension'].' extension not allowed to upload!';
    } 
}

class SyncUpload 
{  
    function save($remotePath,$allowext,$add,$destination)
	{	
		global $boarddir;	
		$msg=true;
    	foreach ($_FILES['ax-files']['error'] as $key => $error) 
    	{
    		$tmp_name = $_FILES['ax-files']['tmp_name'][$key];
    		$name = formspecialcharsformeagain3($_FILES['ax-files']['name'][$key], true);
    		
    		$file_info=pathinfo($name);
            if ($error == UPLOAD_ERR_OK) 
            {
            	if(strpos($allowext, $file_info['extension'])!==false || $allowext=='all')
            	{
                	move_uploaded_file($tmp_name, $remotePath.$add.$name);
            	}
            	else
            	{
            		$msg=$file_info['extension'].' extension not allowed!';
            	}
            }
            else 
            {
                $msg='Error uploading!';
            }
        }
        echo $msg;
        return $msg;
    }
}

class FileUploader 
{
	private $file=false;
    function __construct($remotePath='',$allowext='')
	{
		if(isset($_FILES['ax-files'])) 
		{
            $this->file = new SyncUpload();
        }
        elseif(isset($_REQUEST['ax-file-name'])) 
		{
            $this->file = new AsyncUpload();
        } 
		else 
		{
            $this->file = false; 
        }  
    }

    function uploadfile($remotePath='',$allowext='all',$add='', $destination='')
	{							
		$remotePath.=(substr($remotePath, -1)!='/')?'/':'';
		if(!@file_exists($remotePath))
			@mkdir($remotePath,0755,true);
		
        $msg=$this->file->save($remotePath,$allowext,$add, $destination);
        return $msg;
    }    
}

/* Delete temp directory function */
function deleteTempArchives($directory=false)
{
	global $boardurl, $boarddir;
	if ($directory == $boarddir || $directory == false)
		return false;
		
	if(substr($directory,-1) == "/") {$directory = substr($directory,0,-1);}
	if (!@file_exists($directory) || !@is_dir($directory)) {return false;}
	elseif (!@is_readable($directory)) {return false;}	
	$directoryHandle = opendir($directory);	
	while ($contents = @readdir($directoryHandle))
		{			
			if($contents != '.' && $contents != '..') 
				{
					$path = $directory . "/" . $contents;									
					if (@is_dir($path))
						{deleteTempArchives($path);}
					@unlink($path);
                
				}
		}
       
	@closedir($directoryHandle);
	if (@is_dir($directory)) {@rmdir($directory);}
	elseif (@file_exists($directory)) {@unlink($directory);}
	else {return false;}	
	return true;    
} 

/* Copy entire folder/directory  */
function copyMusicDirectory($source, $destination) 
{
	if ($source == $destination || $source == false || $destination == false)
		return false;
		
	if (@is_dir($source)) 
	{			
		if (@!is_dir($destination))
			@mkdir($destination, 0755);
			
		$directory = @dir($source);
		while (FALSE !== ($readdirectory = $directory->read())) 
		{
			$checkArray = glob($destination . '/*' . formspecialcharsformeagain3($readdirectory, true));
			if ($readdirectory == '.' || $readdirectory == '..' || count($checkArray) > 0) 
				continue;
			
			$PathDir = $source . '/' . $readdirectory; 
			if (@is_dir($PathDir)) 
			{
				copyMusicDirectory($PathDir, $destination . '/' . $readdirectory);
				continue;
			}
			@copy($PathDir, $destination . '/' . premium_coded_filename() . '-_-' . formspecialcharsformeagain3($readdirectory, true));
		}
 
		$directory->close();
	}
	elseif (@file_exists($source) && !@file_exists(formspecialcharsformeagain3($destination, true))) 
		@copy($source, premium_coded_filename() . '-_-' . formspecialcharsformeagain3($destination, true));
	else
		return false;
	
	return true;	
}

/* Safe character filter */
function formspecialcharsformeagain3($var=false, $hq=false)
    {			
        $pattern = '/&(#)?[a-zA-Z0-9]{0,};/';
       	if (!$var)
			return false;		
		
		/* $var = str_replace("'", "`", $var); 	*/
        if (is_array($var)) 
		{   
            $out = array();     
            foreach ($var as $key => $v)
			{					 	    
				$out[$key] = formspecialcharsformeagain3($v);         
            }
        }
		else
		{
            $out = $var;
            while (preg_match($pattern,$out) > 0) 
			{			
                $out = htmlspecialchars_decode($out,ENT_QUOTES);     
            }  
			             
            $out = htmlspecialchars(stripslashes(trim($out)), ENT_QUOTES,'UTF-8',true);             
        }	
			
		return str_replace("&#039;", "`", $out);			  
} 

/* Read contents of music directory */
function ReadMusicDirectory2($dir)
{
	$listDir = array();
	if (!$dir)
		return $listDir;
		
	if($handler = @opendir($dir))
		{
			while (($sub = @readdir($handler)) !== FALSE)
				{
					if ($sub != "." && $sub != ".." && $sub != "Thumb.db")
						{
							if(@is_file($dir."/".$sub))
								{
									$listDir[] = $sub;
								}
							elseif(@is_dir($dir."/".$sub))
								{
									continue;  /*  Folder tree ignored   */
									/* $listDir[$sub] = $this->ReadMusicDirectory2($dir."/".$sub); */
								}
						}
				}   
			closedir($handler);
		}
	return $listDir;   
}

/* Generate a random folder name */
function premium_coded_filename()
{
	$code = false;
	$chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
	shuffle($chars);
	$encoded = array_rand($chars, 20);

	foreach ($encoded as $coded)
		$code .= $chars[$coded];

	return $code;
}
?>