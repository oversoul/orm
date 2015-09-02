<?php
set_exception_handler(function($e) {
	die($e->getMessage());
});
require 'Mapper.php';
require 'Db.php';
require 'Model.php';

// this is a testing file.
class Posts extends Model{

	protected $table = 'posts';
	protected $properties = array('id', 'title', 'slug', 'content', 'users_id');
	// protected $belongsTo = ['Users'];
}

class Users extends Model {

	protected $table = 'users';
	protected $properties = array('id', 'username', 'email');

}

$post = new Posts;

var_dump($post->all());
