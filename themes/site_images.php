<?php 
include_once('../includes/config.php');
function array_remove_val($array, $value)
{
	foreach($value as $values)
	{
		if(in_array($values, $array))
		{ 
			unset($array[array_search($values, $array)]);
		}
	}
	return $array;
}
$jsonArr = array();
if ($handle = opendir(DIR_URL.'themes/images/')) {
	$blacklist = array('.', '..','');
	while (false !== ($file[] = readdir($handle)))
	{	
		sort($file, SORT_NUMERIC); 	// sorting all Directores   
	}
	closedir($handle);
	$new_array = array_remove_val($file, $blacklist);				
	$file = $new_array;
	
	if(count($file)>0)
	{
		//Print all Directories / Files
		foreach($file as $k=>$files)
		{
			$jsonArr[$k]['image'] = SITE_URL."themes/images/".$files;
			$jsonArr[$k]['thumb'] = SITE_URL."themes/images/".$files;
			$jsonArr[$k]['folder'] = "Small";
		}
	}
}
echo json_encode($jsonArr);
?>