<?php
/*
Plugin Name: SlideZoom
Plugin URI: http://blog.miawork.com/?page_id=1317
Description: SlideZoom is a lightweight plugin allow bulk upload images and generate a HighSlide JS gallery. <br />
Output as HTML code , and offering embed the gallery by simple copy and paste to posts or pages.
Author: TatMiNG
Version: 1.0
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
HighSlide JS is Copyright by highslide.com , for commercial use please look at the HighSlide JS homepage : http://highslide.com/ .
*/
session_start(); 
require_once(ABSPATH . '/wp-admin/admin-functions.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/common.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/image.php');

add_action('admin_head','PrintHeader'); 
add_action('admin_menu','AddMediaMnu'); 
add_action('admin_menu','AddOptionMnu'); 

add_action('wp_print_scripts','PrintScriptStyle');
add_action('init','FuncGoMain');

function FuncGoMain() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['sz_main_form']) { 
  	    
    $AryOptions = FuncGetOptions();

    $AryFiles = array();  
    $AryErrors = array(); 

    for ($i=0; $i < count($_FILES['FileUpload']['name']); $i++) {
      Debug_Collector("New file (" . $_FILES['FileUpload']['name'][$i] . ").");

      $file = array(
        'name' 		=> &$_FILES['FileUpload']['name'][$i],
        'type' 		=> &$_FILES['FileUpload']['type'][$i],
        'tmp_name' 	=> &$_FILES['FileUpload']['tmp_name'][$i],
        'error'		=> &$_FILES['FileUpload']['error'][$i],
        'size' 		=> &$_FILES['FileUpload']['size'][$i]
      );

      if (($imginfo = @getimagesize($file['tmp_name'])) === FALSE) {
        Debug_Collector("Image format not correct.");
        continue;
      }

      // resize image (if need)
      list($w_orig,$h_orig,$type,$html) = $imginfo;
      if ($AryOptions['IsResize'] && 
          !($w_orig <= $AryOptions['MaxWidth'] && $h_orig <= $AryOptions['MaxHeight'])) {
        Debug_Collector("Start Resize ...");

        // calculate width & height
        $r_orig = $w_orig / $h_orig;
        if ($AryOptions['MaxWidth']/$AryOptions['MaxHeight'] > $r_orig) {
          $w_new = $AryOptions['MaxHeight'] * $r_orig;
          $h_new = $AryOptions['MaxHeight'];
        } else {
          $w_new = $AryOptions['MaxWidth'];
          $h_new = $AryOptions['MaxWidth'] / $r_orig;
        }
        
	      // Use GD to Resize
	      Debug_Collector("Resizing ...");
	      $result = resize_image($file['tmp_name'], $type, $w_orig, $h_orig, $w_new, $h_new);     

      } else { //No resize = $AryOptions['IsResize'] = false
        Debug_Collector("No need resize.");
        $result = true;
      }
      
      if ($result) { // success
	      $AryFiles[] = wp_handle_upload( $file, array('action'=>'save') );
	      Debug_Collector("Upload Images success.");
      } else {
      	$AryErrors[] = $file['name'];
      	Debug_Collector("Upload Image Error.");
      }
    }    
    $GLOBALS['AryFiles'] = $AryFiles;
    $GLOBALS['AryErrors'] = $AryErrors;
  }
}

function FuncShowMain() {
  global $title;
  global $home;
  global $Debug_Collector;
  global $AryFiles;
  global $AryErrors;
  
 //Get Options
$AryOption = FuncGetOptions();

//ReDefine Options Values if the Options not exist in Database
  if (false === $AryOption) {
    $AryOption = array(
      'IsResize' => 0,
      'MaxWidth' => 800,
      'MaxHeight' => 800,
	  'ThumbWidth' => 120,
	  'ThumbHeight' => 120,
      'IsDebug' => 0,
      'Number_of_uploads' => 10
    );
  }
  
  foreach ($AryOption as $name => $option) 
  				  { $$name = $option; }
  
  // Debugging info
  $good_results = (@is_array($AryFiles) && $AryFiles !== array());
  $bad_results = (@is_array($AryErrors) && $AryErrors !== array());  
  $display_results = $good_results || $bad_results;
  
  if ($display_results) {
    //$debug = ($do_debug) ? "<div class=\"sz_box\"><h3>Debugging info:</h3>\n" . $Debug_Collector . "<pre>".@print_r($Aryfiles,true)."</pre></div>" : "";
  }
  
  // Print result for uploaded images and errors
  $img_html = "";
  $outputhtml = "";
  
if ($display_results) {
	//===
	  if ($good_results) {
	    				$img_html .= "<h3>Images were uploaded successfully.</h3>
	    											<p>To use them in a post, copy and paste the code below.</p>\n<div>";
	  
	  												  foreach ($AryFiles as $file)
													  {
	  										 			$img_html .= htmlentities(
	  										 			sprintf('<img src="%s" alt="%s"/>', 
	  										 			preg_replace('@^https?://.*?/@','/',$file['url']), 
	  										 			basename($file['url'])
																    )
	  										 		   	) . "<br/>";
														
													  $outputhtml .= htmlentities(
	  										 			sprintf('<a href="%s" title="%s" class="highslide" onclick="return hs.expand(this)" target="_blank"><img src="%s" alt="%s" width="%s" height="%s" /><a/>', 
	  										 			preg_replace('@^https?://.*?/@','/',$file['url']), 
	  										 			basename($file['url']),
	  										 			preg_replace('@^https?://.*?/@','/',$file['url']), 
	  										 			basename($file['url']),
														$ThumbWidth,
														$ThumbHeight
																    )
	  										 		   	);
														
													  }
													  
	  }
	//===
	
	//===
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
	//===
	
print <<<OUT
<div class="wrap sz">
  <form enctype="multipart/form-data" method="post">
    <div>
      <div id="icon-upload" class="icon32"><br /></div><h2 id="write-post">$title</h2>
      <p>Only support *.jpg , *.png , *.gif format.</p>
              <!--$img_html-->
              <!--$debug-->
              <br style="clear: left;"/>
              <input type="hidden" name="action" value="save"/>
              <input type="hidden" name="sz_main_form" value="true"/>
      		  <input type="button" value="Add more uploads" onclick="FuncAddUploadControl()" class="button-secondary action""/>
      <div id="sz_ctlcontainer">
OUT;

		for ($i=0;$i<$Number_of_uploads;$i++) {
print <<<CTL
     		<label id="lblUploadID">Image: <input type="file" name="FileUpload[]" size="50"/><br/></label>
CTL;
		}
		
$codebox = ($good_results ? "visible" : "hidden");
$_SESSION["outputhtml"] = $outputhtml;
print <<<OUT
      </div>
      <input type="submit" value="Upload" class="button-secondary action""/>
      <input type="button" value="Add more uploads" onclick="FuncAddUploadControl()" class="button-secondary action"/>
    </div>
	<!------Output------>
	<div id="output_html" Style="visibility: $codebox ;";>
		<h3>Upload Success ! </h3>
		<h5>Copy the HTML below , and paste and your post or page.</h5>
OUT;
?>
		<input type="button" value="Preview" onclick="javascript:window.open('../wp-content/plugins/slidezoom/slidezoom-preview.php','SlideZoom Preview','height=480, width=680');" class="button-secondary action"/><br />
		<textarea id="txtoutputhtml"  cols="70" rows="14"><?=$outputhtml ?></textarea> 
<?php
print <<<OUT
	</div>
  </form>
</div>
OUT;
}
?>
