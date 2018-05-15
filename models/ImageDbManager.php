<?php

class ImageDbManager {
	protected $_db;
	protected $_table;

	public function __construct(){
		$this->_db = new Bddmanager();
		$this->_db = $this->_db->getBdd();
		$this->_table = 'me_image';
	}

	public function read($id){
		$req = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE id = :id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	public function create(){
		
	}
}