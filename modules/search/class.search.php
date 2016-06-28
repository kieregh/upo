<?php
class addPost {
	protected $db;
	public $password;

	
	function __construct($objPost=NULL, $uId) {
		global $db,$fields,$sessUserId;
		
		$this->db = $db;
		$this->id=$uId;
		$this->fields = $fields;
		$this->objPost = $objPost;
		$this->table = 'tbl_post';
		$this->sessUserId = $sessUserId;
	}
	
	function getSearchResult($type=NULL) {
		global $sessUserId;
		$content=NULL;
		$search_qry = NULL;
		
		$CATcond='';
		$hidecond='';

		//echo $type;
		
		if($type == NULL) {
			$search_qry = "SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u'  where A.isActive='y' AND A.title LIKE '%".$this->objPost->search."%' OR tbl_users.username LIKE '%".$this->objPost->search."%' OR tbl_categories.categoryName LIKE '%".$this->objPost->search."%' GROUP BY A.id ORDER BY totalVotes DESC";
			
			/*"select * from tbl_post as p 
							join tbl_users as u 
							on u.id = p.uid
							join tbl_categories c
							on c.id = p.catId
							WHERE p.title like '%".$this->objPost->search."%'
							OR u.username like '%".$this->objPost->search."%'
							OR c.categoryName LIKE '%".$this->objPost->search."%'";*/
		}
		else {
			$search_qry = "SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u'  where A.isActive='y'";
			
			/*"select * 
							from tbl_post p
							join tbl_categories c 
							on p.catId = c.id
							join tbl_users as u 
							on u.id = p.uid";
				*/			
			switch($this->objPost->cat_nm){
				case 'category'			:	$search_qry .= " AND tbl_categories.categoryName like '%".$this->objPost->search_name."%'";
											break;
				
				case 'title'			:	$search_qry .= " AND A.title like '%".$this->objPost->search_name."%'";
											break;
				
				case 'text'				:	$search_qry .= " AND A.description like '%".$this->objPost->search_name."%'";
											break;
				
				case 'author'			:	$search_qry .= " AND tbl_users.username like '%".$this->objPost->search_name."%'";
											break;
				
				case 'site_name'		:	$search_qry .= " AND A.url like '%".$this->objPost->search_name."%'";
											break;
				
			}
			$search_qry .= " GROUP BY A.id ORDER BY totalVotes DESC";
		}
		
		if($search_qry != NULL) {
			
			$qrysel = $this->db->query($search_qry);
			
			$content .= '<div class="main_wrapper mld_section">
								<div class="mdl_lft_column">
									<div class="adv_banner_1"></div>
										<div class="adv_srch">
											<form action="" method="post" class="fieldArea" name="searchfrm" id="searchfrm">
											<div class="search_form">
													<div class="search_nm">
														'.$this->fields->textBox(array("label"=>"Keyword:","name"=>"search_name","extraAtt"=>'placeholder="Search"', "value"=>"")).'
													</div>
													<div class="search_nm">'.
														$this->fields->selectBox(array(
														"label"=>"Type:",
														"name"=>"cat_nm",
														"defaultValue"=>false,
														"allow_null"=>1,
														"class"=>"",
														"choices"=>array("category"=>"Category","title"=>"Title","text"=>"Text","author"=>"Author","site_name"=>"Link"),
														"multiple"=>false,
														"optgroup"=>false,
												))
													.'</div>
													<div class="search_btn">
													'.$this->fields->button(array("onlyField"=>true,"name"=>"btn_search_cat","type"=>"submit","value"=>"Search")).'
														
													</div>
													</div>
											</form>
											
										</div>
									
									<div class="hot_listing">
										<ul id="container">';
			if(mysql_num_rows($qrysel) > 0) {
				while($fetchValues = mysql_fetch_assoc($qrysel)) {
					$content .= postlisthtml($fetchValues);
				}		
			}
			else {
				$content .= RNF;
			}
						$content .= '</ul>
									</div>
								</div>
							</div>';
		}
		return $content;
	}
}
?>