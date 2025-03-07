<?php

class TypeService
{
    /**
     * @throws Exception
     */
    public function load(): void
    {

        AppService::load();
        $this->setTypeShowClassify();
    }

    /**
     * @throws Exception
     */
    public function setTypeShowClassify(): void
    {
        $class = App::$extend[App::$typeId];
        $area = App::$area;
        $year = App::$year;
        $params = array();
        if (empty(App::$args['class'])){
            $pre_title = App::$type_name[App::$typeId];
        }else{
            $pre_title = $class[App::$args['class']].App::$type_name[App::$typeId];
        }
        $params['title'] = '最新'.$pre_title.'_热门'.$pre_title.'大全_'.$pre_title.'排行榜-第'.App::$args['page'].'页-'.App::$domain['name'];
        $params['keywords'] = $pre_title.',最新'.$pre_title.',最新'.$pre_title.'大全,'.$pre_title.'排行榜';
        $params['des'] = App::$domain['name'].'为你提供'.$pre_title.',最新'.$pre_title.',最新'.$pre_title.'大全,'.$pre_title.'排行榜,'.$pre_title.'第'.App::$args['page'].'页';
        AppService::setSEO($params);
        $class_html = '<li><a '.$this->getActive('class','').' href="'.$this->getLink(1,'').'" >全部</a></li>';
        $area_html = '<li><a '.$this->getActive('area','').' href="'.$this->getLink(2,'').'" >全部</a></li>';
        $year_html = '<li><a '.$this->getActive('year','').' href="'.$this->getLink(3,'').'" >全部</a></li>';
        foreach ($class as $k=>$v){
            $class_html = $class_html.'<li><a '.$this->getActive('class',$k).' href="'.$this->getLink(5,$k).'" >'.$v.'</a></li>';
        }
        foreach ($area as $k=>$v){
            $area_html = $area_html.'<li><a '.$this->getActive('area',$k).' href="'.$this->getLink(6,$k).'" >'.$v.'</a></li>';
        }
        foreach ($year as $k=>$v){
            $year_html = $year_html.'<li><a '.$this->getActive('year',$k).' href="'.$this->getLink(7,$k).'" >'.$v.'</a></li>';
        }
        AppService::assign('show_class',$class_html);
        AppService::assign('show_area',$area_html);
        AppService::assign('show_year',$year_html);
        $this->setData();
    }

    /**
     * @throws Exception
     */
    public function setData(): void
    {
        $client = new SolrClient(App::$solr['vod']);
        $client->addField('type_id',App::$typeId,false);
        $client->addField('vod_class',empty(App::$args['class'])?'':App::$extend[App::$typeId][App::$args['class']],false);
        $client->addField('vod_area',empty(App::$args['area'])?'':App::$area[App::$args['area']],false);
        $client->addField('vod_year',App::$args['year'],false);
        $client->setSort('vod_addtime desc');
        $client->setRows(24);
        $client->setStart(App::$args['page']);
        $rows = $client->query();
        $total = $client->getTotal();
        $item_html = '';
        foreach ($rows as $row){
            if (empty($row['vod_actor'])){
                $row['vod_actor'] = '未知';
            }
            $par=array(
                'id'=>$row['vod_id'],
                'en'=>$row['vod_en'],
            );
            $item = '<li class="col-md-2 col-sm-3 col-xs-4">
              <a class="video-pic loading" data-original="'.$row['vod_pic'].'" href="'.$this->getLink(8,$par).'" title="'.$row['vod_name'].'" style="background-image: url('.$row['vod_pic'].')">
                <span class="player"></span>
                <span class="score">'.$row['vod_score'].'</span>
                <span class="note text-bg-r">'.$row['vod_remarks'].'</span></a>
              <div class="title">
                <h5 class="text-overflow">
                  <a href="'.$this->getLink(8,$par).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5>
              </div>
              <div class="subtitle text-muted text-overflow hidden-xs">'.$row['vod_actor'].'</div>
            </li>';
            $item_html = $item_html.$item;
        }
        AppService::assign('dataList',$item_html);
        AppService::assign('data-total',$total);
        AppService::assign('page-now',App::$args['page']);
        $pageurl = '/'.App::$args['dir'].'/'.App::$args['class'].'_'.App::$args['area'].'_'.App::$args['year'].'_{page}_1.html';
        $page = new PageUtils($total, 24, App::$args['page'], $pageurl, 2);
        $page_str = $page->myde_write();
        AppService::assign('pages',$page_str);
    }

    public function getLink($type,$p): string
    {
        $path = '';
        switch ($type){
            case 1:
                $path = '/'.App::$args['dir'].'/_'.App::$args['area'].'_'.App::$args['year'].'_1';
                break;
            case 2:
                $path = '/'.App::$args['dir'].'/'.App::$args['class'].'__'.App::$args['year'].'_1';
                break;
            case 3:
                $path = '/'.App::$args['dir'].'/'.App::$args['class'].'_'.App::$args['area'].'__1';
                break;
            case 4:
                $path = '/'.App::$args['dir'].'/'.App::$args['class'].'_'.App::$args['area'].'_'.App::$args['year'].'_1';
                break;
            case 5:
                $path = '/'.App::$args['dir'].'/'.$p.'_'.App::$args['area'].'_'.App::$args['year'].'_1';
                break;
            case 6:
                $path = '/'.App::$args['dir'].'/'.App::$args['class'].'_'.$p.'_'.App::$args['year'].'_1';
                break;
            case 7:
                $path = '/'.App::$args['dir'].'/'.App::$args['class'].'_'.App::$args['area'].'_'.$p.'_1';
                break;
            case 8:
                return '/'.App::$args['dir'].'d/'.$p['id'].'/';
        }
        return $path.'.html';
    }

    public function getActive($type,$p): string
    {
        $style = '';
        if (App::$args[$type] == $p){
            $style = 'class="active"';
        }
        return $style;
    }
}