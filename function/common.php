<?php

function Debug_Collector($err_message) {
  @$GLOBALS['Debug_Collector'] .= $err_message . "<br/>";
}

function FuncLoadGD()
{ 
	/*$r="";
	if (extension_loaded('gd')) { $r = "<span style=\"font-size: medium; font-family: Arial; color: #009933\">Pass</span>"; }
	else { $r = "<span style=\"font-size: medium; font-family: Arial; color: #990000\">Fail</span>"; }*/
	return extension_loaded('gd');
}

function FuncLoadZip()
{ 
	/*$r="";
	if (extension_loaded('zip')) { $r = "<span style=\"font-size: medium; font-family: Arial; color: #009933\">Pass</span>"; }
	else { $r = "<span style=\"font-size: medium; font-family: Arial; color: #990000\">Fail</span>"; }*/
	return extension_loaded('zip');
}

function PrintHeader() {
print <<<HEAD
<style type="text/css">
    .sz hr { text-align: left; width: 90%; }
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

    <script type="text/javascript">
        function ChangeOutput(mode) {
            switch (mode) {
                case 'html':
                    document.getElementById('htmlbox').setAttribute('style', '');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:none');
                    break;
                case 'bbcode':
                    document.getElementById('htmlbox').setAttribute('style', 'display:none');
                    document.getElementById('bbcodebox').setAttribute('style', '');
                    break;
            }
        }
    </script>
	
HEAD;
}

function PrintScriptStyle()
{
echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.js'></script>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.css' />";
echo "<script type=\"text/javascript\">";
echo "hs.graphicsDir= '".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/graphics/';";
echo	"hs.wrapperClassName=\"wide-border\";";
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
    $IsDebug = (@$_POST['chkIsDebug']) ? 1 : 0;
    $IsResize = (@$_POST['chkIsResize']) ? 1 : 0;
    $MaxWidth = @intval($_POST['txtMaxWidth']);
    $MaxHeight = @intval($_POST['txtMaxHeight']);
	$ThumbMode = $_POST['radThumbMode'];
    $ThumbWidth = @intval($_POST['txtThumbWidth']);
    $ThumbHeight = @intval($_POST['txtThumbHeight']);
    $Number_of_uploads = @intval($_POST['txtUploadCount']);
  	//$ThumbDir = $_POST['ThumbDir'];
	//$ImgDir = $_POST['ImgDir'];
	
  	//Ensure size are bigger then 10
    $MaxWidth = ($MaxWidth < 10) ? 10 : $MaxWidth;
    $MaxHeight = ($MaxHeight < 10) ? 10 : $MaxHeight;
    $ThumbWidth = ($ThumbWidth < 10) ? 10 : $ThumbWidth;
    $ThumbHeight = ($ThumbHeight < 10) ? 10 : $ThumbHeight;
	
    //At least 2 because of PHP's $_FILES structure
    $Number_of_uploads = ($Number_of_uploads < 2) ? 2 : $Number_of_uploads;

	//Check Dir exist
	/*$wud = wp_upload_dir();
	if (!file_exists(bloginfo("directory")."/".$ThumbDir)) 
	{ 	
		if (get_option('uploads_use_yearmonth_folders') == "1" ) { $ThumbDir = $wud['path']; } 
		else { $ThumbDir = $wud['basedir']."/thumbs";  }
	}
	
	if (!file_exists(bloginfo("directory")."/".$ImgDir)) 
	{ 
		if (get_option('uploads_use_yearmonth_folders') == "1" ) { $ThumbDir = $wud['path']; } 
		else { $ThumbDir = $wud['basedir'];  }
	}*/
	
    // save options
    Debug_Collector("Saving options ...");
    update_option('slidezoom_options', 
      							array(  'IsDebug' => $IsDebug,
											'IsResize' => $IsResize, 
              							    'MaxWidth' => $MaxWidth, 
              							    'MaxHeight' => $MaxHeight, 
              							    'ThumbMode' => $ThumbMode, 
              							    'ThumbWidth' => $ThumbWidth,
              							    'ThumbHeight' => $ThumbHeight,
              							    'Number_of_uploads' => $Number_of_uploads
  										 )
								);
	// save options finish
	Debug_Collector("Save options success ...");
  }
}

function cleanfilename($filename)
{
	$filename = strtolower($filename);
	$filename = str_replace(' ','-',$filename);
	$filename = str_replace('[','',$filename);
	$filename = str_replace(']','',$filename);
	return $filename;
}

?>