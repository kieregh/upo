<?php
class comments {
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
		if(isset($this->sessUserId) && $this->sessUserId>0)
					{
						$this->islogin="yes";
					}
					else
					{
						$this->islogin='no';
					}
	}
	public function commentsContent($postId) {
		$content=NULL;
			$SqlPost = $this->db->query("SELECT tbl_post.id ,tbl_post.title,tbl_post.description,tbl_post.description,tbl_post.isimageUrl,tbl_post.embeddcode AS embeddcode,tbl_post.img,tbl_post.url,tbl_post.type, tbl_categories.categoryName,tbl_users.username,tbl_post.createdDate,tbl_post.isSponcer FROM  tbl_post
			INNER JOIN tbl_categories ON (tbl_categories.id = tbl_post.catId)
			INNER JOIN tbl_users ON (tbl_post.uid = tbl_users.id)
			 WHERE tbl_post.id='".$postId."'");
			 $Postres = mysql_fetch_assoc($SqlPost);
			 $content.=postlisthtml($Postres,$i=0);
			
			if($this->sessUserId>0)
			{
			$content.='<div class="commentbox">
					<form action="" method="post" class="fieldarea" name="commentboxfrm" id="commentboxfrm">
						'.$this->fields->textarea(array("name"=>"commentbox","onlyField"=>true,"extraAtt"=>'Placeholder="댓글을 입력하세요"')).
						$this->fields->hidden(array("name"=>"refType","value"=>"p")).
						$this->fields->hidden(array("name"=>"refId","value"=>$postId)).
						$this->fields->button(array("name"=>"postsubmit","type"=>"button","value"=>"등록","extraAtt"=>'onclick="postcomment();"')).'
					</form>
			</div>';
			}
			else {
				$content.= '<div class="flclear"></div>
				<p><a href="'.SITE_URL.'login">로그인</a> 후 포스트에 댓글을 등록할 수 있습니다. </p>';
			}
			$content.='<div id="allcomments">'.$this->getComment($postId).'</div>';
		return $content;
		}
		
public function getComment($postId,$withclass=true)
{
	$content=NULL;
	$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='c'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
						$hidecond[] = $HiddenItem["refId"];
				}
			}
			else {
				$hidecond[]='';
			}
	$SqlComments = $this->db->query("SELECT tbl_comment.*, tbl_users.username FROM tbl_comment LEFT JOIN tbl_users ON (tbl_comment.uId = tbl_users.id) WHERE (tbl_comment.refId ='$postId' AND tbl_comment.refType ='p') order by id DESC");
	$content.='';
	$content.='<div id="dialogBox"></div>';
	while($commentRow = mysql_fetch_assoc($SqlComments))
	{
		if(!in_array($commentRow["id"],$hidecond))
		{
		
		if ( isset($this->sessUserId) && $this->sessUserId > 0 ) {
						$islogin = "yes";
					} else {
						$islogin = 'no';
					}
		$currentTime = date("Y-m-d H:i:s");
		$commentid = $this->db->filtering($commentRow["id"],'output','int','');
		$comment  =  $this->db->filtering($commentRow["comment"],'output','text','');
		$username =	 $this->db->filtering($commentRow["username"],'output','string','');
		$commentDate = $commentRow["createdDate"];
		$dateDiff = get_time_difference($commentDate,$currentTime);
		$Subcomment = getsubComment($commentid,$postId);
		$CmtPoint = getCommentPoint($commentid);
		$total_vote = totalVote($commentid,'c');
		if($withclass==true)
		{
			$content.='<li class="box" id="li-post-'.$commentid.'">';
		}
		else
		{
			$content.='<li class="box">';
		}
		if($this->sessUserId>0)
		{
			$sqlVoteType = $this->db->select("tbl_votes","voteType","refType='p' and refId='$commentid' and uId='$this->sessUserId'",'','id desc limit 0,1',0);
			if(mysql_num_rows($sqlVoteType)>0)
			{
				$voteRes = mysql_fetch_assoc($sqlVoteType);
				if($voteRes["voteType"]=='d')
				{
					$downACt = 'iconact';
					$upACt = 'icon';
				}
				else
				{
					$upACt = 'iconact';
					$downACt='icon';
				}
			}
			else{
				$upACt='icon';
				$downACt='icon';
			}
		}
		else
		{
			$upACt='icon';
			$downACt='icon';
		}
 		$content.='<div class="comment_list">
				<div class="comment_list_one" id="comment-post-'.$commentid.'">
                	<div class="icons-wrapper">
                        <a href="javascript:void(0);" class="'.$upACt.' document up" id="up'.$commentid.'" onclick="voting(\''.$commentid.'\',\'u\',\'c\',\''.$this->islogin.'\',this.id)">UP</a>
                        <a href="javascript:void(0);" class="'.$downACt.' group down" id="down'.$commentid.'" onclick="voting(\''.$commentid.'\',\'d\',\'c\',\''.$this->islogin.'\',this.id)">DOWN</a>
						<a href="javascript:void(0);" onclick="showHideComment(\''.$commentid.'\');" class="mincomment">	
								<img src="'.SITE_IMG.'min.png" id="commentmin-'.$commentid.'"/>
						</a>
                    </div>
                    <div class="comment_list_details">
                    	<span><a href="../../user/'.$username.'/overview/">'.$username.' </a><span id="vote-'.$commentid.'"> '.$total_vote.'</span> points '.$dateDiff.'</span>
                        <p>'.$comment.'</p>
                        <div class="delete_report">
                        	<a href="javascript:void(0);" onclick="getReport(\''.$commentid.'\',\'c\',\''.$islogin.'\');">Report</a><span class="re-btn"><a href="javascript:void(0);" onclick="generatesubcomment(\''.$commentid.'\',\''.$postId.'\',\''.$this->islogin.'\');">Reply</a></span>
							<div id="subcommentformbox'.$commentid.'"></div>
                        </div>
                    </div>
					<div id="subcmtbox'.$commentid.'"></div>	
					'.$Subcomment.'
                </div>
				
				</div></li>';
		}
	}
	
    $content.='';
	return $content;			
}
function subcommentBox($commentId,$postId)
{
	$content=NULL;
	$content='<div class="submit_new_post">
		<form action="" method="post" class="fieldArea" name="replyfrm" id="replyfrm">'.
			$this->fields->textarea(array("name"=>"commentbox".$commentId,"onlyField"=>true,"extraAtt"=>'Placeholder="Type Comment"')).
			$this->fields->hidden(array("name"=>"refType".$commentId,"value"=>"c")).
			$this->fields->hidden(array("name"=>"refId".$commentId,"value"=>$commentId )).
			$this->fields->hidden(array("name"=>"postid","value"=>$postId )).'<div class="spacer10"></div>'.
			$this->fields->button(array("name"=>"postsubmit","type"=>"button","value"=>"Reply","extraAtt"=>'onclick="postSubcomment(\''.$commentId.'\');"'))
		.'</form>
	</div>';
	return $content;
}
	
}

?>