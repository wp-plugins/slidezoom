<?php

function Debug_Collector($err_message) {
  @$GLOBALS['Debug_Collector'] .= $err_message . "<br/>";
}

function FuncLoadGD()
{ return extension_loaded('gd'); }

function PrintScriptStyle()
{
echo "<script type='text/javascript' src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.4.1.1.alpha.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.css' />";
echo "<script type=\"text/javascript\">";
echo "hs.graphicsDir = '".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/graphics/';";
echo	"hs.wrapperClassName = 'wide-border';";
echo "</script>";
}

function AddMediaMnu() 
{ add_submenu_page('upload.php','SlideZoom','SlideZoom', 'edit_posts' , 'slidezoom/slidezoom.php' ,'FuncShowMain'); }

function AddOptionMnu() 
{ add_options_page('SlideZoom','SlideZoom', 'manage_options' , 'slidezoom/slidezoom-options.php' 	); }

function FuncGetOptions() {
  //if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['sz_main_form'] || $_POST['sz_option_form'] ) {
  	$Ary = get_option('slidezoom_options');
    return $Ary;
  //}
}

function FuncUpdateOptions() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  $_POST['sz_option_form'] ) {
    
    $IsResize = (@$_POST['chkIsResize']) ? 1 : 0;
    $MaxWidth = @intval($_POST['txtMaxWidth']);
    $MaxHeight = @intval($_POST['txtMaxHeight']);
    $ThumbWidth = @intval($_POST['txtThumbWidth']);
    $ThumbHeight = @intval($_POST['txtThumbHeight']);
    $IsDebug = (@$_POST['chkIsDebug']) ? 1 : 0;
    $Number_of_uploads = @intval($_POST['txtUploadCount']);
  
  	//Ensure bigger then 1
    $MaxWidth = ($MaxWidth < 1) ? 1 : $MaxWidth;
    $MaxHeight = ($MaxHeight < 1) ? 1 : $MaxHeight;
    $ThumbWidth = ($ThumbWidth < 1) ? 1 : $ThumbWidth;
    $ThumbHeight = ($ThumbHeight < 1) ? 1 : $ThumbHeight;
	
    //At least 2 because of PHP's $_FILES structure
    $Number_of_uploads = ($Number_of_uploads < 2) ? 2 : $Number_of_uploads;

    // save options
    Debug_Collector("Saving options ...");
    update_option('slidezoom_options', 
      							array( 'IsResize' => $IsResize, 
              							    'MaxWidth' => $MaxWidth, 
              							    'MaxHeight' => $MaxHeight, 
              							    'ThumbWidth' => $ThumbWidth,
              							    'ThumbHeight' => $ThumbHeight,
              							    'IsDebug' => $IsDebug,
              							    'Number_of_uploads' => $Number_of_uploads
  										 )
								);
	// save options finish
	Debug_Collector("Save options success ...");
  }
}

function PrintHeader() {
print <<<HEAD
<style type="text/css">
    .sz hr { text-align: left; width: 90%; }
    .sz .selectit { margin-bottom: 2px; }
    .sz_max { width: 30px; }
    .sz_submit { margin: 10px 6px; }
    .sz_box { border: 1px solid #999; background-color: #f6f6f6; margin: 6px;  padding: 6px; color: #333; float: left; clear: both; }
    .sz_box h3 { margin-top: 0px; font-size: 100%; font-weight: bold; }  
</style>
<script type="text/javascript">
	function FuncAddUploadControl() {
	var ctl = document.getElementById('lblUploadID');
	var container = document.getElementById('sz_ctlcontainer');
	if (!ctl || !container) alert('Debug Message : No Controls.');
	var newNode = ctl.cloneNode(true);  
	container.appendChild(newNode);
	}
</script>
HEAD;
}
?>