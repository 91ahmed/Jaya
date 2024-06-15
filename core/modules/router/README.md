```php

	use Core\Modules\Router\Route;

	// the constructor accepts variadic parameter which is the text you want to remove from the route.
	$route = new Route('remove1', 'remove2'); // remove text from the url

	$route->prefix('dashboard'); // add prefix

	var_dump($route->current()); // get the current route

	// route with parameters and conditions
	$route->get('{lang}/home', 'HomeController@index')
		  ->where(['lang' => '^[a-z]+$'])
	      ->execute();

	$route->prefix_end(); // end prefix

	// route with callback
	$route->get('/', function () {
		echo '/';
	})->execute();

	// route with controller and action
	$route->get('about', 'HomeController@about')->execute();
	// route with middleware
	$route->get('{lang}/home', 'HomeController@index', ['auth'])->execute();

	// You can access the current route and the url params from these constants
	var_dump(ROUTER_URL_CURRENT); // current route
	var_dump(ROUTER_URL_PARAMS); // route parameters
	var_dump($GLOBALS['ROUTER_URL_PARAMS']); // route parameters

	// 404 route
	$route->not_found('HomeController@error');
	$route->not_found(function () {
		exit('404 Page Not Found!');
	});
```