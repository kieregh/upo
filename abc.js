
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
        emoticonPath : null,
        msgCatVal: null,
        msgCatLabel: null,
        userInputEle: null,
        chatScrollContainer: null,
        chatLogContainer: null,
        btnShowRecGoods: null,
        btnSend: null,
        options: {},
        events: {
            'keydown [data-role="chatting-inputEle"]' : 'enterClientMsg',
            'keyup [data-role="chatting-inputEle"]' : 'showStatusMsg',
            'mouseover [data-role="msg-row"]' : 'showMsgMenu', // 메세지 row 메뉴 노출 이벤트(desktop)  
            'mouseout [data-role="msg-row"]' : 'hideMsgMenu', // 메세지 row 메뉴 숨김 이벤트(desktop)  
            'keypress [data-role="msg-row"]' : 'showMsgMenu', // 메세지 row 메뉴 노출 이벤트(mobile)
            'tap [data-role="msg-row"]' : 'hideMsgMenu', // 메세지 row 메뉴 숨김  이벤트(mobile)
            'click [data-act]' : 'doUserAct', // 사용자 액션 
            'tap [data-act]' : 'doUserAct', // 사용자 액션
            'scroll [data-role="chatting-scrollContainer"]' : 'checkScrollTop', // 스크롤 이벤트 (채팅내역 더 가져오기)
            'change [data-role="upload-inputFile"]' : 'fileInputChanged', // 파일업로드 input change 
            'click [data-role="open-emoticon"]' : 'showEmoticonBox', // 이모티콘 박스 보여주기 
            'click [data-role="insert-emoticon"]' : 'insertEmoticon', // 이모티콘 입력 
            'change [data-role="category-select"]' : 'setMsgCategory',
            'click [data-role="chatting-sendButton"]' : 'sendClientMsg',
            'keypress [data-role="bot-talks"]' : 'enterUserMsg',
            'click [data-role="btn-send"]' : 'sendUserMsg',
        },
         
        // Initialization
        init: function(options, el) {
            var self = this;
            this.$el = $(el);
            this.$el_id = '#'+this.$el.attr('id');

            // Detect mobile devices
            (function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
            
            if($.browser.mobile) this.$el.addClass('mobile');
            
            // Init options
            this.options = $.extend(true, {}, this.getDefaultOptions(), options);
            this.$el.addClass(this.options.containerClass); // 채팅박스 출력 container 에 class 추가 
            this.module = this.options.moduleName; // module name 값 세팅
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
            this.emoticonBox = this.emoticonBox;
            this.initChatBox(); // load 챗박스                        
        },

        // Default options
        getDefaultOptions: function(){
            return {
                userInputEle : '[data-role="bot-talks"]',
                chatLogContainer : '[data-role="chatting-logContainer"]',
                chatScrollContainer : '[data-role="chatting-scrollContainer"]',
                chatNoticeBox : '[data-role="chatting-noticeBox"]',
                emoticonBox: '[data-role="emoticon-box"]',
                btnShowRecGoods: '[data-role="btn-showRecGoods"]',
                btnSend : '[data-role="btn-send"]',
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
                    self.showNotify(error_msg);
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

            $.get(
                rooturl+'/?r='+raccount+'&m='+this.module+'&a=get_Chatting_Box',
                {
                    botid : botId,
                    themeName: themeName,
                },
                function(response){
                    var result = $.parseJSON(response);
                    self.userLevel = result.userLevel; // 접속자 level
                    self.userGroup = result.userGroup; // 접속자 group
                    self.bot_avatar_src = result.bot_avatar_src; // 봇 아바타 
                    $(container).append(result.chat_box);
                    $(scrollContainer).scrollTop(100000000); // 스크롤 bottom
                    self.AfterInitChatBox();
                }              
            ); 
        },
        
        // ChatBox 로딩 후 초기화 함수들 호출  
        AfterInitChatBox : function(){
           this.undelegateEvents(); // msg box 엘리먼트들 이벤트 바인딩 off 
           this.delegateEvents(); // msg box 엘리먼트들 이벤트 바인딩 on
           var e = $.Event('shown.ps.chatbot', { relatedTarget: this.$el_id });
           this.$el.trigger(e); 
        },
  
        // 메제시 템플릿 초기화 함수 (type : me,other,notice)
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
 
        // 메세지 template 추출 함수 
        getMsgTpl : function(data){
            var d = new Date();
            var date = d.getHours()+':'+d.getMinutes();
            var msg = data.msg;
            var msg_type = data.msg_type;
            var bot_avatar_src = this.bot_avatar_src;
            var emoticon_path = this.emoticon_path;

            // msg 타입에 따른 msg 출력형태 다름 
            if(msg_type=='text') show_msg = msg;
            else if(msg_type=='emoticon') show_msg = '<span class="emoticon_wrap"><img src="'+emoticon_path+'/emo_'+msg+'.png"/></span>';
            
            // 템플릿 추출 및 지정
            var tpl = this.msgTpl[msg_type];
            var last_tpl;

            // 템플릿 $var 값 치환 
            last_tpl = tpl.replace(/\{\$date}/gi,date); // 사용자명  치환
            if(msg_type=='bot') last_tpl = last_tpl.replace(/\{\$bot_avatar_src}/gi,bot_avatar_src); // 메세지 치환
            last_tpl = last_tpl.replace(/\{\$message}/gi,show_msg); // 메세지 치환
         
            
            return last_tpl;

        },        
 
        // 메세지 출력 함수 
        showMsg: function(msgBox,msgTpl){
            var bot = this.botId;
            $(msgBox).append(msgTpl);
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
        showNotify : function(message){
            var notify_msg ='<div id="kiere-notify-msg">'+message+'</div>';
            var notify = $('<div/>', { id: 'kiere-notify', html: notify_msg})
                  .addClass('active')
                  .appendTo(this.$el)
            setTimeout(function(){ 
                $(notify).removeClass('active');
                $(notify).remove();
            }, 3000);
        },
  
        // emoticon 박스 보여주기 
        showEmoticonBox: function(){
            $(this.emoticonBox).slideToggle('fast');
        },
        
        // emoticon 입력  
        insertEmoticon: function(e){
            var ele = e.currentTarget;
            var emoticon_msg = $(ele).data('emotion');
            var emoticon_src = this.emoticonPath+'/emo_'+emoticon_msg+'.png';
            var emoticon_data = {"type":"emoticon","emoticon_src":emoticon_src};
            var msg = this.getMsgTpl(emoticon_data);
            var token = this.getChatToken();
            var msg_data = {"msg":msg,"notice":0,"token": token};
            this.saveMsg(msg_data);
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
                var user_msg = $(this).val();
                var msg = $.trim(user_msg);
                var msg_type='text';
                if(msg){
                    var msg_data = this.setMsgData(msg,msg_type);
                    this.showUserMsg(msg_data); // Prompt show user msg in front side  
                    if(msg.match('추천')) this.getRecGoods();
                    else this.getBotMsg(msg_data); // Get bot msg from server side
                }else{
                    this.showNotify('메세지를 입력해주세요. ');
                    this.focusInput();
                } 
                                                                                 
            }  
        },

        // User click send-btn
        sendUserMsg : function(){

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
            getBotMsg('hi','say_hello');
            getAdMsg(); // 광고 내역 가져오기  
        },

            // 광고 메세지 출력 
        getAdMsg : function(){
            var self = this;
            var botid = this.botId;
            var msgBox = this.chatLogContainer; 
            $.post(rooturl+'/?r='+raccount+'&m='+module+'&a=get_adMsg',{
                botid : botid,
            },function(response) {
                var result = $.parseJSON(response); 
                var ad_msg = result.content;
                $.each(ad_msg,function(key,msg){
                    var msg_data = self.setMsgData(msg,'show_adMsg');
                    getBotMsg(msg_data);   
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
            var botid = this.botId;
            var msg = data.msg;
            var msg_type = data.msg_type;
            var _lang = sessionStorage.getItem('now_lang');
            $.post(rooturl+'/?r='+raccount+'&m='+module+'&a=get_reply',{
                botid : botid,
                message : msg,
                msg_type : msg_type,
                _lang : _lang
            },function(response) {
                var result = $.parseJSON(response); 
                var bot_msg = result.content;
                var msg_data = self.setMsgData(msg,msg_type);           
                self.showBotMsg(msg_data);             
            });
        },

        // Print Bot Msg 
        showBotMsg : function(data){
            var self = this;
            var user_input = this.userInputEle;
            var msgBox = this.chatLogContainer;
            $(user_input).removeAttr('placeholder');
            $(user_input).removeAttr('disabled'); 
            setTimeout(function(){
                $(msgBox).append(data.msg);
                self.resetScrollTop(msgBox);
                self.hideBtnSend(); // 전송버튼 숨김
            },300);
        },
                
        // Print User Msg
        showUserMsg : function(data){
            var msgBox = this.chatLogContainer;
            var user_input = this.userInputEle;
            var user_msg = this.getMsgTpl(data);
            $(user_input).val('');
            $(user_input).attr('placeholder', '답변 준비중... ');
            $(user_input).attr('disabled', 'disabled');
            $(msgBox).append(user_msg);
            resetScrollTop(msgBox);        
        },
        
        // 추천상품 출력 
        getRecGoods : function(){
            var user_input = $(document).find('[data-role="bot-talks"]');
            var bot_id = $(user_input).data('id');
            var msgBox = $('[data-role="cb-box-'+bot_id+'"]');
            $.post(rooturl+'/?r='+raccount+'&m='+module+'&a=do_UserAction',{
                act : 'show-recommendedGoods',
                bot_id : bot_id
            },function(response){
                var result = $.parseJSON(response);
                var bot_msg = result.content;
                showBotMsg(bot_msg);
            }); 
        },
        
        // 추천상품 버튼 노출 및 전송버튼 숨김   
        hideBtnSend : function(){
           $(this.btnShowRecGoods).show();
           $(this.btnSend).hide();
        },

    }

    $.fn.PS_chatbot = function(options) {
        return this.each(function() {
            var chatbot = Object.create(Bottalks);
            $.data(this, 'chatbot', chatbot);
            chatbot.init(options || {}, this);
        });
    };

	
}));