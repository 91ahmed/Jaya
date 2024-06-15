<?php
	
	namespace Core\Framework\Bootstrap;

	class App 
	{
		public function file (string $filePath) 
		{
			$file = trim($filePath, '/\\');
			$file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
			$file = ROOT.DS.$file.'.php';

			if (is_file($file) && file_exists($file)) {
				return require ($file);
			} else {
				throw new \Exception("{$file} file doesn't exists", 1);
			}
		}
	}
?>