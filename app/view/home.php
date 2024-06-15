<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
	</head>
	<body>
		<form action="<?= url('request') ?>" method="POST">
			<input type="text" value="<?= md5('kokowawa'); ?>" name="username">
			<input type="text" value="90" name="age">
			<input type="text" value="98" name="id[]">
			<input type="text" value="4" name="id[]">
			<button type="submit" name="submit">Submit</button>
		</form>
	</body>
</html>