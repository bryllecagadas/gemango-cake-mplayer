<?php

class JsonComponent extends Component {
	public function response($status  = "error", $message = "", $values = array()) {
		$return = array(
			"status" => $status,
			"message" => "Invalid response."
		);
		
		if(in_array($status, array("success", "error"))) {
			$return["status"] = $status;
			unset($return["message"]);
			if($message) {
				$return["mesage"] = $message;
			}
			$return["data"] = $values;
		}
		
		return json_encode($return);
	}
}