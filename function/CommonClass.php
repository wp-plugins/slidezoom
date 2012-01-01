<?php
function sz_LoadjQuery() {
    wp_enqueue_script('jquery');
}

function sz_LoadGD()
{ 
	$r="";
	if (extension_loaded('gd')) { $r = "<span class=\"text_green_bold\">Yes</span>"; }
	else { $r = "<span class=\"text_red_bold\">No</span>"; }
	return $r;
}

function sz_LoadZip()
{ 
	$r="";
	if (extension_loaded('zip')) { $r = "<span class=\"text_green_bold\">Yes</span>"; }
	else { $r = "<span class=\"text_red_bold\">No</span>"; }
	return $r;
}

function sz_CheckTmpFolder()
{
	$r="";
	$path = get_home_path().'/wp-content/plugins/slidezoom/tmp/';
        
	if (is_dir($path) && is_writable($path) ) 
	{ 
		$r = "<span class=\"text_green_bold\">Yes</span>"; 
	}
	else { $r = "<span class=\"text_red_bold\">Please create a folder name [tmp] in SlideZoom directory,and CHMOD it to 777 (writable). </span>"; }
	return $r;
}

function sz_createfolder($path)
{
        mkdir($path, 0755, true);
}

function sz_CheckPHP()
{
	$r="";
	if (!version_compare(phpversion(), "4.1.0", "<")) { $r = "<span class=\"text_green_bold\">Yes</span>"; }
	else { $r = "<span class=\"text_red_bold\">(use PHP 4.1.0 or later)</span>"; }
	return $r;
}

function sz_getMaxUploadSize()
{
	$upload_max_filesize = "<span class=\"text_green_bold\">".ini_get('upload_max_filesize')."</span>";
	return $upload_max_filesize;
}

function sz_my_handle_upload($tmp_name, $name, $location)
{
	$wud = wp_upload_dir();
	$cleanname = sanitize_file_name($name);
	
	rename($tmp_name, $location.'/'.$cleanname);
	$file = array('name' => $cleanname,
						   'url' => $wud['url'].'/'.$cleanname											 
      		   			 );
	return $file;
}

function sz_PrintMainHead() {
		$html .= "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/js/jquery.MultiFile.js'></script>\r\n";
        $html .= "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/js/slidezoom.main.js'></script>\r\n";
		$html .= "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/css/jquery.MultiFile.css' />\r\n";
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/css/slidezoom.css' />\r\n";
        $html .= "<script type=\"text/javascript\">\r\n";
        $html .= "jQuery(document).ready(function() { \r\n";
        $html .= "jQuery('#FileUploader').MultiFile({ STRING: { remove:'<img src=\"".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/img/delete.gif\" border=\"0\"  /><b>remove</b>' } }); \r\n";
        $html .= "});\r\n";
        $html .= "</script>";
        echo $html;
}

function sz_PrintScriptStyle()
{
	$html .= "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide-with-gallery.packed.js'></script>\r\n";
	$html .= "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/easing_equations.js'></script>\r\n";
	$html .= "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.css' />\r\n";
	
	$html .= "<script type=\"text/javascript\">\r\n";
	$html .= "hs.graphicsDir= '".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/graphics/';\r\n";
	$html .= "hs.showCredits = false;\r\n";
	$html .= "hs.wrapperClassName=\"wide-border\";\r\n";
	$html .= "hs.outlineType = \"rounded-white\";\r\n";
	$html .= "hs.outlineWhileAnimating = false;\r\n";
	$html .= "hs.transitions = ['expand', 'crossfade']; \r\n";
	
	$AryOptions = sz_FuncGetOptions();
	foreach ($AryOptions as $name => $option) { $$name = $option; }
	$html .= "hs.align = '".$hsAlign."';\r\n";
	$html .= "hs.easing  = '".$easeOpen."';\r\n";
	$html .= "hs.easingClose  = '".$easeClose."';\r\n";
	$html .= "hs.marginLeft   = ".$hsmarginLeft.";\r\n";
	$html .= "hs.marginTop  = ".$hsmarginTop.";\r\n";
	$html .= "hs.marginRight  = ".$hsmarginRight.";\r\n";
	$html .= "hs.marginBottom  = ".$hsmarginBottom.";\r\n";
	$html .= "hs.expandDuration  = ".$expandDuration.";\r\n";
	$html .= "hs.restoreDuration  = ".$restoreDuration.";\r\n";
	$html .= "hs.fadeInOut   = '".$Fade."';\r\n";
	$html .= "</script>";
	
	echo $html;
}

function sz_AddMediaMenu() 
{ add_submenu_page('upload.php','SlideZoom','SlideZoom', 'edit_posts' , 'slidezoom/slidezoom.php' ,'FuncShowMain'); }

function sz_AddOptionMenu() 
{ add_options_page('SlideZoom','SlideZoom', 'manage_options' , 'slidezoom/slidezoom-options.php'); }

function sz_FuncGetOptions() {
  	$Ary = get_option('slidezoom_options');
    return $Ary;
}

function sz_FuncUpdateOptions() {
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
	
    //At least 2 because of PHP's $_FILES structure
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
    // save options
    sz_Log("Saving options ...");
    update_option('slidezoom_options', 
      							array( 'IsDebug' => $IsDebug,
								    		'IsResize' => $IsResize,
              							    'MaxWidth' => $MaxWidth, 
              							    'MaxHeight' => $MaxHeight, 
              							    'ThumbMode' => $ThumbMode, 
              							    'ThumbWidth' => $ThumbWidth,
              							    'ThumbHeight' => $ThumbHeight,
              							    'Number_of_uploads' => $Number_of_uploads,
											'LinkMode' => $LinkMode,
											'hsAlign' => $hsAlign,
											'hsmarginLeft' => ($hsmarginLeft > 0 ? $hsmarginLeft : 15),
											'hsmarginTop' => ($hsmarginTop > 0 ? $hsmarginTop : 15),
											'hsmarginRight' => ($hsmarginRight > 0 ? $hsmarginRight : 15),
											'hsmarginBottom' => ($hsmarginBottom > 0 ? $hsmarginBottom : 15),
											'easeOpen' => $easeOpen,
											'easeClose' => $easeClose,
											'expandDuration' => ($expandDuration > 200 ? $expandDuration : 500),
											'restoreDuration' => ($restoreDuration > 200 ? $restoreDuration : 500),
											'Fade' => $Fade
  										 )
								);
	// save options finish
	sz_Log("Save options success ...");
  }
}

function sz_getFilename($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}

function sz_getFileExt($filename)
{
    return end(explode(".", $filename));
}

function sz_Log($err_message) {
  @$GLOBALS['Debug_Collector'] .= $err_message . "<br/>";
}

function sz_PrintPath()
{
	echo get_home_path().'<br/>';
	echo WP_ADMIN_DIR.'<br/>';
	echo plugin_dir_path( __FILE__ ).'<br/>'; //Relative to current files.
	echo plugin_dir_url( $file ).'<br/>';
	echo ABSPATH.'<br/>';
	echo WP_PLUGIN_URL.'<br/>';
	echo WP_PLUGIN_DIR.'<br/>';
	echo plugin_basename(__FILE__).'<br/>'; //Relative to current files.
}
?>