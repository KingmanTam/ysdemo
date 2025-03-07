<?php

class Domain
{

    public static function setInfo($domain): void
    {
        $domains = self::getDomain();
        $v = $domains[$domain];
        if (!empty($v)) {
            app::$domain['title'] = $v['title'];
            app::$domain['domain'] = $v['domain'];
            app::$domain['name'] = $v['name'];
            app::$domain['keywords'] = $v['keywords'];
            app::$domain['description'] = $v['description'];
            app::$domain['template'] = $v['template'];
            app::$domain['vod_rank_id'] = $v['vod_rank_id'];
            app::$domain['story_rank_id'] = $v['vod_rank_id'];
            app::$domain['cacheId'] = $v['cacheId'];
        }
    }

    public static function getDomain(): array
    {
        return array(
            "jupiter.com" => array(
                'title' => '木星影院1-2023最新电视剧-高清影视-最新电影电视剧综艺在线观看',
                'domain' => 'jupiter.com',
                'name' => '木星影院1',
                'keywords' => '木星影院1,超清影视,最新电影在线观看,最新最热门免费电影电视剧,最新一期综艺,免费电影网',
                'description' => '木星影院1为您提供2023最新最热门的电视剧、电影大全、电视剧大全免费在线观看和迅雷电影免费下载，每天更新最新抢先电影大片，热门电视剧，最新综艺真人秀，明星信息与相关电影电视剧，同时提供影视剧情、电视剧演员表等相关内容',
                'template' => 'template/',
                'vod_rank_id' => '199877,218824,228649,233586,238547,243184,247132,250110,256088,268734',
                'story_rank_id' => '12465,19563,26541,38965,49652,59645,56321,64856,78952,79563',
                'cacheId' => 'jupiter'
            ),
            "mars.com" => array(
                'title' => '8点影院-2023最新电视剧-高清影视-最新电影电视剧综艺在线观看',
                'domain' => 'mars.com',
                'name' => '8点影院',
                'keywords' => '8点影院,超清影视,最新电影在线观看,最新最热门免费电影电视剧,最新一期综艺,免费电影网',
                'description' => '8点影院为您提供2023最新最热门的电视剧、电影大全、电视剧大全免费在线观看和迅雷电影免费下载，每天更新最新抢先电影大片，热门电视剧，最新综艺真人秀，明星信息与相关电影电视剧，同时提供影视剧情、电视剧演员表等相关内容',
                'template' => 'template/',
                'cacheId' => 'mars'
            ),
        );
    }
}