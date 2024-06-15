<?php

	namespace Core\Modules\Database\Query;

	/**
	 *	@author ahmed hassan
	 *	@link https://91ahmed.github.io
	 */
	trait Inspect
	{
		
		public static function isAssoc ($arr)
		{
			if (is_array($arr)) 
			{
				return count(array_filter(array_keys($arr), 'is_string')) > 0;
			}
		}

		public static function isSequential ($arr)
		{
			if (is_array($arr)) 
			{
				return count(array_filter(array_keys($arr), 'is_string')) <= 0;
			}
		}
	}
?>