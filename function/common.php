<?php
function init_jquery() {
    wp_enqueue_script('jquery');
}

function Debug_Collector($err_message) {
  @$GLOBALS['Debug_Collector'] .= $err_message . "<br/>";
}

function FuncLoadGD()
{ 
	$r="";
	if (extension_loaded('gd')) { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">Yes</span>"; }
	else { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #990000\">No</span>"; }
	return $r;
}

function FuncLoadZip()
{ 
	$r="";
	if (extension_loaded('zip')) { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">Yes</span>"; }
	else { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #990000\">No</span>"; }
	return $r;
}

function checktmpfolder()
{
	$r="";
	$path = get_home_path().'/wp-content/plugins/slidezoom/tmp/';
        
	if (is_dir($path) && is_writable($path) ) 
	{ 
		$r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">Yes</span>"; 
	}
	else { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #990000\">Please create a folder name (tmp) in SlideZoom directory,and CHMOD it to 777 (writable). </span>"; }
	return $r;
}

function createfolder($path)
{
        mkdir($path, 0755, true);
}
function checkphpver()
{
	$r="";
	if (!version_compare(phpversion(), "4.1.0", "<")) { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">Yes</span>"; }
	else { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #990000\">(use PHP 4.1.0 or later)</span>"; }
	return $r;
}

function getMaxuploadsize()
{
	$upload_max_filesize = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">".ini_get('upload_max_filesize')."</span>";
	return $upload_max_filesize;
}

function my_handle_upload($tmp_name, $name,$location)
{
	$wud = wp_upload_dir();
	$cleanname = sanitize_file_name($name);
	
	rename($tmp_name, $location.'/'.$cleanname);
					$file = array(
      		   						'name' => $cleanname,
								'url' => $wud['url'].'/'.$cleanname											 
      		   					  );
	return $file;
}

function PrintMainHead() {
		echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/js/jquery.MultiFile.pack.js'></script>\r\n";
                echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/js/slidezoom.main.js'></script>\r\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/css/jquery.MultiFile.css' />\r\n";
                echo "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/css/slidezoom.css' />\r\n";
}

function PrintScriptStyle()
{
	echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.packed.js'></script>\r\n";
	echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/easing_equations.js'></script>\r\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.css' />\r\n";
	
	echo "<script type=\"text/javascript\">\r\n";
	echo "hs.graphicsDir= '".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/graphics/';\r\n";
	echo "hs.showCredits = false;\r\n";
	echo "hs.wrapperClassName=\"wide-border\";\r\n";
	echo "hs.outlineWhileAnimating = false;\r\n";
	$AryOptions = FuncGetOptions();
	foreach ($AryOptions as $name => $option) { $$name = $option; }
	echo "hs.align = '".$hsAlign."';\r\n";
	echo "hs.easing  = '".$easeOpen."';\r\n";
	echo "hs.easingClose  = '".$easeClose."';\r\n";
	echo "hs.marginLeft   = ".$hsmarginLeft.";\r\n";
	echo "hs.marginTop  = ".$hsmarginTop.";\r\n";
	echo "hs.marginRight  = ".$hsmarginRight.";\r\n";
	echo "hs.marginBottom  = ".$hsmarginBottom.";\r\n";
	echo "hs.expandDuration  = ".$expandDuration.";\r\n";
	echo "hs.restoreDuration  = ".$restoreDuration.";\r\n";
	echo "hs.fadeInOut   = '".$Fade."';\r\n";
	echo "</script>";
}

function AddMediaMnu() 
{ add_submenu_page('upload.php','SlideZoom','SlideZoom', 'edit_posts' , 'slidezoom/slidezoom.php' ,'FuncShowMain'); }

function AddOptionMnu() 
{ add_options_page('SlideZoom','SlideZoom', 'manage_options' , 'slidezoom/slidezoom-options.php'); }

function FuncGetOptions() {
  	$Ary = get_option('slidezoom_options');
    return $Ary;
}

function FuncUpdateOptions() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  $_POST['sz_option_form'] ) {
    $IsDebug = (@$_POST['chkIsDebug']) ? 1 : 0;
    $IsResize = (@$_POST['chkIsResize']) ? 1 : 0;
    $MaxWidth = @intval($_POST['txtMaxWidth']);
    $MaxHeight = @intval($_POST['txtMaxHeight']);
    $ThumbMode = $_POST['radThumbMode'];
    $ThumbWidth = @intval($_POST['txtThumbWidth']);
    $ThumbHeight = @intval($_POST['txtThumbHeight']);
    $Number_of_uploads = @intval($_POST['txtUploadCount']);
    $LinkMode = $_POST['radLinkMode'];

    $MaxWidth = ($MaxWidth < 10) ? 10 : $MaxWidth;
    $MaxHeight = ($MaxHeight < 10) ? 10 : $MaxHeight;
    $ThumbWidth = ($ThumbWidth < 10) ? 10 : $ThumbWidth;
    $ThumbHeight = ($ThumbHeight < 10) ? 10 : $ThumbHeight;
	
    $Number_of_uploads = ($Number_of_uploads < 2) ? 2 : $Number_of_uploads;
	
	$hsAlign = $_POST['radhsAlign'];
	$hsmarginLeft = $_POST['txthsmarginLeft'];
	$hsmarginTop = $_POST['txthsmarginTop'];
	$hsmarginRight = $_POST['txthsmarginRight'];
	$hsmarginBottom = $_POST['txthsmarginBottom'];
	$easeOpen = $_POST['ddlhseo'];
	$easeClose = $_POST['ddlhsec'];
	$expandDuration = $_POST['txtexpandDuration'];
	$restoreDuration = $_POST['txtrestoreDuration'];
	$Fade  = $_POST['ddlhsfade'];

    Debug_Collector("Saving options ...");
    update_option('slidezoom_options', 
      							array(  'IsDebug' => $IsDebug,
								    'IsResize' => $IsResize,
              							    'MaxWidth' => $MaxWidth, 
              							    'MaxHeight' => $MaxHeight, 
              							    'ThumbMode' => $ThumbMode, 
              							    'ThumbWidth' => $ThumbWidth,
              							    'ThumbHeight' => $ThumbHeight,
              							    'Number_of_uploads' => $Number_of_uploads,
											'LinkMode' => $LinkMode,
											'hsAlign' => $hsAlign,
											'hsmarginLeft' => ($hsmarginLeft > 0 ? $hsmarginLeft : 1),
											'hsmarginTop' => ($hsmarginTop > 0 ? $hsmarginTop : 1),
											'hsmarginRight' => ($hsmarginRight > 0 ? $hsmarginRight : 1),
											'hsmarginBottom' => ($hsmarginBottom > 0 ? $hsmarginBottom : 1),
											'easeOpen' => $easeOpen,
											'easeClose' => $easeClose,
											'expandDuration' => ($expandDuration > 200 ? $expandDuration : 200),
											'restoreDuration' => ($restoreDuration > 200 ? $restoreDuration : 200),
											'Fade' => $Fade
  										 )
								);

	Debug_Collector("Save options success ...");
  }
}

function getFilename($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}

function getFileExt($filename)
{
    return end(explode(".", $filename));
}




?>