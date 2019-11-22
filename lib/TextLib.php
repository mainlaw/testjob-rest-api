<?php
	class TextLib
	{
		static function ToUTF8($mix, $codepage = 'cp1251') 
		{ 
	 		if(is_string($mix)) {
	 			$mix = iconv($codepage, 'UTF-8', $mix);
	 			//$mix = iconv('ISO-8859-1', 'UTF-8', $mix);
	 			
	 		} else if(is_array($mix)) {
	 			foreach($mix as $key => $val) {
	 				$mix[$key] = self::ToUTF8($val, $codepage);	
	 			}	
	 		} else if(is_object($mix)) {
	 			foreach($mix as $key => $val) {
	 				$mix->$key = self::ToUTF8($val, $codepage);	
	 			}
	 		}
	 		return $mix;
	 	}
	 }
?>