<?php

class App
{

    public static array $mysql = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'maccms10',
        'user' => 'root',
        'password' => 'root'
    ];
    public static array $redis = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'timeout' => 60*60*2,
        'timeout_l' => 60*60*24
    ];
    public static array $solr = array(
        'vod'=>array(
            'host' => '127.0.0.1',
            'port' => 8983,
            'core' => 'lib_vod_1'),
        'story'=>array(
            'host' => '127.0.0.1',
            'port' => 8983,
            'core' => 'lib_story_1'),
    );
    public static array $args = [
        'type'=>'',
        'class'=>'',
        'area'=>'',
        'year'=>'',
        'page'=>1,
    ];
    public static array $domain = [
        'title' => '木星影院-2023最新电视剧-高清影视-最新电影电视剧综艺在线观看',
        'domain' => 'jupiter.com',
        'name' => '木星影院',
        'keywords' => '木星影院,超清影视,最新电影在线观看,最新最热门免费电影电视剧,最新一期综艺,免费电影网',
        'description' => '木星影院为您提供2023最新最热门的电视剧、电影大全、电视剧大全免费在线观看和迅雷电影免费下载，每天更新最新抢先电影大片，热门电视剧，最新综艺真人秀，明星信息与相关电影电视剧，同时提供影视剧情、电视剧演员表等相关内容',
        'template' => 'template/',
        'vod_rank_id' => '199877,218824,228649,233584,238547,243184,247132,250110,256088,268734',
        'story_rank_id' => '12465,19563,26541,38965,49652,59645,56321,64856,78952,79563',
        'cacheId' => 'jupiter'

    ];


    public static array $type = [
        1 => 'drama',
        2 => 'film',
        3 => 'comics',
        4 => 'variety',
        5 => 'documentary',
        6 => 'short',
        7 => 'actor',
        8 => 'story'
    ];

    public static array $player = [
        '1080zyk' => 'https://vip.zykbf.com/?url=',
        'gsm3u8' => 'https://www.guangsujx.com/m3u8/?url=',
        'tencent' => 'https://api.quanminjiexi.com/?v=',
        'youku' => 'https://jx.jsonplayer.com/player/?url=',
        'iqiyi' => 'https://jx.pjgjg.com/?url=',
        'liangzi' => '',
    ];

    public static array $player_name = [
        '1080zyk' => '路线2',
        'gsm3u8' => '路线3',
        'iqiyi' => '爱奇艺',
        'liangzi' => '路线1',
    ];
    public static array $type_name = [
        1 => '电视剧',
        2 => '电影',
        3 => '动漫',
        4 => '综艺',
        5 => '纪录片',
        6 => '微视频',
        7 => '明星',
        8=> '剧情'
    ];

    public static array $type_suffix = [
        1 => '全集高清免费观看-电视剧',
        2 => '高清在线免费观看下载-电影',
        3 => '全集高清免费观看-动漫',
        4 => '全集高清免费观看-综艺',
        5 => '高清在线观看-免费下载-纪录片',
        6 => '',
        7 => '明星',
        8=> '剧情'
    ];

    public static array $rute = [
        'drama'=> array('type_id'=>1,'type_name'=>'电视剧','type_pid'=>0),
        'film'=> array('type_id'=>2,'type_name'=>'电影','type_pid'=>0),
        'comics'=> array('type_id'=>3,'type_name'=>'动漫','type_pid'=>0),
        'variety'=> array('type_id'=>4,'type_name'=>'综艺','type_pid'=>0),
        'documentary'=> array('type_id'=>5,'type_name'=>'纪录片','type_pid'=>0),
        'short'=> array('type_id'=>6,'type_name'=>'微视频','type_pid'=>0),
        'story'=> array('type_id'=>8,'type_name'=>'剧情','type_pid'=>0),
    ];

    public static array $rute_match = [
        'drama'=> 'type',
        'film'=> 'type',
        'comics'=> 'type',
        'variety'=> 'type',
        'documentary'=> 'type',
        'short'=> 'type',
        'story'=>'story',
        'dramad'=>'detail',
        'filmd'=>'detail',
        'comicsd'=>'detail',
        'varietyd'=>'detail',
        'documentaryd'=>'detail',
        'shortd'=>'detail',
        'dramap'=>'play',
        'filmp'=>'play',
        'comicsp'=>'play',
        'varietyp'=>'play',
        'documentaryp'=>'play',
        'shortp'=>'play',
        'actor'=>'actor',
        'star'=>'star',
        'info'=>'info',
        'rank'=>'rank',
        'last'=>'last',
        'theme'=>'theme',
    ];
    public static array $extend = [
        1=>array(
            'idol' =>'偶像',
            'warfare' =>'战争',
            'science' =>'科幻',
            'suspense' =>'悬疑',
            'comedy' =>'喜剧',
            'crime' =>'犯罪',
            'history' =>'历史',
            'family' =>'家庭',
            'antique' =>'古装',
            'espionage' =>'谍战',
            'love' =>'爱情',
            'mythology' =>'神话',
            'urban' =>'都市',
            'Plot' =>'剧情',
            'campus' =>'校园',
            'military' =>'军旅',
            'action' =>'动作',
            'Horror' =>'惊悚',
            'biography' =>'传记',
            'terror' =>'恐怖',
            'countryside' =>'农村',
            'business' =>'商战',
            'song' =>'歌舞',
            'music' =>'音乐',
        ),
        2=>array(
             'comedy'=>'喜剧' ,
             'terror'=>'恐怖' ,
             'plot'=>'剧情' ,
             'love'=>'爱情' ,
             'quxia'=>'武侠' ,
             'action'=>'动作' ,
             'science'=>'科幻' ,
             'warfare'=>'战争' ,
             'crime'=>'犯罪' ,
             'horror'=>'惊悚' ,
             'suspense'=>'悬疑' ,
             'animation'=>'动画' ,
             'antique'=>'古装' ,
             'disaster'=>'灾难' ,
             'history'=>'历史' ,
             'adventure'=>'冒险' ,
             'biography'=>'传记' ,
             'music'=>'音乐' ,
             'youth'=>'青春' ,
             'record'=>'记录'
        ),
        3=>array(
            'adventure'=>'冒险',
            'hot'=>'热血',
            'science'=>'科幻',
            'action'=>'动作',
            'family'=>'家庭',
            'plot'=>'剧情',
            'classical'=>'经典',
            'girl'=>'少女',
            'funny'=>'搞笑',
            'love'=>'爱情',
            'sports'=>'竞技',
            'fairy'=>'童话',
            'reasoning'=>'推理',
            'puzzle'=>'益智',
        ),
        4=>array(
            'talk'=>'脱口秀',
            'reality'=>'真人秀',
            'music'=>'音乐',
            'party'=>'晚会',
            'emotion'=>'情感',
            'fashion'=>'时尚',
            'travel'=>'旅游',
        ),
        5=>array(
            'record'=>'记录',
            'warfare'=>'战争',
            'history'=>'历史',
            'plot'=>'剧情',
            'biography'=>'传记',
            'disaster'=>'灾难',
            'music'=>'音乐',
            'science'=>'科幻',
            'horror'=>'惊悚' ,
        ),
        6=>array(
            'fun'=>'娱乐',
            'trailers'=>'片花',
            'info'=>'资讯',
            'sport'=>'体育',
            'comment'=>'电影解说',
        ),
        7=>array(),
        8=>array('football'=>'足球',
            'basketball'=>'篮球',
            'tennis'=>'网球',
            'snooker'=>'斯诺克',),
        9=>array(),

    ];
    public static array $area = [
        'cn'=>'大陆' ,
        'hk'=>'香港' ,
        'tw'=>'台湾' ,
        'us'=>'美国' ,
        'japan'=>'日本' ,
        'korea'=>'韩国' ,
        'france'=>'法国' ,
        'britain'=>'英国' ,
        'germany'=>'德国' ,
        'thailand'=>'泰国' ,
        'india'=>'印度' ,
        'italy'=>'意大利',
        'spain'=>'西班牙',
        'canada'=>'加拿大',
        'other'=>'其他'
    ];
    public static array $year = [
        '2023'=>'2023' ,
        '2022'=>'2022' ,
        '2021'=>'2021' ,
        '2020'=>'2020' ,
        '2019'=>'2019' ,
        '2018'=>'2018' ,
        '2017'=>'2017' ,
        '2016'=>'2016' ,
        '2015'=>'2015' ,
        '2014'=>'2014' ,
        '2013'=>'2013' ,
        '2012'=>'2012' ,
        '2011'=>'2011' ,
        '2010'=>'2010' ,
    ];

    /**
     * @var int
     * 是否为蜘蛛
     */
    public static int $is_spider = 0;

    /**
     * @var string
     * ua
     */
    public static string $ua = '';

    /**
     * @var string
     * ua
     */
    public static string $site_url = 'film.com';

    /**
     * @var bool
     * 有效请求
     */
    public static bool $valid_url = true;

    /**
     * @var int
     * 视频id
     */
    public static int $id = 0;

    /**
     * @var int
     * 当前页码
     */
    public static int $page = 1;

    /**
     * @var int
     * 分类id
     */
    public static int $typeId = 0;

    /**
     * @var string
     * 方法
     */
    public static string $method = '';

    /**
     * @var string
     * 页面内容
     */
    public static string $content = '';

    /**
     * @var string
     * 搜索关键词
     */
    public static string $searchKey = '';

    /**
     * @var string
     * error
     */
    public static string $error = '';

    /**
     * @var string
     * 当前目录
     */
    public static string $directory = '';
}