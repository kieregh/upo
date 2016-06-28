<?php
class MembershipPlan {
	protected $db;
	public $password;


	function __construct($module,$objPost=NULL, $paypal) {
		global $db,$fields,$sessUserId;

		$this->db = $db;
		$this->id=$sessUserId;
		$this->sessUserId = $sessUserId;

		$this->fields = $fields;
		$this->objPost = $objPost;
		$this->table = 'tbl_post';
		$this->paypal=$paypal;
	}
	public function membershipPlan() {

		global $sessUserId;

		if ( isset($this->sessUserId) && $this->sessUserId > 0 ) {
						$islogin = "yes";
					} else {
						$islogin = 'no';
					}

		$content=NULL;

		$selplan = $this->db->select("tbl_memberplan","*","isActive='y'",'','',0);
		$totalRows = mysql_num_rows($selplan);
		if($totalRows>0) {
				while($fetchValues = mysql_fetch_assoc($selplan)) {
					$PlanId	= $this->db->filtering($fetchValues["id"], 'output', 'int', '');
					$PlanName= $this->db->filtering($fetchValues["name"], 'output', 'string', '');
					$description= $this->db->filtering($fetchValues["description"], 'output', 'string', '');
					$price= $this->db->filtering($fetchValues["price"], 'output', 'string', '');
				}
		}
		else{
			 $PlanName='';
			 $description='';
			 $price='';
			}
		if($totalRows>0) {
			$content.='<form  action="" name="frmMem" id="frmMem" method="post">
						<div id="fdw-pricing-table">
						<div class="plan plan1">
									<div class="header">'.$PlanName.'</div>
									<div class="price">$'.$price.'</div>
									<div class="monthly">per month</div>
								<ul>
									<li>'.$description.'</li>

								</ul>

								<a id="take_plan" class="signup" href="'.SITE_MOD.'membership/expresscheckout.php">'.UPGRADE.'</a>

						</div>
					</div>
					</form>'; 		}
					else {
						$content.='<p>There is no any membership plan available now.</p>';
					}

		return $content;
	}


	function takeMemberShip() {
		//$this->db->query("UPDATE tbl_users SET isMember = 'y' WHERE id = '".$this->sessUserId."'");
	}

}
?>