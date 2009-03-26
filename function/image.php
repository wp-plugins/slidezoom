<?php

function resize_image($filename, $type, $orig_width, $orig_height, $new_width, $new_height) {

  Debug_Collector("Resizing file using GD ...");
    
  // determine gd create function to use from filetype
  switch($type) {
    case IMAGETYPE_GIF:
      $img_orig = imagecreatefromgif($filename);
      break;
    case IMAGETYPE_JPEG:
      $img_orig = imagecreatefromjpeg($filename);
      break;
    case IMAGETYPE_PNG:
      $img_orig = imagecreatefrompng($filename);
      break;
    default:
      Debug_Collector("Error: unsupported filetype in ".__FUNCTION__.".");
      return false;
  }

  // resize
  $img_new = imagecreatetruecolor($new_width,$new_height);
  $result = imagecopyresampled(
    $img_new, $img_orig,
    0, 0, 0, 0,
    $new_width, $new_height,
    $orig_width, $orig_height
  );
  
  if (!$result) {
  	Debug_Collector("Error: GD resize failed ...");
  	return false;
  }

  // determine gd save function to use from filetype
  switch($type) {
    case IMAGETYPE_GIF:
      $result = imagegif($img_new, $filename);
      break;
    case IMAGETYPE_JPEG:
      $result = imagejpeg($img_new, $filename);
      break;
    case IMAGETYPE_PNG:
      $result = imagepng($img_new, $filename);
      break;
  } 
  
  if (!$result) {
  	Debug_Collector("Error: GD write failed.");
  	return false;
  }
  return true;
}
?>