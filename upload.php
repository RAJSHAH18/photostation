<?php

class uploadFile{

	public function upload($file, $target_dir, $fileNameParam)
	{
		try{
print_r($file);
			//$target_dir = "uploads/";

			$uploadOk = 1;

			$OriginalFilename = $FinalFilename = $file['name'];
			
			$FileCounter = 1;
			$filename = pathinfo($OriginalFilename, PATHINFO_FILENAME);
			$extension =  pathinfo($OriginalFilename, PATHINFO_EXTENSION);
			while (file_exists( 'uploads/'.$FinalFilename ))
			    $FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;

			
			if ($file["size"] > 5000000) {
			
			    $uploadOk = 0;
			}
			
			if($extension != "jpg" && $extension != "png" && $extension != "jpeg"
			&& $extension != "gif" && $extension != "pdf") {
			    $uploadOk = 0;
				
			}
			
			if ($uploadOk == 0) {
				http_response_code();
				
			} else {
				$target_file = $fileNameParam.'.'.$extension;
				//echo "<script>alert($target_file)</script>";
			    if (move_uploaded_file($file["tmp_name"], $target_file)) {
			        echo "<script>alert('hello')</script>";
					return $fileNameParam.'.'.$extension;
			    } else {
					echo $target_file;

					echo "<script>alert('3')</script>";
			        http_response_code();
			    }
			}


		}
		catch(PDOException $e) {
			echo $e;
		}   
	}

}
?>