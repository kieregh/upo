<?php
class message {
	protected $db;
	public $password;

	function __construct($objPost=NULL, $id=0) {
		global $db,$fields,$sessUserId, $sessUsername, $type;
		
		$this->db = $db;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->objPost = $objPost;
		$this->id = $id;
		$this->type = $type;
		$this->table = 'tbl_users';
		$content = $this->messageHeader();
	}
	public function messageHeader(){
		$content=NULL;
		$content.='';
		return $content;		  
	}
	
	public function compose(){
		$content = NULL;
		
		$user = (isset($_GET["user"]))?$this->db->filtering($_GET["user"],'input','string',''):'';
		//$subject = (isset($_GET["subject"]))?$this->db->filtering($_GET["subject"],'input','string',''):'';
		
		$content.='<form method="post" name="friendemail" id="friendemail">
						<div class="compose">
							<div class="clr"></div>
								'.$this->fields->textbox(array("label"=>TO.MEND_SIGN."","name"=>"to","value"=>$user)).'
							<div class="clr"></div>
								'.$this->fields->textbox(array("label"=>SUBJECT.MEND_SIGN."","name"=>"subject")).'
							<div class="clr"></div>
								'.$this->fields->textArea(array("label"=>MESSAGE,"name"=>"description")).'
							<div class="clr"></div>
								'.$this->fields->hidden(array("name"=>"send","value"=>"Send"))
								.$this->fields->button(array("name"=>"send","type"=>"submit","value"=>SEND,"id"=>"send")).'
						</div>
					</form>';
		
		return  $content;
	}
	
	public function inbox(){
		$arry = view_msgbox();
		//print_r($arry);
		$content = NULL;
		if(! empty($arry))
		{
				for ( $i=0; $i<count($arry); $i++) {
			extract($arry[$i]);
			$content .= '<div class="input_section"  id="msg-'.$id.'">
						  <ul>
							<li>
								<div class="request">'.$subject.'</div>
								<div class="from">From<span><a href="'.SITE_URL.'user/'.$from_name.'/overview/">'.$from_name.'</a></span>'.$date.'</div>
								<div class="read_txt">'.$msg.'</div>
								<div class="input_list">
									<ul>
										<li><a href="'.SITE_URL.'message/compose/'.$from_name.'">Reply</a></li>
										<li><a href="javascript:void(0);" onclick="deletemsg('.$id.',\'inbox\')" >Delete</a></li>
									</ul>
								</div>
							</li>
						  </ul>
						</div>';
		}
		}
		else{
			$content .= RNF;
		}
		
	return  $content;
}
	
	public function sent(){
		$arry = view_msgbox("sent");
		$content = NULL;
		if(! empty($arry))
		{
			for ( $i=0; $i<count($arry); $i++) {
			extract($arry[$i]);
			$content .= '<div class="input_section"  id="msg-'.$id.'">
						  <ul>
							<li>
								<div class="request">'.$subject.'</div>
								<div class="from">To<span><a href="'.SITE_URL.'user/'.$to_name.'/overview/">'.$to_name.'</a></span>'.$date.'</div>
								<div class="read_txt">'.$msg.'</div>
								<div class="input_list">
									<ul>
										<li><a href="'.SITE_URL.'message/compose/'.$to_name.'">Reply</a></li>
										<li><a href="javascript:void(0);" onclick="deletemsg('.$id.',\'sent\')">Delete</a></li>
									</ul>
								</div>
							</li>
						  </ul>
						</div>';
		}
		}
		else
		{
			$content.=RNF;
		}
		
		return  $content;
	}
	
	public function delete(){
		$arry = view_msgbox("trash");
		$content = NULL;
		if(! empty($arry))
		{
			for ( $i=0; $i<count($arry); $i++) {
			extract($arry[$i]);
			$content .= '<div class="input_section">
						  <ul>
							<li>
								<div class="request">'.$subject.($trash_to == "y" ? " (inbox)" : " (sent)").'</div>
								<div class="from">'.($trash_to == "y" ? "From" : "To").'<span><a href="'.SITE_URL.'user/'.$to_name.'/overview/">'.$to_name.'</a></span>'.$date.'</div>
								<div class="read_txt">'.$msg.'</div>
								<div class="input_list">
									<ul>
									</ul>
								</div>
							</li>
						  </ul>
						</div>';
		}
		}
		else{
			$content.=RNF;
		}

		return  $content;
	}
}
?>