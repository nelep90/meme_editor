<?php
// gestion de la table "me_image"
class ImageDbManager {
	protected $_db;
	protected $_table;

	public function __construct(){
		$this->_db = new Bddmanager();
		$this->_db = $this->_db->getBdd();
		$this->_table = 'me_image';
	}
	// @param id (int)
	// Lit et retourne une ligne de la table "me_image"
	public function read($id){
		$req = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE id = :id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	// @param url (str)
	// Insert une nouvelle ligne correspondant à une nouvelle image dans la table "me_image"
	public function create($url){
		$req = $this->_db->prepare('INSERT INTO me_image (date_ajout, url) VALUES (NOW(), :url)'); 
		$req->bindParam('url', $url, PDO::PARAM_STR);
		$req->execute();
	}
	// @param url (string)
	// test si un nom d'image(url) existe déja dans la table
	// retourne un bool
	public function urlExist($url){
		$req = $this->_db->prepare('SELECT COUNT(*) AS exist FROM ' . $this->_table . ' WHERE url = :url');
		$req->bindParam('url', $url, PDO::PARAM_STR);
		$req->execute();
		$response = $req->fetch(PDO::FETCH_ASSOC);
		if ($response['exist'] > 0){
			return true;
		} else {
			return false;
		}
	}
	public function listByDate(){
		$req = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' ORDER BY date_ajout DESC');
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}