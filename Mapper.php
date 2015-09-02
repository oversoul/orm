<?php
class Mapper {

	protected $data;

	function __construct( $data ) {
		$this->data = $data;
	}


	public function map()
	{
		$result = [];
		foreach ($this->data as $key => $value) {
			list( $table, $field ) = explode('-', $key);
			$result[$table][$field] = $value;
		}
		return $result;
	}
}