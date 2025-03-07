<?php

class actorService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        $this->setData();
    }


    public function setData(): void
    {
        $params = array();
        $params['title'] = '明星_最新明星大全_娱乐明星推荐第'.App::$args['page'].'页-'.App::$domain['name'];
        $params['keywords'] = '明星,最新明星大全,娱乐明星推荐,明星最新电影,明星最新电视剧';
        $params['des'] = App::$domain['name'].'提供你喜欢的国内外明星资料,明星作品大全，最新明星电影推荐，最新明星电视剧推荐，明星影视在线免费观看';
        AppService::setSEO($params);
        $rows = Db::selectAll('select * from mac_actor order by actor_id limit '.((App::$args['page']-1)*30).',30');
        $total_row = Db::selectOne('select count(actor_id) as num from mac_actor');
        $item = '';
        foreach ($rows as $row){
            $item.='<li class="col-md-2 col-sm-3 col-xs-4">
                    <a class="video-pic loading" data-original="'.$row['actor_pic'].'" href="'.AppService::getLink(6,$row['actor_id']).'" title="'.$row['actor_name'].'" style="background-image: url('.$row['actor_pic'].');">
                      <span class="flower hidden-xs"></span><span class="fnum hidden-xs">'.$row['actor_hits'].'</span><span class="note text-bg-c">'.$row['actor_name'].'</span>
                    </a>
                  </li>';

        }
        $pageurl = '/actor/{page}.html';
        $page = new PageUtils($total_row['num'], 30, App::$args['page'], $pageurl, 2);
        $page_str = $page->myde_write();
        AppService::assign('pages',$page_str);
        AppService::assign('page_num',App::$args['page']);
        AppService::assign('total',$total_row['num']);
        AppService::assign('actor_list',$item);
    }
}