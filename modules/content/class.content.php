<?php
class Content{
	private $pId;
	protected $db;
	public $module;
	protected $fields;
	private $pageName;
	public $pageTitle;	
	public $metaDesc;
	public $metaKeyword;
	public $mascot;
	private $pageDesc;		
	
	function __construct($pId=0) {
		global $db, $fields;
		
		$this->db = $db;
		$this->fields = $fields;
		$this->pId = $pId;
		$this->table = 'tbl_content';
		
		if($this->pId>0){
				$qrysel = $this->db->select($this->table, "*", "pId=".$this->pId, "", "", 0);
				if(mysql_num_rows($qrysel)){
				$fetchRes = mysql_fetch_object($qrysel);
				$this->pageName = $this->db->filtering($fetchRes->pageName,'output','string','ucwords');
				$this->pageTitle = $this->db->filtering($fetchRes->pageTitle,'output','string','ucwords');
				$this->metaKeyword = $this->db->filtering($fetchRes->metaKeyword,'output','string','');
				$this->metaDesc = $this->db->filtering($fetchRes->metaDesc,'output','text','');
				$this->pageDesc = $this->db->filtering($fetchRes->pageDesc,'output','text','');
			}
		}
	}
	public function getCont() {
		$content = NULL;
		$content =$this->pageDesc;
		return $content;
	}
}
?>