<?php
class top {
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module, $id=0,$searchType=NULL) {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->id = $id;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
		$this->searchType = $searchType;
	}
	public function categoryContent($pageNo) {
		$content = NULL;
		$content='<div class="">
    	<div class="">
        	<div class="adv_banner_1"></div>
				<label for="srchType" class="filterresults">Filter Results By:</label>
				<select name="searchType" onChange="return searchType(this.value);" class="filt">
					<option value="">All</option>
					<option value="hour/" '.($this->searchType=="hour"?"selected":"").' >This Hour</option>
					<option value="day/" '.($this->searchType=="day"?"selected":"").'>This Day</option>
					<option value="week/" '.($this->searchType=="week"?"selected":"").'>This Week</option>
					<option value="month/" '.($this->searchType=="month"?"selected":"").'>This Month</option>
					<option value="year/" '.($this->searchType=="year"?"selected":"").'>This Year</option>
				</select>
			 <div class="hot_listing">
				<ul id="container">'.$this->postlisting($pageNo).'
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
		$searchCond = '';
		if($this->searchType=="hour")
		{
			$searchCond	=" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";
		}
		else if($this->searchType=="day")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 24 HOUR)";
		}
		else if($this->searchType=="week")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 168 HOUR)";
		}
		else if($this->searchType=="month")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 720 HOUR)";
		}
		else if($this->searchType=="year")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 9760 HOUR)";
		}
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
		$whereCondition = $hidecond.$CATcond.$searchCond;
		$selRes="SELECT tbl_post.*,tbl_categories.categoryName,tbl_users.username,B.voteType,tbl_comment.createdDate as cDate, COUNT(B.id) AS totalVotes,SUM(IF(B.voteType='u',1,0)) as avgVotes FROM tbl_post INNER JOIN tbl_categories ON(tbl_categories.id = tbl_post.catId) LEFT JOIN tbl_comment ON (tbl_post.id=tbl_comment.postid) INNER JOIN tbl_users ON(tbl_users.id = tbl_post.uid) LEFT JOIN `tbl_votes` AS B ON tbl_post.id=B.refId where tbl_post.isActive='y' ".$whereCondition." GROUP BY tbl_post.id ORDER BY avgVotes DESC";

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
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
		}
}
?>