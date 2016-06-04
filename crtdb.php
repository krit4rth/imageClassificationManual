<?php
/**
 * Created by PhpStorm.
 * User: kritarth
 * Date: 4/6/16
 * Time: 12:54 AM
 */
require 'dbcon.php';

$dbcon = new dbcon();
$link = $dbcon->connect();

$dir    = '/media/kritarth/ExternalStorage/iitdCampusVideo/annotated/d1/';
$files1 = scandir($dir,2);
//$files2 = scandir($dir, 1);
$len = sizeof($files1);
while($len--){
    $q = 'INSERT INTO images';
}

//echo $files1[4];
print_r($files1);
//print_r($files2);
?>
