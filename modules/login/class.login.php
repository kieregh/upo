<?php
class Login {

	protected $db;
	public $module;

	function __construct($module, $objPost=NULL) {
		global $db, $fields;

		$this->db = $db;
		$this->objPost = $objPost;
		$this->fields = $fields;
		$this->module = $module;
		$this->table = 'tbl_users';
	}
	public function loginSubmit() {

		$qrysel = $this->db->select($this->table,"id,email,username,password,isActive","email='".($this->objPost->email)."' OR username='".base64_decode($this->objPost->email)."' AND password='".md5($this->objPost->password)."' ", "", "", 0);
		if(mysql_num_rows($qrysel) > 0) {
			$fetchUser = mysql_fetch_assoc($qrysel);

			$id 		= $this->db->filtering($fetchUser["id"], 'output', 'int', NULL);
			$isActive 	= $this->db->filtering($fetchUser["isActive"], 'output', 'string', NULL);
			$email 		= $this->db->filtering($fetchUser["email"], 'output', 'string', NULL);
			$password 	= $this->db->filtering($fetchUser["password"], 'output', 'string', NULL);
			$username 	= $this->db->filtering($fetchUser["username"], 'output', 'string', NULL);


			//activationCode = $this->db->filtering($fetchUser["activationCode"], 'output', 'string', NULL);
			//activationCode = $this->db->filtering();

			if($password == md5($this->objPost->password) ) {
				if($isActive == 'y') {
					if($this->objPost->remember == 'y') {
							setcookie("email",base64_decode($this->objPost->email), time()+3600,'/');
							setcookie("password", base64_encode($this->objPost->password), time()+3600,'/');
					}
					else
					{
						setcookie("email",base64_decode($this->objPost->email), time()-3600,'/');
						setcookie("password", base64_encode($this->objPost->password), time()-3600,'/');
					}
					$_SESSION["sessUserId"] = intval($id);
					$_SESSION["sessUsername"] = $username;
					$_SESSION["msgType"] = array('type'=>'suc','var' =>''.LOGINSUCCESS.'');
					redirectPage(SITE_URL);
				}
				elseif($isActive == 'n') {
					$_SESSION["msgType"] = array('type'=>'err','var' =>'unapprovedUser');
					redirectPage(SITE_URL.'login');
				}
				elseif($isActive == 'r') {
					$_SESSION["msgType"] = array('type'=>'err','var' =>''.USERDELETE.'');
					redirectPage(SITE_URL.'login');
				}
			}
			else {
				return 'invaildUsers';
			}
		}
		else {
				return 'invaildUsers';
			}
	}
	public function getForm() {
		$content = '';

		$cookieEmail = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
		$cookiePass = isset($_COOKIE['password']) ? base64_decode($_COOKIE['password']) : '';
		$checked = ($cookieEmail != "" && $cookiePass != "") ? 'checked="checked"' : 'n';

		$email = isset($this->objPost->email) ? $this->db->filtering(base64_decode($this->objPost->email),'output','string','') : $cookieEmail;
		$password = isset($this->objPost->password) ? $this->db->filtering($this->objPost->password,'output','string','') : $cookiePass;

		//$remember = isset($this->objPost->remember) ? $this->objPost->remember : '';
		$remember = isset($this->objPost->remember) ? $this->objPost->remember : $checked;


		$content.='<div class="main_wrapper mld_section">

        <div id="wrapper_inner">
                        <div id="login" class="animate form">
                            <form  action="" autocomplete="on" method="post" name="Loginform">
                                <h1>로그인</h1>
                                <p>
                                    <label for="username" class="uname" data-icon="u" > '.EMAILPASSWORD.' :</label>
                                    '.$this->fields->textBox(array("onlyField"=>true,"name"=>"email","id"=>"username","extraAtt"=>'placeholder="mymail@mail.com"','value'=>"$email")).'
								</p>
                                <p>
                                    <label for="password" class="youpasswd" data-icon="p"> '.YOURPASSWORD.' :</label>
                                    '.$this->fields->password(array("onlyField"=>true,"name"=>"password","id"=>"password","extraAtt"=>'placeholder="eg. X8df!90EO"','value'=>"$password")).'
								</p>

									<label class="remember">
									<input type="checkbox" name="remember" id="remember" '.$checked.' value="y"/> '.STAYSIGNIN.'</label>
									&nbsp;&nbsp;&nbsp;&nbsp;<label class="forgot"><a href="'.SITE_URL.'forgot" title="'.FORGOTPASSWORD.'">'.FORGOTPASSWORD.'</a></label>
                                <p class="login button">
									'.$this->fields->button(array("name"=>"submitLogin","type"=>"submit","value"=>"Login")).'
								</p>
                                <p class="change_link">
									'.NOTMEMBERYET.'
									<a href="'.SITE_URL.'signup" class="to_register">'.JOINUS.'</a></p>
                            </form>
                        </div>
                    </div>
    </div>';
		return $content;
	}
}
?>