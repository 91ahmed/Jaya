<?php
	
	use Core\Modules\Router\Route;

	$route = new Route('Jaya-Framework'); // the constructor accepts variadic parameter which is the text you want to remove from the route.

	$route->get('/home/{lang}', 'HomeController@index')->execute();
	$route->post('/request', 'HomeController@request')->execute();

	$route->not_found(function () {
		exit('404 page not found');
	});
?>