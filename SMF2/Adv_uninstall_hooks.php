<?php
/*
    <id>premiumbeat:mp3forsmf</id>
	<name>Premiumbeat Flash MP3 Player For SMF Forums</name>
	<version>2.0.1</version>
	<type>modification</type>
*/	

/*  This file is for removing integration hooks */


global $txt, $smcFunc, $db_prefix, $modSettings;
global $addSettings, $permissions, $tables, $sourcedir;

if (!defined('SMF'))
	require '../SSI.php';


/* Remove integration hooks */
remove_integration_function('integrate_pre_include', '$sourcedir/CustomMusicHooks.php');
remove_integration_function('integrate_actions', 'customMusic_actions');
remove_integration_function('integrate_load_permissions', 'customMusic_load_permissions');
remove_integration_function('integrate_menu_buttons', 'customMusic_menu_buttons');
remove_integration_function('integrate_admin_areas', 'customMusic_admin_areas');

?>