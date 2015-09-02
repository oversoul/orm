<?php
set_exception_handler(function($e) {
	die($e->getMessage());
});
require 'Db.php';
require 'Model.php';

// this is a testing file.
class Post extends Model{

	protected $table = 'posts';
	protected $properties = array('id', 'title', 'slug', 'content', 'users_id');
	//protected $belongsTo = ['User'];
}

class User extends Model {

	protected $table = 'users';
	protected $properties = array('id', 'username', 'email');

}

$post = new Post;

var_dump($post->all());