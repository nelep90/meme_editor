<?php

class MemeDbManager extends ImageDbManager{

	public function __construct(){
		parent::__construct();
		$this->_table = 'me_meme';
	}
}