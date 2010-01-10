<?php
/*
Plugin Name: SlideZoom
Plugin URI: http://blog.miawork.com/?page_id=1317
Description: SlideZoom is a lightweight plugin allow bulk upload images or zip upload to generate a HighSlide JS gallery. <br />
Output as HTML code / BBCode etc...  And offering embed the gallery by simple copy and paste to posts or pages.
Author: TatMing
Version: 1.3.2
Author URI: http://blog.miawork.com/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Please note  :
HighSlide JS is not part of this license and is available under a Creative Commons Attribution-NonCommercial 2.5 License.
HighSlide JS is Copyright by Vevstein Web (highslide.com) , for commercial use please look at the HighSlide JS homepage : http://highslide.com/ .
*/

session_start(); 
require_once(ABSPATH . '/wp-admin/admin-functions.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/common.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/libimage.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/libzip.php');

add_action('admin_head','PrintMainHead');
add_action('admin_menu','AddMediaMnu'); 
add_action('admin_menu','AddOptionMnu'); 

add_action('wp_head','PrintScriptStyle');
add_action('init', 'init_jquery');
add_action('init','ProcessForm');

function ProcessForm() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['sz_main_form']) { 
  /*Declare Section*/
        $AryOptions = FuncGetOptions();
        $AryFiles = array();
        $AryThumbs = array();
        $AryErrors = array();
	$wud = wp_upload_dir();
	$upload_mode = $_POST["sz_mode"];

	switch ($upload_mode)
	{
	
		case "zip":
 			$zip = new clsZip();
			$ziparray = $zip -> unzip($_FILES['ZipUpload']['tmp_name'],  get_home_path().'/wp-content/plugins/slidezoom/tmp/' );
			$FileCount = sizeof($ziparray);
			for ($i=0; $i < $FileCount; $i++) {
				Debug_Collector("[Zip] File -> (" . $ziparray[$i]['name'] . " found).");
		
				if (($imginfo = @getimagesize($ziparray[$i]['tmp_name'])) === false ) {
						Debug_Collector("Image format not correct.");
						continue;
				}
			
				$file = array(
                                                       'name' => $ziparray[$i]['name'],
                                                       'type' => image_type_to_mime_type($imginfo[2]),
      		  				       'tmp_name' => $ziparray[$i]['tmp_name'],
                                                       'error' => $ziparray[$i]['error'],
                                                       'size' => $ziparray[$i]['size']
						 );
															 
				list($w_orig,$h_orig,$type,$html) = $imginfo;
				if ($AryOptions['IsResize'] && ($w_orig > $AryOptions['MaxWidth'] && $h_orig > $AryOptions['MaxHeight']) ) {
					Debug_Collector("Starting Resize ...");
					$image = new clsImage();
					$image -> load($file['tmp_name']);
					$image -> resizeToWidth($AryOptions['MaxWidth']);
					$image -> save($file['tmp_name']);
					$result = true;
				} else { 
					
					$result = true;  Debug_Collector("Skipped resize.");		
			    }
      
				if ($result) { 
					$AryFiles[] = my_handle_upload($file['tmp_name'],$file['name'],$wud['path']);  Debug_Collector("Upload images success.");
				} else {
					$AryErrors[] = $file['name'];   Debug_Collector("Upload Image Error.");
				}

				$result = false;

				
				if ( @intval($AryOptions['ThumbMode']) === 2 ) {
					Debug_Collector("Creating Thumbs ...");
					$uploaded_filename = getFilename(end(explode("/" , $AryFiles[$i]['url']))).'.'.getFileExt(end(explode("/" , $AryFiles[$i]['url'])));
					$thumbname = getFilename(end(explode("/" , $AryFiles[$i]['url']))).'-t.'.getFileExt(end(explode("/" , $AryFiles[$i]['url'])));
					$wud = wp_upload_dir();
					$image = new clsImage();
					$image -> load($wud['path']."/".sanitize_file_name($uploaded_filename));
					$image -> resizeToWidth($AryOptions['ThumbWidth']);
					$image -> save($wud['path']."/".$thumbname);
					$result = true;
				} else { 
			     	$thumbname = end(explode("/" , $AryFiles[$i]['url']));
					$result = true;  Debug_Collector("Skipped create thumbs.");
				}

				if ($result) { // create thumb success
					$AryThumbs[] = $wud['url']."/".$thumbname;  Debug_Collector("Create thumb success.");
				} else {
					$AryErrors[] = end(explode("/" , $AryFiles[$i]['url']));  Debug_Collector("Create thumb Error.");
				}
				$GLOBALS['AryFiles'] = $AryFiles;
				$GLOBALS['AryThumbs'] = $AryThumbs;
				$GLOBALS['AryErrors'] = $AryErrors;

			}
		break;
		
		case "file":
			$FileCount = count($_FILES['FileUpload']['name']);
			for ($i=0; $i < $FileCount; $i++) {
				Debug_Collector("[File] File -> (" . $_FILES['FileUpload']['name'][$i] . ").");

				$file = array(
      		  						   'name' => &$_FILES['FileUpload']['name'][$i],
      		  						   'type' => &$_FILES['FileUpload']['type'][$i],
      		  						   'tmp_name' => &$_FILES['FileUpload']['tmp_name'][$i],
      		  						   'error' => &$_FILES['FileUpload']['error'][$i],
      		  						   'size' => &$_FILES['FileUpload']['size'][$i]
      		  						 );
				
				if (($imginfo = @getimagesize($file['tmp_name'])) === false ) {
						Debug_Collector("Image format not correct.");
						continue;
				}

				list($w_orig,$h_orig,$type,$html) = $imginfo;
				if ($AryOptions['IsResize'] && ($w_orig > $AryOptions['MaxWidth'] && $h_orig > $AryOptions['MaxHeight']) ) {
					Debug_Collector("Starting Resize ...");
					$image = new clsImage();
					$image -> load($file['tmp_name']);
					$image -> resizeToWidth($AryOptions['MaxWidth']);
					$image -> save($file['tmp_name']);
					$result = true;
				} else { 
				
					$thumbname = end(explode("/" , $AryFiles[$i]['url']));
					$result = true;  Debug_Collector("Skipped resize.");		
			    }
      
				if ($result) {
					$AryFiles[] = wp_handle_upload($file , array('action'=>'save') );  Debug_Collector("Upload images success.");
				} else {
					$AryErrors[] = $file['name'];   Debug_Collector("Upload Image Error.");
				}
	  
		
				$result = false;

				if ( @intval($AryOptions['ThumbMode']) === 2 ) {
					Debug_Collector("Creating Thumbs ...");
					$uploaded_filename = getFilename(end(explode("/" , $AryFiles[$i]['url']))).'.'.getFileExt(end(explode("/" , $AryFiles[$i]['url'])));
					$thumbname = getFilename(end(explode("/" , $AryFiles[$i]['url']))).'-t.'.getFileExt(end(explode("/" , $AryFiles[$i]['url'])));
					$wud = wp_upload_dir();
					$image = new clsImage();
					$image -> load($wud['path']."/".sanitize_file_name($uploaded_filename));
					$image -> resizeToWidth($AryOptions['ThumbWidth']);
					$image -> save($wud['path']."/".$thumbname);
					$result = true;
				} else { 
					$thumbname = end(explode("/" , $AryFiles[$i]['url']));
					$result = true;  Debug_Collector("Skipped create thumbs.");
				}

				if ($result) { 
					$AryThumbs[] = $wud['url']."/".$thumbname;  Debug_Collector("Create thumb success.");
				} else {
					$AryErrors[] = end(explode("/" , $AryFiles[$i]['url']));  Debug_Collector("Create thumb Error.");
				}
				$GLOBALS['AryFiles'] = $AryFiles;
				$GLOBALS['AryThumbs'] = $AryThumbs;
				$GLOBALS['AryErrors'] = $AryErrors;
				
			}
		break;
	  } 

	} 

} 

function FuncShowMain() {
  global $title;
  global $home;
  global $Debug_Collector;
  global $AryFiles;
  global $AryThumbs;
  global $AryErrors;
  

$AryOptions = FuncGetOptions();


	$wud = wp_upload_dir();
  if (false === $AryOptions) {
    $AryOptions = array(
	  'IsDebug' => 0,
      'IsResize' => 0,
      'MaxWidth' => 1000,
      'MaxHeight' => 1000,
	  'ThumbMode' => 1,
	  'ThumbWidth' => 160,
	  'ThumbHeight' => 160,
      'Number_of_uploads' => 10,
      'LinkMode' => 'absolute',
	  'hsAlign' => 'auto',
	  'hsmarginLeft' => 15,
	  'hsmarginTop' => 15,
	  'hsmarginRight' => 15,
	  'hsmarginBottom' => 15,
	  'easeOpen' => 'easeOutBack',
	  'easeClose' => 'easeInBack',
	  'expandDuration' => '500',
	  'restoreDuration' => '500',
	  'Fade' => 'true'
    );
  }
    
  foreach ($AryOptions as $name => $option) 
  				  { $$name = $option; }
  
 
  $good_results = (@is_array($AryFiles) && $AryFiles !== array());
  $bad_results = (@is_array($AryErrors) && $AryErrors !== array());  
  $display_results = $good_results || $bad_results;
  
  
  $raw = "";
  $img_html = "";
  $outputhtml = "";
  $bbcode = "";
  $i = 0;
if ($display_results) {

	  if ($good_results) {
	    				$img_html .= "<h3>Images were uploaded successfully.</h3>
	    											<p>To use them in a post, copy and paste the code below.</p>\n<div>";
	  
	  												  foreach ($AryFiles as $file)
													  {
	  										 					$img_html .= htmlentities(
	  										 					sprintf('<img src="%s" alt="%s"/>', 
	  										 					preg_replace('@^https?://.*?/@','/',$file['url']), 
	  										 					basename($file['url']) )
	  										 		 		  	) . "<br/>";
															
															switch ($AryOptions['ThumbMode'])
															{
																case "1":
	  										 					$outputhtml .= htmlentities(
	  										 					sprintf('<a href="%s" title="%s" class="highslide" onclick="return hs.expand(this)" target="_blank"><img src="%s" alt="%s" width="%s" height="%s" /></a>', 
	  										 					($LinkMode == 'relative' ? preg_replace('@^https?://.*?/@','/',$file['url']) : $file['url']), 
	  										 					basename($file['url']),
	  										 					($LinkMode == 'relative' ? preg_replace('@^https?://.*?/@','/',$file['url']) : $file['url']), 
	  										 					basename($file['url']), $ThumbWidth, $ThumbHeight )
	  										 		   			);
																break;
																case "2":
	  										 					$outputhtml .= htmlentities(
	  										 					sprintf('<a href="%s" title="%s" class="highslide" onclick="return hs.expand(this)" target="_blank"><img src="%s" alt="%s"  /></a>', 
	  										 					($LinkMode == 'relative' ? preg_replace('@^https?://.*?/@','/',$file['url']) : $file['url']), 
	  										 					basename($file['url']),
	  										 					($LinkMode == 'relative' ? preg_replace('@^https?://.*?/@','/',$AryThumbs[$i]) : $AryThumbs[$i]), 
	  										 					basename($AryThumbs[$i]) )
	  										 		   			);
																break;
															}
															
															
															$bbcode .= htmlentities(
	  										 					sprintf('[url=%s][img]%s[/img][/url]', 
																$file['url'], 
	  										 					$AryThumbs[$i] )
	  										 		 		  	);
																
															$raw .= $file['url'].chr(13);
															
															$i++;
													  }
													  
	  }
	
  if ($bad_results) {
	    					     $img_html .= "\n<h3>Errors occurred while resizing some images.</h3>\n<p>The following images had errors:</p>\n<ul>\n";
	  												  foreach ($AryErrors as $error) 
													  {
	  										 			$img_html .= "<li>$error</li>\n";
	  										 		  }
	    					     $img_html .= "</ul>\n";
	    					     if ($IsDebug == false)
	    					     { $img_html .= "<p>To get more information, please check debug options.</p>\n"; }  
	  						}
	  							$img_html = "<div class=\"sz_box\">\n$img_html\n</div>\n</div>\n";
  }

	
	$codebox = ($good_results ? "visible" : "hidden");
	$_SESSION["outputhtml"] = $outputhtml;

print <<<HEAD
<div class="wrap sz">
<div id="icon-upload" class="icon32"></div><h2>$title - Batch Images Upload!</h2>
HEAD;

echo "<code>System Info :</code>&nbsp;";
echo "PHP version : ".checkphpver().'&nbsp;&nbsp;';
echo "Zip : ".FuncLoadZip().'&nbsp;&nbsp;';
echo "GD : ".FuncLoadGD().'&nbsp;&nbsp;';
echo "Tmp folder : ".checktmpfolder().'&nbsp;&nbsp;';
echo "Max upload size : ".getMaxuploadsize().'<br />';
echo "<hr />"; 

print <<<FORM
  <form enctype="multipart/form-data" method="post">
    <div>
      <input type="hidden" id="sz_mode" name="sz_mode" value="file"/>
      <input type="hidden" name="action" value="save"/>
      <input type="hidden" name="sz_main_form" value="true"/>
      <input id="rad_file_mode" name="sz_toggle_mode" type="radio" value="file" onclick="toggle_mode(this.value)" checked="checked" />Files upload
      <input id="rad_zip_mode" name="sz_toggle_mode" type="radio" value="zip" onclick="toggle_mode(this.value)" />Zip upload
FORM;

		
print <<<FILE_INPUT
            <div id="sz_ctlcontainer">
   		<p>Only support *.jpg , *.png , *.gif format.</p>
   		<div id="FileUploaderDiv">
    		   <input id="FileUploader" type="file" name="FileUpload[]" size="80" class="multi" maxlength="50" accept="gif|jpg|png" />
                </div>
            </div>
            <div id="sz_file_button" style="padding-left:520px;">
                <input id="btnpost" type="submit" value="Upload" class="button-secondary action" style="width:80px" />
            </div>
FILE_INPUT;
	 
print <<<ZIP_DIV
      <div id="sz_zipcontainer" style="display:none;">
                <label id="lblZipUpload">Zip upload : <input type="file" name="ZipUpload" size="50" /></label>
                <input type="submit" value="Upload" class="button-secondary action"" />
      </div>
ZIP_DIV;
	 
print <<<FOOTER
    </div>
FOOTER;

print <<<MSG
	<!------Output------>
	<div id="output_html" Style="visibility: $codebox ;";>
		<h3>Upload Success ! </h3>
		<h5>Copy the HTML or BBCode below , and paste and your post or page.</h5>
MSG;
?>
	<input type="button" value="Preview" onclick="javascript:window.open('../wp-content/plugins/slidezoom/slidezoom-preview.php','SlideZoom Preview','height=480, width=680');" class="button-secondary action"/><br />
        <input id="radraw"  name="outputmode" type="radio" value="raw" onclick="ChangeOutput(this.value);" />Raw Url
         (Original images url) &nbsp; 
        <input id="radhtml" checked="checked" name="outputmode" type="radio" value="html" onclick="ChangeOutput(this.value);" />HTML
         (For WordPress, eBay, Web, MySpace etc...) &nbsp; 
        <input id="radbbcode" name="outputmode" type="radio" value="bbcode" onclick="ChangeOutput(this.value);" />BBCode
         (For Forum) &nbsp; 
	<div id="rawbox" style="display:none"><textarea id="txtoutputraw"  cols="100" rows="14" onclick="this.select();"><?=$raw ?></textarea></div>
	<div id="htmlbox" style="display:display"><textarea id="txtoutputhtml"  cols="100" rows="14" onclick="this.select();"><?=$outputhtml ?></textarea></div>
	<div id="bbcodebox" style="display:none"><textarea id="txtoutputbbcode"  cols="100" rows="14" onclick="this.select();"><?=$bbcode ?></textarea></div>
		
<?php
print <<<MSG
	</div>
MSG;
?>

<?php
print <<<OUT
  </form>
  </div>
</div>
OUT;
}
?>
