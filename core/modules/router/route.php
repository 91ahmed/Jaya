<?php
	
	namespace Core\Modules\Router;

	class Route
	{
		private $client = [
			'route'  => array(),
			'params' => array(),
			'count'  => 0,
			'key'    => 'client-key',
			'value'  => 'client-values',
		];

		private $domain = [
			'route'  => array(),
			'params' => array(),
			'count'  => 0,
			'key'    => 'domain-key',
			'value'  => 'domain-value',
		];

		private $remove  = array();
		private $params  = array();
		private $found   = 0;
		private $pattern; 
		private $prefix;
		private $middlewares = array();
		private $registered  = array();

		public function __construct (string ...$remove) 
		{
			$this->remove = $remove;

			// Store the current route in a constant
			define ('ROUTER_URL_CURRENT', $this->current());
		}

		public function prefix (string $value) 
		{
			$this->prefix = (string) $value;
		}

		public function prefix_end () 
		{
			$this->prefix = null;
		}

		private function client_parse (string $route) 
		{
			if (!preg_match_all('/^[a-z0-9{}?\/]+$/', $route))
			{
				throw new \Exception("Your route format is invalid", 1);
			}
			else 
			{
				$route = strtolower(trim($route,'/\\'));
				$route = explode('/', $route);

				if (!empty($this->prefix) || !is_null($this->prefix)) 
				{
					if (count($route) == 1 && $route[0] == '') {
						$route[0] = $this->prefix;
					} else {
						array_unshift($route, $this->prefix);
					}
				}

				$this->client['key'] = null;
				$this->client['value'] = null;

				foreach ($route as $key => $value) {
					if (substr($value, 0,1) == '{') {
						$this->client['route'][$key] = ['param' => trim($value, '{}')];
						$this->client['params'][$key] = trim($value, '{}');
						$this->client['count']++;
					} else {
						$this->client['route'][$key] = ['route' => $value];
						$this->client['count']++;
						$this->client['key'] .= $key;						
						$this->client['value'] .= $value.'/';						
					}
				}
			}
		}

		private function domain_parse ()
		{
			$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$route = str_replace($this->remove, '', $route);
			$route = trim($route, '/\\');
			$route = explode('/', $route);

			$this->domain['key'] = null;
			$this->domain['value'] = null;
			$this->domain['route'] = $route;
			$this->domain['count'] = count($route);

			foreach ($this->client['route'] as $key => $value) {
				if (isset($value['route'])) {
					if (isset($this->domain['route'][$key])) {
						if ($value['route'] === $this->domain['route'][$key]) {
							$this->domain['key'] .= $key;
							$this->domain['value'] .= $value['route'].'/';
						}
					}
				} else {
					if (isset($value['param'])) {
						if (isset($this->domain['route'][$key]) && !empty($this->domain['route'][$key])) {
							$this->domain['params'][$key] = $this->domain['route'][$key];
						}
					}
				}
			}
		}

		private function match (string $route, $pattern) 
		{
			$this->client_parse($route);
			$this->domain_parse();

			if (!empty($this->client['route']) && !empty($this->domain['route'])) 
			{
				if ($this->client['count'] == $this->domain['count']) 
				{
					if ($this->client['key'] === $this->domain['key'] && $this->client['value'] === $this->domain['value']) 
					{
						if (count($this->client['params']) == count($this->domain['params'])) 
						{
							$this->params = array_combine($this->client['params'], $this->domain['params']);

							// Store route parameters globally in a constant
							define ('ROUTER_URL_PARAMS', $this->params);
							$GLOBALS['ROUTER_URL_PARAMS'] = $this->params;

							return true;
						}
						
					}
				}
			}

			return false;
		}

		public function get (string $route, $pattern, array $middlewares = []) 
		{
			array_push($this->registered, $route);
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') 
			{
				$match = $this->match($route, $pattern);

				if ($match == true) 
				{
					$this->found = 1;
					$this->pattern = $pattern;
					$this->middlewares = $middlewares;
				}
			}

			return $this;
		}

		public function post (string $route, $pattern, array $middlewares = []) 
		{
			array_push($this->registered, $route);

			if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$match = $this->match($route, $pattern);

				if ($match == true) 
				{
					$this->found = 1;
					$this->pattern = $pattern;
					$this->middlewares = $middlewares;
				}
			}

			return $this;
		}

		private function call (String $pattern)
		{
			$pattern = explode('@', $pattern, 2);

			$controller = '\\App\\Controller\\' . $pattern[0];
			$action = $pattern[1];

			if (!class_exists($controller)) {
				throw new \Exception("Controller Not Found ({$controller})", 1);
				exit();
			} elseif (!method_exists($controller, $action)) {
				throw new \Exception("Controller Method Not Found ({$action})", 1);
				exit();
			} else {
				$execute = new $controller();
				if (!empty($this->params)) {
					$params = array_values($this->params);					
					call_user_func_array(array($execute, $action), $params);
				} else {
					$execute->$action();
				}
			}
		}

		public function where (array $conditions) 
		{
			if (!empty($this->params)) 
			{
				foreach ($this->params as $key => $value) 
				{
					if (isset($conditions[$key])) 
					{
						$regex = '/'.$conditions[$key].'/';

						if (!preg_match_all($regex, $value)) {
							$this->found = 0;
						}
					}
				}
			}

			return $this;
		}

		public function execute ()
		{
			if ($this->found == 1) 
			{
				$pattern = $this->pattern;

				if (!empty($this->middlewares)) 
				{
					foreach ($this->middlewares as $md) {
						$class = ucfirst($md);
						$middleware = "\\App\\Middleware\\$class";

						if (!class_exists($middleware)) {
							throw new \Exception("Middleware class doesn't exists ({$middleware})", 1);
							exit();
						} else {
							$execute = new $middleware();
						}
					}
				}

				if (is_callable($pattern)) {
					if (!empty($this->params)) {
						$params = array_values($this->params);					
						call_user_func_array($pattern, $params);
					} else {
						$pattern();
						//return exit();
					}
				} else if (is_string($pattern)) {
					$this->call($pattern);
					//return exit();
				}
			}

			$this->reset();
		}

		public function not_found ($pattern) 
		{
			if ($this->found == 0) 
			{
				header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found", true, 404);

				if (is_callable($pattern)) {
					$pattern();
					//return exit();
				} else if (is_string($pattern)) {
					$this->call($pattern);
					//return exit();
				}
			}
		}

		public function all () 
		{
			return $this->registered;
		}

		public function current ()
		{
			$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$route = str_replace($this->remove, '', $route);
			$route = trim($route, '/\\');

			return $route;
		}

		private function reset () 
		{
			$this->client = [
				'route'  => array(),
				'params' => array(),
				'count'  => 0,
				'key'    => 'client-key',
				'value'  => 'client-value',
			];

			$this->domain = [
				'route'  => array(),
				'params' => array(),
				'count'  => 0,
				'key'    => 'domain-key',
				'value'  => 'domain-value',
			];

			$this->params  = array();
			$this->pattern = null;
			$this->middlewares = array();
		}
	}
?>