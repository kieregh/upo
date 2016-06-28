<?php
class user {
	protected $db;
	public $password;
	function __construct($objPost=NULL, $id=0) {
		global $db,$fields,$sessUserId, $sessUsername, $type;
		
		$this->db = $db;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->objPost = $objPost;
		$this->id = $id;
		$this->type = $type;
		$this->table = 'tbl_post';
		$content = $this->userHeader();
	}
	public function userHeader(){
		$content=NULL;
		$content.='';
		return $content;		  
	}
	public function postlistingsubmitted($pageNo){
		
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
												
		$CMTSQL=$this->db->select("tbl_post","id","uid='$this->sessUserId'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["id"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				}
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
	}
	
	public function postlistinghistory($pageNo){
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="fr"><a href="javascript:void(0);" onclick="deletehistory()">Clear History</a></div>';
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_history","postId","userId='$this->sessUserId'","","",0);
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["postId"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				}
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
	}
	
    public function postlistingoverview($pageNo,$userID){
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
       				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$userID' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$submitqry=$this->db->select("tbl_post","id","uid='$userID'","","",0);
		
		while($CMTres = mysql_fetch_assoc($submitqry))
		{
			$pIdArr[]=$CMTres["id"];
		}
		
		$commentqry=$this->db->select("tbl_comment","postid","uId='$userID'","","postid");
		
		while($CMTres = mysql_fetch_assoc($commentqry))
		{
			$pIdArr[]=$CMTres["postid"];
		}
		
		$likeqry=$this->db->select("tbl_votes","refId","uId='$userID' AND voteType='u'","","",0);
		
		while($CMTres = mysql_fetch_assoc($likeqry))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		
		$saveqry=$this->db->select("tbl_save","refId","uId='$userID'","","",0);
		
		while($CMTres = mysql_fetch_assoc($saveqry))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		
		//print_r($pIdArr);
		
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			
			$whereCondition = $hidecond;
			$selRes="SELECT tbl_categories.categoryName, tbl_users.username, A.* FROM tbl_post A INNER JOIN tbl_users ON (A.uid = tbl_users.id) INNER JOIN tbl_categories ON (A.catId = tbl_categories.id) LEFT JOIN tbl_votes ON (tbl_votes.refId = A.id) LEFT JOIN tbl_comment ON (tbl_comment.postId = A.id) LEFT JOIN tbl_save ON (tbl_save.refId = A.id) WHERE ((tbl_save.uId ='".$userID."' OR tbl_votes.uId ='".$userID."' OR tbl_comment.uId ='".$userID."'  OR tbl_votes.refType ='p' OR tbl_comment.refType ='p'	OR A.uid='".$userID."') AND A.id IN (".$pIdArr.")) GROUP BY A.id DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				} 
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
	}	

	public function postlistinghidden($pageNo){
		
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_hide","refId","uId='$this->sessUserId'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = NULL;
			//$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				} 
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
			
	
	}
	
	public function postlistingliked($pageNo){
		
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_votes","refId","uId='$this->sessUserId' AND voteType='u'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				}
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
			
	}
	
	public function postlistingdisliked($pageNo){
			
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_votes","refId","uId='$this->sessUserId' AND voteType='d'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				} 
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
			
		
	}
	
	public function postlistingcomments($pageNo){
		
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_comment","postid","uId='$this->sessUserId'","","postid");
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["postid"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
			$qSelRes = $this->db->query($selRes	);	
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				} 
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
	}
	
	public function postlistingsaved($pageNo){
		
		$pIdArr = array();
		$content = NULL;
		$content .='<div class="hot_listing">
        				<ul id="container">';
		if($this->sessUserId >0) {
			$hidecond=NULL;
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		
		$CMTSQL=$this->db->select("tbl_save","refId","uId='$this->sessUserId'","","",0);
		
		while($CMTres = mysql_fetch_assoc($CMTSQL))
		{
			$pIdArr[]=$CMTres["refId"];
		}
		if(count($pIdArr)>0)
		{
			$pIdArr=implode(",",array_unique($pIdArr));
			//print($pIdArr);
			//$whereCondition = $hidecond;
			$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' AND A.id IN (".$pIdArr.") GROUP BY A.id ORDER BY totalVotes DESC";
			
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
					while($fetchValues = mysql_fetch_array($qSelRes)) {
						$content .= postlisthtml($fetchValues);
					}
				}
				else
				{
					$content .= "<li>".RNF."</li>";
				}
			}
		}
		else
		{
			$content .= "<li>".RNF."</li>";
		}
		$content .=  '</ul>
					</div>';
		return $content;
			
	}
}
?>