<?php
class Db
{
	protected static $pdo = null;
	protected static $stmt;
	
	public static function pdo()
	{
		if ( is_null(static::$pdo) ) {
			try {
				static::$pdo = new PDO('mysql:host=localhost;dbname=tests', 'root', '');
				static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		return static::$pdo;
	}

	public static function execute($query, $params = array())
	{
		$stmt = static::pdo()->prepare($query);
		$stmt->setFetchMode ( PDO::FETCH_ASSOC );
		$stmt->execute($params);
		static::$stmt = $stmt;
		return true;
	}

	public static function fetch($mode)
	{
		return static::$stmt->fetch($mode);
	}
}