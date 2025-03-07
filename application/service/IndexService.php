<?php

class IndexService
{

    /**
     * @throws Exception
     */
    public function load(): void
    {
        AppService::load();
        $params = array();
        $params['title'] = App::$domain['title'];
        $params['keywords'] = App::$domain['keywords'];
        $params['des'] = App::$domain['description'];
        AppService::setSEO($params);
        $this->setSlide();
        $this->setIndexClassify();
    }

    /**
     * @throws Exception
     */
    public function setSlide(): void
    {
        $key = App::$domain['cacheId'].':page_index_slide';
        $slide = Cache::get($key);
        if (empty($slide)){
            $rows = Db::selectAll('select * from mac_vod where vod_pic_slide <>\'\'');
            $slide = '';
            foreach ($rows as $row){
                $p=[
                    'type_id'=>$row['type_id'],
                    'vod_id'=>$row['vod_id'],
                    'vod_en'=>$row['vod_en'],
                ];
                $slide = $slide.'<div class="swiper-slide">
                            <div class="box-video-slide">
                                <a class="slide-pic swiper-lazy" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'" style="padding-top:60%;background-position:50% 50%;background-size:cover;background-image: url('.$row['vod_pic_slide'].')">
                                    <span class="slide-title"> '.$row['vod_name'].'</span>
                                    <div class="swiper-lazy-preloader"></div>
                                </a>
                            </div>
                        </div>';
            }
            Cache::set($key,$slide,app::$redis['timeout_l']);
        }
        app::$content = str_replace('{mv:slide}', $slide,app::$content);
    }

    /**
     * @throws Exception
     */
    public function setIndexClassify(): void
    {
        $content = file_get_contents('template/film/html/public/index_classify.html');
        $rows = Db::selectAll('select * from mac_type  where type_pid=0 order by type_id ASC');
        $type_html = '';
        foreach ($rows as $row){
            if ($row['type_id']>5){
                break;
            }
            $type_i_content = $content;
            $type_i_content = str_replace('{mv:type_id}', $row['type_id'],$type_i_content);
            $more_link = '/'.App::$type[$row['type_id']].'/';
            $type_i_content = str_replace('{mv:more}', $more_link,$type_i_content);
            $type_i_content = str_replace('{mv:type_name}', $row['type_name'],$type_i_content);
            $typeItemList = $this->getTypeItemList($row['type_id']);
            $type_i_content = str_replace('{mv:index_type_list}', $typeItemList,$type_i_content);
            $type_html = $type_html . $type_i_content;
        }
        AppService::assign('typeList',$type_html);
    }

    /**
     * @throws Exception
     */
    public function getTypeItemList($typeId): array|string
    {
        $rows = Db::selectAll('select * from mac_vod  where type_id='.$typeId.' order by vod_time_add DESC limit 12');
        $item_html = '';
        foreach ($rows as $row) {
            $p=[
                'type_id'=>$typeId,
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            ];
            $item_html = $item_html.'<li class="col-md-2 col-sm-2 col-xs-4">
                      <a class="video-pic loading" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'"
                         style="background-image: url('.$row['vod_pic'].')">
                        <span class="player"></span>
                        <span class="score">'.$row['vod_score'].'</span>
                        <span class="note text-bg-r">'.$row['vod_remarks'].'</span>
                      </a>
                      <div class="title">
                        <h5 class="text-overflow">
                          <a href="{:mac_url_vod_detail($vo4)}" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a>
                        </h5>
                      </div>
                      <div class="subtitle text-muted text-overflow hidden-xs">演员：'.$row['vod_actor'].'</div>
                    </li>';
        }
        return $item_html;
    }
}