<?php

/* Premiumbeat for SMF - Modification v2.0.1	*/
/*  c/o Underdog @ http://askusaquestion.net	*/
/* This file is for the integration hooks	*/

if (!defined('SMF'))
	die('Hacking attempt...');
	
function customMusic_array_insert(&$input, $key, $insert, $where = 'before', $strict = false)
{
	$position = array_search($key, array_keys($input), $strict);
	
	// Key not found -> insert as last
	if ($position === false)
	{
		$input = array_merge($input, $insert);
		return;
	}
	
	if ($where === 'after')
		$position += 1;

	// Insert as first
	if ($position === 0)
		$input = array_merge($insert, $input);
	elseif (phpversion() >= '5.0.2')
		$input = array_merge(
			array_slice($input, 0, $position, true),
			$insert,
			array_slice($input, $position, null, true)
		);
	else
		$input = array_merge(
			array_slice($input, 0, $position),
			$insert,
			array_slice($input, $position, null)
		);		
}
	
function customMusic_actions(&$actionArray)
{
	global $modSettings;
	loadLanguage('Premiumbeat');
		
	$actionArray['customMusicPopup'] = array('CustomMusicPlaylist.php', 'PremiumbeatPopup');
	$actionArray['customMusic'] = array('CustomMusicPlaylist.php', 'CustomMusicMp3');
}


function customMusic_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context;
	loadLanguage('Premiumbeat');
		
	$permissionList['membergroup'] += array(
			'premiumbeat_config' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),
			'premiumbeat_settings' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),
			'premiumbeat_showdelete' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),		
			'premiumbeat_showlink' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),
			'premiumbeat_showbbc' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),
			'premiumbeat_showplaylists' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),	
			'premiumbeat_showupload' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),	
			'premiumbeat_showdownload' => array(false, 'premiumbeat_perms', 'premiumbeat_perms'),		
	);
	
	$context['non_guest_permissions'] = array_merge(
		$context['non_guest_permissions'],
		array(
			'premiumbeat_config',
			'premiumbeat_settings',
			'premiumbeat_showupload',
			'premiumbeat_showdownload',	
			'premiumbeat_showdelete',				
		)
	);
	
	$permissionGroups['membergroup']['simple'] += array(
			'premiumbeat_perms',		
	);	
	$permissionGroups['membergroup']['classic'] += array(
			'premiumbeat_perms',		
	);	
		
}

function customMusic_menu_buttons(&$menu_buttons)
{
	global $context, $modSettings, $scripturl, $txt;
	loadLanguage('Premiumbeat');
				
	$context['premiumbeat_showlink'] = allowedTo('premiumbeat_showlink');
	if (empty($modSettings['premiumbeat_link'])) {$modSettings['premiumbeat_link'] = 0;} 
	if ($modSettings['premiumbeat_link'] == 0) {return;}	
	customMusic_array_insert($menu_buttons, 'mlist',
		array(
			'premiumbeat1' => array(
                'title' => $txt['premiumbeat_player'],
                'href' => 'javascript:window.open(\''.'index.php?action=customMusicPopup;playlist=999\',\'Premiumbeat\',\'width=202,height=216,resizable=1,top=0,left=0\');void(0);',
                'show' => $context['premiumbeat_showlink'],				
                'sub_buttons' => array(
                ),
            ),
		)
	);
	
	if (allowedTo('premiumbeat_config') || allowedTo('premiumbeat_settings') || allowedTo('premiumbeat_showupload') || allowedTo('premiumbeat_showdownload'))
	{
		customMusic_array_insert($menu_buttons, 'mlist',
			array(
				'premiumbeat2' => array(
					'title' => $txt['customMusic_tabtitle'],
					'href' => $scripturl . '?action=admin;area=premiumbeat',
					'show' => true,					
					'sub_buttons' => array(
						'configuration' => array(
							'title' => $txt['customMusic_tabtitle6'],
							'href' => $scripturl . '?action=admin;area=premiumbeat',
							'show' => (allowedTo('premiumbeat_config') || allowedTo('premiumbeat_settings') || allowedTo('premiumbeat_showupload') || allowedTo('premiumbeat_showdownload')),
						),
						'settings' => array(
							'title' => $txt['premiumbeat_forum'],
							'href' => $scripturl . '?action=admin;area=premiumbeat_settings',
							'show' => allowedTo('premiumbeat_settings'),
						),
					),
				),
			)
		);
	}
}

function customMusic_admin_areas(&$admin_areas)
{
	global $context, $modSettings, $scripturl, $txt;
	loadLanguage('Premiumbeat');
	$subsections = array();
	if (allowedTo('premiumbeat_config'))
		$subsections = array('BrowsePremiumbeat' =>array($txt['premiumbeat_browse']), 'EditPremiumbeat' =>array($txt['premiumbeat_edit_add']));
	if (allowedTo('premiumbeat_settings'))
		$subsections += array('SettingsPremiumbeat' =>array($txt['premiumbeat_settings']), 'PlaylistPremiumbeat' =>array($txt['premiumbeat_playlist']));
	if (allowedTo('premiumbeat_showupload') || allowedTo('premiumbeat_showdownload'))	
		$subsections += array('UploadPremiumbeat' => array($txt['premiumbeat_upload']));	
		
	$subsections += array('LicensePremiumbeat' => array($txt['premiumbeat_license']));	
	/* $subsections = array('BrowsePremiumbeat' =>array($txt['premiumbeat_browse']),						
							'SettingsPremiumbeat' =>array($txt['premiumbeat_settings']),
							'PlaylistPremiumbeat' =>array($txt['premiumbeat_playlist']),
							'EditPremiumbeat' =>array($txt['premiumbeat_edit_add']),	
							'UploadPremiumbeat' => array($txt['premiumbeat_upload']),
							'LicensePremiumbeat' => array($txt['premiumbeat_license']),
						);	*/
	customMusic_array_insert($admin_areas, 'members',
			array(
				'premiumbeat_admin' => array(
				'title' => $txt['customMusic_tabtitle'],
				'permission' => array('premiumbeat_config', 'premiumbeat_settings', 'premiumbeat_showupload', 'premiumbeat_showdownload'),
				'areas' => array(
					'premiumbeat' => array(
						'label' => $txt['customMusic_tabtitle6'],
						'file' => 'CustomMusic.php',
						'function' => 'custom_mp3',
						'icon' => 'premiumbeat_config.png',
						'permission' => array('premiumbeat_config', 'premiumbeat_settings', 'premiumbeat_showupload', 'premiumbeat_showdownload'),			
						'subsections' => $subsections,
					),
					'premiumbeat_settings' => array(
						'label' => $txt['customMusic_tabtitle7'],
						'file' => 'CustomMusicSettings.php',
						'function' => 'custom_mp3_forum_settings',
						'icon' => 'premiumbeat_settings.png',
						'permission' => array('premiumbeat_settings'),
						'subsections' => array(
						'PremiumbeatSettings' => array($txt['premiumbeat_forum']),
						),
					),
				),
			),
		)
	);
}

/* Premiumbeat bbc button */
function customMusic_bbc_button(&$insert)
{
	global $context, $modSettings, $scripturl, $txt;
	loadLanguage('Premiumbeat');
	if (allowedTo('premiumbeat_showbbc'))
		{
			$context['bbc_tags'][1][] = array();
			$context['bbc_tags'][1][] = array(										
					'image' => 'premiumbeat_bbc',
					'code' => 'premiumbeat',
					'before' => '[premiumbeat]',
					'after' => '[/premiumbeat]',
					'description' => $txt['premiumbeat_bbc_mp3']													
			);					
		}	
	return;	
}	

/* Premiumbeat bbc code execution */
function customMusic_bbc_code(&$codes)
{
	global $boardurl;
	if (allowedTo('premiumbeat_showbbc'))
		{				
			$_SESSION['customMusic_checker'] = true;
			$codes[] =	array(
					'tag' => 'premiumbeat',
					'type' => 'unparsed_equals',
					'before' => '<div style="text-align:left;"><div style="float:left;text-align:center;"><a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong=$1;\',\'Premiumbeat\',\'width=214,height=230,resizable=1\');void(0);">
					<img src="'.$boardurl.'/my_music/premiumbeat_play_Mp3.gif" width="20" height="20" alt="" /><br />',
					'after' => '</a></div><div style="clear:both;">&nbsp;</div></div>',				
					'validate' => create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;'),
					'block_level' => true,	
					'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
					'disabled_before' => '',
					'disabled_after' => '',	
					'trim' => 'inside',									
					);
			$codes[] = array(
					'tag' => 'premiumbeat',
					'type' => 'unparsed_content',
					'content' => '<div style="text-align:left;">
					<a href="javascript:window.open(\''.$boardurl.'/index.php?action=customMusicPopup;playlist=989;playsong=$1\',\'Premiumbeat\',\'width=210,height=230,resizable=1\');void(0);">
					<img src="'.$boardurl.'/my_music/premiumbeat_play_Mp3.gif" width="20" height="20" alt="" /></a></div>',				
					'validate' => create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;'),
					'block_level' => true,
					'disabled_before' => '',
					'disabled_after' => '',							
			);					
		}
	else
		{			
			$codes[] = array(
				'tag' => 'premiumbeat',
				'type' => 'unparsed_content',
				'content' => '<div style="text-align:left;"><img src="'.$boardurl.'/my_music/premiumbeat_play_Mp3.gif" width="20" height="20" alt="" /></div>',				
				'validate' => create_function('&$tag, &$data, $disabled', '
				$data = strtr($data, array(\'<br />\' => \'\'));
				if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
					$data = \'http://\' . $data;'),
				'block_level' => true,
				'disabled_before' => '',
				'disabled_after' => '',							
			);	
			$codes[] =	array(
					'tag' => 'premiumbeat',
					'type' => 'unparsed_equals',
					'before' => '<div style="text-align:left;"><div style="float:left;text-align:center;"><img src="'.$boardurl.'/my_music/premiumbeat_play_Mp3.gif" width="20" height="20" alt="" /><br />',
					'after' => '</div><div style="clear:both;">&nbsp;</div></div>',				
					'validate' => create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;'),
					'block_level' => true,	
					'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
					'disabled_after' => ' ($1)',									
					);							
		}			
	return;
}
?>