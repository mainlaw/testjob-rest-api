<?php
	abstract class Api extends HTTP
	{
		protected $params = array();
		
		public function __construct($params)
		{
			$this->params = $params;
		}
		
		public function IndexAction() { return $this->ResponseError('Method Not Allowed', 405); }
		public function ViewAction($value) { return $this->ResponseError('Method Not Allowed', 405); }
		public function UpdateAction() { return $this->ResponseError('Method Not Allowed', 405); }
	}
?>