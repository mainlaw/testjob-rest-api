<?php
	class Tarifs extends Api
	{
		public function IndexAction()
		{
			if(!isset($this->params['users']) || !isset($this->params['services'])) return $this->Response404();
			
			// init db 
			$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			
			// clean input
			$userId = $db->escape_string($this->params['users']);
			$serviceId = $db->escape_string($this->params['services']);
			
			$data = array('tarifs' => array());
			
			// get group id
			$tarifGroupId = $db->queryVal("SELECT tarif_group_id FROM services JOIN tarifs ON services.tarif_id = tarifs.ID WHERE services.ID = '".$serviceId."' AND services.user_id = '".$userId."'");
			
			if($tarifGroupId) {
				// get tarif rows
				$rows = $db->queryAll("SELECT * FROM tarifs WHERE tarif_group_id = '".$tarifGroupId."' ORDER BY ID");
				if($rows) {
					// make tarifs data
					$tarifsData = array();
					foreach($rows as $row) {
						$new_payday = strtotime('midnight +'.$row['pay_period'].' month').date('O');
						
						$tarifsData[] = array(
							'ID' => $row['ID'],
							'title' => $row['title'],
							'price' => round($row['price']),
							'pay_period' => $row['pay_period'],
							'new_payday' => $new_payday,
							'speed' => $row['speed'],
						);
					}
					
					// make tarif group data
					$row = current($rows);
					$tarifGroupData = array(
						'title' => $row['title'],
						'link' => $row['link'],
						'speed' => $row['speed'],
						'tarifs' => $tarifsData,
					);
					
					// make data
					$data = array('tarifs' => array($tarifGroupData));
				}
			} else {
				return $this->responseError('User service not found', 404);	
			}

			return $this->ResponseOk($data);
		}
	}
?>