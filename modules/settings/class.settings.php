<?php
class Settings {
	protected $db;
	public $headTitle;
	function __construct($objPost=NULL,$id=0, $action) {
		global $db,$fields,$sessUserId;
		
		$this->db = $db;
		//$this->id= $uId;
		$this->sessUserId = $sessUserId;
		$this->id = $id;
		$this->fields = $fields;
		$this->objPost = $objPost;
		$this->action = $action;
		$this->table = 'tbl_users';
		
		$this->headTitle = 'Edit Profile';		
				
		$qrysel= $this->db->query("SELECT  user.*,sta.stateName,shoe.shoeSize,dress.dressSize FROM `tbl_users` AS user,`tbl_state` As sta,`tbl_shoe_size` As shoe,`tbl_dress_size` As dress WHERE user.uId =".$this->sessUserId." AND sta.StateID = user.state AND shoe.id = user.shoeId AND dress.id = user.derssId ",0);
		if(mysql_num_rows($qrysel)>0){
			$fetchRes = mysql_fetch_object($qrysel);
			$this->name = $this->db->filtering($fetchRes->name,'output','string','');
			$this->profileImg = $this->db->filtering($fetchRes->profileImg,'output','string','');
			$this->state = $this->db->filtering($fetchRes->state,'output','int','');
			$this->stateName = $this->db->filtering($fetchRes->stateName,'output','string','');
			$this->city = $this->db->filtering($fetchRes->city,'output','string','');
			$this->address = $this->db->filtering($fetchRes->address,'output','text','');
			$this->aboutMe = $this->db->filtering($fetchRes->aboutMe,'output','text','');
			$this->derssId = $this->db->filtering($fetchRes->derssId,'output','int','');
			$this->dressSize = $this->db->filtering($fetchRes->dressSize,'output','string','');
			$this->shoeId = $this->db->filtering($fetchRes->shoeId,'output','int','');
			$this->shoeSize = $this->db->filtering($fetchRes->shoeSize,'output','string','');
			$this->birthDate = convertDate($this->db->filtering($fetchRes->birthDate,'output','string',''));
			$this->zipCode = $this->db->filtering($fetchRes->zipCode,'output','int','');
			$this->website = $this->db->filtering($fetchRes->website,'output','string','');
			$this->settings_Follow = $this->db->filtering($fetchRes->settings_Follow,'output','string','');
			$this->settings_likeOrshare = $this->db->filtering($fetchRes->settings_likeOrshare,'output','string','');
			$this->settings_Comment = $this->db->filtering($fetchRes->settings_Comment,'output','string','');
			$this->settings_partyInvite = $this->db->filtering($fetchRes->settings_partyInvite,'output','string','');
		}
		
	}
	public function getForm() {
	    $content = NULL;
		switch($this->action) {
			case 'edit-profile': {
				$content = $this->editProfile();
				break;
			}
			case 'change-password': {
				$content = $this->changePassword();
				break;
			}
			case 'edit-email': {
				$content = $this->editEmail();
				break;
			}
		}
		return $content; 
	}
	private function editEmail() {
		$content = NULL;
		$content .= '<form class="field_aria" action="" method="post" name="frmpro" id="frmpro">
					 '.$this->fields->radio(array("label"=>"Follow: ","name"=>"settings_Follow","class"=>"radioBtn-bg","value"=>($this->settings_Follow!= '' ? $this->settings_Follow : 'y'),"values"=>array("y"=>"On","n"=>"Off"))).'
					<div>Notify when someone follows you</div>
					<div class="clearfix"></div>
					'.$this->fields->radio(array("label"=>"Like or Share: ","name"=>"settings_likeOrshare","class"=>"radioBtn-bg","value"=>($this->settings_likeOrshare!= '' ? $this->settings_likeOrshare : 'y'),"values"=>array("y"=>"On","n"=>"Off"))).'
					<div>Notify when someone likes or shares your listing</div>
					<div class="clearfix"></div>
					'.$this->fields->radio(array("label"=>"Comment: ","name"=>"settings_Comment","class"=>"radioBtn-bg","value"=>($this->settings_Comment!= '' ? $this->settings_Comment : 'y'),"values"=>array("y"=>"On","n"=>"Off"))).'
					<div>Notify when someone comments on your listing or mentions you</div>
					<div class="clearfix"></div>
					'.$this->fields->radio(array("label"=>"Party Invite: ","name"=>"settings_partyInvite","class"=>"radioBtn-bg","value"=>($this->settings_partyInvite!= '' ? $this->settings_partyInvite : 'y'),"values"=>array("y"=>"On","n"=>"Off"))).'
					<div>Notify when you get invited to a party</div>
					<div class="clearfix"></div>
					<div class="save_profile">
					<div class="cancel_lft">
						<a href="#">Cancel</a>
					</div>
					<div class="join_btn">
						<input type="submit" id="submitEmail" name="submitEmail" value="save profile" />
					</div>
			  	</div>  
				</form>';
		return $content;
	}
	public function editProfile() {
		$content = '';
		$stateName = getTableValue($this->db,"tbl_state","StateID=".$this->state."","stateName");
		$shoeSize = getTableValue($this->db,"tbl_shoe_size","id=".$this->shoeId."","shoeSize");
		$dressSize = getTableValue($this->db,"tbl_dress_size","id=".$this->derssId."","dressSize");
		
        $content .= '<form class="field_aria" action="" method="post" name="frmpro" id="frmpro">
                	'.$this->fields->textBox(array("label"=>"".MEND_SIGN."Your Name: ".getHelpImg(ENTER_FNAME),"name"=>"name","class"=>"","value"=>$this->name)).'
					<div class="clearfix"></div>
                   '.$this->fields->textBox(array("label"=>"".MEND_SIGN."Birth Date: ".getHelpImg(ENTER_ADDR),"name"=>"birthDate","class"=>"","value"=>$this->birthDate)).'
                    <div class="clearfix"></div>
                   '.$this->fields->textBox(array("label"=>"".MEND_SIGN."Street name: ".getHelpImg(ENTER_ADDR),"name"=>"address","class"=>"","value"=>$this->address)).'
                    <div class="clearfix"></div>
					<label>'.MEND_SIGN.'Location: '.getHelpImg(SEL_STATE).'</label>
                    <div class="name">
                   	'.$this->fields->textBox(array("label"=>NULL,"name"=>"city","class"=>"", "onlyField"=>true,"extraAtt"=>'placeholder="City"', "value"=>$this->city)).'
                       '.$this->fields->selectBox(array(
							"label"=>"".MEND_SIGN."State: ".getHelpImg(SEL_STATE),
							"name"=>"state",
							"onlyField"=>true,
							"allow_null"=>1,
							"class"=>"",
							"choices"=>array("0"=>""),
							"value"=>$this->state,
							"defaultValue"=>true,
							"multiple"=>false,
							"optgroup"=>false,
							"intoDB"=>array("val"=>true,
								  	"table"=>"tbl_state",
									"fields"=>"*",
									"where"=>"isActive='y'",
									"orderBy"=>"stateName",
									"valField"=>"StateID",
									"dispField"=>"stateName"						
							)
			
						)).'
                    </div>
                    <div class="clearfix"></div>
                    '.$this->fields->textBox(array("label"=>"".MEND_SIGN."Zip Code: ".getHelpImg(ENTER_PINCODE),"name"=>"zipCode","class"=>"","value"=>$this->zipCode)).'
                    <div class="clearfix"></div>
					'.$this->fields->textBox(array("label"=>"".MEND_SIGN."Website: ".getHelpImg(ENTER_WEBSITE),"name"=>"website","class"=>"","value"=>$this->website)).'
                    <div class="clearfix"></div>
					 <label>'.MEND_SIGN.'Shoe Size: '.getHelpImg(SEL_SHOE).'</label>
                    '.$this->fields->selectBox(array(
							"label"=>"".MEND_SIGN."Shoes Size: ".getHelpImg(SEL_SHOE),
							"name"=>"shoeId",
							"onlyField"=>true,
							"allow_null"=>1,
							"class"=>"",
							"choices"=>array("0"=>""),
							"value"=>$this->shoeId,
							"defaultValue"=>true,
							"multiple"=>false,
							"optgroup"=>false,
							"intoDB"=>array("val"=>true,
								   "table"=>"tbl_shoe_size",
									"fields"=>"*",
									"where"=>"isActive='y'",
									"orderBy"=>"id",
									"valField"=>"id",
									"dispField"=>"shoeSize"						
							)
			
						)).'
                    <div class="clearfix"></div>
                    <label>'.MEND_SIGN.'Dress Size: '.getHelpImg(SEL_DRESS).'</label>
                    '.$this->fields->selectBox(array(
							"label"=>"".MEND_SIGN."Dress Size: ".getHelpImg(SEL_DRESS),
							"name"=>"derssId",
							"onlyField"=>true,
							"allow_null"=>1,
							"class"=>"",
							"choices"=>array("0"=>""),
							"value"=>$this->derssId,
							"defaultValue"=>true,
							"multiple"=>false,
							"optgroup"=>false,
							"intoDB"=>array("val"=>true,
								   "table"=>"tbl_dress_size",
									"fields"=>"*",
									"where"=>"isActive='y'",
									"orderBy"=>"id",
									"valField"=>"id",
									"dispField"=>"dressSize"						
							)
			
						)).'
                    <div class="clearfix"></div>
                    '.$this->fields->textarea(array("label"=>"".MEND_SIGN."About Me: ".getHelpImg(ENTER_ADDR),"name"=>"aboutMe","class"=>"","value"=>$this->aboutMe)).'
					 <div class="clearfix"></div>
					<label>Profile Picture</label>
                    <div class="pro_pic">
                    	<div class="pro_image"></div>
						<div id="" class="">
						  <input value="Upload Image" class="btn_glossy" type="button" name="upload" id="upload" />
						  <div class="flclear"></div>
						  <span id="status" >Only .jpg or .png files are allowed</span>
					   </div>
						<input type="hidden" id="x" name="x" />
						<input type="hidden" id="y" name="y" />
						<input type="hidden" id="w" name="w" />
						<input type="hidden" id="h" name="h" />
						<input type="hidden" name="profile_pic" id="profile_pic" />

                    </div>
                    <div class="clearfix"></div>
                  <div class="save_profile">
                  	<div class="cancel_lft">
                    	<a href="#">Cancel</a>
                    </div>
                    <div class="join_btn">
                		<input type="submit" id="submitProfile" name="submitProfile" value="save profile" />
               		</div>
                  </div>  
	            </form>';  		
		return $content; 
	}
	
	public function changePassword() {
		global $sessUserId;
		$oPassword = isset($this->objPost->oPassword) ? $this->objPost->oPassword : '';
		$nPassword = isset($this->objPost->nPassword) ? $this->objPost->nPassword : '';
		$cPassword = isset($this->objPost->cPassword) ? $this->objPost->cPassword : '';
		
		$qrysel = $this->db->select($this->table,"password","uId=".$sessUserId."");
		$fetchUser = mysql_fetch_object($qrysel);
		$passvalue = $fetchUser->password;
	    $content = '';
        $content .= '<form action="'.$_SERVER['PHP_SELF'].'" name="frmchp" id="frmchp" method="post" class="field_aria">
					'.$this->fields->hidden(array("name"=>"passvalue","value"=>$passvalue,"id"=>"passvalue")).'
                	'.$this->fields->password(array("label"=>MEND_SIGN."Old Password: ".getHelpImg(ENTER_OLD_PWD),"name"=>"oPassword","class"=>"","value"=>"")).'
                    <div class="clearfix"></div>
                    '.$this->fields->password(array("label"=>MEND_SIGN."New Password: ".getHelpImg(ENTER_NEW_PWD),"name"=>"nPassword","class"=>"","value"=>"")).'
                    <div class="clearfix"></div>
                    '.$this->fields->password(array("label"=>MEND_SIGN."Confirm Password: ".getHelpImg(ENTER_NEW_CPWD),"name"=>"cPassword","class"=>"","value"=>"")).'
                    <div class="clearfix"></div>
                   	<div class="save_profile">
                  	<div class="cancel_lft">
                    	<a href="'.SITE_URL.'home">Cancel</a>
                    </div>
					<div class="join_btn">
                		<input type="submit" name="submitChange" id="submitChange" value="Save Password">
					</div>
                  </div>  
	            </form>';  		
		return $content; 
	}
	
	public function changePassProcedure() {
		global $sessUserId;
		$oPassword = isset($this->objPost->oPassword) ? $this->objPost->oPassword : '';
		$nPassword = isset($this->objPost->nPassword) ? $this->objPost->nPassword : '';
		$cPassword = isset($this->objPost->cPassword) ? $this->objPost->cPassword : '';

		$qrysel = $this->db->select($this->table,"password","uId=".$sessUserId);
		$fetchUser = mysql_fetch_array($qrysel);
		if($fetchUser["password"] != md5($oPassword)) {

			return 'wrongPass';
		}
		else if($nPassword != $cPassword) {
			return 'passNotmatch';
		}
		else {
			$value = new stdClass();
			$value->password = md5($cPassword);
			$value->ipAddress = $_SERVER["REMOTE_ADDR"];
			$qryUpd = $this->db->update($this->table, $value, "uId=".$sessUserId."", '');
			
			return 'succChangePass';			
		}
	}

}
?>