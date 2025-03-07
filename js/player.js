var killErrors=function(value){return true};window.onerror=null;window.onerror=killErrors;
window.onresize=function(){if(window.name=="macopen1"){MacPlayer.Width=$(window).width()-$(".MacPlayer").offset().left-15;MacPlayer.HeightAll=$(window).height()-$(".MacPlayer").offset().top-15;MacPlayer.Height=MacPlayer.HeightAll;if(mac_showtop==1){MacPlayer.Height-=20}$(".MacPlayer").width(MacPlayer.Width);$(".MacPlayer").height(MacPlayer.HeightAll);$("#buffer").width(MacPlayer.Width);$("#buffer").height(MacPlayer.HeightAll);$("#Player").width(MacPlayer.Width);$("#Player").height(MacPlayer.Height)}};
var MacPlayer = {
    'GoPreUrl': function() {
        if (this.Num > 0) {
            this.Go(this.Src + 1, this.Num)
        }
    },
    'GetPreUrl': function() {
        return this.Num > 0 ? this.GetUrl(this.Src + 1, this.Num) : ''
    },
    'GoNextUrl': function() {
        if (this.Num + 1 != this.PlayUrlLen) {
            this.Go(this.Src + 1, this.Num + 2)
        }
    },
    'GetNextUrl': function() {
        return this.Num + 1 <= this.PlayUrlLen ? this.GetUrl(this.Src + 1, this.Num + 2) : ''
    },
    'GetUrl': function(s, n) {
        return mac_link.replace('{src}', s).replace('{src}', s).replace('{num}', n).replace('{num}', n)
    },
    'Go': function(s, n) {
        location.href = this.GetUrl(s, n)
    },
    'GetList': function() {
        this.RightList = '';
        for (i = 0; i < this.Data.from.length; i++) {
            from = this.Data.from[i];
            url = this.Data.url[i];
            listr = "";
            sid_on = 'h2';
            sub_on = 'none';
            urlarr = url.split('#');
            for (j = 0; j < urlarr.length; j++) {
                urlinfo = urlarr[j].split('$');
                name = '';
                url = '';
                list_on = '';
                from1 = '';
                if (urlinfo.length > 1) {
                    name = urlinfo[0];
                    url = urlinfo[1];
                    if (urlinfo.length > 2) {
                        from1 = urlinfo[2]
                    }
                } else {
                    name = "第" + (j + 1) + "集";
                    url = urlinfo[0]
                }
                if (this.Src == i && this.Num == j) {
                    sid_on = 'h2_on';
                    sub_on = 'block';
                    list_on = "list_on";
                    this.PlayUrlLen = urlarr.length;
                    this.PlayUrl = url;
                    this.PlayName = name;
                    if (from1 != '') {
                        this.PlayFrom = from1
                    }
                    if (j < urlarr.length - 1) {
                        urlinfo = urlarr[j + 1].split('$');
                        if (urlinfo.length > 1) {
                            name1 = urlinfo[0];
                            url1 = urlinfo[1]
                        } else {
                            name1 = "第" + (j + 1) + "集";
                            url1 = urlinfo[0]
                        }
                        this.PlayUrl1 = url1;
                        this.PalyName1 = name1
                    }
                }
                listr += '<li></li>'
            }
            this.RightList += '<div ></div>'
        }
    },
    'ShowList': function() {
        $('#playright').toggle()
    },
    'Tabs': function(a, n) {
        var b = $('#sub' + a).css('display');
        for (var i = 0; i <= n; i++) {
            $('#main' + i).attr('className', 'h2');
            $('#sub' + i).hide()
        }
        if (b == 'none') {
            $('#sub' + a).show();
            $('#main' + a).attr('className', 'h2_on')
        } else {
            $('#sub' + a).hide()
        }
    },
    'Show': function() {
        if (mac_showtop == 0) {
            $("#playtop").hide()
        }
        if (mac_showlist == 0) {
            $("#playright").hide()
        }
        setTimeout(function() {
            MacPlayer.AdsEnd()
        }, this.Second * 1000);
        $("#topdes").get(0).innerHTML = '' + '正在播放：第一集';
        $("#playright").get(0).innerHTML = '<div class="rightlist" id="rightlist" style="height:' + this.Height + 'px;">' + this.RightList + '</div>';
        $("#playleft").get(0).innerHTML = this.Html + '';
    },
    'ShowBuffer': function() {
        var w = this.Width - 100;
        var h = this.Height - 100;
        var l = (this.Width - w) / 2;
        var t = (this.Height - h) / 2 + 20;
        $(".MacBuffer").css({
            'width': w,
            'height': h,
            'left': l,
            'top': t
        });
        $(".MacBuffer").toggle()
    },
    'AdsEnd': function() {
        $('#buffer').hide()
    },
    'Install': function() {
        this.Status = false;
        $('#install').parent().show();
        $('#install').show()
    },
    'Play': function() {
        var a = mac_colors.split(',');
        document.write('<style>.MacPlayer{background: #' + a[0] + ';font-size:14px;color:#' + a[1] + ';margin:0px;padding:0px;position:relative;overflow:hidden;width:100%px;height:' + this.HeightAll + 'px;}.MacPlayer a{color:#' + a[2] + ';text-decoration:none}a:hover{text-decoration: underline;}.MacPlayer a:active{text-decoration: none;}.MacPlayer table{width:100%;height:100%;}.MacPlayer ul,li,h2{ margin:0px; padding:0px; list-style:none}.MacPlayer #playtop{text-align:center;height:20px; line-height:21px;font-size:12px;}.MacPlayer #topleft{width:150px;}.MacPlayer #topright{width:100px;} .MacPlayer #topleft{text-align:left;padding-left:5px}.MacPlayer #topright{text-align:right;padding-right:5px}.MacPlayer #playleft{width:100%;height:100%;overflow:hidden;}.MacPlayer #playright{height:100%;overflow-y:auto;}.MacPlayer #rightlist{width:120px;overflow:auto;scrollbar-face-color:#' + a[7] + ';scrollbar-arrow-color:#' + a[8] + ';scrollbar-track-color: #' + a[9] + ';scrollbar-highlight-color:#' + a[10] + ';scrollbar-shadow-color: #' + a[11] + ';scrollbar-3dlight-color:#' + a[12] + ';scrollbar-darkshadow-color:#' + a[13] + ';scrollbar-base-color:#' + a[14] + ';}.MacPlayer #rightlist ul{ clear:both; margin:5px 0px}.MacPlayer #rightlist li{ height:21px; line-height:21px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}.MacPlayer #rightlist li a{padding-left:15px; display:block; font-size:12px}.MacPlayer #rightlist h2{ cursor:pointer;font-size:13px;font-family: "宋体";font-weight:normal;height:25px;line-height:25px;background:#' + a[3] + ';padding-left:5px; margin-bottom:1px}.MacPlayer #rightlist .h2{color:#' + a[4] + '}.MacPlayer #rightlist .h2_on{color:#' + a[5] + '}.MacPlayer #rightlist .ul_on{display:block}.MacPlayer #rightlist .list_on{color:#' + a[6] + '} </style><div class="MacPlayer"><table border="0" cellpadding="0" cellspacing="0"><tr><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="playtop"><tr><td width="100" id="topleft"><a target="_self" href="javascript:void(0)" onclick="MacPlayer.GoPreUrl();return false;">上一集</a> <a target="_self" href="javascript:void(0)" onclick="MacPlayer.GoNextUrl();return false;">下一集</a></td><td id="topcc"><div id="topdes" style="height:26px;line-height:26px;overflow:hidden"></div></td><td width="100" id="topright"><a target="_self" href="javascript:void(0)" onClick="MacPlayer.ShowList();return false;">开/关列表</a></td></tr></table></td></tr><tr style="display:none"><td colspan="2" id="install" style="display:none"></td></tr><tr><td id="playleft" valign="top">&nbsp;</td><td id="playright" valign="top">&nbsp;</td></tr></table></div>');
        document.write('<script src="/player/dplayer.js"></script>')
    },
    'Down': function() {},
    'Init': function() {
        this.Status = true;
        this.Url = location.href;
        this.Par = location.search;
        this.Data = {
            'from': mac_from.split('$$$'),
            'server': 0,
            'url': mac_urlx10d26.split('$$$')
        };
        this.Width = window.name == 'macopen1' ? mac_widthpop : mac_width;
        this.HeightAll = window.name == 'macopen1' ? mac_heightpop : mac_height;
        this.Height = this.HeightAll;
        if (mac_showtop == 1) {
            this.Height -= 20
        }
        if (this.Url.indexOf('#') > -1) {
            this.Url = this.Url.substr(0, this.Url.indexOf('#'))
        }
        console.log(this.Data.url)
        console.log(this.Data.url[0].indexOf("$"))
        if (this.Data.url[0].indexOf("$")>-1){
            var playurls = this.Data.url[0].split('$')
            MacPlayer.PlayUrl = playurls[1];
        }else {
            MacPlayer.PlayUrl = this.Data.url[0];
        }
        this.Prestrain = mac_prestrain;
        this.Buffer = mac_buffer;
        this.Second = mac_second;
        this.Flag = mac_flag;
        var a = this.Url.match(/\d+.*/g)[0].match(/\d+/g);
        var b = a.length;
        this.Id = a[(b - 3)] * 1;
        this.Src = a[(b - 2)] * 1 - 1;
        this.Num = a[(b - 1)] * 1 - 1;
        this.PlayFrom = this.Data.from[0];
        this.PlayServer = '';
        this.PlayNote = '';
        //this.GetList();
        //this.NextUrl = this.GetNextUrl();
        //this.PreUrl = this.GetPreUrl();
        this.Path = SitePath + 'player/';
        MacPlayer.Play()
    }
};

MacPlayer.Init();