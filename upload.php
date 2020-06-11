<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["userImage"]["type"])){
	$msg = '';
	$uploaded = FALSE;
	$extensions = array("jpeg", "jpg", "png");
	$fileTypes = array("image/png","image/jpg","image/jpeg");
	$file = $_FILES["userImage"];
	$file_extension = strtolower(end(explode(".", $file["name"])));
	$target_Algorithim = $_POST['Point_to_Point'];
	$target_valueC = $_POST['ccons'];
	$target_valueB = $_POST['bcons'];
	if ($target_Algorithim=='add' or $target_Algorithim=="subtract" or $target_Algorithim=="blending" or $target_Algorithim=="divide" or $target_Algorithim=="Oand" or $target_Algorithim=="Oor" or $target_Algorithim=="Oxor"){
		$file1 = $_FILES["userImage1"];
		$sourcePath1 = $file1['tmp_name'];
		$targetPath1 = 'uploads/'.$file1['name'];
		move_uploaded_file($sourcePath1,$targetPath1);
	}
	
	if(!$target_valueC and !$target_valueB){$target_valueC=0;$target_valueB=0;}
	
	if (in_array($file["type"],$fileTypes) && in_array($file_extension, $extensions)) {
		if ($file["error"] > 0)
		{
			$msg = 'Error Code: ' . $file["error"];
		}
		else
		{
			if (file_exists("upload/" . $file["name"])) {
				$msg = $file["name"].' ya existe.';				
			}
			else
			{
				$sourcePath = $file['tmp_name'];
				$targetPath = 'uploads/'.$file['name'];
				move_uploaded_file($sourcePath,$targetPath);
				$msg = 'Image Subida .....!!';
				$uploaded = TRUE;
			}
		}
	}
	else
	{
		$msg = '***archivo invalido***';
	}
	#echo ($uploaded ? $msg : '<span class="msg-error">'.$msg.'</span><br>');
	
	if ($target_Algorithim=="thresholding"){
		echo '<br><img src="uploads/histograma.png">';
	}
	else if ($target_Algorithim=="histogramEq"){
		echo '<br><img src="uploads/histograma.png">';
	}
	
	$dir_proyect = "C:\\xampp\\htdocs\\proccess\\";
	//$exe = "/opt/lampp/htdocs/proccess/".$target_Algorithim.".py";
	$exe = "C:\\xampp\\htdocs\\proccess\\".$target_Algorithim.".py";
	
	$dir_file = $dir_proyect.$targetPath;
	
	//echo '<br>',$exe, '<br>';
	//echo $dir_file, '<br>';
	
	$dir_python = "G:\\Anaconda\\python.exe";
	
	
	if ($target_Algorithim=='add' or $target_Algorithim=="subtract" or $target_Algorithim=="Oand" or $target_Algorithim=="Oor" or $target_Algorithim=="Oxor" or $target_Algorithim=="blending" or $target_Algorithim=="divide"){
		$dir_file1 = $dir_proyect.$targetPath1;
		if($target_Algorithim=="blending"){
			$message = exec("$dir_python $exe $dir_file $dir_file1 $target_valueB 2>&1");
		}
		else{
			$message = exec("$dir_python $exe $dir_file $dir_file1 2>&1");
		}

		
	}
	else{
		$message = exec("$dir_python $exe $dir_file $target_valueC $target_valueB 2>&1");
		//$cmd = "<br>G:\\Anaconda\\python.exe $exe $dir_file $target_valueC $target_valueB 2>&1";
		//echo $cmd;
	}
	
	print_r($message);
	echo '<img src="'.$message.'">';
}

exit;
die();
?>
