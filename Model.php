<?php
class Model {

	protected $table;
	protected $properties = [];
	protected $belongsTo = [];
	protected $hasOne = [];

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

		if ( ! empty( $this->hasOne ) ) {
			$joins = [];
			foreach ($this->hasOne as $hasone) {
				$model = new $hasone;
				$joins[] = " LEFT JOIN `".$model->table."` ON `".$this->table."`.`id`=`".$model->table."`.`".strtolower($this->table)."_id`";
			}
			$join .= implode(' ', $joins);
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

		if ( ! empty( $this->hasOne ) ) {
			foreach ($this->hasOne as $hasone) {
				$model = new $hasone;
				$fieldsArr[] = $this->getFields($model);
			}
		}

		return implode(', ', $fieldsArr);
	}

	public function buildQuery()
	{
		$query = "SELECT ";
		$join = $this->getJoins();
		$fields = $this->getAllFields();
		$query .= $fields . " FROM " . $this->table . " " . $join;
		var_dump($query);
		return $query;
	}

	public function all()
	{
		$query = $this->buildQuery();
		Db::execute($query);
		$data = [];
		while ( $row = Db::fetch( PDO::FETCH_ASSOC ) ) {
			$data [] = $this->hydrate( $row );
		}
		return $data;
	}

	public function one()
	{
		$query = $this->buildQuery();
		Db::execute($query);
		return $this->hydrate( Db::fetch(PDO::FETCH_ASSOC));
	}

	public function hydrate($data)
	{
		return (new Mapper( $data ))->map();
	}
}
