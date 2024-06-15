<?php
	namespace Core\Modules\validation;

	use Core\Modules\Validation\Numbers;
	use Core\Modules\Validation\Strings;
	use Core\Modules\Validation\Arrays;

	class Examine
	{
		use Numbers, Strings, Arrays;

		private $data;
		private $input;
		private $error = array();
		private $errors_count = 0;

		private $message = [
			'required' => 'is required',
			'invalid'  => 'format is invalid',

			// Arrays
			'array'            => 'must be an array',
			'array_assoc'      => 'must be an associative array',
			'array_sequential' => 'must be a sequential array',
			'array_positive'   => 'must be a sequential array containing positive numbers',
			'array_negative'   => 'must be a sequential array containing negative numbers',
			'array_integers'   => 'must be a sequential array containing integers',
			'array_natural'    => 'must be a sequential array containing natural numbers',
			'array_whole'      => 'must be a sequential array containing whole numbers',
			'array_rational'   => 'must be a sequential array containing rational numbers',
			'array_digits'     => 'must be a sequential array containing digits',
			'array_digit'      => 'must be a sequential array containing single digit',

			// Numbers
			'digits' 		  => 'must contain digits only',
			'digit'  		  => 'must contain single digit',
			'number_rational' => 'must be a rational number',
			'number_natural'  => 'must be a natural number',
			'number_whole'    => 'must be a whole number',
			'number_integer'  => 'must be an integer number',
			'number_positive' => 'must be a positive number',
			'number_negative' => 'must be a negative number',

			// Strings
			'alpha'  => 'must be letters only',
			'alphas' => 'must contain letters and spaces',
			'alnum'  => 'must contain letters and digits',
			'alnums' => 'must contain letters, digits and spaces',
		];

		public function request (string $inputName): Examine
		{
			if (isset($_REQUEST[$inputName])) {
				$this->data  = $_REQUEST[$inputName];
				$this->input = $inputName;
			} else {
				throw new \Exception("({$inputName}) request doesn't exists", 1);
			}

			return $this;
		}

		public function data ($data, string $label = ''): Examine 
		{
			$this->data = $data;

			if (empty($label)) {
				$this->input = 'error';
			} else {
				$this->input = $label;
			}
			
			return $this;
		}

		/**
		 *	Check empty values
		 */
		public function required (): Examine
		{
			if ($this->is_request()) 
			{
				if (is_string($this->data)) 
				{
					if ($this->data == null || is_null($this->data) || $this->data == '') 
					{
						$this->error[$this->input] = $this->message['required'];
						$this->errors_count++;
					}
				}
				else if (is_array($this->data)) 
				{
					if (empty($this->data) || count($this->data) == 0) 
					{
						$this->error[$this->input] = $this->message['required'];
						$this->errors_count++;
					}
					else 
					{
						foreach ($this->data as $key => $value) {
							if (empty($value) || is_null($value) || $value == '') {
								$this->error[$this->input] = $this->message['required'];
								$this->errors_count++;
								break;
							}
						}
					}
				}
			}

			return $this;
		}

		private function is_request (): bool
		{
			return (bool) !empty($this->input) && isset($this->data);
		}

		public function errors (): array 
		{
			return (array) $this->error;
		}

		public function is_valid (): bool
		{
			if ($this->errors_count == 0) 
			{
				if (empty($this->error) && count($this->error) == 0) 
				{
					return true;
				}
			}

			return false;
		}
	}
?>