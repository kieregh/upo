<?php
class multireddit{
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module, $id=0,$objPost=NULL,$redditId) {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->id = $id;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
		$this->redditId = $redditId;
		$this->table = "tbl_multireddit";
	}
	public function multiRedditContent($pageNo) {
		$content = NULL;
		$content = '<div class="main_wrapper mld_section">
							<div class="mdl_lft_column">

								<div class="adv_banner_1"></div>

								';

							if($this->sessUserId > 0)
							{
								$content.='<div class="clear"></div>
								<div class="host_left_tabs">
									<ul>';
								$sqlGetReddit = $this->db->select("tbl_multireddit","multiRedditName,id","userId='".$this->sessUserId."'",'','',0);
								$content.='<li>채널모음</li>';
								while($fetchRes = mysql_fetch_array($sqlGetReddit))
								{
								$content.='	<li><a href="'.SITE_URL.'me/'.$fetchRes["id"].'/'.$fetchRes['multiRedditName'].'/" >'.$fetchRes['multiRedditName'].'</a></li>';

								}
								$content.='</ul>
  									'.$this->fields->button(array("name"=>"createRedditbtn","type"=>"submit","value"=>"Create","extraAtt"=>'onClick="displayForm();"')).'
									<form name="multiRedditForm" id="multiRedditForm" method="post" action="'.SITE_URL.'me/">
	                                '.$this->fields->textBox(array("onlyField"=>true,"name"=>"multiRedditName","id"=>"multiRedditName","extraAtt"=>'style="display:none"')).'
   									'.$this->fields->button(array("name"=>"submitReddit","type"=>"submit","value"=>"Add","extraAtt"=>'style="display:none"')).'
									</form></div>';
							}
						$classOfListing = ($this->sessUserId > 0)?'<div class="hot_listing hot_page_section">':'<div class="hot_listing">';
						$content.=$classOfListing.'
									<ul id="container">
										'.$this->postlisting($pageNo).'
									</ul>
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
			$CATcond =($unSubStr!='')?'AND A.catId NOT IN('.$unSubStr.')':'';
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
		$whereCondition = $hidecond.$CATcond;
		$cat  =  getTableValue($this->db, 'tbl_multireddit', 'id='.$this->redditId, 'catId');
		$categoryCondition = ($cat!="")?" AND A.catId IN (".$cat.") ":'AND A.catId=""';
		$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$categoryCondition.$whereCondition." GROUP BY A.id ORDER BY totalVotes DESC";
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
		return $content;
	}
	public function addMultiReddit($objPost){
			$objPost->userId 	= $this->sessUserId;
			$objPost->multiRedditName = $this->db->filtering($objPost->multiRedditName, 'input', 'string', NULL);
			$objPost->createdDate = date('Y-m-d h:i:s');
			$this->db->insert($this->table,$objPost);
			//$this->db->insert("tbl_categories",$objPost);
			$id = mysql_insert_id();
			$_SESSION["msgType"] = array('type'=>'suc','var'=>'recAdded');
			redirectPage(SITE_URL.'me/'.$id.'/'.$objPost->multiRedditName.'/');

	}
	public function categoryNameListing()
	{
		$content = NULL;
		$qrycatId = $this->db->select("tbl_multireddit","catId","userId='".$this->sessUserId."' and id='".$this->redditId."'","","",0);
		$fetchRes = mysql_fetch_assoc($qrycatId);
		$catIdstr = $fetchRes['catId'];
		$catIdArr = explode(',',$catIdstr);
		if($catIdstr!="")
		{
			for($i=0;$i<count($catIdArr);$i++)
			{
			$content.='	<li><a href="#">r/'.getCategoryName($catIdArr[$i]).'</a><a href="javascript:void(0)" onClick="deleteMultiRedditCategory(\''.$this->redditId.'\',\''.$catIdArr[$i].'\')" class="closit">X</a></li>';
			}
		}
		return $content;
	}
	public function multiredditRightPanel()
	{
			$content = NULL;
			$qrycat = $this->db->select("tbl_multireddit","catId,redditDesc","userId='".$_SESSION['sessUserId']."' and id='".$_GET['redditId']."'","","",0);
			$rescat = mysql_fetch_assoc($qrycat);

			$content.='
				<div class="search_fill">
					<!--<label><input type="radio" name="visibility" value="private">Private</label>
					<label><input type="radio" name="visibility" value="public">Public</label>-->
					<div class="edit_desc"><a href="javascript:void(0);" onClick="displayDescBlock();">Edit Description</a></div>
					<div class="delete"><a href="javascript:void(0)" onClick="deleteReddit(\''.$_GET['redditId'].'\')" >Delete</a></div>

					<div class="displayDesc clearfix">'.$rescat['redditDesc'].'</div>
					<div id="editDescriptionBox" class="editDescriptionBox">
					<form name="editDesc" id="editDesc" method="post" action="" class="fieldArea">
					'.$this->fields->textArea(array("name"=>"redditDesc","value"=>"","onlyField"=>true)).'
					<div class="spacer10"></div>'.
					$this->fields->hidden(array("label"=>NULL,"name"=>'redditId',"class"=>"","value"=>$this->redditId)).
					$this->fields->button((array("name"=>"submitDesc","value"=>"add","type"=>"button","extraAtt"=>"onclick=\"SubmitDescForm();\""))).
					$this->fields->button((array("name"=>"cancel","value"=>"cancel","type"=>"button","extraAtt"=>"onclick=\"displayDescBlock();\""))).'
					</form>
					</div>

					';
					$catStr = $rescat["catId"];
					$catArr = explode(',',$catStr);

		 $content.='<div class="multi">'.(($catStr=="")?'0':count($catArr)).'개의 채널을 모음:</div>
					<ul class="multi_section" id="multi_section_category">';
					if($catStr!="")
					{
					for($i=0;$i<count($catArr);$i++)
					{

			$content.='	<li><a href="../../../c/'.$catArr[$i].'/'.getCategoryName($catArr[$i]).'/">r/'.getCategoryName($catArr[$i]).'</a><a href="javascript:void(0)" onClick="deleteMultiRedditCategory(\''.$_GET['redditId'].'\',\''.$catArr[$i].'\')" class="closit">X</a></li>';
					}
					}
			$content.='<div id="listingOfCatlink"></div>
					</ul>
							<form id="addredditcat" name="addredditcat" method="post" action="">
								<input type="text" id="tags" name="categoryAddName">
								<input type="hidden" id="redditId" value="'.$_GET['redditId'].'">
								<input type="button" id="find" name="addCat" value="+" onclick="addSubraditCat();">
								<div id="addredditCatErr" class="addredditCatErr"></div>
							</form>
				</div>
			';

		return $content;
	}
}
?>