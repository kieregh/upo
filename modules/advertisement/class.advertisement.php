<?php
class advertisement {
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module, $id=0) {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->id = $id;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
	}
	
}
?>