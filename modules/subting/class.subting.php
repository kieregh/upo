<?php
class subting {
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
		if($id>0){
			$selCate = $this->db->query("SELECT * FROM tbl_categories WHERE id = '".$id."'");
			$fetchCate = mysql_fetch_assoc($selCate);
			//echo "<pre>";print_r($fetchCate);
		    $this->subId 			= $this->db->filtering($fetchCate['subId'], 'output', 'int', '');
		    $this->languageId 		= $this->db->filtering($fetchCate['languageId'], 'output', 'int', '');
		    $this->categoryName 	= $this->db->filtering($fetchCate['categoryName'], 'output', 'string', '');
		    $this->title 			= $this->db->filtering($fetchCate['title'], 'output', 'string', '');
		    $this->description 		= $this->db->filtering($fetchCate['description'], 'output', 'string', '');
		    $this->sidebar 			= $this->db->filtering($fetchCate['sidebar'], 'output', 'string', '');
		    $this->submissionText 	= $this->db->filtering($fetchCate['submissionText'], 'output', 'string', '');
		    $this->typeSubting 		= $this->db->filtering($fetchCate['typeSubting'], 'output', 'string', '');
		    $this->postType 		= $this->db->filtering($fetchCate['postType'], 'output', 'string', '');
		    $this->uId 				= $this->db->filtering($fetchCate['uId'], 'output', 'int', '');
		    $this->isActive 		= ($this->db->filtering($fetchCate['isActive'],'output','string','')=='y') ? 'y' : 'n';
		    $this->createdDate 		= $this->db->filtering($fetchCate['createdDate'], 'output', 'string', '');
		}else{
			$this->subId 			= '';
			$this->languageId 		= '';
			$this->categoryName 	= '';
			$this->title 			= '';
			$this->description 		= '';
			$this->sidebar 			= '';
			$this->submissionText 	= '';
			$this->typeSubting 		= '';
			$this->postType 		= '';
			$this->uId 				= '';
			$this->isActive 		= 'y';
			$this->createdDate 		= '';
		}
	}
	public function subtingContent() {
		global $sessUserId;
		$content=NULL;
		$content.=
		'<div class="submit_new_post">
            <form method="post" name="frmsubting" id="frmsubting" enctype="multipart/form-data">
               	<div class="post_box">
               		'.$this->fields->textBox(array("label"=>"Name:","name"=>"categoryName","value"=>$this->categoryName)).'
               		'.$this->fields->textBox(array("label"=>"Title:","name"=>"title","value"=>$this->title)).'
               		'.$this->fields->textArea(array("label"=>"Description:","name"=>"description","value"=>$this->description)).'
               		'.$this->fields->textArea(array("label"=>"Sidebar:","name"=>"sidebar","value"=>$this->sidebar)).'
               		'.$this->fields->textArea(array("label"=>"Submission Text:","name"=>"submissionText","value"=>$this->submissionText)).'
					'.$this->fields->radio(array("label"=>"Type:","name"=>"typeSubting","class"=>"radioBtn-bg","values"=>array("0"=>"public","1"=>"private"),"value"=>$this->typeSubting)).'
					'.$this->fields->radio(array("label"=>"Content option:","name"=>"postType","class"=>"radioBtn-bg","value"=>"a","values"=>array("a"=>"All","t"=>"Text","l"=>"Link"),"value"=>$this->postType)).'
					'.$this->fields->hidden(array("name"=>"id","value"=>$this->id))
					.$this->fields->hidden(array("name"=>"submitSubting","value"=>"".ADDPOST.""))
					.$this->fields->button(array("name"=>"submitSubting","type"=>"submit","value"=>($this->id > 0)?"Edit Subting":"Add Subting","class"=>"post_submit1")).'
                </div>
            </form>
        </div>';
		return $content;
	}
}
?>