<?php

class PlayService
{

    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        $this->setData();
    }


    /**
     * @throws Exception
     */
    public function setData(): void
    {
        $key = 'vod:vod_'.App::$id;
        $row = Cache::get($key);
        if (empty($row)){
            $row = Db::selectOne('select * from mac_vod where vod_id='.App::$id);
            Cache::set($key,json_encode($row),App::$redis['timeout_l']);
        }else{
            $row = json_decode($row,true);
        }
        if (empty($row)){
            throw new Exception();
        }
        $params = array();
        if ($row['type_id']==2){
            $params['title'] = $row['vod_name'].'在线播放完整版'.'-'.App::$domain['name'].'高清资源免费观看下载';
        }else{
            $params['title'] = $row['vod_name'].'第'.App::$args['nid'].'集在线播放'.'-'.App::$domain['name'].'高清资源免费观看下载';
        }
        $params['keywords'] = $row['vod_name'].'正在播放,'.$row['vod_name'].'高清在线免费观看';
        $params['des'] = App::$domain['name'].'提供你喜欢的'.$row['vod_name'].'剧情:'.$row['vod_content'];
        AppService::setSEO($params);
        AppService::assign('type_name',App::$type_name[$row['type_id']]);
        AppService::assign('vod_name',$row['vod_name']);
        AppService::assign('type_url',App::$type[$row['type_id']]);
        AppService::assign('vod_id',$row['vod_id']);
        AppService::assign('type_id',$row['type_id']);
        AppService::assign('vod_up',$row['vod_up']);
        AppService::assign('vod_down',$row['vod_down']);
        AppService::assign('vod_content',$row['vod_content']);
        AppService::assign('vod_year',$row['vod_year']);
        AppService::assign('vod_class',$row['vod_class']);
        $play_from = $row['vod_play_from'];
        $fromList = explode('$$$', $play_from);
        $play_from_html1 = '';
        $count = 1;
        $player = '';
        foreach ($fromList as $f){
            if ($count==App::$args['sid']){
                $player = $f;
                App::$content = str_replace('{mv:current_from}', App::$player_name[$f],App::$content);
            }
            $from_html1 = '<li><a class="gico '.$f.'" href="#con_playlist_'.$count.'" tabindex="-1" data-toggle="tab">'.App::$player_name[$f].'</a></li>';
            $count++;
            $play_from_html1.=$from_html1;
        }
        App::$content = str_replace('{mv:play_from1}', $play_from_html1,App::$content);
        $play_url = $row['vod_play_url'];
        $play_url_arr = explode('$$$',$play_url);
        $count = 1;
        $url_html = '';
        $f_dir = App::$type[$row['type_id']];
        $next_data = [
            'pre'=>false,
            'next'=>false,
        ];
        foreach ($play_url_arr as $urls){
            $active = 0;
            if ($count==App::$args['sid']){
                $active = 4;
            }
            $play_url_list = explode('#',$urls);
            $play_url_html = '<ul class="clearfix fade in '.AppService::getActive($active).'" id="con_playlist_'.$count.'">';
            $nid_num=1;
            $pre_data= [];

            foreach ($play_url_list as $item) {
                $p=[
                    'dir'=>$f_dir,
                    'vod_id'=>$row['vod_id'],
                    'vod_en'=>$row['vod_en'],
                    'sid'=>$count,
                    'nid'=>$nid_num,
                ];
                $js = explode('$', $item);
                if (App::$args['sid']==$count&&(App::$args['nid']+1)==$nid_num){
                    $next_data['next'] = true;
                    $next_data['next_data'] = $p;
                }
                if (App::$args['sid']==$count&&App::$args['nid']==$nid_num){
                    if ($nid_num!=1){
                        $next_data['pre'] = true;
                        $next_data['pre_data'] = $pre_data;
                    }
                    $player_url = App::$player[$player];
                    App::$content = str_replace('{mv:play_url_now}', $player_url.$js[1],App::$content);
                    $item_html = '<li><a class="active" href="'.AppService::getLink(5,$p).'" >'.$js[0].'</a></li>';
                }else{
                    $item_html = '<li><a href="'.AppService::getLink(5,$p).'" >'.$js[0].'</a></li>';
                }

                $play_url_html.=$item_html;
                $nid_num++;
                $pre_data = $p;
            }
            $play_url_html.='</ul>';
            $count++;
            $url_html.=$play_url_html;
        }
        $this->setPlayNext($next_data);
        AppService::assign('play_url',$url_html);
        $this->setRecommend();
    }

    public function setPlayNext($p): void
    {
        if ($p['pre']){
            $pre_html = '<a rel="nofollow" class="b-l b-r" href="'.AppService::getLink(5,$p['pre_data']).'" title="上一集" id="pre">
              <i class="iconfont hidden-xs hidden-sm">&#xe8ff;</i>上一集</a>';
        }else{
            $pre_html = '<a rel="nofollow" class="b-l b-r"  title="没有了" id="pre" target="_self">
              <i class="iconfont hidden-xs hidden-sm">&#xe8ff;</i>没有了</a>';
        }
        if ($p['next']){
            $next_html = '<a href="'.AppService::getLink(5,$p['next_data']).'" title="下一集" class="b-l" id="next" >下一集
              <i class="iconfont hidden-xs hidden-sm">&#xe65e;</i></a>';
        }else{
            $next_html = '<a  title="下一集" class="b-l" id="next">没有了
              <i class="iconfont hidden-xs hidden-sm">&#xe65e;</i></a>';
        }
        AppService::assign('pre_link',$pre_html);
        AppService::assign('next_link',$next_html);
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
        AppService::assign('recommend',$html);
        AppService::setDPTop();
    }
}