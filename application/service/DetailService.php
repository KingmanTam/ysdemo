<?php

class DetailService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        $this->setDetails();
    }

    /**
     * @throws Exception
     */
    public function setDetails(): void
    {
        $row = Cache::get('vod:vod_'.App::$id);
        if (empty($row)){
            $row = Db::selectOne('select * from mac_vod where vod_id=' . App::$id);
            Cache::set('vod:vod_'.App::$id,json_encode($row),App::$redis['timeout']);
        }else{
            $row = json_decode($row,true);
        }
        if (empty($row)){
            throw new Exception('资源未找到！');
        }
        $params = array();
        if ($row['type_id'] == 6){
            $params['title'] = $row['vod_name'].'_'.$row['vod_class'].'-'.App::$domain['name'];
            $params['keywords'] = $row['vod_name'];
        }else{
            $params['title'] = $row['vod_name'].'详情简绍_'.App::$type_name[$row['type_id']].'-'.App::$domain['name'];
            $params['keywords'] = $row['vod_name'].'剧情简绍,'.$row['vod_name'].'免费下载,'.$row['vod_name'].'高清资源';
        }
        $params['des'] = App::$domain['name'].'提供你喜欢的'.$row['vod_name'].'剧情:'.$row['vod_content'];
        AppService::setSEO($params);
        AppService::assign('vod_name',$row['vod_name']);
        AppService::assign('vod_pic',$row['vod_pic']);
        AppService::assign('vod_id',$row['vod_id']);
        AppService::assign('type_id',$row['type_id']);
        AppService::assign('vod_score',$row['vod_score']);
        AppService::assign('vod_remarks',$row['vod_remarks']);
        AppService::assign('vod_actor',$row['vod_actor']);
        AppService::assign('vod_year',$row['vod_year']);
        AppService::assign('vod_class',$row['vod_class']);
        AppService::assign('vod_director',$row['vod_director']);
        AppService::assign('vod_area',$row['vod_area']);
        AppService::assign('vod_duration',$row['vod_duration']);
        AppService::assign('vod_lang',$row['vod_lang']);
        AppService::assign('vod_year',$row['vod_year']);
        AppService::assign('vod_time',date('Y-m-d',$row['vod_time']));
        AppService::assign('vod_blurb',$row['vod_blurb']);
        AppService::assign('vod_content',$row['vod_content']);
        $play_from = $row['vod_play_from'];
        $fromList = explode('$$$', $play_from);
        $play_from_html = '';
        $play_from_html1 = '';
        $count = 1;
        foreach ($fromList as $f){
            $active = 0;
            if ($count==1){
                $active = 4;
                App::$content = str_replace('{mv:current_from}', $f,App::$content);
            }
            $from_html = '<li class="hidden-xs '.AppService::getActive($active).'">
                        <a class="gico '.$f.'" href="#con_playlist_'.$count.'" data-toggle="tab">'.App::$player_name[$f].'</a></li>';
            $from_html1 = '<li><a class="gico '.$f.'" href="#con_playlist_'.$count.'" tabindex="-1" data-toggle="tab">'.App::$player_name[$f].'</a></li>';
            $count++;
            $play_from_html.=$from_html;
            $play_from_html1.=$from_html1;
        }
        AppService::assign('play_from',$play_from_html);
        AppService::assign('play_from1',$play_from_html1);
        $play_url = $row['vod_play_url'];
        $play_url_arr = explode('$$$',$play_url);
        $count = 1;
        $url_html = '';
        $f_dir = App::$type[$row['type_id']];
        foreach ($play_url_arr as $urls){
            $active = 0;
            if ($count==1){
                $active = 4;
            }
            $play_url_list = explode('#',$urls);
            $play_url_html = '<ul class="clearfix fade in '.AppService::getActive($active).'" id="con_playlist_'.$count.'">';
            $nid_num=1;
            foreach ($play_url_list as $item) {
                $p=[
                    'dir'=>$f_dir,
                    'vod_id'=>$row['vod_id'],
                    'vod_en'=>$row['vod_en'],
                    'sid'=>$count,
                    'nid'=>$nid_num,
                ];
                $js = explode('$', $item);
                $item_html = '<li><a href="'.AppService::getLink(5,$p).'" >'.$js[0].'</a></li>';
                $play_url_html.=$item_html;
                $nid_num++;
            }
            $play_url_html.='</ul>';
            $count++;
            $url_html.=$play_url_html;
        }

        App::$content = str_replace('{mv:play_url}', $url_html,App::$content);
        $this->setRecommend();
    }

    /**
     * @throws Exception
     */
    public function setRecommend(): void
    {
        $client = new SolrClient(App::$solr['vod']);
        $client->addField('vod_id','[* TO '.App::$id.'}');
        $client->setRows(12);
        $rows = $client->query();
        $html = '';
        foreach ($rows as $row){
            $p=[
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            ];
            $item = '<li class="col-md-2 col-sm-3 col-xs-4">
              <a class="video-pic loading" data-original="'.$row['vod_pic'].'" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'" style="background-image: url('.$row['vod_pic'].'">
                <span class="player"></span>
                <span class="score">'.$row['vod_score'].'</span>
                <span class="note text-bg-r">'.$row['vod_remarks'].'</span></a>
              <div class="title">
                <h5 class="text-overflow">
                  <a href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5>
              </div>
              <div class="subtitle text-muted text-overflow hidden-xs">'.$row['vod_actor'].'</div>
            </li>';
            $html.=$item;
        }
        App::$content = str_replace('{mv:recommend}', $html,App::$content);
        $this->setStory();
        AppService::setDPTop();
    }

    public function setStory(): void
    {
        $row = Db::selectOne('select * from mac_story where vod_id=' . App::$id . ' order by epis desc limit 1');
        $html = '';
        if (!empty($row)){
            $content = mb_substr($row['content'], 0,100);
            $html = '<div class="layout-box clearfix details-story">
                        <div class="box-title"><h3 class="m-0">'.$row['name'].'分集剧情</h3>
                            <div class="more pull-right"><a href="/story/'.App::$id.'/" class="text-muted" title="更多">更多 <i
                                    class="icon iconfont"></i></a></div>
                        </div>
                        <div class="item"><h4 class="text-overflow"><a class="pull-left" href="/story/detail-'.App::$id.'_'.$row['story_id'].'.html"
                                                                       target="_blank">'.$row['name'].'<em>第'.$row['epis'].'集剧情</em><span
                                class="hidden-xs"></span></a><a class="pull-right" href="/story/detail_'.App::$id.'_'.$row['story_id'].'.html"
                                                                title="第'.$row['epis'].'集剧情" target="_blank">查看详细 <i
                                class="icon iconfont"></i></a></h4>
                            <div class="details-content">
                                '.$content.'...</p>
                            </div>
                        </div>
                    </div>';
        }
        App::$content = str_replace('{mv:vod_story}', $html,App::$content);
    }

}