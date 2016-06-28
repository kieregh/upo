<?php
class category {
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module, $id=0) {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->id = $id;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
	}
	public function categoryContent($pageNo) {
		$content = NULL;
		$content='<div class="">
    	<div class="">
        	<div class="adv_banner_1"></div>
			 <div class="hot_listing">
				<ul id="container" >'.$this->postlisting($pageNo).'
			   </ul>
			</div>
		   </div>
    </div>';
		return $content;
	}
	public function postlisting($pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND tbl_post.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {

				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {

						$hidecond .= " AND tbl_post.id !='".$HiddenItem["refId"]."'";

				}
			}
			else {
				$hidecond='';

			}
		}
		$whereCondition = $hidecond.$CATcond;
		$selRes="SELECT tbl_post.*,tbl_categories.categoryName,tbl_users.username FROM tbl_post
		INNER JOIN tbl_categories ON(tbl_categories.id = tbl_post.catId)
		INNER JOIN tbl_users ON(tbl_users.id = tbl_post.uid)
		where tbl_post.isActive='y' ".$whereCondition." ORDER BY tbl_post.createdDate DESC";

		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  = $pager->page;
			$selRes = $selRes." limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
		}
		return $content;
		}
}
?>