<?php 
class clsZip {
	
		function unzip($src_file, $dest_dir=false, $create_zip_name_dir=false, $overwrite=true)
		{	
		  if(function_exists("zip_open"))
		  {   
		      if(!is_resource(zip_open($src_file)))
		      { 
		          $src_file=dirname($_SERVER['SCRIPT_FILENAME']).$src_file; 
		      }
			
		      if (is_resource($zip = zip_open($src_file)))
		      {          
		          $splitter = ($create_zip_name_dir === true) ? "." : "/";
		          if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
		         
		          
		          $this->create_dirs($dest_dir);
		
				  $i = 0;
				  
		          while ($zip_entry = zip_read($zip))
		          {
		           
		            $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
		            if ($pos_last_slash !== false)
		            {
		              $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
		            }
		
		            if (zip_entry_open($zip,$zip_entry,"r"))
		            {

					 $seed = 1;
					 $wud = wp_upload_dir();
					 if (file_exists($wud['path'].'/'.sanitize_file_name(zip_entry_name($zip_entry))))
					 {
					 		while (file_exists($wud['path'].'/'.getFilename(sanitize_file_name(zip_entry_name($zip_entry))).$seed.'.'.getFileExt(sanitize_file_name(zip_entry_name($zip_entry)))) )
					 		{
						 		$seed++;
						 	}
						 	$savefilename = getFilename(sanitize_file_name(zip_entry_name($zip_entry))).$seed.'.'.getFileExt(sanitize_file_name(zip_entry_name($zip_entry)));
					 	    $file_name = $dest_dir.$savefilename;
					 }
					 else
					 {
							$savefilename = sanitize_file_name(zip_entry_name($zip_entry));
							$file_name = $dest_dir.zip_entry_name($zip_entry);
					 }
					 
		              if ($overwrite === true || $overwrite === false && !is_file($file_name))
		              {
		                $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));           
		                
		                if(!is_dir($file_name))            
		                file_put_contents($file_name, $fstream );
		                if(file_exists($file_name))
		                {
		                    chmod($file_name, 0777);
							      $file[$i] = array(
      		   												  'name' => $savefilename,
															  'tmp_name' => $file_name,
															  'error' => '',
															  'size' => filesize($file_name)
      		   									       );
		                }
		                else
		                {
		                }
		              }
		             
		              zip_entry_close($zip_entry);
		            }
					$i++;
		          }
		          zip_close($zip);
		      }
		      else
		      {
		        return $file;
		      }
		      return $file;
		  }
		  else
		  {
		      if(version_compare(phpversion(), "4.1.0", "<"))
		      $infoVersion="(use PHP 4.1.0 or later)";
		  }
		}
		
		function create_dirs($path)
		{
		  if (!is_dir($path))
		  {
		    $directory_path = "";
		    $directories = explode("/",$path);
		    array_pop($directories);
		   
		    foreach($directories as $directory)
		    {
		      $directory_path .= $directory."/";
		      if (!is_dir($directory_path))
		      {
		        mkdir($directory_path);
		        chmod($directory_path, 0777);
		      }
		    }
		  }
		}

}
?>