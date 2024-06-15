<?php

	namespace Core\Modules\Database\Query;

	use Core\Modules\Database\Query\Query;
	use Core\Modules\Database\Pdo\Connect;

	/**
	 *	@author ahmed hassan
	 *	@link https://91ahmed.github.io
	 */
	class SQL extends Query
	{
		use Connect;

		private $connect;
		public $pages = 0;

		public function connectPDO () 
		{
			if (!empty($this->info)) {
				foreach ($this->info as $key => $value) {
					$this->set($key, $value);
				}
			}

			$conn = $this->pdo();

			return $conn;
		}

		public function beginTransaction () 
		{
			$this->connect = $this->connectPDO();
			return $this->connect->beginTransaction();
		}

		public function commit () 
		{
			$this->connect = $this->connectPDO();
			return $this->connect->commit();
		}

		public function rollBack () 
		{
			$this->connect = $this->connectPDO();
			return $this->connect->rollBack();
		}

		/**
		 *	Returns an object containing all of the result set rows.
		 *	@return object
		 */
		public function fetch ()
		{
			$this->connect = $this->connectPDO();

			// SQL Query
			$stmt = $this->connect->prepare($this->sqlQuery);
			$stmt->execute($this->data);
			$result = $stmt->fetchAll();

			$this->sqlQuery = null;
			$this->data = [];

			// result
			return (object) $result;
		}

		/**
		 *	Fetches the next row from a result set.
		 *	@return object
		 */
		public function fetch_row ()
		{
			$this->connect = $this->connectPDO();

			// SQL Query
			$stmt = $this->connect->prepare($this->sqlQuery);
			$stmt->execute($this->data);
			$result = $stmt->fetch();

			$this->sqlQuery = null;
			$this->data = [];

			// result
			return (object) $result;
		}

		/**
		 *	Execute sql query.
		 *	@return void
		 */
		public function execute ()
		{
			$this->connect = $this->connectPDO();

			// Execute Query
			$stmt = $this->connect->prepare($this->sqlQuery);
			$stmt->execute($this->data);
		}

		public function paginate ($rowsCount, $pageNumber = 1)
		{
			$this->connect = $this->connectPDO();

			// Get rows count
			$count = $this->connect->prepare("SELECT * FROM {$this->table}");
			$count->execute();
			$rows_count = $count->rowCount();
			
			// Rows at page
			$rows_at_page = $rowsCount;
			
			// Pages count
			$pages_counts = (int)ceil($rows_count / $rows_at_page);
			$this->pages = $pages_counts;
			
			if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
				$pageNumber = $_REQUEST['page'];
			}

			// Start
			$start = ($pageNumber - 1) * $rows_at_page;
			
			// End
			$end = $rows_at_page;

			if ($this->config['driver'] === 'mysql' || $this->config['driver'] === 'sqlite')
			{
				$this->sqlQuery .= " LIMIT {$start}, {$end}"; // LIMIT offset, limit
			} 
			else if ($this->config['driver'] === 'pgsql')
			{
				$this->sqlQuery .= " offset {$start} limit {$end}";
			}

			// Execute Query
			$stmt = $this->connect->prepare($this->sqlQuery);
			$stmt->execute($this->data);
			$result = $stmt->fetchAll();

			$this->sqlQuery = null;
			$this->data = [];

			if ($pageNumber > $this->pages) {
				return array();
			} else if (empty($result)) {
				return array();
			} else {
				return (object) $result;
			}
		}

		public function __destruct ()
		{
			unset($this->connect);
		}
	}
?>