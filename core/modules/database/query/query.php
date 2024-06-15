<?php

	namespace Core\Modules\Database\Query;

	use Core\Modules\Database\Query\Inspect;

	/**
	 *	@author ahmed hassan
	 *	@link https://91ahmed.github.io
	 */
	class Query
	{
		use Inspect;

		protected $table; // table name
		protected $data = array(); // placeholder data
		protected $errors = array(); // error messages
		protected $sqlQuery; // holds the sql query
		protected $info = array(); // database information

		public function table (string $table = '') 
		{
			$this->table = $table;
		}

		public function config (array $info = []) 
		{
			if (Inspect::isAssoc($info)) {
				$this->info = $info;
			}
		}

		public function select ($columns = '*')
		{
			if (Inspect::isSequential($columns))
			{
				// Convert columns array to string
				$columns = (string) implode(', ', $columns);
				$columns = trim($columns, ' ');

				$this->sqlQuery .= "SELECT {$columns} FROM ".$this->table;
			}
			elseif (Inspect::isAssoc($columns)) 
			{
				// Convert columns array to string
				$aliases = null;
				foreach ($columns as $key => $value) {
					$aliases .= $key.' AS '.$value.', ';
				}

				// Trim comma
				$aliases = (string) trim($aliases, ', ');

				$this->sqlQuery .= "SELECT {$aliases} FROM ".$this->table;
			}
			elseif (is_string($columns))
			{
				$this->sqlQuery .= "SELECT {$columns} FROM ".$this->table;
			}
			else
			{
				// Error
				$msg = "select() method accepts one parameter and it should be a string or array";
				array_push($this->errors, $msg);
				throw new \Exception($msg, 1);
			}
			
			return $this;
		}

		public function where (string $column)
		{
			$column = trim($column, ' ');
			$this->sqlQuery .= " WHERE {$column}";

			return $this;
		}

		public function equal ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' = ?';

			return $this;
		}

		public function notEqual ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' != ?';

			return $this;
		}

		public function greaterThan ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' > ?';

			return $this;
		}

		public function greaterThanOrEquel ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' >= ?';

			return $this;
		}

		public function lessThan ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' < ?';

			return $this;
		}

		public function lessThanOrEquel ($value)
		{
			$value = trim($value, ' ');
			array_push($this->data, $value);
			$this->sqlQuery .= ' <= ?';

			return $this;
		}

		public function and (string $column)
		{
			$this->sqlQuery .= " AND {$column}";

			return $this;
		}

		public function or (string $column)
		{
			$this->sqlQuery .= " OR {$column}";

			return $this;
		}

		public function isNull ()
		{
			$this->sqlQuery .= " IS NULL";

			return $this;
		}

		public function isNotNull ()
		{
			$this->sqlQuery .= " IS NOT NULL";

			return $this;
		}


		public function limit (int $value)
		{
			array_push($this->data, $value);

			$this->sqlQuery .= " LIMIT ?";

			return $this;
		}

		public function like (string $pattern)
		{
			array_push($this->data, $pattern);

			$this->sqlQuery .= " LIKE ?";

			return $this;
		}

		public function in (array $values)
		{
			// Check the parameter is a sequential array or not
			if (!Inspect::isSequential($values)) {
				// Error
				$msg = "in() method accepts one parameter and it should be a sequential array";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			$this->data = array_merge($this->data, $values);

			$in  = str_repeat('?,', count($values) - 1) . '?';

			$this->sqlQuery .= " IN ($in)";

			return $this;
		}

		public function between ($value1, $value2)
		{
			if (!is_scalar($value1) || !is_scalar($value2)) {
				// Error
				$msg = "between() method has invalid parameter value";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			array_push($this->data, $value1);
			array_push($this->data, $value2);

			$this->sqlQuery .= " BETWEEN ? AND ?";

			return $this;
		}

		public function innerJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' INNER JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function leftJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' LEFT JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function leftOuterJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' LEFT OUTER JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function rightJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' RIGHT JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function rightOuterJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' RIGHT OUTER JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function fullJoin (string $table2, string $column1, string $operator, string $column2)
		{
			$this->sqlQuery .= ' FULL OUTER JOIN '.$table2.' ON '.$column1.' '.$operator.' '.$column2;

			return $this;
		}

		public function crossJoin (string $table2)
		{
			$this->sqlQuery .= ' CROSS JOIN '.$table2;

			return $this;
		}

		public function union (array $columns, string $table2)
		{
			// Check the parameter is a sequential array or not
			if (!Inspect::isSequential($columns)) {
				// Error
				$msg = "union() method accepts one parameter and it should be a sequential array";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			// Convert columns array to string
			$columns = (string) implode(', ', $columns);

			$this->sqlQuery .= ' UNION SELECT '.$columns.' FROM '.$table2;

			return $this;
		}

		public function unionAll (array $columns, string $table2)
		{
			// Check the parameter is a sequential array or not
			if (!Inspect::isSequential($columns)) {
				// Error
				$msg = "unionAll() method accepts one parameter and it should be a sequential array";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			// Convert columns array to string
			$columns = (string) implode(', ', $columns);

			$this->sqlQuery .= " UNION ALL SELECT {$columns} FROM {$table2}";

			return $this;
		}

		public function groupBy (array $columns)
		{
			// Check the parameter is a sequential array or not
			if (!Inspect::isSequential($columns)) {
				// Error
				$msg = "groupBy() method accepts one parameter and it should be a sequential array";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			// Convert columns array to string
			$columns = (string) implode(', ', $columns);

			$this->sqlQuery .= " GROUP BY {$columns}";

			return $this;
		}

		public function having (string $column)
		{
			$this->sqlQuery .= " HAVING {$column}";

			return $this;
		}

		public function orderBy (array $columns, string $sort = 'DESC')
		{
			if (!Inspect::isSequential($columns)) {
				// Error
				$msg = "orderBy() method accepts one parameter and it should be a sequential array";
				array_push($this->errors, $msg); // Store the error message in the errors array
				throw new \Exception($msg, 1); // Throw an exception
			}

			$order = ['DESC', 'ASC'];

			// Convert columns array to string
			$columns = (string) implode(', ', $columns);

			if (in_array($sort, $order)) {
				$this->sqlQuery .= " ORDER BY {$columns} {$sort}";
			} else {
				$this->sqlQuery .= " ORDER BY {$columns} DESC";
			}
			
			return $this;
		}

		public function count (string $column, string $alias = '') 
		{
			$this->sqlQuery = "SELECT COUNT({$column}) AS {$alias} FROM $this->table";

			return $this;
		}

		public function max (string $column, string $alias = '') 
		{
			$this->sqlQuery = "SELECT MAX({$column}) AS {$alias} FROM $this->table";

			return $this;
		}

		public function min (string $column, string $alias = '') 
		{
			$this->sqlQuery = "SELECT MIN({$column}) AS {$alias} FROM $this->table";

			return $this;
		}

		public function avg (string $column, string $alias = '') 
		{
			$this->sqlQuery = "SELECT AVG({$column}) AS {$alias} FROM $this->table";

			return $this;
		}

		public function sum (string $column, string $alias = '') 
		{
			$this->sqlQuery = "SELECT SUM({$column}) AS {$alias} FROM $this->table";

			return $this;
		}

		public function delete ()
		{
			$this->sqlQuery = "DELETE FROM $this->table";

			return $this;
		}

		public function insert (array $data)
		{
			// Check the array is associative or not
			if (!Inspect::isAssoc($data)) {
				throw new \Exception('insert() method accepts 1 parameter should be an associative array', 1);
			}

			$this->data = array_merge($this->data, array_values($data));

			$this->sqlQuery = "INSERT INTO $this->table (";

			foreach ($data as $column => $value) {
				$this->sqlQuery .= $column.', ';
			}

			$this->sqlQuery = trim($this->sqlQuery, ', ');
			$this->sqlQuery .= ') VALUES (';

			foreach ($data as $column => $value) {
				$this->sqlQuery .= '?, ';
			}

			$this->sqlQuery = trim($this->sqlQuery, ', ');
			$this->sqlQuery .= ');';

			return $this;
		}

		public function update (array $data)
		{
			// Check the array is associative or not
			if (!Inspect::isAssoc($data)) {
				throw new \Exception('update() method accepts 1 parameter should be an associative array with key and value ["column" => "value"]', 1);
			}
			
			$this->data = array_merge($this->data, array_values($data));

			$this->sqlQuery = "UPDATE $this->table SET ";
			
			foreach ($data as $column => $value) {
				$this->sqlQuery .= $column . " = ?, ";
			}

			$this->sqlQuery = trim($this->sqlQuery, ', ');
			
			return $this;
		}

		public function custom ($customQuery)
		{
			$this->sqlQuery = $customQuery;

			return $this;
		}
	} 
?>