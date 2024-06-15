<?php
	
	namespace App\Controller;

	use Core\Modules\Sessions\Session;

	class Base
	{
		protected $session;

		public function __construct () 
		{
			// Start session
			$this->session = new Session();
			$this->session->start();
		}
	}
?>