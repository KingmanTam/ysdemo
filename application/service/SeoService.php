<?php

class SeoService
{
    public function robots(): void
    {
        $html = Cache::get(App::$domain['cacheId'] . ':robots');
        if (empty($html)){
            $html = file_get_contents('template/film/html/public/robots.html');
            Cache::set(App::$domain['cacheId'] . ':robots',$html,App::$redis['timeout_l']);
        }
        App::$content = str_replace('{mv:site_url}', App::$site_url,$html);
    }

    /**
     * @throws Exception
     */
    public function sitemap(): void
    {
        $html = Cache::get(App::$domain['cacheId'] . ':sitemap');
        if (empty($html)){
            $html = file_get_contents('template/film/html/public/map.html');
            Cache::set(App::$domain['cacheId'] . ':sitemap',$html,App::$redis['timeout_l']);
        }
        $rows = Db::selectAll('select * from mac_vod order by vod_time_add DESC limit 500');
        $item_html = '';
        foreach ($rows as $row){
            $item_html.='<url>
                            <loc>http://'.App::$site_url.'/'.App::$type[$row['type_id']].'d/'.$row['vod_id'].'/</loc>
                            <lastmod>'.date('Y-m-d',$row['vod_time_add']).'</lastmod>
                            <changefreq>daily</changefreq>
                            <priority>0.8</priority>
                          </url>';
        }
        App::$content = str_replace('{mv:sitemap}', $item_html,$html);
    }

    public function content(): void
    {
        $html = Cache::get(App::$domain['cacheId'] . ':content');
        if (empty($html)){
            $html = file_get_contents('template/film/html/public/content.html');
            Cache::set(App::$domain['cacheId'] . ':sitemap',$html,App::$redis['timeout_l']);
        }
        $row = Db::selectOne('select * from mac_vod where vod_id='.App::$id);
        App::$content = str_replace('{mv:content}', $row['vod_content'],$html);
    }
}