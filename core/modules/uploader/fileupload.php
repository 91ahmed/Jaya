<?php
	namespace Core\Modules\Uploader;

	class FileUpload
	{
		private $file;
		private $path;
		private $random = false;

		private $name = array();
		private $name_random = array();
		private $extension  = array();
		private $tmp_name  = array();
		private $type = array();
		private $size = array();
		private $error = array();

		public $file_info = array();

		public function file ($fileName) 
		{
			if (isset($_FILES[$fileName])) 
			{
				$this->file = $_FILES[$fileName];


				if (is_string($this->file['name'])) // if single file
				{
					if (!empty($this->file['name'])) 
					{
						array_push($this->name, $this->file['name']);
						array_push($this->name_random, $this->generate_random_name().$this->split_extension($this->file['name']));
						array_push($this->tmp_name, $this->file['tmp_name']);
						array_push($this->type, $this->file['type']);
						array_push($this->size, $this->file['size']);
						array_push($this->error, $this->file['error']);
						array_push($this->extension, $this->split_extension($this->file['name']));
					}
				} 
				elseif (is_array($this->file['name'])) // if multiple files
				{
					if (!empty($this->file['name'][0])) 
					{
						foreach ($this->file['name'] as $key => $value)
						{
							array_push($this->name, $value);
							array_push($this->name_random, $this->generate_random_name().$this->split_extension($value));
							array_push($this->extension, $this->split_extension($value));
						}

						foreach ($this->file['tmp_name'] as $key => $value)
						{
							array_push($this->tmp_name, $value);
						}

						foreach ($this->file['type'] as $key => $value)
						{
							array_push($this->type, $value);
						}						

						foreach ($this->file['size'] as $key => $value)
						{
							array_push($this->size, $value);
						}

						foreach ($this->file['error'] as $key => $value)
						{
							array_push($this->error, $value);
						}
					}
				}
			}

			$this->file_info['name'] = $this->name; 
			$this->file_info['name_random'] = $this->name_random; 
			$this->file_info['tmp_name'] = $this->tmp_name; 
			$this->file_info['type'] = $this->type; 
			$this->file_info['size'] = $this->size; 
			$this->file_info['extension'] = $this->extension; 
			$this->file_info['error'] = $this->error; 
		}

		public function path (string $path)
		{
			$path = trim($path, '/');
			$path = trim($path, '\\');
			$path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
			$path = $path.''.DIRECTORY_SEPARATOR;
			$this->path = $path;

			if (!empty($this->file_info['name'])) 
			{
				if (!is_dir($this->path)) 
				{
					// If you want to create the whole path - just pass the 3rd argument as a true.
					mkdir($this->path, 0777, true);
				}
			}
		}

		public function upload () 
		{
			if (!empty($this->file_info['name']))
			{
				for ($t = 0; $t <= count($this->file_info['tmp_name'])-1; $t++) 
				{
					$name = $this->file_info['name'][$t];
					$name_random = $this->file_info['name_random'][$t];
					$tmp = $this->file_info['tmp_name'][$t];

					if ($this->random == false) {
						move_uploaded_file($tmp, $this->path.$name);
					} else {
						move_uploaded_file($tmp, $this->path.$name_random);
					}
				}
			}
		}

		public function get_name ()
		{
			if ($this->random == false) {
				return $this->name;
			} else {
				return $this->name_random;
			}
		}

		public function get_size ()
		{
			return $this->size;
		}

		public function get_type ()
		{
			return $this->type;
		}

		public function get_extension ()
		{
			return $this->extension;
		}

		public function get_error ()
		{
			return $this->error;
		}

		// Set random to true
		public function generate_name ()
		{
			$this->random = true;
		}

		// Get file extension from the name
		private function split_extension ($fileName)
		{
			$ext = explode('.', $fileName);
			$ext = '.'.end($ext);
			
			return $ext;
		}

		// Generate random name
		private function generate_random_name ($length = 15) 
		{
			$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$random = date('Ymd').'-'.date('his').'-'.substr(str_shuffle(str_repeat($chars, 5)), 0, $length);

			return $random;
		}
	}

	/*

		$file = new FileUpload();
		$file->file('file_input_name');
		$file->path('/public/storage/products/');
		$file->generate_name();
		$file->upload();

		$fileName = $file->get_name();
		$fileSize = $file->get_size();
		$fileExtension = $file->get_extension();
		$fileType = $file->get_type();
	*/
?>