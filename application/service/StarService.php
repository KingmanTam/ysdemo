<?php

class StarService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {
        if (App::$args['method']=='index'){
            App::$method = 'star';
        }
        if (App::$args['method']=='detail'){
            App::$method = 'star_detail';
        }
        if (App::$args['method']=='works'){
            App::$method = 'star_works';
        }
        AppService::load();
        $this->setIndexData();
    }

    /**
     * @throws Exception
     */
    public function setIndexData(): void
    {
        $rows = Db::selectAll('select * from mac_actor where actor_id<=' . App::$args['id'].' ORDER BY actor_id desc LIMIT 4');
        if (empty($rows)){
            throw new Exception();
        }
        $count = 1;
        $hot_star = '';
        foreach ($rows as $row){
            if ($count==1){
                AppService::assign('actor_pic',$row['actor_pic']);
                AppService::assign('actor_id',$row['actor_id']);
                AppService::assign('actor_up',$row['actor_up']);
                AppService::assign('actor_down',$row['actor_down']);
                AppService::assign('actor_sex',$row['actor_sex']);
                AppService::assign('actor_remarks',$row['actor_remarks']);
                AppService::assign('actor_height',$row['actor_height']);
                AppService::assign('actor_weight',$row['actor_weight']);
                AppService::assign('actor_starsign',$row['actor_starsign']);
                AppService::assign('actor_blood',$row['actor_blood']);
                AppService::assign('actor_name',$row['actor_name']);
                AppService::assign('actor_en',$row['actor_en']);
                AppService::assign('actor_alias',$row['actor_alias']);
                AppService::assign('actor_birthday',$row['actor_birthday']);
                AppService::assign('actor_birtharea',$row['actor_birtharea']);
                AppService::assign('actor_school',$row['actor_school']);
                AppService::assign('actor_blurb',$row['actor_blurb']);
                AppService::assign('actor_area',$row['actor_area']);
                AppService::assign('actor_blurb',$row['actor_blurb']);
            }else{
                $hot_star.='<li class="col-md-4 col-sm-4 col-xs-4 active">
                <a class="star-pic loading img-circle" data-original="'.$row['actor_pic'].'" style="padding-top:100%;" href="'.AppService::getLink(6,$row['actor_id']).'"><span>'.$row['actor_name'].'</span></a>
              </li>';
            }
            $count++;
        }
        AppService::assign('hot_star',$hot_star);
        $params = array();
        if (App::$args['method']=='index'){
            $this->setList($rows[0]['actor_name']);
            $params['title'] = $rows[0]['actor_name'].'最新电影_'.$rows[0]['actor_name'].'最新电视剧_'.$rows[0]['actor_name'].'详细资料'.'-明星-'.App::$domain['name'];
            $params['keywords'] = $rows[0]['actor_name'].'最新电影,'.$rows[0]['actor_name'].'最新电视剧,'.$rows[0]['actor_name'].'最新综艺节目,'.$rows[0]['actor_name'].'影视作品大全';
            $params['des'] = App::$domain['name'].'提供你喜欢的明星'.$rows[0]['actor_name'].'的全部资料,'.$rows[0]['actor_name'].'主演的电视剧、电影和综艺节目,在线免费观看'.$rows[0]['actor_name'].'全部影视作品';
            AppService::setSEO($params);
        }
        if (App::$args['method']=='detail'){
            $params['title'] = $rows[0]['actor_name'].'详细资料_'.$rows[0]['actor_name'].'演艺经历_'.$rows[0]['actor_name'].'艺术成就'.'-明星-'.App::$domain['name'];
            $params['keywords'] = $rows[0]['actor_name'].'详细资料,'.$rows[0]['actor_name'].'演艺经历,'.$rows[0]['actor_name'].'艺术成就';
            $params['des'] = App::$domain['name'].'提供你喜欢的'.$rows[0]['actor_name'].'的全部资料,'.$rows[0]['actor_name'].$rows[0]['actor_blurb'];
            AppService::setSEO($params);
            $this->setDetail($rows[0]['actor_content']);
        }
        if (App::$args['method']=='works'){
            $params['title'] = $rows[0]['actor_name'].'参演的电影_'.$rows[0]['actor_name'].'参演的电视剧_'.$rows[0]['actor_name'].'全部影视作品在线观看'.'-明星-'.App::$domain['name'];
            $params['keywords'] = $rows[0]['actor_name'].'参演的电影,'.$rows[0]['actor_name'].'参演的电视剧,'.$rows[0]['actor_name'].'全部影视作品在线观看';
            $params['des'] = App::$domain['name'].'提供你喜欢的'.$rows[0]['actor_name'].'的全部资料,'.$rows[0]['actor_name'].$rows[0]['actor_blurb'];
            AppService::setSEO($params);
            $this->setAllWorks($rows[0]['actor_name']);
        }
    }


    public function setList($name): void
    {
        $client = new SolrClient(App::$solr['vod']);
        $client->addField('vod_actor',$name,true);
        $client->setRows(12);
        $rows = $client->query();
        $item = '';
        foreach ($rows as $row) {
            $par=array(
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            );
            $item.= '<li class="col-md-2 col-sm-3 col-xs-4">
              <a class="video-pic loading" data-original="'.$row['vod_pic'].'" href="'.AppService::getLink(7,$par).'" title="'.$row['vod_name'].'" style="background-image: url('.$row['vod_pic'].'">
                <span class="player"></span>
                <span class="score">'.$row['vod_score'].'</span>
                <span class="note text-bg-r">'.$row['vod_remarks'].'</span></a>
              <div class="title">
                <h5 class="text-overflow">
                  <a href="'.AppService::getLink(7,$par).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5>
              </div>
              <div class="subtitle text-muted text-overflow hidden-xs">'.$row['vod_actor'].'</div>
            </li>';
        }
        AppService::assign('star_zp_list',$item);
    }


    public function setAllWorks($name): void
    {
        $client = new SolrClient(App::$solr['vod']);
        $client->addField('vod_actor',$name,true);
        $client->setRows(24);
        $client->setStart(App::$args['page']);
        $rows = $client->query();
        $total = $client->getTotal();
        $item = '';
        foreach ($rows as $row) {
            $par=array(
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            );
            $item.= '<li class="col-md-2 col-sm-3 col-xs-4">
              <a class="video-pic loading" data-original="'.$row['vod_pic'].'" href="'.AppService::getLink(7,$par).'" title="'.$row['vod_name'].'" style="background-image: url('.$row['vod_pic'].'">
                <span class="player"></span>
                <span class="score">'.$row['vod_score'].'</span>
                <span class="note text-bg-r">'.$row['vod_remarks'].'</span></a>
              <div class="title">
                <h5 class="text-overflow">
                  <a href="'.AppService::getLink(7,$par).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5>
              </div>
              <div class="subtitle text-muted text-overflow hidden-xs">'.$row['vod_actor'].'</div>
            </li>';
        }
        $page_url = '/star/works_'.App::$args['id'].'_{page}.html';
        $page = new PageUtils($total, 24, App::$args['page'], $page_url, 2);
        $page_str = $page->myde_write();
        AppService::assign('pages',$page_str);
        AppService::assign('data-total',$total);
        AppService::assign('page-now',App::$args['page']);
        AppService::assign('star_zp_list',$item);
    }

    public function setDetail($content): void
    {
        AppService::assign('star_content',$content);
    }
}