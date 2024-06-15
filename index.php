<?php
	ob_start();

	define('ROOT', __DIR__);
	define('DS', DIRECTORY_SEPARATOR);

	require (ROOT.DS.'core'.DS.'framework'.DS.'bootstrap'.DS.'app.php');

	$app = new \Core\Framework\Bootstrap\App();

	$app->file('config');
	$app->file('core/framework/bootstrap/autoload');
	$app->file('core/helpers/framework');
	$app->file('core/helpers/generator');
	$app->file('core/helpers/sanitizer');
	$app->file('core/framework/routes/web');

	unset($app);

	ob_end_flush();
?>