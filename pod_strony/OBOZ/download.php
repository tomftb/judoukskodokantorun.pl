<?php
session_start();
			$file = $_GET['file'];
			$typ=$_GET['typ'];
			$directory=$_GET['directory'];
			$dir_with_file=$directory."/".$file;
			 
		header("Content-Type:image/jpg"); //".$_GET['typ']
		header("Content-Disposition: attachment; filename=".$file); //.basename($dir_with_file)
if (file_exists($dir_with_file)) {
								IF (is_readable($dir_with_file)){
																readfile($dir_with_file);
								} else {
										echo "Plik nie jest do odczytu !";
								};				

} else {
	echo "Plik nie istnieje !";
}