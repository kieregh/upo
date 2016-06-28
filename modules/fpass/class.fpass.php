<?php
class Fpass {
	
	protected $db;
	protected $fields;
	public $email;
	public $fpCode;
	public function __construct($objPost=NULL) {
		global $db, $fields;
				
		$this->db = $db;
		$this->objPost = $objPost;
		$this->fields = $fields;		
	}
	public function getForm() {
		
		$content='';
		$content .='<div class="submit_new_post">
						<form method="post" name="frmFpass" id="frmFpass" action="">
							<h2>Reset Password</h2>
								<div class="post_box">
										'.$this->fields->textBox(array("label"=>"".EMAIL."".MEND_SIGN."","name"=>"email","extraAtt"=>'placeholder="Email"',"value"=>"")).'
								</div>
								<div class="post_box post_submit">
								<input type="hidden" name="submitPass">
										'.$this->fields->button(array("name"=>"submitPass","type"=>"submit","value"=>"Submit")).'
								</div>
						</form>
					</div>';
		return $content;	
	}
	
	public function forgotProdedure() {
		
			$email = isset($this->objPost->email) ? $this->objPost->email : '';
			$value = new stdClass();
			$qrysel = $this->db->select("tbl_users","id,email,name,isActive","email='".$email."'","","",0);
			if(mysql_num_rows($qrysel) > 0) {
					$fetchUser = mysql_fetch_array($qrysel);
					$email = base64_decode($this->db->filtering($fetchUser["email"], 'output', 'string', NULL));
				 	$isActive = $this->db->filtering($fetchUser["isActive"], 'output', 'string', NULL);
					//$activationCode = $this->db->filtering($fetchUser["activationCode"], 'output', 'string', NULL);
					if($isActive == 'n' && strlen($activationCode) == 10){
						return 'inactivatedUser';
					}
					else if($isActive == 'n') {
						return 'unapprovedUser';
					}
					else {
						$to = $email;
						$username = $fetchUser["name"];
						$id = (int)$fetchUser["id"];
						$greetings = $fetchUser["name"];
						$subject = "Forgot Password";
						$password = generateRandString();
						$value->password = md5($password);
						$this->db->update("tbl_users",$value, "id=".$id."","");
						$msgContent = '<p>We have reset your password as per your request.</p>
							<p>Email: '.$email.'</p>
							<p>Password: '.$password.'</p>
							<p><a href="'.SITE_MOD.'login/" title="Please click here to login">Please click here to login</a> with reseted password and change your password to something that is easier to remember.</p>';
						 $message = generateTemplates($greetings, REGARDS, $subject, $msgContent);
						
						sendEmailAddress($to, $subject, $message);
						return 'succForgotPass';
					}
				}
			else {
				return 'wrongEmail';
			}
		
	}
}

?>