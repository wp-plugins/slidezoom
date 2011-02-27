<?php
 if (!isset($_SESSION)) { session_start(); }
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
	echo "<script type=\"text/javascript\" src=\"highslide/highslide-with-gallery.packed.js\"></script>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"highslide/highslide.css\" />";
	echo "<script type=\"text/javascript\">";
	echo "hs.graphicsDir=\"highslide/graphics/\";";
	echo "hs.showCredits = false;";
	echo "hs.wrapperClassName=\"wide-border\";";
	echo "hs.outlineWhileAnimating = false;";
	echo "hs.addSlideshow({ \r\n";
	echo "	interval: 5000, repeat: false, useControls: true, fixedControls: true, \r\n";
	echo " overlayOptions: { opacity: .6, position: 'top center',	hideOnMouseOut: true } });  \r\n";
	echo "hs.transitions = ['expand', 'crossfade']; \r\n";
	echo "</script>";
	?>
</head>
<body>
<?php  echo html_entity_decode($outputhtml); ?>
</body>
</html>
