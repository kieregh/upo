function showMe(num)
    {
        $('#div_one'+num).siblings('.section').hide();
        $('#div_one'+num).fadeToggle(5);
    }

function displayMessagePart(e) {
    $("#closeMsgPart").click(function () {
        $("#msgPart").fadeOut(1e3, "linear")
    });
    setTimeout(function () {
        $("#msgPart").fadeOut(1200, "linear")
    }, 9e3)
}

function setLanguage(e, t) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/home/ajax.home.php",
        data: {
            language: e,
            setLanguate: "true"
        },
        success: function (e) {
            window.location.href = t
        }
    })
}

function updatePageContent(e, t, n, r, i) {
    urlPath = SITE_URL + "modules/" + t + "/" + n + r + "&pageNo=" + i;
    $("#" + e + "").html('<div style="margin:80px; text-align:center;"><div style="padding:18px;"><img src="' + SITE_URL + 'themes/images/loadingWait.gif" alt="" border="0" /></div></div>');
    $.ajax({
        type: "GET",
        url: urlPath,
        success: function (t) {
            if (t != "") {
                displayMessagePart(true);
                $("#" + e + "").html(t).show(3e4)
            }
        }
    })
}

function readMoresearchResult(e, t, n) {
    urlPath = SITE_URL + "modules/search/ajax.search.php?act=" + e + "&srchObset=" + n;
    $("#" + t + "").html('<div style="margin:80px; text-align:center;"><div style="padding:18px;"><img src="' + SITE_URL + 'themes/images/loading-horizontal.gif" alt="" border="0" /></div></div>');
    $.ajax({
        type: "GET",
        url: urlPath,
        success: function (e) {
            if (e != "") {
                $("#" + t + "").html(e).show(3e4)
            }
        }
    })
}

function clearthisDiv(e) {
    $("#" + e).html("")
}

function voting(e, t, n, r,sellink) {
	if (r == "yes") {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                votetype: t,
                id: e,
                oncontent: n,
                voting: "true"
            },
            success: function (a) {
                $("#vote-" + e).html(a["total"])
				if(t=="u")
				{
					$("#"+sellink).removeClass("icon");
					$("#"+sellink).addClass("iconact");
					$("#"+sellink).next().removeClass("iconact");
					$("#"+sellink).next().addClass("icon");

				}
				else{
					$("#"+sellink).removeClass("icon");
					$("#"+sellink).addClass("iconact");
					$("#"+sellink).prev().removeClass("iconact");
					$("#"+sellink).prev().addClass("icon");

				}

            }
        })
    } else {
        alert("로그인 후 사용해주세요.")
    }
}

function post_hide(e, t, n) {
    if (n == "yes") {
        var r = jQuery("#container");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                id: e,
                oncontent: t,
                type: "hide"
            },
            success: function (t) {
                if (t["status"] == 200) {
                    //$("#li-post-" + e).remove();
					$("#li-post-" + e).fadeOut('slow', function(){ $("#li-post-" + e).remove(); r.masonry('reload');});

                }
            }
        })
    } else {
        alert("Please login to Hide")
    }
}

function post_unhide(e, t, n) {
    if (n == "yes") {
        var r = $("#container");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                id: e,
                oncontent: t,
                type: "unHide"
            },
            success: function (t) {
                if (t["status"] == 200) {
                    //$("#li-post-" + e).fadeOut();
                    $("#li-post-" + e).fadeOut('slow', function(){ $("#li-post-" + e).remove(); r.masonry('reload');});
                } else {}
            }
        })
    } else {
        alert("Please login to Hide")
    }
}

function getReport(e, t, n) {
    if (n == "yes") {
		var r = $("#container");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                id: e,
                oncontent: t,
                type: "report"
            },
            success: function (t) {
                $("#li-post-" + e).fadeOut('slow', function(){ $("#li-post-" + e).remove(); r.masonry('reload');});
            }
        })
    }
	else
	{
		 alert("Please login to Report.")
	}
}

function getsave(e, t) {
    if (t == "yes") {
		var r = $("#container");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                id: e,
                type: "save"
            },
            success: function (t) {
                if (t["status"] == 201) {
                    $("#save-" + e).html("Unsave")
                } else if (t["status"] == 202 && MODULE_A == 'user') {
                   // $("#li-post-" + e).fadeOut();
                   // $container.masonry("reload");
				   $("#li-post-" + e).fadeOut('slow', function(){ $("#li-post-" + e).remove(); r.masonry('reload');});
                } else if (t["status"] == 202){
                    $("#save-" + e).html("Save")
                } else {}
            }
        })
    } else {
        alert("Please Login to Save..")
    }
}

function post_share(e, t) {
    try {} catch (n) {
        console.log("Error in post_share - " + n)
    }
}

function subscribeCategory(e, t) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/home/ajax.home.php",
        data: {
            subscribeCat: true,
            catId: e,
            type: t
        },
        success: function (e) {
            $("#categorySubscription").html(e)
        }
    })
}

function getsharedialog(e, n) {
	if (n == "yes") {
    $.ajax({
        type: "POST",
        url: SITE_URL + "modules/home/ajax.home.php",
        data: {
            postid: e,
            action: "getshareform"
        },
        dataType: "json",
        async: false,
        success: function (t) {
            $("#sharedialogbox" + e).html(t)
			$(".ui-dialog-titlebar-close").click();
        }
    });
    $("#sharedialogbox" + e).dialog({
        width: "300.6px",
        height: "auto",
        maxWidth: 500,
        fluid: true,
        modal: true
    })
	}
	 else {
        alert("Please Login to Share..")
    }
}

function validateEmail(e) {
    var t = /^[a-z0-9]+(\.[a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
    return t.test(e)
}

function validateCaptcha(e) {}

function submitsharefun(e) {
    var t = $("#linkuser").val();
    if (validateEmail(t) == true) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: $(document.share).serialize(),
            dataType: "json",
            async: false,
            success: function (t) {
                if (t != "") {
                    $("#lbl_msg_captcha").html(t)
					$(".ui-dialog-titlebar-close").click();
                } else {
                    $("#sharedialogbox" + e).html();
                  //  $("#sharedialogbox" + e).dialog("close")
					$(".ui-dialog-titlebar-close").click();
                }
            }
        })
    } else {
        $("#lbl_msg").html("Invalid email")
    }
}

function take_plan(e) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "ajax.membership.php",
        data: {
            type: "take_action"
        },
        dataType: "json",
        async: false,
        success: function (e) {}
    });
    window.location.href = SITE_URL
}

function displayForm() {
    $("#multiRedditName").show();
    $("#submitReddit").show();
    $("#createRedditbtn").hide()
}

function addSubraditCat() {
    var e = $("#tags").val();
    var t = $("#redditId").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/multireddit/ajax.multireddit.php",
        data: {
            categoryAddName: e,
            redditId: t
        },
        success: function (e) {
            if (e == "catnotexist") {
                $("#addredditCatErr").html("Invalid category name")
            } else if (e == "donorepeat") {
                $("#addredditCatErr").html("Do not repeat category")
            } else {
                $("#multi_section_category").html(e)
            }
            $("#tags").val("");
            MultiredditPostListing(t)
        }
    })
}

function deleteMultiRedditCategory(e, t) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/multireddit/ajax.multireddit.php",
        data: {
            redditId: e,
            catId: t,
            deletecatId: true
        },
        success: function (t) {
            $("#multi_section_category").html(t);
            MultiredditPostListing(e)
        }
    })
}

function MultiredditPostListing(e) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/multireddit/ajax.multireddit.php",
        data: {
            redditId: e,
            postListing: true
        },
        success: function (e) {
            $("#container").html("");
            $("#container").html(e);
            $("#container").masonry("reload");
            $("#container").infinitescroll({
                navSelector: "#page-nav",
                nextSelector: "#page-nav a",
                itemSelector: ".box",
                donetext: "No more posts to load.",
                loadingImg: SITE_URL + "themes/images/loading.gif",
                loadingText: "",
                debug: false,
                errorCallback: function () {
                    $("#infscr-loading").animate({
                        opacity: .8
                    }, 100).fadeOut("normal")
                }
            }, function (e) {
                var t = $(e);
                setTimeout(function () {
                    t.imagesLoaded(function () {
                        $("#container").masonry("appended", t);
                        $("#container").masonry("reload")
                    })
                }, 10)
            })
        }
    })
}

function makehistory(e, t) {
    if (t > 0) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "modules/home/ajax.home.php",
            data: {
                postid: e,
                user: t,
                makeHistory: "true"
            },
            dataType: "json",
            async: false,
            success: function (e) {}
        })
    }
}

function deletemsg(e, t) {
    if (confirm("Are You Sure?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + "modules/message/ajax.message.php",
            data: {
                id: e,
                type: t,
                act: "deletemsg"
            },
            success: function (t) {
                if (t["status"] == "success") {
                    $("#msg-" + e).remove()
                }
            }
        })
    }
}

function showHideEmbedd(e, t) {
    try {
        var n = $("#embedd-" + e);
        if (n.hasClass("show") == false) {
            n.addClass("show",function(){
				$('#container').masonry('reload');
			});
            $("#imgembeddbutton-" + e).attr("src", t + "pausenew.png")
        } else {
            n.removeClass("show",function(){
				$('#container').masonry('reload');
			});
            $("#imgembeddbutton-" + e).attr("src", t + "playnew.png")
        }
    } catch (r) {
        //console.log("Error in showHideEmbedd function - " + r)
    }
}

function showHideComment(e){
	$("#comment-post-" + e).css("height","36px");
	$("#comment-post-" + e).css("overflow","hidden");
}

function onlyNumbers(e) {
    if (e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 9 || e.keyCode == 27 || e.keyCode == 13 || e.keyCode == 65 && e.ctrlKey === true || e.keyCode >= 35 && e.keyCode <= 39) {
        return
    } else {
        if (e.shiftKey || (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault()
        }
    }
}

function _get_banner(e, t) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/advertisement/ajax.advertisement.php",
        data: {
            page_id: e,
            slot_id: t,
            type: "advertisement"
        },
        success: function (e) {
            if (t == 1) {
                $(".adv_banner_1").html(e);
                $("#top-banner-timing").val(e["display_sec"])
            }
            if (t == 2) {
                $(".adv_banner_2").html(e);
                $("#top-banner-timing").val(e["display_sec"])
            }
        }
    })
}

function _update_banner_rec(e) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + "modules/advertisement/ajax.advertisement.php",
        data: {
            banner_id: e,
            type: "advertisement_click"
        },
        success: function (e) {
            if (slot_id == 1) {
                $(".adv_banner_1").html(e);
                $("#top-banner-timing").val(e["display_sec"])
				//window.reload();
            }
            if (slot_id == 2) {
                $(".adv_banner_2").html(e);
                $("#top-banner-timing").val(e["display_sec"])
				//window.reload();
            }
        }
    })
}
$(document).ready(function () {
    $(".bxslider").bxSlider({
        pager: false
    });
    $(".only_alpha").live("keydown", function (e) {
        onlyNumbers(e)
    })
});
$(function () {
    $(".share-link").unbind("click").bind("click", function () {
        try {
            var e = $(this);
            var t = e.attr("data-id");
            if (t != "") {
                var n = $("#dv-post-" + t);
                if (n.hasClass("share-now")) n.removeClass("share-now");
                else n.addClass("share-now")
            }
        } catch (r) {
            console.log("Error in share-link click event - " + r)
        }
    })
});

$(document).ready(function(){

	$(window).scroll(function(){
	if ($(this).scrollTop() > 500) {
	$('.scrollup').fadeIn();
	} else {
	$('.scrollup').fadeOut();
	}
	});

	$('.scrollup').click(function(){
	$("html, body").animate({ scrollTop: 0 }, 600);
	return false;
	});

});
