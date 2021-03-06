<?php
class prefer {
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
		$content = $this->preferHeader();
		
		
			$qrysel= $this->db->select("tbl_ads_cost","pageId,slotId,cost,display_sec,createdDate,isActive", "", "", 0);
			if(mysql_num_rows($qrysel)>0){
			$fetchRes = mysql_fetch_object($qrysel);
				$this->pageId = $this->db->filtering($fetchRes->pageId,'output','string','');
				$this->slotId = $this->db->filtering($fetchRes->slotId,'output','int','');
				$this->cost = $this->db->filtering($fetchRes->cost,'output','int','');
				$this->second = $this->db->filtering($fetchRes->display_sec,'output','int','');
				$this->isActive = $this->db->filtering($fetchRes->isActive,'output','string','');
			}
			
	}
	public function preferHeader(){
		$content=NULL;
		$content.='';
		return $content;		  
	}
	
	public function createAdvertisment($pageNo){
		$content = NULL;
		$pageslot = $this->db->select("tbl_ads_cost", "cost,display_sec", " pageId = ".$this->pageId." AND slotId = ".$this->slotId."",'','',0);
		$slotrow = mysql_fetch_assoc($pageslot);
		
		$content = '<div class="advertisement_cost">
		<form action="" name="frmadvert" id="frmadvert" method="post" enctype="multipart/form-data">
		<div class="create_adv">';
				
			if($this->pageId==1) {
				$pagename = "Home Page";
			}
			else if($this->pageId==2) { 
				$pagename = "Inner Page";
			} 
			else { 
				$pagename = "Category Page";
			}
					
			$content.= $this->fields->selectBox(array(
                    "label"=>PAGE." ".NAME.":",
                    "name"=>"pageId",
                    "extraAtt"=>"onchange=changePage()",
					"defaultValue"=>false,
                    "allow_null"=>0,
                    "class"=>"selectBox-bg",
                    "choices"=>array("1"=>"Home page","2"=>"Inner page","3"=>"Category page",),
                    "value"=>"",
                    "defaultValue"=>false,
                    "multiple"=>false,
                    "optgroup"=>false,
					"intoDB"=>array(""=>""), 
				)).
				'<div id="slotname">'.$this->fields->selectbox(array(
					"label"=>SLOT.":",
					"name"=>"categoryName",
					 "extraAtt"=>"onchange=changeSlotCost()",
					"class"=>"selectBox-bg",
					 "allow_null"=>0,
					"choices"=>array(""=>"Please Select"),
					"defaultValue"=>false,
					"multiple"=>false,
					"optgroup"=>false,
					"intoDB"=>array("val"=>true,
						   "table"=>"tbl_ads_cost",
							"fields"=>"id,slotId,CONCAT('Slot ',slotId) AS slotName",
							"where"=>"pageId=1",
							"orderBy"=>"slotId",
							"valField"=>"slotId",
							"dispField"=>"slotName"
						)
				)).'</div>
	
			<div id="cost"><label>'.PER.' '.CLICK.':&nbsp;</label><span>'.$slotrow["cost"].'</span><div class="flclear"></div><label>'.DISPLAY_SEC.':&nbsp;</label><span>'.$slotrow["display_sec"].'</span></div>'.
			
			$this->fields->textBox(array("label"=>ENTER_BUDGET." ($):","id"=>"budget","name"=>"budget","class"=>"logintextbox-bg only_alpha","extraAtt"=>"onkeyup=getBudget('price')")).
			$this->fields->hidden(array("id"=>"hiden_clicks","name"=>"hiden_clicks","value"=>"".$slotrow["cost"]."")).
			
			$this->fields->textBox(array("label"=>TOT_NUM_CLICK.":","name"=>"total_click","class"=>"logintextbox-bg only_alpha","extraAtt"=>"onkeyup=getBudget('clicks')")).
			
			$this->fields->textBox(array("label"=>ADD_LINK.":","id"=>"addlink","name"=>"addlink","class"=>"","value"=>"")).
			
			$this->fields->fileBox(array("label"=>"".SELECTIMG."","name"=>"banner","class"=>"error_img","extraAtt"=>"onchange=imageCaution(this)")).'
			
				<div id="costerror" class="error"></div>
				<div id="calculate"></div>
				<div id="link"></div>';
			$content .= $this->fields->button(array("name"=>"payadvertisement","type"=>"submit","value"=>"","class"=>"paynowadvertisement"));												
		$content .= '</div>
	</form></div>';
		
		return $content;
	}
	
	public function friend($pageNo){
		$content = NULL;
		$content .= '<div class="submit_new_post">
						<form method="post" name="friendemail" id="friendemail">
							<div class="post_box">
									'.$this->fields->textbox(array("label"=>"".USERNAME.":".MEND_SIGN."","name"=>"email")).'<div class="spacer10"></div>'.
									 $this->fields->button(array("name"=>"addfriend","type"=>"button","extraAtt"=>"onclick=\"addFriend()\"","value"=>"Add")).	
									'
							</div>
						</form>
					</div>';
		$content.='<div id="friendlistdisplay">'.$this->friendlist().'</div>';	
		return $content;
	}
	
	public function friendlist(){
		
		$fIdArr = array();
		$content = NULL;
		$username='';
		$CMTSQL=$this->db->select("tbl_friend","fid","uid='$this->sessUserId'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$fIdArr[]=$CMTres["fid"];
		}
		if(count($fIdArr)>0)
		{
			$fIdArr=implode(",",array_unique($fIdArr));
			
			$selRes = "SELECT `username`,`id` FROM `tbl_users` WHERE id IN(".$fIdArr.")";
			$qSelRes = $this->db->query($selRes);	
			$totalRows = mysql_num_rows($qSelRes);
			while($NameRes = mysql_fetch_assoc($qSelRes))
			{
				
				$username .= '<div id="'.$NameRes["id"].'delete" class="frnd_list_view">'.$NameRes["username"].'<a href="'.SITE_URL.'message/compose/'.$NameRes["username"].'"><img src="'.SITE_IMG.'message.png"/></a><a href="javascript:void(0);" onclick="deletefriend(\''.$NameRes["id"].'\')"><img src="'.SITE_IMG.'delete.png"/></a></div>';
				
			}
		}
		$content .= '<div class="submit_new_post">
						'.$username.'			
					</div>';
		return $content;
	}
	
	public function password($pageNo){
		$content = NULL;
		$content .='<div class="submit_new_post">
						<form method="post" name="frmprefer" id="frmprefer">
							<div class="post_box post_box1">
								'.$this->fields->password(array("label"=>"".CURRENTPASS.":".MEND_SIGN."","name"=>"currentpass")).
								$this->fields->password(array("label"=>"".NEWPASS.":".MEND_SIGN."","name"=>"newpass")).
								$this->fields->password(array("label"=>"".VERIFYPASS.":".MEND_SIGN."","name"=>"verifypass")).
								'<div class="spacer15"></div><div class="post_submit">'.$this->fields->button(array("name"=>"changepassword","type"=>"submit","value"=>"".SAVE."")).'</div>
                    		</div>
						</form>
					</div>';
		
		return $content;
	}
	
	public function submitProcedure($objPost) {

		global $sessUserId;
		$currentpass = isset($objPost->currentpass) ? $objPost->currentpass : '';
		$newpass = isset($objPost->newpass) ? $objPost->newpass : '';
		$verifypass = isset($objPost->verifypass) ? $objPost->verifypass : '';

		$qrysel = $this->db->select($this->table,"password","id=".$sessUserId."");
		$fetchUser = mysql_fetch_array($qrysel);
		
		if($fetchUser["password"] == md5($currentpass)) {
			if($newpass == $verifypass)
			{
				$value = new stdClass();
				$value->password = md5($verifypass);
				$value->ipAddress = $_SERVER["REMOTE_ADDR"];
				$qryUpd = $this->db->update($this->table, $value, "id=".$sessUserId."", '');
				$_SESSION["msgType"] = array('type'=>'suc','var' =>MSGCHANGEPASS);
			}
			else
			{
				$_SESSION["msgType"] = array('type'=>'suc','var' =>DOESMATCHPASSWORD);
			}
		}
		else
		{
			$_SESSION["msgType"] = array('type'=>'err','var' =>WRONGPASSWORD);
		}
	}
	
	public function submitProcedureDeleteAccount($objPost) {

		global $sessUserId;
		$username = isset($objPost->username) ? $objPost->username : '';
		$password = isset($objPost->password) ? $objPost->password : '';

		$qrysel = $this->db->select($this->table,"username,password","id=".$sessUserId."");
		$fetchUser = mysql_fetch_array($qrysel);
		
		if($fetchUser["username"] == $username) {
			if(md5($password) == $fetchUser["password"])
			{
				$value = new stdClass();
				$value->isActive = 'r';
				$value->ipAddress = $_SERVER["REMOTE_ADDR"];
				$qryUpd = $this->db->update($this->table, $value, "id=".$sessUserId."", '');
				//$_SESSION["msgType"] = array('type'=>'suc','var' =>"UserDeletedSuccessfully");
				if(isset($_SESSION)) {
					foreach($_SESSION  as $k => $v) {
						$_SESSION[$k] = NULL;
						unset($_SESSION[$k]);
					}
				}
				$_SESSION["msgType"] = array('type'=>'suc','var' =>MSGDELETEACCOUNT);
				redirectPage(SITE_URL);
			}
			else
			{
				$_SESSION["msgType"] = array('type'=>'suc','var' =>WRONGPASSWORD);
			}
		}
		else
		{
			$_SESSION["msgType"] = array('type'=>'suc','var' =>WRONGUSERNM);
		}
	}
	
	public function delete($pageNo){
		$content = NULL;
		$content = '<div class="submit_new_post">
						<form method="post" name="frmpreferdelete" id="frmpreferdelete">
							<div class="post_box">
									'.DELMSG1.'<br />'.DELMSG2.'
							</div>
							<div class="post_box">
									'.$this->fields->textBox(array("label"=>"".USERNAME.":".MEND_SIGN."","name"=>"username")).
									$this->fields->password(array("label"=>"".PASSWORD.":".MEND_SIGN."","name"=>"password")).'
								<div class="spacer10"></div>
								<label>&nbsp;</label>
								<div class="conformation">
									<input type="checkbox" name="confirmdelete" id="confirmdelete" class="confirm">
									<div>&nbsp;'.CONFIRMDELETE.'</div>
									<label for="confirmdelete" generated="true" class="error"></label>
									<span id="errcheck" class="error"></span>
								</div>

								<div class="post_submit">
									'.$this->fields->button(array("name"=>"deleteaccount","type"=>"submit","value"=>"".DELETE."")).'
    	                		</div>
							</div>							
						</form>
					</div>';
					
		return $content;
	}
}
?>