<?php
class Secure{

	public static function html($string){
		$traitement = htmlentities($string);
		return trim($traitement);
	}
}