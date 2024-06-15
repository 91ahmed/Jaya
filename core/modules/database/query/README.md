```php
	
	/*
	use Modules\SQLQuery\SQL;

	// EX: ALL METHODS
	$table = new SQL('table');
	$table->select();
	$table->select('*');
	$table->select('column1, column2 AS c2');
	$table->select(['column1', 'column2']);
	$table->select(['column' => 'alias']);
		  ->where('column')->equal(3)
		  ->where('column')->isNull()
		  ->where('column')->isNotNull()
		  ->and('column')->notEqual(99.05)
		  ->or('column')->in(['value1', 'value2'])
		  ->or('column')->between(5,9)
		  ->or('column')->like('%pattern%')
		  ->innerJoin('table2', 'table1.column', '=', 'table2.column')
		  ->leftJoin('table2', 'table1.column', '=', 'table2.column')
		  ->rightJoin('table2', 'table1.column', '=', 'table2.column')
		  ->fullJoin('table2', 'table1.column', '=', 'table2.column')
		  ->crossJoin('table2')
		  ->union(['column1', 'column2'], 'table2')
		  ->unionAll(['column1', 'column2'], 'table2')
		  ->orderBy(['column'], 'ASC')
		  ->groupBy(['column'])
		  ->having('column')->equal('value')
		  ->limit(5);

	$result = $table->fetch();

	// SELECT all
	$table = new SQL('table');
	$table->select() // Default all (*)
	$result = $table->fetch();

	// SELECT columns
	$table = new SQL('table');
	$table->select('column1, column2, column3');
	$result = $table->fetch();

	$table = new SQL('table');
	$table->select(['column1', 'column2', 'column3']);
	$result = $table->fetch();

	// SELECT aliases
	$table = new SQL('table');
	$table->select([
		'column1' => 'alias1',
		'column2' => 'alias2',
		'column3' => 'alias3',
	]);
	$result = $table->fetch();

	$table = new SQL('table');
	$table->select('column1 AS c1, column2 AS c2, column3 AS c3');
	$result = $table->fetch();

	// Aggregate Functions (COUNT - SUM - MAX- MIN - AVG)
	$table = new SQL('table');
	$data  = $table->count('column', 'alias')->fetch_row();

	$table = new SQL('table');
	$data  = $table->max('column', 'alias')->fetch_row();

	$table = new SQL('table');
	$data  = $table->min('column', 'alias')->fetch_row();

	$table = new SQL('table');
	$data  = $table->avg('column', 'alias')->fetch_row();

	$table = new SQL('table');
	$data  = $table->sum('column', 'alias')->fetch_row();

	// (OR) You can use select() method
	$table = new SQL('table');
	$data  = $table->select([
					'MAX(column)'   => 'alias',
					'MIN(column)'   => 'alias',
					'COUNT(column)' => 'alias',
					'SUM(column)'   => 'alias',
					'AVG(column)'   => 'alias',
				])->fetch_row();	

	// INSERT
	$table = new SQL('table');
	$table->insert([
		'column1' => 'value1',
		'column2' => 'value2',
		'column3' => 'value3'
	]);
	$table->execute();

	// UPDATE
	$table = new SQL('table');
	$table->update([
		'column1' => 'new_value1',
		'column2' => 'new_value2'
	]);
	$table->where('column')->equal('value');
	$table->execute();

	// DELETE
	$table = new SQL('table');
	$table->delete()->where('column')->equal('value');
	$table->execute();
	*/
```