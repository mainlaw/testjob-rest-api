<?php
	class Tarif extends Api
	{
		public function UpdateAction()
		{	
			if(!isset($this->params['users']) || !isset($this->params['services'])) return $this->Response404();
			
			// init db 
			$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			
			// clean input
			$userId = $db->escape_string($this->params['users']);
			$serviceId = $db->escape_string($this->params['services']);

			// check user service
			$service = $db->queryOne("SELECT ID, tarif_id FROM services WHERE ID = '".$serviceId."' AND user_id = '".$userId."'");
			if(!$service) return $this->ResponseError('User service not found', 404);
			$currTarifId = $service['tarif_id'];
			
			// get group id
			$tarifGroupId = $db->queryVal("SELECT tarif_group_id FROM tarifs WHERE ID = '".$currTarifId."'");
			
			// get alowed ids
			$tarifs = $db->queryAll("SELECT ID, pay_period FROM tarifs WHERE tarif_group_id = '".$tarifGroupId."'");
			$tarifs = array_column($tarifs, NULL, 'ID');
						
			// check request
			$json = file_get_contents("php://input");
			if(!$json) return $this->ResponseError('Empty request', 400);
			$request = json_decode($json, true);
			if(!isset($request['tarif_id'])) return $this->ResponseError('Param `tarif_id` not set', 400);
			
			// check new tarif id
			$newTarifId = $request['tarif_id'];
			if(!is_numeric($newTarifId)) return $this->ResponseError('Param `tarif_id` should be numeric', 400);
			if(!isset($tarifs[$newTarifId])) return $this->ResponseError('Tarif not allowed for this service', 400);
			
			// calc payday		
			$payPeriod = $tarifs[$newTarifId]['pay_period'];
			$time = strtotime('midnight +'.$payPeriod.' month');
			$payday = date('Y-m-d', $time);
			
			
			$db->query("UPDATE services SET tarif_id = '".$newTarifId."', payday = '".$payday."' WHERE ID = '".$serviceId."'");
			return $this->ResponseOk();
		}
	}
?>