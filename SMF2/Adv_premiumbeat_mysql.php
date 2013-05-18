<?php
/*
    <id>premiumbeat:mp3forsmf</id>
	<name>Premiumbeat Flash MP3 Player For SMF Forums</name>
	<version>2.0.1</version>
	<type>modification</type>
*/	

/*  This file is for mysql setup and transferring possible values from v1.71 or prior versions */


/* START - Mysql setup and transfers  */
global $modSettings, $smcFunc, $db_prefix, $context, $scripturl;

/*  Set variables and arrays  */
$mymusic = array();
$music = array();
$myset = array();
$setting['variable'] = array();
$setting['value'] = array();
$max = 9999;
$counter = 1;
$tally = 0;
$types = array('tag', 'playlist', 'enable', 'description', 'url', 'id_user');
$sets = array('myplaylist', 'height', 'width', 'autoplay', 'type', 'skin', 'skin_type', 'autofile', 'title', 'equip');
$forum_sets = array('reference', 'override', 'filename', 'forumlink', 'autoplay', 'skin', 'skin_type', 'forumlinkDrop');
$data = array('id', 'user_id', 'my_playlist', 'file', 'author', 'date', 'downloads');
$user = array('user_id', 'last_playlist', 'pref_playlist', 'downloads', 'autoplay', 'visibility');

$new_columnsTypes = array(
                          'premiumbeat_mp3s' => array ('int(10) unsigned NOT NULL AUTO_INCREMENT', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL default 0', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL'),				
		                  'premiumbeat_settings' => array ('int(10) unsigned NOT NULL AUTO_INCREMENT', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL'),
						  'premiumbeat_forum_settings' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL'),
						  'premiumbeat_data' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT', 'INT(10) UNSIGNED NOT NULL', 'varchar(255) NOT NULL', 'INT(10) UNSIGNED NOT NULL', 'INT(10) UNSIGNED', 'INT(10) UNSIGNED NOT NULL'),
						  'premiumbeat_users' => array('int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL'));				

/*  Create premiumbeat tables if they do not exist  */
$checkit = false;
$table = 'premiumbeat_mp3s';
$checkit = check_table_existsPremium($table);
if ($checkit == false)
{
	$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table} 
                                   (
									`tag` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
									`playlist` INT(10) UNSIGNED NOT NULL,
									`enable` int(10) unsigned NOT NULL default 0,
									`description` varchar(255) NOT NULL,
									`url` varchar(255) NOT NULL,
									`id_user` int(10) unsigned NOT NULL default 0,
									PRIMARY KEY (`tag`))");		
}

$table = 'premiumbeat_settings';
$checkit = false;
$checkit = check_table_existsPremium($table);

if ($checkit == false) 
{
	$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table} 
								(
								`myplaylist` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								`height` INT(10) UNSIGNED NOT NULL,
								`width` int(10) unsigned NOT NULL,
								`autoplay` int(10) unsigned NOT NULL,
								`type` int(10) unsigned NOT NULL,
								`skin` varchar(255) NOT NULL,
								`skin_type` int(10) unsigned NOT NULL,
								`autofile` varchar(255) NOT NULL,
								`title` varchar(255) NOT NULL,	
								`equip` int(10) unsigned NOT NULL,									
								PRIMARY KEY (`myplaylist`))");	
		
}
$table = 'premiumbeat_forum_settings';
$checkit = false;
$checkit = check_table_existsPremium($table);

if ($checkit == false) 
{
	$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table} 
								(
								`reference` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,										
								`override` varchar(255) NOT NULL,
								`filename` varchar(255) NOT NULL,
								`forumlink` int(10) unsigned NOT NULL,
								`autoplay` varchar(255) NOT NULL,
								`skin` varchar(255) NOT NULL,	
								`skin_type` int(10) unsigned NOT NULL,	
								`forumlinkDrop` int(10) unsigned NOT NULL,								
								PRIMARY KEY (`reference`))");	
		
}

$table = 'premiumbeat_data';
$checkit = false;
$checkit = check_table_existsPremium($table);

if ($checkit == false) 
{
	$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table} 
							(
							`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,		
							`user_id` mediumint(8) UNSIGNED NOT NULL,
							`my_playlist` INT(10) UNSIGNED NOT NULL,										
							`file` varchar(255) NOT NULL,
							`author` INT(10) UNSIGNED NOT NULL,		
							`date` INT(10) UNSIGNED NOT NULL,
							`downloads` INT(10) UNSIGNED NOT NULL,																				
							PRIMARY KEY (`id`))");	
		
}

$table = 'premiumbeat_users';
$checkit = false;
$checkit = check_table_existsPremium($table);

if ($checkit == false) 
{
	$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table} 
							(
							`user_id` INT(10) UNSIGNED NOT NULL,		
							`last_playlist` INT(10) UNSIGNED NOT NULL,	
							`pref_playlist` INT(10) UNSIGNED NOT NULL,							
							`downloads` INT(10) UNSIGNED NOT NULL,	
							`autoplay` INT(10) UNSIGNED NOT NULL,	
							`visibility` INT(10) UNSIGNED NOT NULL,																													
							PRIMARY KEY (`user_id`))");	
		
}	
				
/*  Add extra needed columns into existing tables for columns only native to Premiumbeat  */
/*  This is necessary due to possible manual deletion or otherwise  */
$tableName = 'premiumbeat_settings';	
$request = false;
$i = 0;
$columnName = false;
foreach ($sets as $columnName)
{
	$a = false;                           
	$columnType = $new_columnsTypes[$tableName][$i];
	$a = checkFieldPremium($tableName,$columnName);			   
	if ($a == false)
		$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$tableName ADD $columnName $columnType");					                                       
			 				   
	$i++;
}			   

$tableName = 'premiumbeat_users';	
$request = false;
$i = 0;
$columnName = false;
foreach ($user as $columnName)
{
	$a = false;                           
	$columnType = $new_columnsTypes[$tableName][$i];
	$a = checkFieldPremium($tableName,$columnName);			   
	if ($a == false)
		$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$tableName ADD $columnName $columnType");				                                    
			 				   
	$i++;
}		
	
$tableName = 'premiumbeat_data';	
$request = false;
$i = 0;
$columnName = false;
foreach ($data as $columnName)
{
	$a = false;                           
	$columnType = $new_columnsTypes[$tableName][$i];
	$a = checkFieldPremium($tableName,$columnName);			   
	if ($a == false)
		$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$tableName ADD $columnName $columnType");					                                        
			 				   
	$i++;
}	
			    
$tableName = 'premiumbeat_mp3s';	
$request = false;
$i = 0;
$columnName = false;
foreach ($types as $columnName)
{
	$a = false;                           
	$columnType = $new_columnsTypes[$tableName][$i];
	$a = checkFieldPremium($tableName,$columnName);			   
	if ($a == false)
		$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$tableName ADD $columnName $columnType");					                                   
		 				   
	$i++;
}		

$tableName = 'premiumbeat_forum_settings';	
$request = false;
$i = 0;
$columnName = false;
foreach ($forum_sets as $columnName)
{
	$a = false;                           
	$columnType = $new_columnsTypes[$tableName][$i];
	$a = checkFieldPremium($tableName,$columnName);			   
	if (!$a)
	{
		$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$tableName ADD $columnName $columnType");	
		
		if ($columnName == 'skin')
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '000000' WHERE `{$db_prefix}$tableName`.{$columnName} = false LIMIT 1");
		elseif ($columnName == 'skin_type')
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET skin_type = 1 WHERE `{$db_prefix}$tableName`.{$columnName} = 0 LIMIT 1");
		elseif ($columnName == 'forumlinkDrop')
			$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET forumlinkDrop = 1 WHERE `{$db_prefix}$tableName`.{$columnName} = 0 LIMIT 1");	
						
	}					                                  
		 				   
	$i++;
}			   

/*  Create needed reference if it does not exist  */
$a = false;
$tag = 1;
$a = check_ref($tag);
if ($a == false)
{
	$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_forum_settings WHERE reference LIKE {string:like}', array('like' => $tag));
	$request = $smcFunc['db_query']('', "INSERT INTO `{$db_prefix}premiumbeat_forum_settings` (`reference`, `override`, `filename`, `forumlink`, `autoplay`, `skin`, `skin_type`, `forumlinkDrop`) VALUES ('1', '1', 'mp3_music', '1', '99', '000000', '1', '1')");		
}
			   
/*  Insert/reset default permission values  */
/*
$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}permissions WHERE permission LIKE {string:like}', array('like' => 'premiumbeat_config'));
$request = $smcFunc['db_query']('', "INSERT INTO `{$db_prefix}permissions` (`id_group`, `permission`, `add_deny`) VALUES ('2', 'premiumbeat_config', '1')");		
$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}permissions WHERE permission LIKE {string:like}', array('like' => 'premiumbeat_settings'));
$request = $smcFunc['db_query']('', "INSERT INTO `{$db_prefix}permissions` (`id_group`, `permission`, `add_deny`) VALUES ('2', 'premiumbeat_settings', '1')");		
*/

/*  Check for v1.71 (or prior) mp3's and settings - drop them and put into arrays */
foreach ($sets as $set)
{
	if (!empty($modSettings['customMusic_'.$set])) 
	{
		$myset[$set]  = $modSettings['customMusic_'.$set];
		$smcFunc['db_query']('', 'DELETE FROM {db_prefix}settings WHERE variable = {string:kind}',
									array('kind' => 'customMusic_'.$set,));
	}
	else
		$myset[$set] = 0;
}

if ($myset['skin'] == 0)
	$myset['skin'] = '000000';
	
$tally = 0;
while ($counter < $max)
{
	foreach ($types as $type)
	{
		if (!empty($modSettings['customMusic_'.$type.'_'.$counter]))
 		{
			$tally = $counter;
			$music[$type][$counter] = $modSettings['customMusic_'.$type.'_'.$counter]; $tally = $counter;
			$smcFunc['db_query']('', 'DELETE FROM {db_prefix}settings WHERE variable LIKE {string:like}',
													array('like' => 'customMusic_%_' . $counter,));
		}

	}
	$counter++;
}

/*  Alter existing premiumbeat tables/columns with mp3's and settings from v1.71 (or prior)  */
$result1 = array();
$val = array();
$mp3_setting['tag'] = array();
$amt_values = 0;
$a = false;
$a = checkFieldPremium('premiumbeat_mp3s','tag');
if ($a == true)
{
	$result1 = $smcFunc['db_query']('', "SELECT * FROM {db_prefix}premiumbeat_mp3s");
	$amt_values = $smcFunc['db_num_rows']($result1); 
	$smcFunc['db_free_result']($result1);
}
$x = 0;
if ($amt_values > 0)
{   
	$x = 1;      
	$result = $smcFunc['db_query']('', 'SELECT tag FROM {db_prefix}premiumbeat_mp3s ORDER BY tag ASC');      
	while ($val = $smcFunc['db_fetch_assoc']($result))
	{                               
		$mp3_setting['tag'][$x] = $x;                			
		$x++;
	}  
	$smcFunc['db_free_result']($result);
}

$tableName = 'premiumbeat_mp3s';
$columnName = 'tag';
$check = false;
$x = 1;
while ($x < ($tally+1))
{
	if (empty($music['tag'][$x]))
		$music['tag'][$x] = false;
		
	$value = $music['tag'][$x];
	if (empty($mp3_setting['tag'][$x]))
		$mp3_setting['tag'][$x] = false;
		
	if (empty($music['playlist'][$x])) 
		$music['playlist'][$x] = 0;
		
	$music['playlist'][$x]++;
	if ($value == true)
	{
		$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_mp3s WHERE tag LIKE {string:like}',
												array('like' => $value));
		$request = $smcFunc['db_query']('', "INSERT INTO `{$db_prefix}premiumbeat_mp3s` (`tag`) VALUE ('$value')");
	}
	$x++;
}
$count = 1;
if ($tally > 0)
{
	while ($count < ($tally + 1))
	{
		$tableName = 'premiumbeat_mp3s';
		foreach ($types as $columnName)
		{
			if ($columnName == 'enable' && empty($music['enable'][$count])) 
				$music['enable'][$count] = false;
				
			if ($columnName == 'enable' && ($music['enable'][$count] == false) && ($music['tag'][$count] == true)) 
				createpremiumval($tableName, $columnName, '0', $count);
			elseif (($columnName == 'playlist') && ($music['playlist'][$count] == 1) && ($music['tag'][$count] == true))
			{
				$a = $music['playlist'][$count];
				createpremiumval($tableName, 'playlist', $a, $count);
			} 
			elseif ((!empty($music[$columnName][$count])) && ($music['tag'][$count] == true))
			{
				$value = $music[$columnName][$count];
				if ($columnName == true)
					createpremiumval($tableName, $columnName, $value, $count); 
			}
		}
		$count++;				
	}
}
$a = false;
$tally2 = 0;
$a = checkFieldPremium('premiumbeat_mp3s','tag');
if ($a == true)
{
	$result1 = $smcFunc['db_query']('', "SELECT * FROM {db_prefix}premiumbeat_mp3s");
	$tally2 = $smcFunc['db_num_rows']($result1); 
	$smcFunc['db_free_result']($result1);
}


/*  Create default settings for playlist 1 if they do not exist  */
$result1 = array();
$amt_values = 0;
$a = false;
$playlist = 1;
$a = checkFieldPremium('premiumbeat_settings','myplaylist');
if ($a == true)
{
	$result1 = $smcFunc['db_query']('', "SELECT myplaylist FROM {db_prefix}premiumbeat_settings WHERE myplaylist = 1");
	while ($val1 = $smcFunc['db_fetch_assoc']($result1))
	{
		$check = false;
		if (empty($val1['myplaylist']))
			$val1['myplaylist'] = false;
			
		if ($val1['myplaylist'] == 1)
			$check = true;
			
	}
	$smcFunc['db_free_result']($result1);
	
	if ($check == false)
	{   
	   	$myval = 1;
		$request = $smcFunc['db_query']('', 'DELETE FROM {db_prefix}premiumbeat_settings WHERE myplaylist LIKE {string:list}',
													array('list' => $myval));
		$request = $smcFunc['db_query']('', "INSERT INTO {db_prefix}premiumbeat_settings (`myplaylist`, `height`, `width`, `autoplay`, `type`, `skin`, `skin_type`, `autofile`, `title`, `equip`) VALUES ('1', '215', '200', '0', '0', '000000', '5', false, false, '0')");		
	}	
}

/* Insert integration hooks */
add_integration_function('integrate_pre_include', '$sourcedir/CustomMusicHooks.php');
add_integration_function('integrate_actions', 'customMusic_actions');
add_integration_function('integrate_load_permissions', 'customMusic_load_permissions');
add_integration_function('integrate_menu_buttons', 'customMusic_menu_buttons');
add_integration_function('integrate_admin_areas', 'customMusic_admin_areas');
add_integration_function('integrate_bbc_buttons', 'customMusic_bbc_button');
add_integration_function('integrate_bbc_codes', 'customMusic_bbc_code');

/* Adjust tables */
PB_adjust_tables();

$time = 1; 
$url = $scripturl . '?action=admin;area=premiumbeat;';
@header("Refresh: $time; url=$url"); 
/* END - Mysql setup and transfers  */

/* Check if the column exists */
function checkFieldPremium($tableName,$columnName)
{
	$checkTable = false;
	$checkTable = check_table_existsPremium($tableName);
	if ($checkTable == true)
	{
		global $db_prefix, $smcFunc;
		$check = false;
		$checkval = false;
		$check = $smcFunc['db_query']('', "DESCRIBE {$db_prefix}$tableName $columnName");
		$checkval = $smcFunc['db_num_rows']($check);
		$smcFunc['db_free_result']($check);
		if ($checkval > 0)
			return true;
	}
	
	return false;
} 

/*  Returns amount of columns in a table  */
function checkTablePremium($tableName)
{
	$checkTable = false;
	$checkTable = check_table_existsPremium($tableName);
	if ($checkTable == true)
	{
		global $db_prefix, $smcFunc;
		$check = false;
		$checkval = false;
		$check = $smcFunc['db_query']('', "DESCRIBE {$db_prefix}$tableName");
		$checkval = $smcFunc['db_num_rows']($check);
		$smcFunc['db_free_result']($check);
		if ($checkval > 0) 
			return $checkval;
	}
	
	return false;
} 

/*  Check if table exists  */
function check_table_existsPremium($table)
{
	global $db_prefix, $smcFunc;
	$check = false;
	$checkval = false;
	$check = $smcFunc['db_query']('', "SHOW TABLES LIKE '{$db_prefix}$table'");
	$checkval = $smcFunc['db_num_rows']($check);
	$smcFunc['db_free_result']($check);
	if ($checkval >0)
		return true;
		
	return false;
}


/*  Update table -> column -> value   */
function createpremiumval($tableName, $columnName, $value, $tag) 
{
	global $smcFunc, $db_prefix;
	$i = 0;
	$request = false;   
	if (empty($tableName) || empty($columnName) || empty($tag)) {return;}
	if (empty($value))
		$value = false;
		
	$request = $smcFunc['db_query']('', "UPDATE {$db_prefix}$tableName SET $columnName = '{$value}' WHERE `{$db_prefix}$tableName`.`tag` = {$tag} LIMIT 1");					 
}

/* Check for reference value */
function check_ref($tag)
{
	global $db_prefix, $smcFunc;		
	$result2 = $smcFunc['db_query']('', "SELECT reference FROM {db_prefix}premiumbeat_forum_settings WHERE reference = {$tag} LIMIT 1");
	$result3 = $smcFunc['db_num_rows']($result2);	
	$smcFunc['db_free_result']($result2);		
	if ($result3 > 0)
		return true;
		
	return false;
}	

/* Adjust Premiumbeat tables to MyISAM type & common Collation */
function PB_adjust_tables()
{	
	global $smcFunc, $db_name;	
	$tables = array('premiumbeat_data', 'premiumbeat_forum_settings', 'premiumbeat_mp3s', 'premiumbeat_settings', 'premiumbeat_users');
	
	/* Query Engine & Collation of the SMF settings table */
	$result = $smcFunc['db_query']('', "SHOW TABLE STATUS FROM `$db_name`");
	while ($val = $smcFunc['db_fetch_assoc']($result))
	{
		$engine = $val['Engine'];
		$collation = $val['Collation'];
		$charsetx = explode('_', $val['Collation']);
		$charset = $charsetx[0];          
	}
	$smcFunc['db_free_result']($result);
	
	/* Adjust SpamBlocker tables to match */
	foreach ($tables as $table)
	{
		$alterTable = $smcFunc['db_query']('', "ALTER TABLE {db_prefix}{$table} CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
		$alterTable = $smcFunc['db_query']('', "ALTER TABLE {db_prefix}{$table} ENGINE = {$engine}");
	}	
}	
?>