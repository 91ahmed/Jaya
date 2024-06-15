<?php
	
	namespace Core\Modules\Validation;

	trait Strings 
	{
		public function alpha (): Examine
		{
			if ($this->is_request())
			{
				if (!empty($this->data)) 
				{
					if (is_string($this->data))
					{
						if (!preg_match_all('/^[a-zA-Z]+$/', $this->data)) 
						{
							$this->error[$this->input] = $this->message['alpha'];
							$this->errors_count++;
						}
					}
					else
					{
						$this->error[$this->input] = $this->message['alpha'];
						$this->errors_count++;
					}
				}
			}

			return $this;
		}		

		public function alphas (): Examine
		{
			if ($this->is_request())
			{
				if (!empty($this->data)) 
				{
					if (is_string($this->data))
					{
						if (!preg_match_all('/^[a-zA-Z\s]*$/', $this->data)) 
						{
							$this->error[$this->input] = $this->message['alphas'];
							$this->errors_count++;
						}
					}
					else
					{
						$this->error[$this->input] = $this->message['alphas'];
						$this->errors_count++;
					}
				}
			}

			return $this;
		}

		public function alnum (): Examine
		{
			if ($this->is_request())
			{
				if (!empty($this->data)) 
				{
					if (is_string($this->data))
					{
						if (!preg_match_all('/^[a-zA-Z0-9]+$/', $this->data)) 
						{
							$this->error[$this->input] = $this->message['alnum'];
							$this->errors_count++;
						}
					}
					else
					{
						$this->error[$this->input] = $this->message['alnum'];
						$this->errors_count++;
					}
				}
			}

			return $this;
		}

		public function alnums (): Examine
		{
			if ($this->is_request())
			{
				if (!empty($this->data)) 
				{
					if (is_string($this->data))
					{
						if (!preg_match_all('/^[a-zA-Z0-9\s]+$/', $this->data)) 
						{
							$this->error[$this->input] = $this->message['alnums'];
							$this->errors_count++;
						}
					}
					else
					{
						$this->error[$this->input] = $this->message['alnums'];
						$this->errors_count++;
					}
				}
			}

			return $this;
		}
	}
?>