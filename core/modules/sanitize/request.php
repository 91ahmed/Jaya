<?php
	namespace Core\Modules\Sanitize;

	class Request
	{
		public static function alpha ($request)
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^a-zA-Z]+/i', '', $request);
		}			

		public static function alphas ($request)
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^a-zA-Z\s]+/i', '', $request);
		}

		public static function alnum ($request)
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^a-zA-Z0-9]+/i', '', $request);
		}	

		public static function alnums ($request)
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^a-zA-Z0-9\s]+/i', '', $request);
		}		

		public static function digits ($request)
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^0-9]+/i', '', $request);
		}

		public static function digit ($request)
		{
			$request = trim($_REQUEST[$request]);
			$request = preg_replace('/[^0-9]+/i', '', $request);
			return substr($request, 0,1);
		}

		public static function integer ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return filter_var($request, FILTER_SANITIZE_NUMBER_INT);
		}

		public static function natural ($request) 
		{
			$request = (string) trim($_REQUEST[$request]);
			$request = preg_replace('/[^0-9]+/i', '', $request);
			return ltrim($request, '0');
		}

		public static function whole ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^0-9]+/i', '', $request);
		}

		public static function positive ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return preg_replace('/[^0-9]+/i', '', $request);
		}

		public static function email ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return filter_var($request, FILTER_SANITIZE_EMAIL);
		}

		public static function url ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return filter_var($request, FILTER_SANITIZE_URL);
		}

		public static function string ($request) 
		{
			$request = trim($_REQUEST[$request]);
			return (string) htmlspecialchars($request);
		}
	}
?>