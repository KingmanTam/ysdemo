<?php

class RankService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        AppService::setCommon();
        $params = array();
        $title = '最新热门电视剧电影综艺动漫排行榜';
        if (App::$id==1){
            $title = '热门电视剧排行榜,最新电视剧榜单';
        }
        if (App::$id==2){
            $title = '热门电影排行榜,最新电影榜单';
        }
        if (App::$id==3){
            $title = '热门动漫排行榜,最新动漫榜单';
        }
        if (App::$id==4){
            $title = '热门综艺排行榜,最新综艺榜单';
        }
        if (App::$id==5){
            $title = '热门纪录片排行榜,最新纪录片榜单';
        }
        $params['title'] = $title.App::$domain['name'];
        $params['keywords'] = $title;
        $params['des'] = App::$domain['name'].'为你提供最新'.$title;
        AppService::setSEO($params);
        $this->setData();
    }

    /**
     * @throws Exception
     */
    public function setData(): void
    {
        App::$content = str_replace('{mv:active'.App::$id.'}', 'active',App::$content);
        if (App::$id==0){
            $this->setRankIndex();
        }else{
            $this->setRankClassify();
        }
    }

    /**
     * @throws Exception
     */
    public function setRankIndex(): void
    {
        $html = '';
        for ($i = 1; $i < 6; $i++) {
            $rows = Db::selectAll('select * from mac_vod where type_id=' . $i.' order by vod_hits_month desc limit 10');
            $top_head = '<div class="col-md-4 col-sm-12 col-xs-12 active">
                              <div class="box-title pg-0">
                                <h3 class="m-0"><i class="icon iconfont text-color">&#xe66e;</i>'.App::$type_name[$i].'</h3>
                                <div class="more pull-right">
                                  <a href="/top/'.$i.'.html" class="text-muted" title="更多">更多 <i class="icon iconfont">&#xe65e;</i></a>
                                </div>
                              </div>
                              <div class="box-video-text-list">
                                <ul class="clearfix p-0 m-0">';
            $num = 1;
            foreach ($rows as $row){
                $active = 0;
                if ($num<4){
                    $active = 4;
                }
                $top_head.='<li class="list p-0"><a class="pull-left" href="/" title="'.$row['vod_name'].'"><em class="num '.AppService::getActive($active).'"> '.$num.'</em>'.$row['vod_name'].'</a>
                            <span class="hits text-color">'.$row['vod_score'].'</span>
                            </li>';
                $num++;
            }
            $top_head.= '</ul></div></div>';
            $html.=$top_head;
        }
        AppService::assign('topList',$html);
    }

    /**
     * @throws Exception
     */
    public function setRankClassify(): void
    {
        $rows = Db::selectAll('select * from mac_vod where type_id=' . App::$id . ' order by vod_hits_month desc limit 60');
        $top_body = '<div class="box-title">
                    <h3 class="m-0"><i class="icon iconfont text-color">&#xe66e;</i>'.App::$type_name[App::$id].'榜单TOP60</h3>
                    <div class="more pull-right"><span class="text-muted pull-right">共<span class="text-color">“60”</span>个视频</div>
                    </div>';
        $count = 1;
        foreach ($rows as $row){
            $item = '<div class="col-md-2 col-sm-4 col-xs-4 hotlist clearfix">
            <a class="video-pic loading"  href="/" title="'.$row['vod_name'].'" style="background-image: url('.$row['vod_pic'].');">
            <span class="top"><em>'.$count.'</em></span>
            <span class="tips red">'.$row['vod_score'].'</span>
            <span class="player"></span>
            <span class="note text-bg-r">'.$row['vod_remarks'].'</span>
            </a>
            <div class="title"><h5 class="text-overflow"><a href="" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5></div>
            </div>';
            $top_body.=$item;
            $count++;

        }
        AppService::assign('topList',$top_body);
    }
}