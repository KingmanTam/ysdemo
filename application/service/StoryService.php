<?php

class StoryService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        if (App::$method=='story'){
            $this->setLastUpdate();
        }else{
            $this->setIndexDetail();;
        }
    }

    /**
     * @throws Exception
     */
    public function setLastUpdate(): void
    {
        $params['title'] = '最新电视剧剧情_电视剧全集剧情简绍-第'.App::$args['page'].'页'.App::$domain['name'];
        $params['keywords'] = '最新电视剧剧情,电视剧全集剧情简绍';
        $params['des'] = App::$domain['name'].'提供你喜欢的电视剧剧情经典台词,电视剧分集简绍等，更多剧情清关注'.App::$domain['name'];
        AppService::setSEO($params);
        $client = new SolrClient(App::$solr['story']);
        $client->setRows(15);
        $client->setStart(App::$args['page']);
        $client->setSort('add_time desc');
        $rows = $client->query();
        $total = $client->getTotal();
        $item_html = '';
        $count = (App::$args['page']-1)*15+1;
        foreach ($rows as $row){
            $item_html.='<li class="list1 text-overflow"><em class="key">'.$count.'.</em><a href="/story/detail_'.$row['vod_id'].'_'.$row['story_id'].'.html"
                                                                                      title="'.$row['name'].'第'.$row['epis'].'集分集剧情"><span
                                    class="time pull-right hidden-xs">'.date('Y-m-d,H:i:s',$row['add_time']).'</span>'.$row['name'].'分集剧情介绍第'.$row['epis'].'集剧情</a>
                            </li>';
            $count++;
        }
        $pageurl = '/story/index_{page}.html';
        $page = new PageUtils($total, 15, App::$args['page'], $pageurl, 4);
        $page_str = $page->myde_write();
        AppService::assign('pages',$page_str);
        AppService::assign('story_total',$total);
        AppService::assign('page',App::$args['page']);
        AppService::assign('story_list',$item_html);
        AppService::setDPTop();
    }


    /**
     * @throws Exception
     */
    public function setIndexDetail(): void
    {
        $row = Db::selectOne('select * from mac_vod where vod_id=' . App::$args['vod_id']);
        if (empty($row)){
            throw new Exception();
        }
        $detail_link = '/'.App::$type[$row['type_id']].'d/'.$row['vod_id'].'.html';
        AppService::assign('detail_link',$detail_link);
        AppService::assign('vod_remarks',$row['vod_remarks']);
        AppService::assign('vod_id',App::$args['vod_id']);
        AppService::assign('vod_class',$row['vod_class']);
        AppService::assign('vod_actor',$row['vod_actor']);
        AppService::assign('vod_year',$row['vod_year']);
        AppService::assign('vod_pic',$row['vod_pic']);
        AppService::assign('vod_director',$row['vod_director']);
        AppService::assign('vod_area',$row['vod_area']);
        AppService::assign('vod_lang',$row['vod_lang']);
        AppService::assign('vod_area',$row['vod_area']);
        AppService::assign('vod_blurb',$row['vod_blurb']);
        if (App::$args['story_method']=='all'){
            AppService::assign('story_content',$row['vod_content']);
        }
        AppService::assign('vod_time',date('Y-m-d,H:i:s',$row['vod_time']));
        $client = new SolrClient(App::$solr['story']);
        $client->addField('vod_id',App::$args['vod_id'],false);
        $client->setRows(200);
        $client->setSort('epis desc');
        $client->setSort('add_time desc');
        $rows = $client->query();
        if (App::$args['story_method']=='all'){
            $this->setIndexEpis($rows,$row);
        }else{
            $this->setDetailEpis($rows);
        }
    }

    /**
     * @throws Exception
     */
    public function setIndexEpis($rows, $vod): void
    {
        $item_html = '';
        $last_num = 1;
        $story_name = '';
        foreach ($rows as $row){
            $last_num = $row['epis'];
            $story_name = $row['name'];
            $item_html.='<li><a href="/story/detail_'.App::$args['vod_id'].'_'.$row['story_id'].'.html">第'.$row['epis'].'集</a></li>';
        }
        if (empty($story_name)){
            $story_name = $vod['vod_name'];
        }
        $params = array();
        $params['title'] = $story_name.'剧情简绍_'.$story_name.'全集剧情_'.$story_name.'分集剧情简绍-剧情-'.App::$domain['name'];
        $params['keywords'] = $story_name.'剧情简绍,'.$story_name.'全集剧情,'.$story_name.'分集剧情简绍';
        $params['des'] = App::$domain['name'].'提供你喜欢的电视剧'.$story_name.'全集剧情和分集剧情等,更多剧情请关注'.App::$domain['name'];
        AppService::setSEO($params);
        AppService::assign('story_title',$story_name.'剧情简绍');
        AppService::assign('story_name',$story_name);
        AppService::assign('story_last',$last_num);
        AppService::assign('story_epis',$item_html);
        $this->setRecommend();
    }


    /**
     * @throws Exception
     */
    public function setDetailEpis($rows): void
    {
        $item_html = '';
        $last_num = 1;
        $story_name = '';
        $story_content = '';
        $epis = 1;
        foreach ($rows as $row){
            $last_num = $row['epis'];
            $story_name = $row['name'];
            if ($row['story_id'] == App::$args['story_id']){
                $story_content = $row['content'];
                $epis = $row['epis'];
                $item_html.='<li><a class="active" href="/story/detail_'.App::$args['vod_id'].'_'.$row['story_id'].'.html">第'.$row['epis'].'集</a></li>';
            }else{
                $item_html.='<li><a href="/story/detail_'.App::$args['vod_id'].'_'.$row['story_id'].'.html">第'.$row['epis'].'集</a></li>';
            }
        }
        $params = array();
        $params['title'] = $story_name.'第'.$epis.'集剧情简绍-剧情-'.App::$domain['name'];
        $params['keywords'] = $story_name.'第'.$epis.'集剧情简绍';
        $params['des'] = App::$domain['name'].'提供你喜欢的电视剧'.$story_name.'第'.$epis.'集剧情简绍,更多剧情请关注'.App::$domain['name'];
        AppService::setSEO($params);
        AppService::assign('story_title',$story_name.'第'.$epis.'集剧情简绍');
        AppService::assign('story_name',$story_name);
        AppService::assign('story_content',$story_content);
        AppService::assign('story_last',$last_num);
        AppService::assign('story_epis',$item_html);
        $this->setRecommend();
    }
    /**
     * @throws Exception
     */
    public function setRecommend(): void
    {
        $rows = Db::selectAll('select * from mac_vod where vod_id<'.App::$args['vod_id'].' order by vod_id desc limit 12');
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