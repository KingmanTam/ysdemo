<?php

class LastService
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
        $params['title'] = '最近更新的100个电视剧、动漫、综艺、剧情、电影'.'-'.App::$domain['name'];
        $params['keywords'] = '最近更新的100个电视剧、动漫、综艺、剧情、电影';
        $params['des'] = App::$domain['name'].'提供最近更新电视剧、电影、综艺、动漫、剧情前100部';
        AppService::setSEO($params);
        $rows = Db::selectAll('select * from mac_vod order by vod_time desc limit 100');
        $count = 1;
        $pre_html = '';
        $oth_html = '';
        foreach ($rows as $row){
            $p=[
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            ];
            if ($count<7){
                $pre_html.= '<li class="col-md-2 col-sm-4 col-xs-4">
                        <a class="video-pic loading" data-original="'.$row['vod_pic'].'" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'" >
                        <span class="tips red">'.$row['vod_class'].'</span>
                        <span class="player"></span>
                        <span class="num">'.$count.'</span>
                        <span class="note text-bg-r">'.$row['vod_remarks'].'</span>
                        </a>
                        <div class="title"><h5 class="text-overflow"><a href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'</a></h5></div>
                         <div class="subtitle text-muted text-overflow hidden-xs">'.$row['vod_actor'].'</div>
                        </li>';
            }else{
                $oth_html.='<li class="list2 text-overflow"><a class="pull-left" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'">
                    <em class="num">'.$count.'</em>'.$row['vod_name'].'&nbsp;/&nbsp;'.$row['vod_remarks']. '</a>
                    <span class="time hidden-xs" style="color: #f39d07">' .date('Y-m-d',$row['vod_time']).'</span></li>';
            }
            $count++;
        }
        AppService::assign('top_list',$pre_html);
        AppService::assign('oth_list',$oth_html);
    }
}