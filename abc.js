
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function( root, jQuery ) {
            if ( jQuery === undefined ) {
                // require('jQuery') returns a factory that requires window to
                // build a jQuery instance, we normalize how we use modules
                // that require this pattern but the window provided is a noop
                // if it's defined (how jquery works)
                if ( typeof window !== 'undefined' ) {
                    jQuery = require('jquery');
                }
                else {
                    jQuery = require('jquery')(root);
                }
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {

    var Bottalks = {

        // Instance variables
        // ==================

        $el: null,
        $el_id: null,
        module: null,
        vendor: null,
        botId: null, // bot id  
        botUid: null,
        cmod: null,
        bot_type: null, // 일반 채팅방, 그룹 채팅방 
        bot_open: null,
        bot_avatar_src: null,
        themePath: null, // 플러그인 초기화시 지정 
        themeName: null, // 플러그인 초기화시 지정
        msgTpl: {},
        userLevel: 0,
        userGroup: 0,
        currentPage: 1,
        recnum: null, // 출력갯수 
        orderby: null, // asc, desc 기준 
        sort: null, // sort 기준 
        totalPage: null,
        totalRow: null,
        is_owner: false,
        owner_id: null, // 방장 id 
        perm_write: null, // 작성 권한 
        is_admin: is_admin==1?true:false,
        is_login: memberid!=''?true:false,
        uploadInputEle : null,
        emoticon_path: null,
        emoticonPath : null,
        msgCatVal: null,
        msgCatLabel: null,
        userInputEle: null,
        chatScrollContainer: null,
        chatLogContainer: null,
        btnShowRecGoods: null,
        btnSend: null,
        q_StartTime: null, // 질문 시작 시간 
        mbruid:null,
        showTimer: null,
        options: {},
        events: {
                
            'scroll [data-role="chatting-logContainer"]' : 'checkScrollTop', // 스크롤 이벤트 (채팅내역 더 가져오기)
            'click [data-role="btn-showEmoticon"]' : 'showEmoticonBox', // 이모티콘 박스 보여주기 
            'click [data-role="emoticon"]' : 'insertEmoticon', // 이모티콘 입력 
            'keypress [data-role="bot-talks"]' : 'enterUserMsg',
            'focusin [data-role="bot-talks"]' : 'showBtnSend',
            'click body' : 'hideBtnSend',
            'click [data-role="btn-send"]' : 'processInput',
            'click [data-role="btn-showFstKwd"]' : 'showFstKwd', // 첫 번째 키워드 그룹 보여주기 
            'click [data-role="kwd-item"]' : 'showKWDMenu', // keyword 메뉴 클릭
            'click [data-role="btn-showRecGoods"]' : 'getRecGoods',
            'click [data-role="learning-word"]' : 'learningData',
            'click [data-role="slot-more"]' : 'getMoreSlot',
        },
        
        // check mobile device   
        isMobile: function(){
            try{ document.createEvent("TouchEvent"); return true; }
            catch(e){ return false; }
        },

        // Initialization
        init: function(options, el) {
            var self = this;
            this.$el = $(el);
            this.$el_id = '#'+this.$el.attr('id');
            this.$el.css("position","relative");           
            if(this.isMobile==true) this.$el.addClass('mobile');
            
            // Init options
            this.options = $.extend(true, {}, this.getDefaultOptions(), options);
            this.$el.addClass(this.options.containerClass); // 채팅박스 출력 container 에 class 추가 
            this.module = this.options.moduleName; // module name 값 세팅
            this.cmod = this.options.cmod; // cs or vod 
            this.botId = this.options.botId; // bot id 값 세팅
            this.themePath = this.options.themePath;
            this.themeName = this.options.themeName?this.options.themeName:null;
            this.recnum = this.options.recnum?this.options.recnum:10; // 출력 갯수
            this.orderby = this.options.orderby?this.options.orderby:'asc';
            this.sort = this.options.sort?this.options.sort:'uid'; 
            this.userInputEle = this.options.userInputEle;
            this.chatScrollContainer = this.options.chatScrollContainer;
            this.chatLogContainer = this.options.chatLogContainer;
            this.btnShowRecGoods = this.options.btnShowRecGoods;
            this.btnSend = this.options.btnSend;
            this.emoticonBox = this.options.emoticonBox;
            this.emoticon_path = this.options.emoticon_path;
            this.mbruid = this.options.mbruid;
            this.showTimer = this.options.showTimer;
            this.initChatBox(); // load 챗박스                        
        },

        // Default options
        getDefaultOptions: function(){
            return {
                userInputEle : '[data-role="bot-talks"]',
                chatLogContainer : '[data-role="chatting-logContainer"]',
                chatScrollContainer : '[data-role="chatting-logContainer"]',
                chatNoticeBox : '[data-role="chatting-noticeBox"]',
                emoticonBox: '[data-role="emoticon-box"]',
                btnShowRecGoods: '#show-firstKwd',
                btnSend : '[data-role="btn-send"]',
                learningDataForm : '#form-learningData',
                orderby: 'desc', // 출력 순서 기본값
                recnum: 10, // 출력갯수 
                useEnterSend: true,
            }            
        },
        
        // 채팅 더 가져오기 이벤트 
        checkScrollTop : function(e){
            var msg_box = this.chatLogContainer;
            var scrollTop = $(this.chatScrollContainer).scrollTop();
            var msg_row = $(msg_box).find('[data-role="msg-row"]:first');
            var currentPage = this.currentPage;
            var totalPage = this.totalPage;
            if((scrollTop<50) && (currentPage<totalPage)){
                this.getMoreChat(currentPage);
                this.currentPage++;                  
            } 
        },

        // 채팅내역 더 가져오기 
        getMoreChat : function(currentPage){
            var msg_box = this.chatLogContainer;
            $.get(rooturl+'/?r='+raccount+'&m='+this.module+'&a=do_userAct',{
                act : 'getMoreChat',
                currentPage : currentPage,
                sort : this.sort,
                orderby : this.orderby,
                recnum : this.recnum, 
                bid : this.botId,
                themeName : this.themeName
            },function(response) {
                var result = $.parseJSON(response);
                var error = result.error;
                if(error){
                    var error_msg = result.error_msg;
                    var noti_data = {"container":null,"msg":error_msg}; 
                    self.showNotify(noti_data);
                }else{
                    $(msg_box).prepend(result.content);                    
                }

            });   
        },

        // chatting box 로딩 및 접속자 권한/관련 데이타 세팅  
        initChatBox : function(){
            var self = this;
            var botId = this.botId;
            var container = this.$el;
            var msgBox = this.chatLogContainer; // 메세지 출력 박스 
            var scrollContainer = this.chatScrollContainer; // scroll 대상 
            var themeName = this.themeName; // 테마명 
            var mbruid = this.mbruid;
            var showTimer = this.showTimer;
            
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_Chatting_Box',{
                botid : botId,
                theme_name: themeName,
                mbruid : mbruid,
                showTimer : showTimer,
            },function(response) {
                var result = $.parseJSON(response);
                self.userLevel = result.userLevel; // 접속자 level
                self.userGroup = result.userGroup; // 접속자 group
                self.bot_avatar_src = result.bot_avatar_src; // 봇 아바타
                self.vendor = result.vendor;
                self.botUid = result.botUid; 
                $(container).append(result.chat_box);
                $(scrollContainer).scrollTop(100000000); // 스크롤 bottom
                self.AfterInitChatBox();         
            }); 
        },
        
        // ChatBox 로딩 후 초기화 함수들 호출  
        AfterInitChatBox : function(){
           this.undelegateEvents(); // msg box 엘리먼트들 이벤트 바인딩 off 
           this.delegateEvents(); // msg box 엘리먼트들 이벤트 바인딩 on
           var e = $.Event('shown.ps.chatbot', { relatedTarget: this.$el_id });
           this.$el.trigger(e); 
           this.sayHello(); // 인사말 
        },
  
        // 메제시 템플릿 초기화 함수 (type : me,other,notice) ---> 사용 안함
        initMsgTpl : function(){
            var self = this;
            $.get(
                rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_Msg_Tpl',
                {},
                function(response){
                    var result = $.parseJSON(response);
                    self.msgTpl = $.extend(self.msgTpl,result);
                }
            );
        },
        
        // slot 더보기 
        getMoreSlot : function(e){
            var self = this;
            var ele = e.currentTarget;
            var totalPage = $(ele).attr('data-totalPage');
            var page = $(ele).data('page');
            var nextPage = parseInt(page)+1;
            var slotBox = $(ele).parent();
            var token = $(ele).data('token');
            var mbruid = this.mbruid;
            var botId = this.botId;
            var swiperId = '#swiper-'+token.replace('.','');
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_MoreSlot',{
                page : nextPage,
                token : token,
                mbruid : mbruid,
                botid : botId
            },function(response) {
                var result = $.parseJSON(response);
                var content = result.content;
                $(ele).remove();
                $(slotBox).append(content);
                setTimeout(function(){
                   //self.init_afterAjax(); 
                   var initSlide = parseInt(page)*8;

                   var swiper = new Swiper(swiperId,{
                       init: true,
                       speed: 600,
                       pagenation: false,
                       spaceBetween: 20,
                       freeMode: true,
                       centeredSlides: true,
                       slidesPerView:3.5,
                       initialSlide: initSlide,
                       observer: true,
                       touchEventsTarget: 'wrapper'
                   });
                   console.log(swiper);  
                },10)
            }); 
        },

        // Get user msg template  
        getUserMsgTpl : function(data){
            // tpl_type : user or bot
            var d = new Date();
            var date = d.getHours()+':'+d.getMinutes();
            var emoticon_path = this.emoticon_path;
            var msg = data.msg;
            var msg_type = data.msg_type;
            var show_msg;

            // msg 타입에 따른 msg 출력형태 다름 
            if(msg_type=='text') show_msg = msg;
            else if(msg_type=='emoticon') show_msg = '<span class="emoticon_wrap"><img src="'+emoticon_path+'emo_'+msg+'.png"/></span>';
            var user_msg_tpl = '';
            user_msg_tpl +='<div class="cb-chatting-chatline">';
                user_msg_tpl +='<div class="cb-chatting-sent">';
                    user_msg_tpl +='<div class="cb-chatting-info">';
                        user_msg_tpl +='<span class="cb-chatting-date">'+ date +'</span>';
                    user_msg_tpl +='</div>';
                    user_msg_tpl +='<div class="cb-chatting-balloon">';
                       user_msg_tpl +='<p><span>'+ show_msg +'</span></p>';
                    user_msg_tpl +='</div>';
                user_msg_tpl +='</div>';
            user_msg_tpl +='</div>';   

          
            return user_msg_tpl;
        },        
        
        delegateEvents: function() {
            this.bindEvents(false);
        },

        undelegateEvents: function() {
            this.bindEvents(true);
        },

        bindEvents: function(unbind){
            var bindFunction = unbind ? 'off' : 'on';
            for (var key in this.events) {
                var eventName = key.split(' ')[0];
                var selector = key.split(' ').slice(1).join(' '); 
                var methodNames = this.events[key].split(' ');
                for(var index in methodNames) {
                    if(methodNames.hasOwnProperty(index)) {
                        var method = this[methodNames[index]];
                        // Keep the context
                        method = $.proxy(method, this);

                        if (selector == '') {
                            this.$el[bindFunction](eventName, method);
                        } else {
                            // scroll 이벤트는 해당 엘리먼트에 직접 바인딩 해야한다. 
                            if(eventName=='scroll') $(selector)[bindFunction](eventName,method);
                            else this.$el[bindFunction](eventName, selector, method);
                        }
                    }
                }
            }
        },
        
        // 알림 출력   
        showNotify : function(data){
            var container = data.container?data.container:this.chatLogContainer;
            var msg = data.msg;
            var notify_msg ='<div id="kiere-notify-msg">'+msg+'</div>';
            var notify = $('<div/>', { id: 'kiere-notify', html: notify_msg})
                  .addClass('active')
                  .appendTo(container)
            setTimeout(function(){ 
                $(notify).removeClass('active');
                $(notify).remove();
            }, 2000);
        },
  
        // emoticon 박스 보여주기 
        showEmoticonBox: function(){
            $(this.emoticonBox).slideToggle('fast');
        },
        
        // emoticon 입력  
        insertEmoticon: function(e){
            var self = this;
            var ele = e.currentTarget;
            var msg = $(ele).data('emotion');
            var msg_type = 'emoticon';   
            var msg_data = this.setMsgData(msg,msg_type);
            this.showUserMsg(msg_data);
            setTimeout(function(){
               self.getBotMsg(msg_data);
            },200);
            $(this.emoticonBox).slideToggle('fast');
        },
        
        // chat toekn 생성 
        getChatToken : function(){
            function chr4(){
               return Math.random().toString(16).slice(-4);
            }
            return chr4() + chr4() + '.' + chr4() + chr4() + chr4();
        },

        // 입력창 포커스 이벤트 
        focusInput : function(){
            var userInputEle = this.userInputEle;
            setTimeout(function(){
                $(userInputEle).focus();              
            },10);
        },

        // User enter input 
        enterUserMsg : function(e){
            if (e.which == 13) {
                this.processInput();                                                   
            }  
        },

        // User click send-btn
        processInput : function(){
            var user_input = this.userInputEle;
            var msg = $(user_input).val();
            var msg_type = 'text';
            if(msg){
                var msg_data = this.setMsgData(msg,msg_type);
                this.showUserMsg(msg_data); // Prompt show user msg in front side  
                //if(msg.match('추천')) this.getRecGoods();
                //else this.getBotMsg(msg_data); // Get bot msg from server side
                this.getBotMsg(msg_data); 
            }else{
                var noti_data = {"container":'',"msg":'메세지를 입력해주세요. '}
                this.showNotify(noti_data);
                this.focusInput();
            }
        },
        
        init_afterAjax: function(){
            RC_initSwiper(); // require rc.swiper.js
        }, 


        // 키워드 추출 함수  
        getKeywordList : function(data){
            var self = this;
            var vendor = this.vendor;
            var bot = this.botUid;
            var cmod = this.cmod;
            var parent = data.parent?data.parent:0;
            var depth = data.depth?data.depth:0;
            var issun = data.issun?data.issun:0 ;
            var keyword = data.keyword;
            var parent_keyword = data.parent_keyword?data.parent_keyword:0;
            var mbruid = this.mbruid;
            var token = this.getChatToken();
            var container = '';//depth?$(document).find('[data-role="chatContent-wrapper-'+depth+'"]'):'';
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_ChatKeywordList',{
                vendor : vendor,
                bot : bot,
                parent : parent,
                depth : depth,
                issun : issun,
                keyword : keyword,
                parent_keyword : parent_keyword,
                mbruid : mbruid,
                token: token, 
                cmod : cmod
            },function(response) {
                var result = $.parseJSON(response); 
                var keywordList = result.content;
                self.showBotContent(container,keywordList,result);   
                self.init_afterAjax();           
            });

        },

        // 키워드 추출 함수  
        getEventGoods : function(data){
            var self = this;
            var vendor = this.vendor;
            var bot = this.botUid;
            var cmod = this.cmod;
            var eventGoods = data.eventGoods;            
            var container = '';//depth?$(document).find('[data-role="chatContent-wrapper-'+depth+'"]'):'';
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_eventGoods',{
                vendor : vendor,
                bot : bot,
                parent : parent,
                cmod : cmod,
                eventGoods: eventGoods
            },function(response){
                var result = $.parseJSON(response); 
                var eventGoodsList = result.content;
                self.showBotContent(container,eventGoodsList,result);   
                self.init_afterAjax();
            });

        },

        // 봇 컨텐츠 출력 : 키워드 or 상품 
        showBotContent : function(container,content,result){
            var self = this;
            var msgBox = this.chatScrollContainer;
            var user_input = this.userInputEle 

            if(container) $(container).html(content);
            else $(msgBox).append(content); 
            $(user_input).removeAttr('placeholder', '');
            $(user_input).removeAttr('disabled'); 
            this.resetScrollTop();
            this.hideBtnSend(); // 전송버튼 숨김
            //this.checkEventGoods(result.eventGoods);
         },

         checkEventGoods: function(eventGoods){
            if(eventGoods){
                var data = {"eventGoods":eventGoods};
                this.getEventGoods(data);
            }
         }, 
 
        // reset scrollTop 
        resetScrollTop : function(){
            var msgBox = this.chatScrollContainer;
            var height = $(msgBox).scrollHeight;
            if(height) $(msgBox).scrollTop(height);
            else $(msgBox).scrollTop(10000);        
        },

        // 챗봇 인사
        sayHello : function(){
            var msg_data = this.setMsgData('hi','say_hello');
            this.getBotMsg(msg_data);
            if(this.cmod=='cs' || this.cmod =='vod') this.getAdMsg(); // 광고 내역 가져오기  
            // if(this.cmod=='cs'){
            //     var keyword_data = {"parent":null,"depth":null}
            //     this.getKeywordList(keyword_data);
            // }
        },

        // 광고 메세지 출력 
        getAdMsg : function(){
            var self = this;
            var botid = this.botId;
            var msgBox = this.chatLogContainer; 
            var mbruid = this.mbruid;
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_adMsg',{
                botid : botid,
                mbruid : mbruid
            },function(response) {
                var result = $.parseJSON(response); 
                var ad_msg = result.content;
                $.each(ad_msg,function(key,msg){
                    var msg_data = self.setMsgData(msg,'show_adMsg');
                    self.getBotMsg(msg_data);   
                });           
            });
        },

        // set msg data : msg, msg_type ....
        setMsgData : function(msg,msg_type){
            var msg_data = {"msg":msg,"msg_type":msg_type}
            return msg_data;  
        },

        // DB 에서 찾아서 답변 (msg_type : text, recommend product)
        getBotMsg : function(data){
            var self = this;
            var botid = this.botId;
            var msg = data.msg;
            var msg_type = data.msg_type;
            var _lang = sessionStorage.getItem('now_lang');
            var cmod = this.cmod;
            var showTimer = this.showTimer;
            var mbruid = this.mbruid;
            var token = this.getChatToken();
           
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_reply',{
                botid : botid,
                message : msg,
                msg_type : msg_type,
                _lang : _lang,
                cmod: cmod,
                mbruid: mbruid,
                token: token,
                showTimer : showTimer
            },function(response) {
                var result = $.parseJSON(response); 
                var bot_msg = result.content;
                var res_type = result.res_type;
                if(res_type=='slot'){
                   self.showBotContent('',bot_msg,result);
                }else{                    
                    var msg_data = self.setMsgData(bot_msg,msg_type);           
                    self.showBotMsg(msg_data);     
                }                 
                self.init_afterAjax();             
            });
        },

        // 네 아래에서 선택해주세요 
        yesFind : function(type){
            var self = this;
            var msg_arr = {
                "find-select" : "<p>네,찾아드리겠습니다^^</p><p>아래에서 선택해주세요.</p>",
                "recommend-select" : "<p>네,추천해드리겠습니다^^</p><p>아래에서 선택해주세요.</p>",
                "recommend-wait" : "<p>네,추천해드리겠습니다^^</p><p>잠시만 기다려주세요.</p>"
            };
            var msg = msg_arr[type];
            var msg_type = 'yes_find';
            var msg_data = {"msg_type":msg_type,"msg":msg,"res_type":"text"};//this.setMsgData(msg,msg_type);           
            
            this.getBotMsg(msg_data);
        },

        // 첫번째 keyword 보여주기   
        showFstKwd : function(){
            var self = this;
            this.yesFind('find-select'); // 네,찾아드리겠습니다^^아래에서 선택해주세요.

            setTimeout(function(){
                var data = {"parent":null,"depth":null};
                self.getKeywordList(data);
            },100);             
        },
        
        // show keyword menu
        showKWDMenu : function(e){
            var self = this;
            var target = e.currentTarget;
            var this_uid = $(target).data('uid');
            var this_depth = $(target).data('depth');
            var issun = $(target).data('issun'); // 하위 카테고리 존재 여부
            var keyword = $(target).data('keyword');
            var parent_keyword = $(target).data('pkeyword');
            var kwd_url = $(target).data('url');
            var parent = this_uid;
            var depth = parseInt(this_depth)+1; 
            var mbruid = this.mbruid;
            var data = {
                    "parent": parent,
                    "depth": depth,
                    "issun": issun,
                    "keyword": keyword,
                    "parent_keyword": parent_keyword
                };
            var yes_msg;
            
            if(this.cmod=='vod') yes_msg = keyword + " 영화 추천해줘~~";
            else yes_msg = keyword + " 로 찾아줘~~"; 
            var msg_type = 'text';
            var msg_data = self.setMsgData(yes_msg,msg_type);           
            
            // 고객 메세지 출력 
            this.showUserMsg(msg_data);
            if(this.cmod=='vod'){
               // 봇 메세지 출력 (Yes sir!)
                setTimeout(function(){
                    var find_type;
                    if(issun) find_type = 'recommend-select';
                    else find_type = 'recommend-wait';
                    self.yesFind(find_type);
                },200);     
            }
            
            var call = $.urlParam('call');

            // keyword or content(slot) 출력 
            setTimeout(function(){
                if(self.cmod=='cs' && !issun){
                   if(call=='external')  window.open(kwd_url, '_blank');   
                   else window.open(kwd_url, '_blank'); 
                } 
                else self.getKeywordList(data);

            },400);  

            // keyword or content(slot) 출력 
            setTimeout(function(){
               var user_input = self.userInputEle;
               $(user_input).removeAttr('placeholder');
               $(user_input).removeAttr('disabled'); 

            },400);                
            
        },

        // Print Bot Msg 
        showBotMsg : function(data){
            var self = this;
            var user_input = this.userInputEle;
            var msgBox = this.chatLogContainer;
            $(user_input).removeAttr('placeholder');
            $(user_input).removeAttr('disabled'); 
            $(msgBox).append(data.msg);
            self.resetScrollTop();
            self.hideBtnSend(); // 전송버튼 숨김
            setTimeout(function(){
               self.setTimer('stop'); // 답변 시간 저장
            },200);
            
        },
                
        // Print User Msg
        showUserMsg : function(data){
            var msgBox = this.chatLogContainer;
            var user_input = this.userInputEle;
            var user_msg = this.getUserMsgTpl(data);
            $(user_input).val('');
            $(user_input).attr('placeholder', '답변 준비중... ');
            $(user_input).attr('disabled', 'disabled');
            $(msgBox).append(user_msg);
            this.resetScrollTop();  
            this.setTimer('start'); // 질문 시작 시간 저장       
        },
        
        // 학습데이타 저장  
        learningData : function(e){
            e.preventDefault();
            var self = this;
            var eTarget = e.currentTarget;
            var ontol = $(eTarget).data('ontol');
            var learningDataForm = this.options.learningDataForm;
            var notiContainer = '#formGroup-'+ontol;
            var data = $(learningDataForm).serialize();
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=save_learningData',{
                data : data
            },function(response){
                var result = $.parseJSON(response);
                var msg = result.msg;
                var noti_data = {"container":notiContainer,"msg":msg};
                self.showNotify(noti_data);
            }); 
        },

        // 추천상품 출력 
        getRecGoods : function(){
            var self = this;
            var msgBox = this.chatLogContainer;
            var user_input = this.userInputEle;
            var bot_id = this.botId;
            $.post(rooturl+'/?r='+raccount+'&m='+this.module+'&a=do_UserAction',{
                act : 'show-recommendedGoods',
                bot_id : bot_id
            },function(response){
                var result = $.parseJSON(response);
                var msg = result.content;
                var msg_type ='show_adMsg';
                var msg_data = {"msg":msg,"msg_type":msg_type};           
                self.showBotMsg(msg_data);
            }); 
        },
        
        // 추천상품 버튼 숨김 및 전송버튼 노출   
        showBtnSend : function(){
           $(this.btnShowRecGoods).hide();
           $(this.btnSend).show();
           this.setTimer('reset'); // 질문 시작 리셋
        },

        // 추천상품 버튼 노출 및 전송버튼 숨김   
        hideBtnSend : function(){
           $(this.btnShowRecGoods).show();
           $(this.btnSend).hide();
        },

        getMicrotime : function(getAsFloat) {
            var s, now, multiplier;

            if(typeof performance !== 'undefined' && performance.now) {
                now = (performance.now() + performance.timing.navigationStart) / 1000;
                multiplier = 1e6; // 1,000,000 for microseconds
            }
            else {
                now = (Date.now ? Date.now() : new Date().getTime()) / 1000;
                multiplier = 1e3; // 1,000
            }

            // Getting microtime as a float is easy
            if(getAsFloat) {
                return now;
            }

            // Dirty trick to only get the integer part
            s = now | 0;
            
            return (Math.round((now - s) * multiplier ) / multiplier ) + ' ' + s;
        },
        
        // 질문시작 시간 
        get_Q_startTime : function(){
            var mtime = this.getMicrotime();
            var mtime_arr = mtime.split(' ');
            var q_StartTime = parseInt(mtime_arr[0])+parseInt(mtime_arr[1]);

            return q_StartTime;
        },

        // control timer
        setTimer : function(act){
            if(act=='start'){
               ContTimer.resetPlay(); // require jquery.timmer.js
               console.log(ContTimer);
               this.q_StartTime = this.get_Q_startTime(); // 질문 시작 시간 저장   
            }
            else if(act=='stop') ContTimer.stop();
            else if(act=='reset') ContTimer.reset();
        },

    };

    $.fn.PS_chatbot = function(options) {
        return this.each(function() {
            var bottalks = Object.create(Bottalks);
            $.data(this, 'bottalks', bottalks);
            bottalks.init(options || {}, this);
        });
    };
    
}));