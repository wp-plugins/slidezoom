<?php

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
	if (is_dir(get_home_path().'/wp-content/plugins/slidezoom/tmp/')) { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #009933\">Yes</span>"; }
	else { $r = "<span style=\"font-size: small; font-weight:bold; font-family: Arial; color: #990000\">Please create a folder name (tmp) in SlideZoom directory.</span>"; }
	return $r;
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

function PrintHeader() {
print <<<SCRIPT
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
		
        function ChangeOutput(mode) {
            switch (mode) {
                case 'raw':
                    document.getElementById('rawbox').setAttribute('style', 'display:display');
                    document.getElementById('htmlbox').setAttribute('style', 'display:none');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:none');
                    break;
                case 'html':
                    document.getElementById('rawbox').setAttribute('style', 'display:none');
                    document.getElementById('htmlbox').setAttribute('style', 'display:display');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:none');
                    break;
                case 'bbcode':
                    document.getElementById('rawbox').setAttribute('style', 'display:none');
                    document.getElementById('htmlbox').setAttribute('style', 'display:none');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:display');
                    break;
            }
        }
		
        function toggle_mode(e) {
            switch (e) {
                case 'file':
                    document.getElementById('sz_mode').value = 'file';
                    document.getElementById('sz_file_button').setAttribute('style', 'display:display');
                    document.getElementById('sz_ctlcontainer').setAttribute('style', 'display:display');
                    document.getElementById('sz_zipcontainer').setAttribute('style', 'display:none');
                    break;
                case 'zip':
                    document.getElementById('sz_mode').value = 'zip';
                    document.getElementById('sz_file_button').setAttribute('style', 'display:none');
                    document.getElementById('sz_ctlcontainer').setAttribute('style', 'display:none');
                    document.getElementById('sz_zipcontainer').setAttribute('style', 'display:display');
                    break;
            }
        }
		
    </script>
SCRIPT;
}

function PrintScriptStyle()
{
echo "<script type=\"text/javascript\" src='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.js'></script>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href='".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/highslide.css' />";
echo "<script type=\"text/javascript\">";
echo "hs.graphicsDir= '".get_bloginfo('wpurl')."/wp-content/plugins/slidezoom/highslide/graphics/';";
echo "hs.showCredits = false;";
echo "hs.wrapperClassName=\"wide-border\";";
echo "</script>";
}

function AddMediaMnu() 
{ add_submenu_page('upload.php','SlideZoom','SlideZoom', 'edit_posts' , 'slidezoom/slidezoom.php' ,'FuncShowMain'); }

function AddOptionMnu() 
{ add_options_page('SlideZoom','SlideZoom', 'manage_options' , 'slidezoom/slidezoom-options.php' 	); }

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
											'LinkMode' => $LinkMode
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

function cleanfilename($filename)
{
	$filename = strtolower($filename);
	$filename = str_replace('!','',$filename);
	$filename = str_replace('@','',$filename);
	$filename = str_replace('#','',$filename);
	$filename = str_replace('$','',$filename);
	$filename = str_replace('%','',$filename);
	$filename = str_replace('^','',$filename);
	$filename = str_replace('&','',$filename);
	$filename = str_replace('*','',$filename);
	$filename = str_replace('(','',$filename);
	$filename = str_replace(')','',$filename);
	$filename = str_replace('-','_',$filename);
	$filename = str_replace(' ','_',$filename);
	$filename = str_replace('[','',$filename);
	$filename = str_replace(']','',$filename);
	$filename = str_replace('+','',$filename);
	$filename = str_replace('=','',$filename);
	$filename = str_replace(';','',$filename);
	$filename = str_replace(',','',$filename);
	return $filename;
}


?>