<?php
require_once(ABSPATH . '/wp-admin/admin-functions.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/common.php');
?>

<?php
if ($_POST["action"] == "option")
{ FuncUpdateOptions(); }
?>

<?php
//Get Options
$AryOptions = FuncGetOptions();
$upload_path = attribute_escape(str_replace(ABSPATH, '', get_option('upload_path')));
//ReDefine Options Values if the Options not exist in Database
if (false === $AryOptions)
{
    $AryOptions = array(
      'IsDebug' => 0,
      'IsResize' => 0,
      'MaxWidth' => 1000,
      'MaxHeight' => 1000,
	  'ThumbMode' => 1,
	  'ThumbWidth' => 160,
	  'ThumbHeight' => 160,
      'Number_of_uploads' => 10
    );
}

foreach ($AryOptions as $name => $option) { $$name = $option; }
	
  //Define HTML CheckBox Attribute
  $AttIsDebug  = ($IsDebug) ? ' checked="checked"' : "";
  $AttIsResize = ($IsResize) ? ' checked="checked"' : "";
  $AttIsThumbMode1 = ($ThumbMode == "1" ? ' checked="checked"' : "");
  $AttIsThumbMode2 = ($ThumbMode == "2" ? ' checked="checked"' : "");
  
	//Print HTML
print <<<OUT
  <div class="wrap">
  <div id="icon-options-general" class="icon32"><br /></div>
  <h2>SlideZoom Settings</h2>
OUT;
  	if ($_POST["action"] == "option")
	 { echo "<div style=\"background-color: rgb(255, 251, 204);\" id=\"message\" class=\"updated fade\"><p>Setting updated.</p></div>"; }
print <<<OUT
  </div>
  <form method="POST">
        <h3>General Options</h3>
		 
<table class="form-table">
<tr valign="top">
<th scope="row" style="width:260px;">How many images uploader on start?</th>
<td>
<input name="txtUploadCount" id="txtUploadCount" value="$Number_of_uploads" type="text" size="4" />
<span class="setting-description">Default is<code>10</code>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row">Store uploads in this folder :</th>
<td><input name="upload_path" type="text" id="upload_path" value="$upload_path" size="30" ReadOnly Disabled /><br />
<span class="setting-description">Due to SlideZoom using same upload path with WordPress, <br />if you would upload to other directory, please change on <a href="options-misc.php"><code>Miscellaneous Settings</code></a>
</span>
</td>
</tr>

</table>

<hr/>

<h3>Image Options</h3>
<table class="form-table">

<tr valign="top">
<th scope="row" style="width:260px;"><input name="chkIsResize" id="chkIsResize" value="1" type="checkbox" $AttIsResize />&nbsp;Resize uploaded images when :</th>
<td>
		<b>Width</b> over <input name="txtMaxWidth" id="txtMaxWidth" value="$MaxWidth" type="text" size="4" />px and
		<b>Height</b> over <input name="txtMaxHeight" id="txtMaxHeight" value="$MaxHeight" type="text" size="4" />px
		<span class="setting-description">&nbsp;Apply to the images that uploaded by files or zip.</span>
</td>
</tr>

<tr valign="top">
<th scope="row">Thumbnail size : </th>
<td>
	<b>Width : </b><input name="txtThumbWidth" id="txtThumbWidth" value="$ThumbWidth" type="text" size="4" />px 
	<b>Height : </b><input name="txtThumbHeight" id="txtThumbHeight" value="$ThumbHeight" type="text" size="4" />px
</td>
</tr>


<tr valign="top">
<th scope="row">Thumbnail Mode : </th>
<td>
    <input id="radThumbMode1" name="radThumbMode" type="radio" value="1" $AttIsThumbMode1 />Original mode<br />
		<span class="setting-description">
				&nbsp;Only apply HTML attribute : <a href="http://www.w3schools.com/tags/att_img_width.asp"><code>Width</code></a>
					and <a href="http://www.w3schools.com/tags/att_img_height.asp"><code>Height</code></a>
					to change the display size.&nbsp;The uploaded images will not be touch.
		</span><br />
    <input id="radThumbMode2" name="radThumbMode" type="radio" value="2" $AttIsThumbMode2 />Resize mode<br />
		<span class="setting-description">
				Resize images to thumbnails with size above. 
		</span>
</td>
</tr>

</table>
		<!--Show debug info<input name="chkIsDebug" id="chkIsDebug" value="1" type="checkbox" $AttIsDebug />-->
		  <input type="hidden" name="action" value="option"/>
		  <input type="hidden" name="sz_option_form" value="1"/>
<p class="submit">
		  <input type="submit" value="Save Changes" class="button-primary" />		
</p>
  </form>
OUT;
?>