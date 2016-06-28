<?php
class addPost {
	protected $db;
	public $password;

	
	function __construct($objPost=NULL, $uId,$lId=1,$cat='') {
		global $db,$fields,$sessUserId;
		
		$this->db = $db;
		$this->id=$uId;
		$this->fields = $fields;
		$this->lId = $lId;
		$this->objPost = $objPost;
		$this->table = 'tbl_post';
		$this->cat = $cat;
		
	}
	public function getForm() {
		
		global $sessUserId;
		$content=NULL;
		$content.='<div class="submit_new_post">
            	<form method="post" name="frmpost" id="frmpost" enctype="multipart/form-data">
                	<div class="post_box">
                    		'.$this->fields->textBox(array("label"=>"".MEND_SIGN.TITLE.":","name"=>"title")).'
                    </div>
                    <div class="post_box">
                    		'.$this->fields->textArea(array("label"=>"".MEND_SIGN.TEXT.":","name"=>"description")).'
                    </div>
					<div class="post_box">
							'.$this->fields->fileBox(array("label"=>"".SELECTIMG.":","name"=>"postImage","class"=>"")).'
							<div class="clearfix textaligencenter"><em><strong>OR</strong></em></div><div class="spacer15"></div>'.$this->fields->textBox(array("label"=>"Image Url:","name"=>"postImageUrl")).'
                    </div>
					<div class="post_box">
                    		'.$this->fields->textArea(array("label"=>"Embedd Code:","name"=>"embeddcode")).'
                    </div>
                    <div class="post_box">
						<div class="ui-widget">
						  <label for="tags">'.MEND_SIGN.CATEGORY.':</label>
						  <input id="tags" type="text" name="categoryName" onblur="changeText();" value="'.$this->cat.'">
						</div>
						<div id="catText"></div>
                    </div>
                    <div class="post_box">
                    		<label>'.MEND_SIGN.CAPTCHA.':</label>
					<div>
					
						
					
				<span class="cpt">
					<img id="imgCaptcha" src="'.SITE_INC.'capcha/random.php" height="25" alt="Captcha Code" border="0" class="fl"/>
					<a href="javascript:void(0)" onclick="document.getElementById(\'imgCaptcha\').src=\''.SITE_INC.'capcha/random.php?\'+Math.random();$(\'#captcha\').focus();$(\'#captcha\').val(\'\');" id="change-image" ><img id="changeCaptcha" src="'.SITE_IMG.'captcha-ref.jpg" alt="Captcha Refresh" border="0" class="fl" /></a>
				</span>
				<div class="captch_box">
				<input name="captcha" id="captcha" type="text" class="sizeone" autocomplete="off">
				</div>
			</div>
                    </div>
                    <div class="post_box post_submit">
						'.$this->fields->hidden(array("name"=>"submitPost","value"=>"".ADDPOST.""))
						.$this->fields->button(array("name"=>"submitPost","type"=>"submit","value"=>"".ADDPOST."")).'
                    </div>
                </form>
            </div>';
		return $content; 
	}
	public function getFromLink(){
		global $sessUserId;
		$content=NULL;
		$content.='<div class="submit_new_post">
            	<form method="post" name="frmlink" enctype="multipart/form-data" id="frmlink">
                	<div class="post_box">
                    	'.$this->fields->textBox(array("label"=>"".MEND_SIGN.TITLE.":","name"=>"title")).'
                    </div>
                    <div class="post_box">
                    	'.$this->fields->textBox(array("label"=>"".MEND_SIGN.URL.":","name"=>"url","class"=>"error_label")).'
                    </div>
                    <div class="post_box">
						'.$this->fields->fileBox(array("label"=>"".SELECTIMG.":","name"=>"postImage","class"=>"")).'
						<div class="clearfix textaligencenter"><em><strong>OR</strong></em></div><div class="spacer15"></div>'.$this->fields->textBox(array("label"=>"Image Url:","name"=>"postImageUrl")).'
                    </div>
					<div class="post_box">
                    		'.$this->fields->textArea(array("label"=>"Embedd Code:","name"=>"embeddcode")).'
                    </div>
                    <div class="post_box">
						<div class="ui-widget">
						  <label for="tags"><font color="#FF0000">*</font>'.CATEGORY.':</label>
						  <input id="tags" type="text" name="categoryName" onblur="changeText();" value="'.$this->cat.'">
						</div>
						<div id="catText"></div>
                    </div>
                    <div class="post_box">
                    		<label>'.MEND_SIGN.CAPTCHA.':</label>
							<div>
								<span class="cpt">
									<img id="imgCaptcha" src="'.SITE_INC.'capcha/random.php" height="25" alt="Captcha Code" border="0" class="fl"/>
									<a href="javascript:void(0)" onclick="document.getElementById(\'imgCaptcha\').src=\''.SITE_INC.'capcha/random.php?\'+Math.random();$(\'#captcha\').focus();$(\'#captcha\').val(\'\');" id="change-image" ><img id="changeCaptcha" src="'.SITE_IMG.'captcha-ref.jpg" alt="Captcha Refresh" border="0" class="fl" /></a>
								</span>
								<div class="captch_box">
								<input name="captcha" id="captcha" type="text" class="sizeone" autocomplete="off">
								</div>
							</div>
                    </div>
                    <div class="post_box post_submit">
						'.$this->fields->hidden(array("name"=>"submitLink","value"=>"".ADDLINK."")).'
						'.$this->fields->button(array("name"=>"submitLink","type"=>"submit","value"=>"".ADDLINK."")).'
                    </div>
                </form>
            </div>'; 		
		return $content;
	}
	public function submitProcedure() {
		global $sessUserId;
		$title = isset($this->objPost->title) ? $this->objPost->title : '';
		$description= isset($this->objPost->description) ? $this->objPost->description : '';
		$qrysel = $this->db->select($this->table,"title","description","uId=".$sessUserId);
		$fetchUser = mysql_fetch_array($qrysel);
	}

}
?>