 <?php
class Registration
{
    private $id;
    protected $db;
    public $module;
    protected $fields;
    
    
    function __construct($db, $module, $id = 0, $objPost = NULL)
    {
        global $db, $fields;
        $this->db      = $db;
        $this->module  = $module;
        $this->fields  = $fields;
        $this->id      = $id;
        $this->objPost = $objPost;
		$this->table   = 'tbl_users';		
		 
        if (isset($objPost->username) && $objPost->email != '') {
            $this->name = $this->db->filtering($objPost->username, 'output', 'string', '');
			$this->email = base64_decode($this->db->filtering($objPost->email, 'output', 'string', ''));
			$this->password = $this->db->filtering($objPost->password, 'output', 'string', '');
		   }
    }
   
   public function getForm(){
		
		$content = NULL;
		$content .='  <div class="main_wrapper mld_section">
    	
        <div id="wrapper_inner">
                        <div id="login" class="animate form">
                            <form  action="" name="frmRegi" id="frmRegi" method="post"> 
                                <h1>'.REGISTRATION.'</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" >'.USERNAME.':</label>
                                    '.$this->fields->textBox(array("onlyField"=>true,"name"=>"username","extraAtt"=>'placeholder="영문 숫자만 가능"')).'
								</p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e"> '.YOUREMAIL.':</label>
                                    '.$this->fields->textBox(array("onlyField"=>true,"name"=>"email","id"=>"email","extraAtt"=>'placeholder="mymail@mail.com"')).'
								</p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p">'.YOURPASSWORD.':</label>
                                    '.$this->fields->password(array("onlyField"=>true,"name"=>"password","extraAtt"=>'placeholder="예) X8df!90EO"')).'
								</p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p">'.CONFIRMPASS.':</label>
                                    '.$this->fields->password(array("onlyField"=>true,"name"=>"cpassword","extraAtt"=>'placeholder="예) X8df!90EO"')).'
								</p>
								<p>
									<label for="Captcha" class="cap">'.CAPTCHA.':</label>
								</p>
									<span class="cpt">
							
					<img id="imgCaptcha" src="'.SITE_INC.'capcha/random.php" height="25" alt="Captcha Code" border="0" class="fl"/>
					<a href="javascript:void(0)" onclick="document.getElementById(\'imgCaptcha\').src=\''.SITE_INC.'capcha/random.php?\'+Math.random();$(\'#captcha\').focus();$(\'#captcha\').val(\'\');" id="change-image" ><img id="changeCaptcha" src="'.SITE_IMG.'captcha-ref.jpg" alt="Captcha Refresh" border="0" class="fl" /></a>
				</span><br /><br /><div class="clear"></div><label>&nbsp;</label>
				<input name="captcha" id="captcha" type="text" class="sizeone" autocomplete="off">
								
                                
                                <p class="login button"> 
								'.$this->fields->hidden(array("name"=>"submitAddForm","value"=>"".SIGNUP."")).
								$this->fields->button(array("name"=>"submitAddForm","type"=>"submit","class"=>"login button","value"=>"".SIGNUP."")).'
                                     
								</p>
                                <p class="change_link">
									'.ALREADYMEM.'
									<a href="'.SITE_URL.'login" class="to_register">'.LOGIN.'</a>								</p>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
    </div>';
		return $content;
	}
}
?>