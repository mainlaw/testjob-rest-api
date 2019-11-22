<?php
	class Router extends HTTP
	{
		public function __construct()
		{
			header("Access-Control-Allow-Orgin: *");
      	header("Access-Control-Allow-Methods: *");
      	header("Content-Type: application/json");
		}
		
		private function Call($route) 
		{
			if(!$route) throw new Exception('Empty request');
			
			$basedir = 'model/';
			$urlSegments = explode('/', trim($route, '/'));
			
			// get params
			$params = array();
			while(count($urlSegments) > 2) {
				$params[array_shift($urlSegments)] = array_shift($urlSegments);
			}
			
			// get & check model name
			$modelName = ucfirst(array_shift($urlSegments));
			//if(!is_file($basedir.$modelName.'.php')) throw new Exception('Model not found');
			if(!is_file($basedir.$modelName.'.php')) return $this->ResponseError('Model not found', 404);
			
			// get values
			$value = array_shift($urlSegments);
			$values = $value === NULL ? array() : array($value);
			
			// get method
			$method = $_SERVER['REQUEST_METHOD'];
			if($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
				if($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
					$method = 'PUT';
				} else {
					throw new Exception('Method not supported');
				}
			}
			
			// get action
			$action = '';
			switch($method) {
				//case 'GET': $action = 'UpdateAction'; break;
				case 'PUT': $action = 'UpdateAction'; break;
				case 'GET': $action = $values ? 'ViewAction' : 'IndexAction'; break;
				default: throw new Exception('Unexpected method');
			}
			
			// create model
			$model = new $modelName($params);
			return call_user_func_array(array($model, $action), $values);
		}
		
		public function Run($uri = NULL)
		{
			if($uri === NULL) $uri = $_SERVER['REQUEST_URI'];
			$dir = dirname($_SERVER['SCRIPT_NAME']);
			$dir = trim($dir, '/\\');
			$dir = '/'.$dir;
			$route = preg_replace('~^'.$dir.'~', '', $uri);
			//echo($route);
			
			//phpinfo();
			try {
				$result = self::Call($route);
			} catch(Exception $e) {
				$result = $this->ResponseError($e->getMessage(), 500);
			}
			return $result;
		}
	}
?>