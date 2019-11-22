<?php
	error_reporting(E_ALL);
	require_once('db_cfg.php');
	
	spl_autoload_register(function($className) {
		$dirs = array(
			'class',
			'model',
			'lib',
		);	
		foreach($dirs as $dir) {
			$path = $dir.'/'.$className.'.php';
			//echo($path.'<br/>');
			if(is_file($path)) {
				require_once($path);
				break;
			}
		}
	});
	
   // Router
   $router = new Router;
   echo($router->Run());
?>