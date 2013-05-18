<?php

/* Premium Beat for SMF - Modification v2.0 */
/*  c/o Underdog @ http://askusaquestion.net  */
/* This file is for adding the mp3 database entries to the mp3 playlist and for the popup setup  */

if (!defined('SMF'))
	die('Hacking attempt...');
	
function CustomMusicMp3()
{
	global $db_prefix, $txt, $context, $user_info, $sourcedir;

	/*  Hide the playlist from direct url access  */
	if (empty($_SESSION['customMusic_checker']))
		$_SESSION['customMusic_checker'] = false;
		
	if ($_SESSION['customMusic_checker'] == false)
	{
		fatal_lang_error('customMusic_check', false);
		return;
	}	
	
	if (empty($_SESSION['playlist_id']))
		$_SESSION['playlist_id'] = 1;
		
	$playlist_id = (int)$_SESSION['playlist_id'];
	$playsong = !empty($_SESSION['premiumbeat_bbc']) ? $_SESSION['premiumbeat_bbc'] : false;

	/* Have to fill the arrays to stop any undefined warnings and the filter */
	$filter = array();
	$mylist_title = array();
	$mylist_songs = array();
	$playlist = array();
	$playlistx = 0;
	$result1 = array();
	$filter = array('--','&quot;','!','@','#','$','%','^','*','(',')','{','}','|','"','<','>','[',']','\\',"'",',','+','~','`');  
	$val = false;

	/* START - Read databse entries for mp3's */	
	$a = checkFieldPremium1('premiumbeat_mp3s','tag');
	$b = checkFieldPremium1('premiumbeat_settings','myplaylist');
	$c = checkFieldPremium1('premiumbeat_forum_settings','reference');
	if ($a == false || $b == false || $c == false)
		die();
		
	if ($playlist == 989 && $playsong == false)
		die();
		
	$list = array();
	$result1 = array();
	$val = false;
	$song = 0;
	$type = 0;
	$equip = array();
	$checkbbc[0] = false;
	$checkbbcx = false;
	$autolist['filename'] = array(); 
	$setting['override'] = false;
	$setting['forumlink'] = false;
	$columns = array('filename', 'forumlink', 'override', 'autoplay');	
	$columnx = 'premiumbeat_playlist_*';	
	$columnz = 'premiumbeat_playlist_';
	$tablex = 'permissions';

	/* Adjust variables for playlist bbc - Only enabled upload playlists will be available or if moderator/admin/permission for all ! */
	$checkbbc = preg_split('/,/', str_replace('http://', '', $playsong), -1, null);
	if ((substr($checkbbc[0], 0, 9) == 'playlist=') && strlen($checkbbc[0]) < 13)
	{
		$playlist_id = (int)(str_replace('playlist=', '', $checkbbc[0]));
		if ($playlist_id < 1 || $playlist_id > 1000)
			$playlist_id = 989;
			
		$checkbbcx = true;	
	}
	
	/* mp3 entries for auto list from folder */
	$result1 = db_query("SELECT myplaylist, autofile, equip, settings.forumlink, settings.override, settings.reference, settings.filename
						 FROM {$db_prefix}premiumbeat_settings
						 LEFT JOIN {$db_prefix}premiumbeat_forum_settings AS settings ON (settings.reference = 1)	
						 WHERE myplaylist > 0
						 ORDER BY myplaylist", __FILE__, __LINE__);
									 
	while ($val = mysql_fetch_assoc($result1))
	{	
		$playlisty = !empty($val['myplaylist']) ?  (int)$val['myplaylist'] : 0;	
		if ((int)$playlisty < 1)
			continue;
			
		$autolist['filename'][$playlisty] = !empty($val['autofile']) ?  $val['autofile'] : false;	
		$default['filename'] = !empty($val['filename']) ?  $val['filename'] : false;
		$equip[$playlisty] = !empty($val['equip']) ?  $val['equip'] : false;
		$setting['forumlink'] = !empty($val['forumlink']) ?  $val['forumlink'] : false;	
		$setting['override'] = !empty($val['override']) ?  $val['override'] : false;
		$playlistx = (int)$setting['override'];			
	}
	mysql_free_result($result1);

	/* Checks for playlist bbc (after equip column is queried) */
	if ($checkbbcx && ((int)$equip[$playlist_id] == 1 || allowedTo('premiumbeat_config') || allowedTo('premiumbeat_showplaylists')) && allowedTo('premiumbeat_showbbc'))
		$playsong = false;	
	elseif ($checkbbcx)
	{
		$autolist['filename'][$playlist_id] = false;
		$playsong = false;	
		require_once($sourcedir . '/Subs-Members.php');
		$check = false;
		$premiumbeat_info = $user_info;	
		
		/* Check if we are overiding the permission settings */			
		if ($setting['forumlink'] == 1)
		{
			/* First check if admin or guest */
			if ((int)$premiumbeat_info['is_admin'] == 1) 
				$check_group = 999;
			elseif ((int)$premiumbeat_info['is_guest'] == 1) 
				$check_group = -1;
			elseif((int)$premiumbeat_info['groups'][0] == 0)
				$check_group = 4;							
			/* Else user will be designated as the first highest rated group id */ 			
			else
			{							
				$check_user = $premiumbeat_info['groups'];
				$check_group = $check_user[0];								
				foreach ($check_user as $user)
				{
					$z = 1;
					$stars[0]  = 0;				
					$result2 = db_query("SELECT	ID_GROUP, stars FROM `{$db_prefix}membergroups` 
										WHERE `{$db_prefix}membergroups`.`ID_GROUP` = '$user'", __FILE__, __LINE__);								
					while ($val = mysql_fetch_assoc($result2))
					{
						$stars[$z] = substr($val['stars'], 0, 1);
						if ((int)$stars[$z-1] > (int)$stars[$z])
							$check_group = (int)$val['ID_GROUP'];
						$z++; 
					}
					mysql_free_result($result2);									
				}				
			}						
			$result2 = db_query("SELECT	ID_GROUP, permission FROM `{$db_prefix}permissions` 
									WHERE (`{$db_prefix}permissions`.`ID_GROUP` = '$check_group') AND (`{$db_prefix}permissions`.`permission` RLIKE '$columnx')", __FILE__, __LINE__);				
			while ($val = mysql_fetch_assoc($result2))
				$playlistx = ((int)str_replace($columnx, "" , $val['permission']));							
						
			mysql_free_result($result2);									
		}
		else
		{
			$playlistx = (int)$setting['override'];
			$autolist['filename'][$playlistx] = !empty($setting['override']) ?  $setting['override'] : false;
		}	
					
		$playlist_id = $playlistx;			
	}	
			
	/* Check for mp3 bbc files */
	if ($playsong == true && allowedTo('premiumbeat_showbbc')) 
	{
		$playlist_id = 989;
		$playsongs = preg_split('/,/', $playsong, -1, null);
		$playsong = false;		
		foreach ($playsongs as $playsong)
		{
			$c = strtolower(substr($playsong, -4, 4));
			if ($c == '.mp3')
			{				
				$list['url'][$song] = $playsong;
				$list['description'][$song] = str_replace(substr($playsong, -4, 4), '', basename($playsong));	
				$premfilex = explode('-_-', $list['description'][$song]);	
				$list['description'][$song] = str_replace($premfilex[0] . '-_-', '', $list['description'][$song]);					
				$list['enable'][$song] = 1;
				$list['playlist'][$song] = $playlist_id;
				$list['tag'][$song] = $song;
				$type = 1;	
				$song++;			
			}
				
		}
		$count = $song;								
	}

	/* Check for auto playlist */	
	if ((!empty($autolist['filename'][$playlist_id])) && $autolist['filename'][$playlist_id] != false)
	{
		$z = ReadMusicDirectory($autolist['filename'][$playlist_id]);		
		foreach ($z as $b)
		{
			$c = strtolower(substr($b, -4, 4));
			if ($c == '.mp3')
			{
				$list['url'][$song] = $autolist['filename'][$playlist_id] . '/' . $b;
				$list['description'][$song] = str_replace(substr($b, -4, 4), "" , $b);
				$premfilex = explode('-_-', $list['description'][$song]);	
				$list['description'][$song] = str_replace($premfilex[0] . '-_-', '', $list['description'][$song]);
				$list['enable'][$song] = 1;
				$list['playlist'][$song] = $playlist_id;
				$list['tag'][$song] = $song;
				$type = 1;
				$song++;
			}
		}	
		$count = $song;			
	}

	if ($playlist != 989 && $playsong == false)
	{	
		/* mp3 entries from the database */			
		$result1 = db_query("SELECT tag, playlist, enable, description, url, settings.type FROM {$db_prefix}premiumbeat_mp3s 
										LEFT JOIN {$db_prefix}premiumbeat_settings AS settings ON (settings.myplaylist = playlist)
										WHERE ((playlist = {$playlist_id}) OR (playlist = 99)) AND (enable = 1) ORDER BY tag ASC ", __FILE__, __LINE__);
		while ($val = mysql_fetch_assoc($result1))
		{	
			if ((empty($val['url'])) || (empty($val['tag'])))
				continue;
					 
			if ($val['tag'] == 0)
				continue;
					
			if (empty($val['description']))
				$val['description'] = false;
				 
			if (empty($val['myplaylist']))
				$val['myplaylist'] = 1;
				
			if (empty($val['type']))
				$val['type'] = 0;
					  	                         
			$list['tag'][$song] = $val['tag'];
			$list['playlist'][$song] = $val['playlist'];
			$list['enable'][$song] = $val['enable'];
			$list['description'][$song] = $val['description'];
			$list['url'][$song] = $val['url'];
			$type = (int)$val['type'] + 1;
			$song++;		
		}
		mysql_free_result($result1);
		$count = $song;
	/* END - Read databse entries for mp3's */
	}
	
	/* Fill the array with the playlist  */
	$counterz = 0;
	while ($counterz < $count)
	{
		$mylist_songs[$counterz] = formspecialcharsformeagain2($list['url'][$counterz], true);
		$mylist_title[$counterz] = formspecialcharsformeagain2($list['description'][$counterz], false);	
		$premfilex = explode('-_-', $mylist_title[$counterz]);	
		$mylist_title[$counterz] = str_replace($premfilex[0] . '-_-', '', $mylist_title[$counterz]);		
		$playlist[$counterz] = "<track><path>".$mylist_songs[$counterz]."</path><title>".$mylist_title[$counterz]."</title></track>";		
		$counterz++;
	}

	$_SESSION['customMusic_checker'] = false;
	$premiumbeat = playlist($playlist, $type, $count); 
	header ("content-type: text/XML");
	echo '<!--[if IE]>
		  <?xml version=\"1.0\" encoding=\"utf-8\" ?>\n 
	      <![endif]-->';  
	echo $premiumbeat; 

}

/*  Execute the playlist depending on format  */
function playlist($playlist, $type, $songcount)
{
	$songcounter = 0;
	if ($type < 1 || $type > 2)
		$type = 1;
	$play = '<?xml version="1.0" encoding="UTF-8"?><xml>';
	if ($type == 1)	
		shuffle($playlist);
		
	while (!empty($playlist[$songcounter]))
	{
		$opt = $songcounter;
		$play .= $playlist[$opt];		
		$songcounter++;
	}
	
	$play .= '</xml>';
	return $play;
}

function PremiumbeatPopup()
{
	global $scripturl, $db_prefix, $txt, $context, $boarddir, $user_info, $sourcedir;
	$context['robot_no_index'] = true;	
	$columns = array('filename', 'forumlink', 'override', 'autoplay');
	$columnx = 'premiumbeat_playlist_';	
	$tablex = 'permissions';	
	$title = 'Premiumbeat Player';		
	$playlist = !empty($_REQUEST['playlist']) ? (int) $_REQUEST['playlist'] : 0;
	$_SESSION['premiumbeat_bbc'] = false;
	if (empty($_SESSION['customMusic_checker']))
		$_SESSION['customMusic_checker'] = false;
		
	foreach ($columns as $column)
		$setting[$column] = false;	
			
	if (allowedTo('premiumbeat_showbbc') && $playlist == 989)
	{
		$playsong = !empty($_REQUEST['playsong']) ? $_REQUEST['playsong'] : false;
		$_SESSION['premiumbeat_bbc'] = $playsong;
	}
	elseif ($playlist == 989)
	{
		$playlist = 999;
		$playsong = false;		
	}		
		
	if ($playlist == 0) 
		$playlist = 999;
		
	require_once($sourcedir . '/Subs-Members.php');
	$check = false;
	$premiumbeat_info = $user_info;	
	
	if ($playlist != 989)
	{	
		/* Load the forum settings */			
		$result2 = db_query("SELECT	filename, forumlink, override, autoplay FROM {$db_prefix}premiumbeat_forum_settings WHERE reference = 1 LIMIT 1", __FILE__, __LINE__);
		while ($val = mysql_fetch_assoc($result2))
		{						  
			foreach ($columns as $column)
			{
				if (empty($val[$column]))
					$val[$column] = false;
					
				$setting[$column] = $val[$column];										
			}
		}
		mysql_free_result($result2);
		if (empty($setting['forumlink'])) 
			$setting['forumlink'] = 0;		
	}
	
	if ($playlist != 999 && $_SESSION['customMusic_checker'] == false)
	{
		/* $playlist = !empty($setting['override']) ? (int) $setting['override'] : 0; */
		/* $_SESSION['customMusic_checker'] = true; */
		$playlist = 999;
		$premiumbeat_info['is_guest'] = 1;			
	} 
	
	if ($playlist == 0)
		$playlist = 999;

	/* if using playlist link */		
	if ($playlist = 999)
	{	
		/* Check if we are overiding the permission settings */			
		if ($setting['forumlink'] == 1)
		{
			/* First check if admin or guest */
			if ((int)$premiumbeat_info['is_admin'] == 1) 
				$check_group = 999;
			elseif ((int)$premiumbeat_info['is_guest'] == 1) 
				$check_group = -1;
			elseif((int)$premiumbeat_info['groups'][0] == 0)
				$check_group = 4;							
			/* Else user will be designated as the first highest rated group id */ 			
			else
			{							
				$check_user = $premiumbeat_info['groups'];
				$check_group = $check_user[0];								
				foreach ($check_user as $user)
				{
					$z = 1;
					$stars[0]  = 0;				
					$result2 = db_query("SELECT	ID_GROUP, stars FROM `{$db_prefix}membergroups` 
										WHERE `{$db_prefix}membergroups`.`ID_GROUP` = '$user'", __FILE__, __LINE__);								
					while ($val = mysql_fetch_assoc($result2))
					{
						$stars[$z] = substr($val['stars'], 0, 1);
						if ((int)$stars[$z-1] > (int)$stars[$z])
							$check_group = (int)$val['ID_GROUP'];
							
						$z++; 
					}
					mysql_free_result($result2);									
				}				
			}						
			$result2 = db_query("SELECT	ID_GROUP, permission FROM `{$db_prefix}permissions` 
								WHERE (`{$db_prefix}permissions`.`ID_GROUP` = '$check_group') AND (`{$db_prefix}permissions`.`permission` RLIKE '$columnx')", __FILE__, __LINE__);				
			while ($val = mysql_fetch_assoc($result2))
			{						  
				$playlist = str_replace($columnx, "" , $val['permission']);
				$playlist = (int)$playlist; 
				$_SESSION['customMusic_checker'] = true; 
			}
			mysql_free_result($result2);					
		}
		else
		{
			$playlist = (int)$setting['override'];
			$_SESSION['customMusic_checker'] = true;
		}
		
		if ($playlist == 999)
		{
			$playlist = (int)$setting['override'];
			$_SESSION['customMusic_checker'] = true;
		}				
	}	
	
	$playlist_id = $playlist;	
	$allow = 'premiumbeat_playlist_'. $playlist;
	$mygroups = groupsAllowedTo($allow);		
	$check_group = array_unique($mygroups['allowed']);
	if (empty($check_group[1]))
		$check_group[1] = 0;
			
	/* Load the needed forum settings */			
	$result2 = db_query("SELECT skin, skin_type FROM {$db_prefix}premiumbeat_forum_settings WHERE reference = 1 LIMIT 1", __FILE__, __LINE__);	
	while ($val = mysql_fetch_assoc($result2))
	{						  
		$skin = $val['skin'] ? $val['skin'] : '000000';						
		$skinType = $val['skin_type']? (int) $val['skin_type'] : 5;	
	}
	mysql_free_result($result2);
		
	/* Adjustable parameters  */	
	$width = 200;
	$height = 215;		
	$autoplay = 'yes';   /*  yes = autoplay,    no = manual play  */
	
	$columns_settings = array('height', 'width', 'autoplay', 'skin', 'skin_type', 'title');

	if ($playlist_id != 989)
	{	
		$a = check_block_playlist2($playlist_id);
		if ($a == true)
		{
			$result1 = db_query("SELECT myplaylist, height, width, autoplay, type, skin, skin_type, title FROM {$db_prefix}premiumbeat_settings  
									WHERE (myplaylist = {$playlist_id}) LIMIT 1", __FILE__, __LINE__);
    		while ($val = mysql_fetch_assoc($result1))
    		{   
				if ((empty($val['myplaylist'])))
					continue;   
				if ((int)$val['myplaylist'] < 1)
					continue;
				foreach ($columns_settings as $sets)
       			{
					if (empty($val[$sets])) 
						$val[$sets] = 0;
				}
				$autoplay = 'no';
				if($val['autoplay'] == 1)
					$autoplay = 'yes';   
					$width = $val['width'];
					$height = $val['height'];
					$skin = $val['skin']; 
					$skinType = $val['skin_type']; 
					$title = $val['title'];							                                 
			}
			mysql_free_result($result1);   
		}
	}	
	if($playlist_id < 1) 
		$playlist_id = 1;
		
	$_SESSION['playlist_id'] = $playlist_id;
	$context['template_layers'] = array();
	require_once($boarddir . '/Themes/default/' . 'CustomMusicPopup.template.php');	
	template_mp3_popup($height, $width, $skin, $skinType, $autoplay, $playlist_id, $title);	
}

/* Check if playlist settings exist */
function check_block_playlist2($play)
{
	global $db_prefix;      
	$result2 = db_query("SELECT myplaylist FROM {$db_prefix}premiumbeat_settings WHERE myplaylist = {$play} LIMIT 1", __FILE__, __LINE__);
	$result3 = mysql_num_rows($result2);   
	mysql_free_result($result2);      
	if ($result3 > 0)
		return true;
		
	return false;
}   

/* Safe character filter */
function formspecialcharsformeagain2($var=false, $hq=false)
{
	$pattern = '/&(#)?[a-zA-Z0-9]{0,};/';
   	if (!$var)
		return false;
			
	if (is_array($var))
	{   
		$out = array();     
		foreach ($var as $key => $v)
			$out[$key] = formspecialcharsformeagain2($v);         
            
	}
	else
	{
		$out = $var;
		while (preg_match($pattern,$out) > 0)
			$out = htmlspecialchars_decode($out,ENT_QUOTES);     
                                       
		$out = htmlspecialchars(stripslashes(trim($out)), ENT_QUOTES,'UTF-8',true);   
	}
				     
	return str_replace("&#039;", "`", $out);		
} 

/* Check if the column exists */
function checkFieldPremium1($tableName,$columnName)
{
	$checkTable = false;
	$checkTable = check_table_existsPremium1($tableName);
	if ($checkTable == true)
	{
		global $db_prefix;
		$check = false;
		$checkval = false;
		$check = db_query("DESCRIBE {$db_prefix}$tableName $columnName", __FILE__, __LINE__);
		$checkval = mysql_num_rows($check);
		mysql_free_result($check);
		if ($checkval > 0)
			return true;
	}
	return false;
} 

/*  Check if table exists  */
function check_table_existsPremium1($table)
{
	global $db_prefix;
	$check = false;
	$checkval = false;
	$check = db_query("SHOW TABLES LIKE '{$db_prefix}$table' ", __FILE__, __LINE__);
	$checkval = mysql_num_rows($check);
	mysql_free_result($check);
	if ($checkval >0)
		return true;
		
	return false;
}

/* Patch for the player template */
function template_main()
{
	/* This patch stops the template error */
	/* Just leave it empty */
	return;
} 

/* Read contents of autolist music directory */
function ReadMusicDirectory($dir)
{
	$listDir = array();
	if($handler = @opendir($dir))
	{
		while (($sub = @readdir($handler)) !== FALSE)
		{
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db")
			{
				if(@is_file($dir."/".$sub))
					$listDir[] = $sub;
				elseif(@is_dir($dir."/".$sub))
					$listDir[$sub] = $this->ReadMusicDirectory($dir."/".$sub);
			}
		}   
		closedir($handler);
	}
	return $listDir;   
} 
?>