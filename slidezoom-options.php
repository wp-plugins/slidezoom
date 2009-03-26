<?php
require_once(ABSPATH . '/wp-admin/admin-functions.php');
require_once(ABSPATH . '/wp-content/plugins/slidezoom/function/common.php');

if ($_POST["action"] == "option")
{ FuncUpdateOptions(); }

//Get Options
$AryOption = FuncGetOptions();
$upload_path = attribute_escape(str_replace(ABSPATH, '', get_option('upload_path')));
//ReDefine Options Values if the Options not exist in Database
if (false === $AryOption)
{
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

foreach ($AryOption as $name => $option) { $$name = $option; }
	
  //Define HTML CheckBox
  $html_resize = ($IsResize) ? ' checked="checked"' : "";
  $html_debug  = ($IsDebug) ? ' checked="checked"' : "";

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
<th scope="row">How many images uploader on start?</th>
<td>
<input name="txtUploadCount" id="txtUploadCount" value="$Number_of_uploads" type="text" size="4" />
<span class="setting-description">Default is<code>10</code>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row">Store uploads in this folder :</th>
<td><input name="upload_path" type="text" id="upload_path" value="$upload_path" size="30" ReadOnly Disabled />
<span class="setting-description">Due to SlideZoom using same upload path with WordPress, <br />if you would upload to other directory, please change on <a href="options-misc.php"><code>Miscellaneous Settings</code></a>
</span>
</td>
</tr>

</table>

<hr/>

<h3>Image Options</h3>
<table class="form-table">

<tr valign="top">
<th scope="row">Uploaded images :</th>
<td><input name="chkIsResize" id="chkIsResize" value="1" type="checkbox" $html_resize />Resize images when 
		max width over <input name="txtMaxWidth" id="txtMaxWidth" value="$MaxWidth" type="text" size="4" />px or
		max height over <input name="txtMaxHeight" id="txtMaxHeight" value="$MaxHeight" type="text" size="4" />px
</td>
</tr>

<tr valign="top">
<th scope="row">Thumbnail : </th>
<td>
Resize images to thumbnail with width <input name="txtThumbWidth" id="txtThumbWidth" value="$ThumbWidth" type="text" size="4" />px and height <input name="txtThumbHeight" id="txtThumbHeight" value="$ThumbHeight" type="text" size="4" />px
</td>
</tr>

</table>
		<!--Show debug info<input name="chkIsDebug" id="chkIsDebug" value="1" type="checkbox" $html_debug />-->
		  <input type="hidden" name="action" value="option"/>
		  <input type="hidden" name="sz_option_form" value="1"/>
<p class="submit">
		  <input type="submit" value="Save Changes" class="button-primary" />		
</p>
  </form>
OUT;
?>