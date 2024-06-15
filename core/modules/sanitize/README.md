```php
	
	use Core\Modules\Sanitize\Request;
	use Core\Modules\Sanitize\Data;

	// Sanitize the data that comes from $_GET and $_POST http request
	$var1 = Request::alpha('request_name');

	// Sanitize value
	$var2 = Data::alpha('your_value');
```

### Methods
alpha()
alphas()
alnum()
alnums()
digits()
digit()
integer()
natural()
whole()
positive()
email()
url()
string()