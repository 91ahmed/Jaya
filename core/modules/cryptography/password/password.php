<?php
	
	namespace Core\Modules\Cryptography\Password;
	
	class Password
	{
		public static $digits = array(
			'y' => '01', 'd' => '02', 'm' => '03', 'p' => '04', 'x' => '05', 'o' => '06', 'k' => '07', 'u' => '08',
			'g' => '09', 'l' => '10', 'z' => '11', 'q' => '12', 'h' => '13', 'a' => '14', 'j' => '15', 'r' => '16',
			's' => '17', 'w' => '18', 'c' => '19', 'f' => '20', 'i' => '21', 'b' => '22', 't' => '23', 'e' => '24',
			'n' => '25', 'v' => '26',

			'A' => '27', 'B' => '28', 'C' => '29', 'D' => '30', 'E' => '31', 'F' => '32', 'G' => '33', 'H' => '34',
			'I' => '35', 'J' => '36', 'K' => '37', 'L' => '38', 'M' => '39', 'N' => '40', 'O' => '41', 'P' => '42',
			'Q' => '43', 'R' => '44', 'S' => '45', 'T' => '46', 'U' => '47', 'V' => '48', 'W' => '49', 'X' => '50',
			'Y' => '51', 'Z' => '52',

			'!' => '53', '@' => '54', '#' => '55', '$' => '56', '%' => '57', '^' => '58', '&' => '59', '*' => '60',
			'_' => '61', '-' => '62', '+' => '63', '=' => '64', '?' => '65',

			'1' => '66', '2' => '67', '3' => '68', '4' => '69', '5' => '70', '6' => '71', '7' => '72', '8' => '73',
			'9' => '74', '0' => '75'
		);

		public static $letters = array(
			'0' => 'g',
			'1' => 'h',
			'2' => 'w',
			'3' => 'q',
			'4' => 'v',
			'5' => 'j',
			'6' => 'r',
			'7' => 'x',
			'8' => 'm',
			'9' => 'p'
		);

		public static $cipher = array(
			'g' => ['1','z','D','E','O','Y'],
			'h' => ['2','y','C','F','P','Z'],
			'w' => ['3','u','B','G','Q'],
			'q' => ['4','t','A','H','R'],
			'v' => ['5','s','a','I','S'],
			'j' => ['6','o','b','J','T'],
			'r' => ['7','n','c','K','U'],
			'x' => ['8','l','d','L','V'],
			'm' => ['9','k','e','M','W'],
			'p' => ['0','i','f','N','X']
		);

		public static function encrypt(string $text) 
		{
			$text = str_split($text, 1);
			$encrypt_digits  = [];
			$encrypt_letters = [];
			$cipher = null;
			$start = ['$', '=', '_'];

			shuffle($start);

			foreach ($text as $t) {
				array_push($encrypt_digits, static::$digits[$t]);
			}

			$encrypt_digits = array_reverse($encrypt_digits);

			$encrypt_digits = implode('', $encrypt_digits);
			$encrypt_digits = str_split($encrypt_digits, 1);

			foreach ($encrypt_digits as $d) {
				array_push($encrypt_letters, static::$letters[$d]);
			}

			$cipher = $start[0];

			foreach ($encrypt_letters as $l) {
				shuffle(static::$cipher[$l]);

				$cipher .= static::$cipher[$l][0];
			}
			
			return $cipher;
		}

		public function decrypt (string $cipher) 
		{
			$cipher = trim($cipher, '$=_');
			$cipher = str_split($cipher, 2);
			$cipher = array_reverse($cipher);
			$cipher = implode('', $cipher);
			$cipher = str_split($cipher, 1);

			$letters = null;

			foreach ($cipher as $c) {
				foreach (static::$cipher as $k => $v) {
					if (in_array($c, static::$cipher[$k])) {
						$letters .= $k;
					}
				}
			}

			$letters = str_split($letters, 1);
			$digits = null;

			foreach ($letters as $t) {
				$digits .= array_search($t, static::$letters);
			}

			$digits = str_split($digits, 2);
			$text = null;

			foreach ($digits as $d) {
				$text .= array_search($d, static::$digits);
			}

			return $text;
		}
	}

	/*

		use Core\Cryptography\Password;

		$text = 'ahmed@1991';

		$encrypt = Password::encrypt($text);
		$decrypt = Password::decrypt($encrypt);

		var_dump($encrypt);
		echo '<br>';
		var_dump($decrypt);

	*/
?>