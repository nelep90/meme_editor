<?php
abstract class Secure{

	public static function html($string){
		$tratement = utf8_encode($string);
		$traitement = htmlspecialchars($string);
		return trim($traitement);
	}
}