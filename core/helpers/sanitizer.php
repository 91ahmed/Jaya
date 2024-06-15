<?php

	//	Extract (negative / positive / fraction) numbers [-2, -1, 0, 1, 2, 3.5, 5.8]
	function sz_numeric ($value) 
	{
		$value = trim($value);
		return trim(preg_replace('/[^-?\d+(\. \d+)?]/', '', $value), '.');
	}	

	// Extract (positive) numbers [0, 1, 2, 3, 4, 5]
	function sz_digit ($value) 
	{
		$value = trim($value);
		return preg_replace('/[^\d]/', '', $value);
	}

	// Extract (negative / positive) numbers [-2, -1, 0, 1, 2]
	function sz_integer ($value) 
	{
		$value = trim($value);
		return preg_replace('/[^-0-9]/', '', $value);
	}

	// Extract english letters
	function sz_alpha ($value)
	{
		$value = trim($value);
		return (string) preg_replace('/[^A-Za-z\-]/', '', $value);
	}	

	// Extract english letters / spaces
	function sz_alphas ($value)
	{
		$value = trim($value);
		return (string) preg_replace('/[^A-Za-z\s\-]/', '', $value);
	}

	// Extract numbers / english letters
	function sz_alnum ($value)
	{
		$value = trim($value);
		return (string) preg_replace('/[^A-Za-z0-9\-]/', '', $value);
	}	

	// Extract numbers / english letters / spaces
	function sz_alnums ($value)
	{
		$value = trim($value);
		return (string) preg_replace('/[^A-Za-z0-9\s\-]/', '', $value);
	}

	// Remove Special characters
	function sz_rm_special ($value)
	{
		$chars = '`~!@#$%^&*()_-+=[]{}|\\:;\'"<>?/,.';
		$chars = str_split($chars);

		$value = trim($value);
		$value = str_replace($chars, '', $value);
		return (string) $value;
	}	

	// Remove Special characters and spaces
	function sz_rm_specialw ($value)
	{
		$chars = '`~!@#$%^&*()_-+=[]{}|\\:;\'"<>?/,.';
		$chars = str_split($chars);

		$value = trim($value);
		$value = str_replace($chars, '', $value);
		$value = str_replace(' ', '', $value);
		return (string) $value;
	}

	// Sanitize URL
	function sz_url ($value) 
	{
		$value = trim($value);
		return (string) filter_var($value, FILTER_SANITIZE_URL);
	}

	// Sanitize Email
	function sz_email ($value) 
	{
		$value = trim($value);
		return (string) filter_var($value, FILTER_SANITIZE_EMAIL);
	}

?>