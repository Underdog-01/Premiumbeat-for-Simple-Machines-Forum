<?php
/* This file is here to protect the current directory */
foreach (explode('/', dirname(!empty($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : str_replace('\\','/',__FILE__))) as $sub_directory)
{
	if (empty($directoryA))
		$directoryA = $sub_directory .'/';
	else
		$directoryA .= $sub_directory .'/';
		
	$directoryX[] = $directoryA;
}

$directories = array_reverse($directoryX);

foreach ($directories as $directory)
{	
	if (@file_exists($directory . 'Settings.php'))
	{	
		@require($directory . 'Settings.php');
		if (!empty($boardurl))
			@header('Location: ' . $boardurl);
	}	
}
exit;
?>