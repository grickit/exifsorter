<?php

$parentPath = '/media/storage/dhoagland/leah/';
$photoFiles = scandir($parentPath);

$countMake = array();
$countDate = array();

function handle_Make($exifData,&$countMake) {
  $exifMake = (isset($exifData['Make']) ? $exifData['Make'] : 'None');
  $exifMake = preg_replace("/[^A-Za-z0-9 ]/", '', $exifMake);

  if(!isset($countMake[$exifMake])) $countMake[$exifMake] = 0;
  $countMake[$exifMake]++;
  
  return $exifMake;
}

function handle_Date($exifData,&$countDate) {
  $exifDate = (isset($exifData['DateTimeOriginal']) ? date('Y-m',strtotime($exifData['DateTimeOriginal'])) : 'None');

  if(!isset($countDate[$exifDate])) $countDate[$exifDate] = 0;
  $countDate[$exifDate]++;

  return $exifDate;
}

foreach($photoFiles as $key => $currentPhoto) {
  if($currentPhoto == '.' || $currentPhoto == '..') continue;

  $exifData = exif_read_data($parentPath.$currentPhoto);

  $exifMake = handle_Make($exifData,$countMake);
  $exifDate = handle_Date($exifData,$countDate);
  
  $newPath = "/media/storage/dhoagland/leah_sorted/{$exifMake}/{$exifDate}/";
  
  if(!file_exists($newPath)) mkdir($newPath,0777,true);
  echo "Copying to {$newPath}{$currentPhoto} ...\n";
  copy($parentPath.$currentPhoto,$newPath.$currentPhoto);

}

var_dump($countMake);
var_dump($countDate);

?>