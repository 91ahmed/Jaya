<?php
	
	namespace App\Controller;

	use App\Controller\Base;
	use Core\Modules\Database\Query\SQL;
	use Core\Modules\Validation\Examine;

	class HomeController extends Base
	{
		
		public function index () 
		{

			return view('home');
			/*
			$query = new SQL();

			try 
			{
				$query->table('users'); // set database table
				$query->config(['database' => 'jaya']); // set database information

				$query->beginTransaction(); // Begin transaction

				$result = $query->select('*')->fetch(); // Query

				var_dump($result); // Print result

				//$query->commit();
			} 
			catch (\Exception $e) 
			{
				echo $e->getMessage(); // Print error

				$query->rollBack();
			}
			*/
		}

		public function request () 
		{

			$data = new Examine();
			$data->request('username')->required()->alnums();
			$data->request('age')->required()->rational();
			$data->request('id')->required()->array_digit();
			$data->data(['user' => 'ahmed91'], 'name')->assoc();
			$data->data([23], 'age')->sequential();

			var_dump($data->errors());

			if ($data->is_valid())
			{
				echo 'success';
			}
		}
	}
?>