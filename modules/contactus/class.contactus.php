<?php
class Contactus{

	public $name;
	public $phone;
	public $email;
	public $comment;
	public $module;
	protected $fields;

	function __construct($module,$objPost=NULL) {
		global $db, $fields;
		$this->db = $db;
		$this->module = $module;
		$this->fields = $fields;
		$this->objPost = $objPost;
		$this->table='tbl_contactus';

		$this->mascot = $this->db->filtering(getMascot(8),'output','string','ucwords');

		if(isset($objPost->name) && $objPost->name != "" && $objPost->email!='') {
				$this->name = $this->db->filtering($objPost->name,'output','string','');
				$this->phone = $this->db->filtering($objPost->phone,'output','string','');
				$this->email = base64_decode($this->db->filtering($objPost->email,'output','string',''));
				$this->comment = $this->db->filtering($objPost->comment,'output','string','');
		}
	}
	public function getForm()
	{
		$content='';
		$content.='
    	<section class="monkeyimg"><img src="'.SITE_IMG.'monkey-img.png" alt=" " /></section>
    	<section class="boxwhite">';
		if($this->mascot!="")
		{
			$content .= '<div class="commentbox-about">
            	'.$this->mascot.'</div>';

		}

		$content .= '<h2>Contact Us</h2>';

		if($this->mascot!=""){$content .='<p class="space30"></p>';}

        $content.='<form action="'.$_SERVER["PHP_SELF"].'" name="frmContact" id="frmContact" method="post" class="formfield">'.
				$this->fields->label(array("label"=>"Name: ".MEND_SIGN))."<br/>".
				$this->fields->textBox(array("name"=>"name","class"=>"midwidth fl","value"=>$this->name,"onlyField"=>true)).
                    '<div class="space10 flclear"></div>'.
				$this->fields->label(array("label"=>"Phone: "))."<br/>".
                $this->fields->textBox(array("name"=>"phone","class"=>"midwidth fl","value"=>$this->phone,"onlyField"=>true,"extraAtt"=>"maxlength=20")).
                    '<div class="space10 flclear"></div>'.
					$this->fields->label(array("label"=>"Email: ".MEND_SIGN))."<br/>".
                $this->fields->textBox(array("name"=>"email","class"=>"midwidth fl","value"=>$this->email,"onlyField"=>true)).
				 '<div class="space10 flclear"></div>'.
				 $this->fields->label(array("label"=>"Questions / Comments: "))."<br/>".
				$this->fields->textArea(array("label"=>"Questions / Comments: ","name"=>"comment","class"=>"textareaheight textareawidth","value"=>$this->comment,"onlyField"=>true,"extraAtt"=>"cols='' rows=''")).

				'<div class="space25 flclear"></div>
				<div class="buttonSection">
				<div class="fl">'.$this->fields->button(array("name"=>"submitAddForm", "type"=>"submit", "class"=>"width200 aligncenter", "value"=>"Submit", "extraAtt"=>"")).'</div><div class="flclear"></div>
					</div></form>


        </section>';
		return $content;
	}

}

?>