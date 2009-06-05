<?php session_start(); ?>
<?php
if ( isset($_SESSION["outputhtml"]) )
{
$outputhtml = $_SESSION["outputhtml"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>SlideZoom Preview</title>
	<?php
	echo "<script type=\"text/javascript\" src=\"highslide/highslide.js\"></script>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"highslide/highslide.css\" />";
	echo "<script type=\"text/javascript\">";
	echo "hs.graphicsDir=\"highslide/graphics/\";";
	echo "hs.wrapperClassName=\"wide-border\";";
	echo "</script>";
	?>
</head>
<body>
<?php  echo html_entity_decode($outputhtml); ?>
</body>
</html>
