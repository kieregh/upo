<?php
class AddFund {
	function __construct($objPost=NULL, $id) {
		global $db,$fields;
		$this->db = $db;
		$this->id=$id;
		$this->fields = $fields;
		$this->objPost = $objPost;
		$this->table = 'tbl_users';
	}
}
?>