<?php
	class DB extends mysqli
	{
		public function query($q, $resultmode = NULL)
		{
			$result = parent::query($q, $resultmode);
			if(!$result) {
				error_log("Mysql error: ".$this->error);
				throw new Exception('Database error');
			}
			return $result;
		}
		public function queryAll($q, $resultmode = NULL) 
		{
			$result = $this->query($q, $resultmode);
			$all = $result->fetch_all(MYSQLI_ASSOC);
			$all = TextLib::ToUTF8($all);
			return $all;
		}
		public function queryOne($q, $resultmode = NULL) 
		{
			$result = $this->query($q, $resultmode);
			$one = $result->fetch_assoc();
			$one = TextLib::ToUTF8($one);
			return $one;
		}
		public function queryVal($q, $resultmode = NULL) 
		{
			$row = $this->queryOne($q, $resultmode);
			if(!$row) return NULL;
			return TextLib::ToUTF8(current($row));
		}
	}
?>