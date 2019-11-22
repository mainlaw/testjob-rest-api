<?php
	abstract class HTTP
	{
		protected function Response($data, $statusCode)
		{
			header("HTTP/1.1 ".$statusCode." ".$this->StatusStr($statusCode));
			return json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		
		protected function StatusStr($statusCode)
		{
			$statuses = array(
         	200 => 'OK',
         	204 => 'No Content',
         	400 => 'Bar Request',
         	404 => 'Not Found',
         	405 => 'Method Not Allowed',
         	500 => 'Internal Server Error',
      	);
      	return isset($statuses[$statusCode]) ? $statuses[$statusCode] : $statuses[500];
		}
		
		protected function ResponseError($msg, $statusCode)
		{
			$data = array(
				'result' => 'error', 
				'errorMsg' => $msg
			);
			return $this->Response($data, $statusCode);	
		}
		
		protected function ResponseOk($data = array(), $statusCode = 200) { 
			$dummy = array(
				'result' => 'ok'
			);
			$data = array_replace($dummy, $data);
			return $this->Response($data, $statusCode);	
		}
		protected function Response404()
		{
			return $this->ResponseError($this->StatusStr(404), 404);	
		}
	}
?>