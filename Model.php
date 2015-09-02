<?php
class Model {

	protected $table;
	protected $properties = [];
	protected $belongsTo = [];

	public function __construct( $data = array() )
	{
		if ( ! empty($data) ) {
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	protected function getFields($model)
	{
		$fields = [];
		foreach ($model->properties as $property) {
			$fields[] = '`' . $model->table . '`.`' . $property . '` as `'.$model->table.'-'.$property.'`';   
		}
		return implode(', ', $fields);
	}

	public function getJoins()
	{
		$join = '';
		if ( ! empty( $this->belongsTo ) ) {
			$joins = [];
			foreach ($this->belongsTo as $belongs) {
				$model = new $belongs;
				$joins[] = " LEFT JOIN `".$model->table."` ON `".$model->table."`.`id`=`".$this->table."`.`".strtolower($model->table)."_id`";
			}
			$join = implode(' ', $joins);
		}
		return $join;
	}

	public function getAllFields()
	{
		$fieldsArr[] = $this->getFields($this);
		if ( ! empty( $this->belongsTo ) ) {
			foreach ($this->belongsTo as $belongs) {
				$model = new $belongs;
				$fieldsArr[] = $this->getFields($model);
			}
		}
		return implode(', ', $fieldsArr);
	}

	public function all()
	{
		$query = "SELECT ";
		$join = $this->getJoins();
		$fields = $this->getAllFields();
		$query .= $fields . " FROM " . $this->table . " " . $join;
		var_dump($query);
		Db::execute($query);
		$data = [];
		while ( $row = Db::fetch( PDO::FETCH_ASSOC ) ) {
			$data [] = $this->hydrate( $row );
		}
		return $data;
	}

	public function hydrate($data)
	{
		return $data;
		return new $this( $data );
	}
}
