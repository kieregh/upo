<?php
class Detail {
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module,$objPost,$postId) {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->postId = $postId;
		if($this->postId>0)
		{
			$qryPostDetail = $this->db->query("SELECT tbl_post.id ,tbl_post.title,tbl_post.description,tbl_post.isimageUrl,tbl_post.description,tbl_post.embeddcode AS embeddcode,tbl_post.img,tbl_post.url,tbl_post.type, tbl_categories.categoryName,tbl_users.username,tbl_post.createdDate FROM  tbl_post
			INNER JOIN tbl_categories ON (tbl_categories.id = tbl_post.catId)
			INNER JOIN tbl_users ON (tbl_post.uid = tbl_users.id)
			WHERE tbl_post.id='".$this->postId."'");
			$fetchValues = mysql_fetch_assoc($qryPostDetail);
			$this->id = $this->db->filtering($fetchValues["id"], 'output', 'int', '');
			$this->title = $this->db->filtering($fetchValues["title"], 'output', 'string', '');
			$this->description = $this->db->filtering($fetchValues["description"], 'output', 'string', '');
			$this->img = $fetchValues["img"];
			$this->categoryName = $this->db->filtering($fetchValues["categoryName"], 'output', 'string', '');
			$this->username = $this->db->filtering($fetchValues["username"], 'output', 'string', '');
			$this->createdDate = $this->db->filtering($fetchValues["createdDate"], 'output', 'string', '');
			$this->embeddcode	= $this->db->filtering($fetchValues["embeddcode"],'output','text','');
			$this->isimageUrl	= $this->db->filtering($fetchValues["isimageUrl"],'output','string','');
			$cuttentDate = date('Y-m-d h:i:s');
			//$this->dateDiff = get_time_difference($this->createdDate,$cuttentDate);
			$this->dateDiff = time_elapsed_string($this->createdDate);

		}
	}
	public function detailPageContent()
	{
		$content = NULL;
		$content ='<div class="adv_banner_1"></div>

		<div class="hot_listing">
        	<ul>
            	<li>
                <div class="details_pic">';
					if($this->isimageUrl == "y")
					{
						$shareImageUrl = $this->img;
						$content .='<a href="#"><img src="'.$this->img.'" height="500px" width="500px"/></a>';

					}
					else if($this->img != ""){
						$shareImageUrl=SITE_UPD."post/500x500/th1_".$this->img."";
						$content .='<a href="#"><img src="'.SITE_UPD.'post/500x500/th1_'.$this->img.'" /></a>';
					}/*else{
						$shareImageUrl=SITE_IMG."no_img.jpg" ;
						$content .='<img src="'.SITE_IMG.'no_img.jpg" />';
					}*/
					$content.='
                </div>';
					if ( isset($this->sessUserId) && $this->sessUserId > 0 ) {
							$islogin = "yes";
						} else {
							$islogin = 'no';
						}

					$total_vote = totalVote($this->id,'p');
				$this->catId = getCategoryid($this->categoryName);
                $content .='<div class="height20"></div>
                	<div class="icons-wrapper">
                        <a href="javascript:void(0);" class="icon document" onclick="voting(\''.$this->id.'\',\'u\',\'p\',\''.$islogin.'\')">Up</a>
                      <div class="like_point" id="vote-'.$this->id.'">'.$total_vote.'</div>
						<a href="javascript:void(0);" class="icon group" onclick="voting(\''.$this->id.'\',\'d\',\'p\',\''.$islogin.'\')">Down</a>
                    </div>
                	<div class="hot_item_heading detail_box">
                    	<div>'.$this->title.'</div>
						'.($this->embeddcode != "" || $this->embeddcode != NULL ?
					  	'' : NULL).'
                        <p>'.$this->dateDiff.' <a href="#">'.$this->username.'</a> 님이 <a href="'.SITE_URL.'c/'.$this->catId.'/'.urlencode($this->categoryName).'/" title="'.$this->categoryName.'">'.$this->categoryName.'</a> 에 등록함</p>
                        <p>'.$this->description.'</p>
                        				<div class="comment_txt">
											<a href="'.SITE_URL.'comments/'.$this->id.'/'.urlencode($this->title).'" title="Comments">'.COMMENTS.'</a>
										</div>
										<div class="share">
											<a href="javascript:void(0);" class="share-link" id="share-'.$this->id.'" onclick="getsharedialog(\''.$this->id.'\')">'.SHARE.'</a>
											<div id="sharedialogbox'.$this->id.'"></div>
										</div>

										<div class="hide">
											<a href="javascript:void(0);" onclick="post_hide(\''.$this->id.'\',\'p\',\''.$islogin.'\')">'.HIDE.'</a>
										</div>
										<div class="save">
											<a href="javascript:void(0);" onclick="getsave(\''.$this->id.'\',\''.$islogin.'\')" id="save-'.$this->id.'">'.SAVE.'</a>
										</div>
										<div class="report">
											<a href="javascript:void(0);" onclick="getReport(\''.$this->id.'\',\'p\',\''.$islogin.'\');" id="report-'.$this->id.'">'.REPORT.'</a>
										</div>
										<div id="embedd-'.$this->id.'">
											'.$this->embeddcode.'
										</div>
										<div class="fr">
											<a href="https://www.facebook.com/sharer.php?u='.selfURL().'&p%5Bimages%5D%5B0%5D='.$shareImageUrl.'&p%5Btitle%5D='.$this->title.'&p%5Bsummary%5D='.$this->description.'" onclick="window.open(this.href,\'facebook\',\'width=500, height=300, left=24, top=24, scrollbars, resizable\'); return false;"><img src="'.SITE_IMG.'fbshare.png" /></a>
											<a href="http://twitter.com/home?status='.selfURL().'" onclick="window.open(this.href,\'facebook\',\'width=500, height=300, left=24, top=24, scrollbars, resizable\'); return false;"><img src="'.SITE_IMG.'twitshare.png" /></a>
										</div>

                    </div>

                </li>
            </ul>
        </div>';
		return $content;
	}
}
?>