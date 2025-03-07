<?php


require 'application/core/Db.php';

class AppService
{

    /**
     * @throws Exception
     */
    public static function load(): void
    {
        $main = Cache::get(App::$domain['cacheId'] . ':main');
        if (empty($main)) {
            $main = file_get_contents('template/film/html/public/main.html');
            Cache::set(App::$domain['cacheId'] . ':page_main', $main, App::$redis['timeout_l']);
        }
        $key = App::$domain['cacheId'] . ':page_' . App::$method;
        $content = Cache::get($key);
        if (empty($content)) {
            $content = file_get_contents('template/film/html/public/' . App::$method . '.html');
            Cache::set($key, $content, App::$redis['timeout_l']);
        }
        App::$content = str_replace('{mv:content}', $content, $main);
        self::setCommon();
    }

    /**
     * @throws Exception
     */
    public static function setCommon(): void
    {
        self::setFooter();
        $include = Cache::get(App::$domain['cacheId'] . ':page_include');
        if (empty($include)) {
            $include = file_get_contents('template/film/html/public/include.html');
            Cache::set(App::$domain['cacheId'] . ':page_include', $include, App::$redis['timeout_l']);
        }
        $head = Cache::get(App::$domain['cacheId'] . ':page_head');
        if (empty($head)) {
            $head = file_get_contents('template/film/html/public/head.html');
            Cache::set(App::$domain['cacheId'] . ':page_head', $head, App::$redis['timeout_l']);
        }
        $head = self::setMenu($head);
        self::assign('name',app::$domain['name']);
        self::assign('site_url',app::$domain['domain']);
        self::assign('site_wapurl',app::$domain['domain']);
        self::assign('include',$include);
        self::assign('head',$head);
    }

    public static function setSEO($params): void
    {
        self::assign('title',$params['title']);
        self::assign('keywords',$params['keywords']);
        self::assign('des',$params['des']);
    }

    /**
     * @throws Exception
     */
    public static function setFooter(): void
    {
        $key = App::$domain['cacheId'] . ':page_footer';
        $footer = Cache::get($key);
        if (empty($footer)) {
            $footer = file_get_contents('template/film/html/public/footer.html');
            $footer = str_replace('{mv:site_url}', App::$site_url, $footer);
            Cache::set($key, $footer, App::$redis['timeout_l']);
        }
        self::assign('footer',$footer);
    }

    /**
     * @throws Exception
     */
    private static function setMenu($head): string
    {
        /*$key = App::$domain['cacheId'] .':page_menu';
        $menu = Cache::get($key);
        if (!empty($menu)){
            return str_replace('{mv:menu}', $menu,$head);
        }*/
        $rows = Db::selectAll('select * from mac_type where type_pid =0 ORDER BY type_id ASC');
        if (App::$method == 'index') {
            $menu = '<li id="nav-index" ' . self::getActive(1) . '><a href="/" title="主页">首页</a></li>';
        } else {
            $menu = '<li id="nav-index"><a href="/" title="主页">首页</a></li>';
        }
        $menu_item = '';
        foreach ($rows as $row) {
            $type_id = $row['type_id'];
            $typeId = App::$typeId;
            if (App::$typeId == $type_id) {
                $menu_item = $menu_item . '<li id="nav-' . $row['type_en'] . '" ' . self::getActive(1) . '><a href="' . self::getLink(1, $row['type_en']) . '" title="' . $row['type_name'] . '">' . $row['type_name'] . '</a></li>';
            } else {
                $menu_item = $menu_item . '<li><a  href="' . self::getLink(1, $row['type_en']) . '">' . $row['type_name'] . '</a></li>';
            }
        }

        $menu = $menu . $menu_item;
        //Cache::set($key,$menu,App::$redis['timeout_l']);
        return str_replace('{mv:menu}', $menu, $head);
    }

    /**
     * @throws Exception
     */
    public static function setDPTop(): void
    {
        $key = App::$domain['cacheId'] . ':page_dptop';
        $key_f = App::$domain['cacheId'] . ':page_dptopf';
        $top_first = Cache::get($key_f);
        $d_top = Cache::get($key);
        if (!(empty($d_top) || empty($top_first))) {
            self::assign('top_list',$d_top);
            self::assign('top_first',$top_first);
        } else {
            $rows = Db::selectAll('select * from mac_vod where vod_id in('.App::$domain['vod_rank_id'].') order by vod_id desc');
            $html = '';
            $count = 1;
            foreach ($rows as $row) {
                $p = [
                    'type_id' => $row['type_id'],
                    'vod_id' => $row['vod_id'],
                    'vod_en' => $row['vod_en'],
                ];
                if ($count == 1) {
                    self::setFirstTop($row, $p);
                } else if ($count < 4 && $count > 1) {
                    $html .= '<li class="list p-0">
                            <a class="pull-left" href="' . AppService::getLink(4, $p) . '" title="' . $row['vod_name'] . '">
                                <em class="num active" class="num">' . $count . '</em>' . $row['vod_name'] . '</a>
                            <span class="hits text-color">' . $row['vod_id'] . '</span></li>';
                } else {
                    $html .= '<li class="list p-0">
                            <a class="pull-left" href="' . AppService::getLink(4, $p) . '" title="' . $row['vod_name'] . '">
                                <em class="num">' . $count . '</em>' . $row['vod_name'] . '</a>
                            <span class="hits text-color">' . $row['vod_id'] . '</span></li>';
                }
                $count++;
            }
            Cache::set($key, $html, App::$redis['timeout_l']);
            self::assign('top_list',$html);
        }
        $story_count = 1;
        $key_story = App::$domain['cacheId'] . ':page_story_dptop';
        $story_list_html = Cache::get($key_story);
        $story_html = '';
        if (!empty($story_list_html)){
            self::assign('rank_story_list',$story_list_html);
        }else{
            $storys = Db::selectAll('select * from mac_story where story_id in('.App::$domain['story_rank_id'].') order by story_id asc');
            foreach ($storys as $sy){
                if ($story_count < 4) {
                    $story_html .= '<li class="list p-0">
                            <a class="pull-left" href="/story/detail_'.$sy['vod_id'].'_'.$sy['story_id'].'.html" title="' . $sy['name'] . '">
                                <em class="num active" class="num">' . $story_count . '</em>' . $sy['name'] . '</a></li>';
                } else {
                    $story_html .= '<li class="list p-0">
                            <a class="pull-left" href="/story/detail_'.$sy['vod_id'].'_'.$sy['story_id'].'.html" title="' . $sy['name'] . '">
                                <em class="num">' . $story_count . '</em>' . $sy['name'] . '</a></li>';
                }
                $story_count ++;
            }
            Cache::set($key_story, $story_html, App::$redis['timeout_l']);
            self::assign('rank_story_list',$story_html);
        }
    }

    public static function setFirstTop($row, $p): void
    {
        $first_html = '<a class="video-pic" href="' . AppService::getLink(4, $p) . '" title="' . $row['vod_name'] . '" style="background: url(' . $row['vod_pic'] . ') no-repeat top center;background-size:cover;">
                            <span class="note text-bg-r">' . $row['vod_remarks'] . '</span>
                            <span class="num">top</span></a>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12" style=" padding-top:0px; padding-right:0px;">
                        <div class="col-md-12 pg-0 text-overflow">' . $row['vod_name'] . '</div>
                        <div class="col-md-12 pg-0 text-overflow">' . $row['vod_area'] . '</div>
                        <div class="col-md-12 p-0 text-overflow">' . $row['vod_class'] . '</div>
                        <div class="col-md-12 pg-0 text-overflow">
                            <span>人气：</span>
                            <span class="hits text-color">
                    <em>' . $row['vod_id'] . '</em></span>
                        </div>
                    </div>';
        Cache::set(App::$domain['cacheId'] . ':page_dptopf', $first_html, App::$redis['timeout_l']);
        App::$content = str_replace('{mv:top_first}', $first_html, App::$content);
    }

    public static function getQParams(): string
    {
        $q = '';
        if (!empty(App::$args['type'])) {
            $q .= 'type_id:' . App::$typeId;
        }
        if (!empty(App::$args['class'])) {
            if (empty($q)) {
                $q .= ' vod_class:' . App::$extend[App::$typeId][App::$args['class']];
            } else {
                $q .= ' AND vod_class:' . App::$extend[App::$typeId][App::$args['class']];
            }
        }
        if (!empty(App::$args['area'])) {
            if (empty($q)) {
                $q .= ' vod_area:' . App::$area[App::$args['area']];
            } else {
                $q .= ' AND vod_area:' . App::$area[App::$args['area']];
            }
        }
        if (!empty(App::$args['year'])) {
            if (empty($q)) {
                $q .= ' vod_year:' . App::$args['year'];
            } else {
                $q .= ' AND vod_year:' . App::$args['year'];
            }
        }
        return $q;
    }

    public static function assign($key,$value): void
    {
        $key = '{mv:'.$key.'}';
        App::$content = str_replace($key, $value,App::$content);
    }
    public static function getActive($type): string
    {
        if ($type == 0) {
            return '';
        }
        if ($type == 1) {
            return 'class="active"';
        }
        if ($type == 2) {
            return 'class="num active"';
        }
        if ($type == 3) {
            return 'class="num"';
        }
        if ($type === 4) {
            return 'active';
        }
        return '';
    }


    public static function getLink($type, $p): string
    {
        if ($type == 1) {
            return '/' . $p . '/';
        }
        if ($type == 2) {
            return '/film/' . $p . '_____.html';
        }
        if ($type == 3) {
            return '/' . App::$type[$p['type_id']] . '/' . $p['class'] . '___1_1.html';
        }
        if ($type == 4) {
            return '/' . App::$type[$p['type_id']] . 'd/' . $p['vod_id'] . '/';
        }
        if ($type == 5) {
            return '/' . $p['dir'] . 'p/' . $p['vod_id'] . '_' . $p['sid'] . '_' . $p['nid'] . '.html';
        }
        if ($type == 6) {
            return '/star/index_' . $p . '.html';
        }

        if ($type == 7) {
            return '/' . App::$type[$p['type_id']] . 'd/' . $p['vod_id'] . '/';
        }
        return '/' . $p . '/';
    }
}