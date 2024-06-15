```php
	use Core\Cryptography\Password\Password;

	$text = 'ahmedhassan1241991';

	$encrypt = Password::encrypt($text);
	$decrypt = Password::decrypt($encrypt);
```