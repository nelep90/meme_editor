<?php
 
class Bddmanager
{
    protected $bdd;
    private $host = "localhost";
    private $login = "root";
    private $password = "";
 
    public function __construct()
    {
        $bdd = new PDO('mysql:host=' . $this->host . ';dbname=meme_editor;charset=utf8', $this->login, $this->password);
        $this->bdd = $bdd;
 
    }
    public function getBdd(){
    	return $this->bdd;
    }
 
}