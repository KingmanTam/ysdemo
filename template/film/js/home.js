
var zanpian = {
//浏览器信息
    'browser':{
        'url': document.URL,
        'domain': document.domain,
        'title': document.title,
        'language': (navigator.browserLanguage || navigator.language).toLowerCase(),//zh-tw|zh-hk|zh-cn
        'canvas' : function(){
            return !!document.createElement('canvas').getContext;
        }(),
        'useragent' : function(){
            var ua = navigator.userAgent;//navigator.appVersion
            return {
                'mobile': !!ua.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                'ios': !!ua.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                'android': ua.indexOf('Android') > -1 || ua.indexOf('Linux') > -1, //android终端或者uc浏览器
                'iPhone': ua.indexOf('iPhone') > -1 || ua.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
                'iPad': ua.indexOf('iPad') > -1, //是否iPad
                'trident': ua.indexOf('Trident') > -1, //IE内核
                'presto': ua.indexOf('Presto') > -1, //opera内核
                'webKit': ua.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                'gecko': ua.indexOf('Gecko') > -1 && ua.indexOf('KHTML') == -1, //火狐内核
                'weixin': ua.indexOf('MicroMessenger') > -1 //是否微信 ua.match(/MicroMessenger/i) == "micromessenger",
            };
        }()
    },
//系统公共
    'cms': {
        //提示窗口
        'floatdiv': function() {
            $("<link>").attr({
                rel: "stylesheet",
                type: "text/css",
            }).appendTo("head");
        },
        //选项卡切换
        'tab': function() {
            $("#myTab li a").click(function(e) {
                $(this).tab('show');
                //$($(this).attr('href')).find('a').lazyload({effect: "fadeIn"});
            });
        },
        //内容详情折叠
        'collapse': function() {
            var w = document.documentElement ? document.documentElement.clientWidth : document.body.clientWidth;
            if (w > 640) {
                $(".list_type").addClass("in");
            }

        },
        'scrolltop': function() {
            var a = $(window);
            $scrollTopLink = $("a.backtop");
            a.scroll(function() {
                500 < $(this).scrollTop() ? $scrollTopLink.css("display", "block") : $scrollTopLink.css("display", "none")
            });
            $scrollTopLink.on("click", function() {
                $("html, body").animate({
                    scrollTop: 0
                }, 400);
                return !1
            })
        },
        //AJAX模态弹窗加载
        'modal': function(url){
            $('.zanpian-modal').modal('hide');
            $(".modal-dialog .close").trigger('click');//先关闭窗口
            $('.zanpian-modal').remove();
            $('.modal-backdrop').remove();
            $.ajax({
                type: 'get',
                cache: false,
                url: url,
                timeout: 3000,
                success: function($html) {
                    $('body').append($html);
                    $('.zanpian-modal').modal('show');
                    $("body").css("padding","0px");
                    $("body").css("padding-top","60px");
                }
            })
        },
        //公共
        'all': function(url){
            $('body').on("click", "#login,#user_login,#navbar_user_login", function(event){
                $('.zanpian-modal').modal('hide');
                if(!zanpian.user.islogin()){
                    event.preventDefault();
                    zanpian.user.loginform();
                    return false;
                }
            });
            $('.navbar-search').click(function(){
                $('.user-search').toggle();
                $('#nav-signed,#example-navbar-collapse').hide();
            })
            $('.navbar-navmore').click(function(){
                $('.user-search').toggle();
                $('#nav-signed,.user-search').hide();
            })
            //显示更多
            $('body').on("click", ".more-click", function() {
                var self = $(this);
                var box = $(this).attr('data-box');
                var allNum = $(this).attr('data-count');
                var buNum = allNum - $(this).attr('data-limit');
                var sta = $(this).attr('data-sta');
                var hideItem = $('.' + box).find('li[rel="h"]');
                if (sta == undefined || sta == 0) {
                    hideItem.show(200);
                    $(this).find('span').text('收起部分' + buNum);
                    self.attr('data-sta', 1);
                } else {
                    hideItem.hide(200);
                    $(this).find('span').text('查看全部' + allNum);
                    self.attr('data-sta', 0);
                }

            });
            //键盘上一页下一页
            var prevpage = $("#pre").attr("href");
            var nextpage = $("#next").attr("href");
            $("body").keydown(function(event) {
                if (event.keyCode == 37 && prevpage != undefined) location = prevpage;
                if (event.keyCode == 39 && nextpage != undefined) location = nextpage;
            });
            //播放窗口隐藏右侧板块
            $('body').on("click", "#player-shrink", function() {
                $(".player_right").toggle();
                $(".player_left").toggleClass("max");
                $(".player-shrink").toggleClass("icon-left");
            });
            //关闭右侧关注我们
            $("#widget-WeChat").click(function(){
                $(this).hide();
            });
            if ($('.player_playlist').length > 0){
                zanpian.player.playerlist() ;
            }
            $(window).resize(function() {
                zanpian.player.playerlist() ;
            });
            $(".player-tool em").click(function() {
                $html = $(this).html();
                try {
                    if ($html == '关灯') {
                        $(this).html('开灯')
                    } else {
                        $(this).html('关灯')
                    }
                } catch (e) {}
                $(".player-open").toggle(300);
                $(".player_left").toggleClass("player-top")
                $(".player_right").toggleClass("player-top")
            });
        }
    },
    'list': {
        //列表AJAX响应
        'ajax': function() {
            $('body').on("click", ".list_type ul li a", function(e) {
                if (type_parms != undefined && type_parms != null) {
                    var curdata = $(this).attr('data').split('-');
                    if (curdata[0] == 'id' || curdata[0] == 'sid') {
                        type_parms = {
                            "id": curdata[1],
                            "mcid": "0",
                            "area": "0",
                            "year": "0",
                            "letter": "0",
                            "sid": "0",
                            "wd": "0",
                            "sex": "0",
                            "zy": "0",
                            "order": "0",
                            "picm": 1,
                            "p": 1
                        };
                        deltype();
                    }
                    type_parms[curdata[0]] = curdata[1];
                    type_parms['p'] = 1;
                    url = parseurl(type_parms);
                    $(this).parent().siblings().children("a").removeClass('active');
                    $(this).addClass('active');
                    zanpian.list.url(url);
                    deltitle()
                }
                return false;
            });
            $('body').on("click", ".ajax-page ul li a,.tv_detail_week a", function(e) {
                e.preventDefault();
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                var curdata = $(this).attr('data').split('-');
                type_parms[curdata[0]] = curdata[1];
                var url = parseurl(type_parms);
                zanpian.list.url(url);
            });
            $('body').on("click", ".ajax-nav-tabs li a", function(e) {
                e.preventDefault();
                var curdata = $(this).attr('data').split('-');
                type_parms[curdata[0]] = curdata[1];
                type_parms['p'] = 1;
                var url = parseurl(type_parms);
                $(this).parent().siblings().removeClass('active');
                $(this).parent().addClass('active');
                zanpian.list.url(url);
            });
            $('body').on("click", ".seach-nav-tabs li a", function(e) {
                e.preventDefault();
                var curdata = $(this).attr('data').split('-');
                type_parms[curdata[0]] = curdata[1];
                type_parms['p'] = 1;
                var url = parseurl(type_parms);
                $('.seach-nav-tabs li a').each(function(e) {
                    $(this).removeClass('active');
                });
                $(this).addClass('active');
                zanpian.list.url(url);
            });
            $('body').on("click", "#conreset a", function(e) {
                var curdata = $(this).attr('data').split('-');
                type_parms = {
                    "id": curdata[1],
                    "mcid": "0",
                    "area": "0",
                    "year": "0",
                    "letter": "0",
                    "sid": "0",
                    "wd": "0",
                    "sex": "0",
                    "zy": "0",
                    "order": "0",
                    "picm": 1,
                    "p": 1
                };
                url = parseurl(type_parms);
                zanpian.list.url(url);
                deltype();
                deltitle();
            });
            function deltitle() {
                var constr = '';
                $('.list_type ul li a').each(function(e) {
                    if ($(this).attr('class') == 'active') {
                        if ($(this).html() == '全部') constr += ' ';
                        else constr += '<span>' + $(this).html() + '</span>';
                    }
                });
                if (constr != '') $('.conbread').html(constr);
            }
            function deltype() {
                $('.list_type ul li a').each(function(e) {
                    $(this).removeClass('active');
                    if ($(this).html() == '全部') {
                        $(this).attr('class', 'active');
                    }
                });
                return false;
            }
            function emptyconbread() {
                $('.list_type ul li a').each(function(e) {
                    $(this).removeClass('active');
                    if ($(this).html() == '全部') {
                        $(this).attr('class', 'active');
                    }
                });
                return false;
            }
            function parseurl(rr) {
                var url = cms.root + type_ajax_url;
                for (var c in rr) {
                    if (rr[c] != '0') {
                        url = url + "-" + c + "-" + rr[c];
                    }
                }
                return url;
            }
        },
        'url': function(url) {
            if (($('#content li').length > 3)) $("html,body").animate({
                scrollTop: $("#content").offset().top - 93
            }, 500);
            $("#content").html('<div class="loading">努力加载中……</div>');
            $.get(url,function(data, status) {
                var value = jQuery('#content', data).html();
                if (value == null || value == '') {
                    value = '<div class="kong">抱歉，没有找到相关内容！</div>';
                }
                $("#content").html(value);
                $("#short-page").html(jQuery('#short-page', data).html())
                $("#long-page").html(jQuery('#long-page', data).html())
                $("#total-page").html(jQuery('#total-page', data).html())
                $("#current-page").html(jQuery('#current-page', data).html())
                $("#count").html(jQuery('#count', data).html())
                $(".loading").lazyload({
                    effect: 'fadeIn'
                });
                if(zanpian.browser.language=='zh-hk' || zanpian.browser.language=='zh-tw'){
                    $(document.body).s2t();
                }
            });

        },
    },
    'detail': {
        'collapse': function() { //内容详情折叠
            $('body').on("click", "[data-toggle=collapse]", function() {
                $this = $(this);
                $($this.attr('data-target')).toggle();
                $($this.attr('data-default')).toggle();
                if ($this.attr('data-html')) {
                    $data_html = $this.html();
                    $this.html($this.attr('data-html'));
                    $this.attr('data-html', $data_html);
                }
                if ($this.attr('data-val')) {
                    $data_val = $this.val();
                    $this.val($this.attr('data-val'));
                    $this.attr('data-val', $data_val);
                }
            });
        },
        //播放列表折叠
        'playlist': function() {
            //更多播放地址切换
            $(".player-more .dropdown-menu li").click(function() {
                $("#playTab").find('li').removeClass('active');
                var activeTab = $(this).html();
                var prevTab = $('.player-more').prev('li').html();
                $('.player-more').prev('li').addClass('active').html(activeTab);
                //var prevTab = $('#playTab li:nth-child(2)').html();
                //$('#playTab li:nth-child(2)').addClass('active').html(activeTab);
                $(this).html(prevTab);
            });
            if ($('.player-more').length > 0) {
                $(".dropdown-menu li.active").each(function() {
                    var activeTab = $(this).html();
                    var prevTab = $('.player-more').prev('li').html();
                    $('.player-more').prev('li').addClass('active').html(activeTab);
                    $(this).html(prevTab).removeClass('active');
                });
            }
            //手机端播放源切换
            $(".mplayer .dropdown-menu li").click(function() {
                var sclass = $(this).find('a').attr('class');
                var stext = $(this).text();
                $("#myTabDrop2 .name").text(stext);
                $("#myTabDrop2").removeClass($("#myTabDrop2").attr('class'));
                $("#myTabDrop2").addClass(sclass);
            });
            var WidthScreen = true;
            for (var i = 0; i < $(".playlist ul").length; i++) {
                series($(".playlist ul").eq(i), 20, 1);
            }
            function series(div, n1, n2) { //更多剧集方法
                var len = div.find("li").length;
                var n = WidthScreen ? n1 : n2;
                if (len > 24) {
                    for (var i = n2 + 18; i < len - ((n1 / 2) - 2) / 2; i++) {
                        div.find("li").eq(i).addClass("hided");
                    }
                    var t_m = "<li class='more open'><a target='_self' href='javascript:void(0)'>更多剧集</a></li>";
                    div.find("li").eq(n2 + 17).after(t_m);
                    var more = div.find(".more");
                    var _open = false;
                    div.css("height", "auto");
                    more.click(function() {
                        if (_open) {
                            div.find(".hided").hide();
                            $(this).html("<a target='_self' href='javascript:void(0)'>更多剧集</a>");
                            $(this).removeClass("closed");
                            $(this).addClass("open");
                            $(this).insertAfter(div.find("li").eq(n2 + 17));
                            _open = false;
                        } else {
                            div.find(".hided").show();
                            $(this).html("<a target='_self' href='javascript:void(0)'>收起剧集</a>");
                            $(this).removeClass("open");
                            $(this).addClass("closed");
                            $(this).insertAfter(div.find("li:last"));
                            _open = true;
                        }
                    })
                }
            }
        },
    },
    'player': {
        //播放页面播放列表
        'playerlist': function() {
            var height = $(".player_left").height();
            if ($('.player_prompt').length > 0){
                var height = height-50;
            }
            $(".player_playlist").height(height - 55);
            var mheight = $(".mobile_player_left").height();
            if ($(".player_playlist").height() > mheight){
                $(".player_playlist").height(mheight - 55);
            }
        },
    },
    'barrage': { //弹幕
        'index': function() {
            $.ajaxSetup({
                cache: true
            });
            if ($(".play_barrage").length) {
                $("<link>").attr({
                    rel: "stylesheet",
                    type: "text/css",
                }).appendTo("head");
            }
            if ($('.barrage_switch').is('.on')) {
                zanpian.barrage.get();
            }
            $('body').on("click", "#slider", function() {
                if ($('.barrage_switch').is('.on')) {
                    $('.barrage_switch').removeClass('on');
                    $.fn.barrager.removeAll();
                    clearInterval(looper);
                    return false;
                } else {
                    $('.barrage_switch').addClass('on');
                    zanpian.barrage.get(0);
                }
            });
            $("#barrage-submit").click(function(e){
                if (!zanpian.user.islogin()) {
                    zanpian.user.loginform();
                    return false;
                }
                $("#barrage-form").zanpiansub({
                    curobj: $("#barrage-submit"),
                    txt: '数据提交中,请稍后...',
                    onsucc: function(result) {
                        $.hidediv(result);
                        if (parseInt(result['rcode']) > 0) {
                            zanpian.barrage.get(1);
                        }
                    }
                }).post({
                    url: cms.root + 'index.php?s=user-home-addcomm'
                });
                return false;
            });
        },
        'get': function(t) {
            if (cms.id != undefined && cms.id != null && cms.id != '') {
                var url = cms.root + "index.php?s=home-barrage-index-t-" + t + "-id-" + cms.id;
            } else {
                return false;
            }
            $.getJSON(url, function(data) {
                //是否有数据
                if (typeof(data) != 'object') {
                    return false;
                }
                var looper_time = data.looper_time;
                var items = data.items;
                var total = items.length;
                var run_once = true;
                var index = 0;
                barrager();
                function barrager(){
                    if(t==0){
                        if (run_once) {
                            looper = setInterval(barrager, looper_time);
                            run_once = false;
                        }
                    }
                    $('#zanpiancms_player').barrager(items[index]);
                    if(t==0){
                        index++;
                        if (index == total) {
                            clearInterval(looper);
                            return false;
                        }
                    }

                }

            });
        }
    },
    'search': { //搜索
        'autocomplete': function(){
            var $limit = $('.zanpian_search').eq(0).attr('data-limit');
            if( $limit > 0){
                $.ajaxSetup({
                    cache: true
                });
                $.getScript("//cdn.bootcss.com/jquery.devbridge-autocomplete/1.2.26/jquery.autocomplete.min.js", function(response, status) {
                    $ajax_url = cms.root+'index.php?s=home-search-vod';
                    $('.zanpian_wd').autocomplete({
                        serviceUrl : $ajax_url,
                        params: {'limit': $limit},
                        paramName: 'q',
                        maxHeight: 400,
                        transformResult: function(response) {
                            var obj = $.parseJSON(response);
                            return {
                                suggestions: $.map(obj.data, function(dataItem) {
                                    return { value: dataItem.vod_name, data: dataItem.vod_url};
                                })
                            };
                        },
                        onSelect: function (suggestion) {
                            location.href = suggestion.data;
                            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
                        }
                    });
                });
            }
        },
    },
    'language':{//简繁转换
        's2t':function(){
            if(feifei.browser.language=='zh-hk' || feifei.browser.language=='zh-tw'){
                $.getScript("//cdn.feifeicms.co/jquery/s2t/0.1.0/s2t.min.js", function(data, status, jqxhr) {
                    $(document.body).s2t();//$.s2t(data);
                });
            }
        },
        't2s':function(){
            if(feifei.browser.language=='zh-cn'){
                $.getScript("//cdn.feifeicms.co/jquery/s2t/0.1.0/s2t.min.js", function(data, status, jqxhr) {
                    $(document.body).t2s();//$.s2t(data);
                });
            }
        }
    },
//图片处理
    'image': {
        //幻灯与滑块
        'swiper': function(){
            $.ajaxSetup({
                cache: true
            });
            $.getScript("https://cdn.bootcdn.net/ajax/libs/Swiper/3.4.2/js/swiper.min.js", function(){
                var swiper=new Swiper('.box-slide',{pagination:'.swiper-pagination',lazyLoading:true,preventClicks:true,paginationClickable:true,autoplayDisableOnInteraction:false,autoplay:3000,loop:true,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',});var swiper=new Swiper('.details-slide',{pagination:'.swiper-pagination',autoHeight:true,loop:true,nextButton:'.details-slide-next',prevButton:'.details-slide-pre',paginationType:'fraction',keyboardControl:true,lazyLoading:true,lazyLoadingInPrevNext:true,lazyLoadingInPrevNextAmount:1,lazyLoadingOnTransitionStart:true,});var swiper=new Swiper('.news-switch-3',{lazyLoading:true,slidesPerView:3,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:3,spaceBetween:0},992:{slidesPerView:2,spaceBetween:0},767:{slidesPerView:1,spaceBetween:0}}});var swiper=new Swiper('.news-switch-4',{lazyLoading:true,slidesPerView:4,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:4,spaceBetween:0},992:{slidesPerView:3,spaceBetween:0},767:{slidesPerView:2,spaceBetween:0}}});var swiper=new Swiper('.news-switch-5',{lazyLoading:true,slidesPerView:5,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:4,spaceBetween:0},992:{slidesPerView:3,spaceBetween:0},767:{slidesPerView:2,spaceBetween:0}}});var swiper=new Swiper('.vod-swiper-4',{lazyLoading:true,slidesPerView:4,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:4,spaceBetween:0},767:{slidesPerView:3,spaceBetween:0}}});var swiper=new Swiper('.vod-swiper-5',{lazyLoading:true,slidesPerView:5,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:4,spaceBetween:0},767:{slidesPerView:3,spaceBetween:0}}});var swiper=new Swiper('.vod-swiper-6',{lazyLoading:true,slidesPerView:6,spaceBetween:0,nextButton:'.swiper-button-next',prevButton:'.swiper-button-prev',breakpoints:{1200:{slidesPerView:5,spaceBetween:0},992:{slidesPerView:4,spaceBetween:0},768:{slidesPerView:3,spaceBetween:0}}});
            });
        },
        //延迟加载
        'lazyload': function(){
            $.ajaxSetup({
                cache: true
            });
            $.getScript("https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js", function(response,status){
                $(".loading").lazyload({
                    effect : "fadeIn",
                    failurelimit: 15
                });
            });
        },
        //生成二维码
        'qrcode': function(){
            if($(".qrcode")){
                $(".qrcode").append('<img class="qrcode" width="150" height="150" src="//bshare.optimix.asia/barCode?site=weixin&url='+encodeURIComponent(window.location.href)+'"/>');
            }
            $("#qrcode").popover({
                html: true
            });
            $("#qrcode").on('show.bs.popover', function () {
                $("#qrcode").attr('data-content','<img class="qrcode" width="150" height="150" src="//bshare.optimix.asia/barCode?site=weixin&url='+encodeURIComponent(window.location.href)+'"/>');
            })
        },
    },
    'mobile':{//移动端专用
        'jump': function(){
            if( cms.murl && (zanpian.browser.url != cms.murl) ){
                location.replace(cms.murl);
            }
        },
    },
};
$(document).ready(function(){
    if(zanpian.browser.useragent.mobile){
        zanpian.mobile.jump();
    }else{
        zanpian.barrage.index();
    }
    zanpian.image.swiper();//幻灯片
    zanpian.cms.floatdiv();//窗口提示信息
    zanpian.cms.all();//主要加载
    zanpian.cms.tab();//切换
    zanpian.cms.collapse();
    zanpian.cms.scrolltop();
    zanpian.image.lazyload();//图片延迟加载
    zanpian.search.autocomplete();//联系搜索
    zanpian.image.qrcode();//二维码
    zanpian.list.ajax();//列表AJAX
    zanpian.detail.collapse();
    zanpian.detail.playlist();//更多剧集
});

$(function (){

    $("#searchbar1").click(function() {
        let val = $('#wd1').val();

        if (val === undefined || val===''){
            val = '*';
        }
        window.location.href='/search/'+val+'_1.html';
    });
    $("#searchbar2").click(function() {
        let val = $('#wd2').val();

        if (val === undefined || val===''){
            val = '*';
        }
        window.location.href='/search/'+val+'_1.html';
    });
})