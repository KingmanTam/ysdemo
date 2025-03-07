<?php

class SearchService
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
        $key = App::$searchKey;
        $page = App::$page;
        $client = new SolrClient(App::$solr['vod']);
        $client->addField('vod_name',$key,false);
        $client->setRows(24);
        $client->setStart($page);
        $rows = $client->query();
        $total = $client->getTotal();
        $dataList_html = '';
        AppService::assign('search_key',$key);
        AppService::assign('total',$total);
        $params = array();
        $params['title'] = '搜索《'.$key.'》第'.$page.'页'.'-'.App::$domain['name'];
        $params['keywords'] = '搜索,'.$key;
        $params['des'] = App::$domain['name'].'为你提供'.$key.'全部搜索结果,'.$key.'的全部内容第'.$page.'页';
        AppService::setSEO($params);
        foreach ($rows as $row){
            $p=[
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            ];
            $item = '<div class="details-info-min col-md-12 col-sm-12 col-xs-12 clearfix news-box-txt p-0">
                        <div class="col-md-3 col-sm-4 col-xs-3 news-box-txt-l clearfix">
                            <a class="video-pic loading" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'" data-original="'.$row['vod_pic'].'" style="padding-top: 150%; background-image: url('.$row['vod_pic'].')">
                                <span class="note text-bg-c">'.$row['vod_name'].'</span></a>
                        </div>
                        <div class="col-md-9 col-sm-8 col-xs-9 clearfix pb-0">
                            <div class="details-info p-0">
                                <ul class="info clearfix">
                                    <li class="col-md-12 col-sm-12 col-xs-12 hidden-md ">
                                        <a href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'">'.$row['vod_name'].'
                                            <font size="3" style="float:right" color="#ff7701">'.$row['vod_score'].'分</font></a>
                                    </li>
                                    <li class="col-md-6 col-sm-6 col-xs-12 text hidden-md">
                                        <span>状态：</span>'.$row['vod_remarks'].'</li>
                                    <li class="col-md-12 col-sm-12 col-xs-12 text">
                                        <span>类型：</span>'.$row['vod_class'].'</li>
                                    <li class="col-md-12 col-sm-12 col-xs-12 text">
                                        <span>主演：</span>'.$row['vod_actor'].'</li>
                                    <li class="col-xs-12 text hidden-sm hidden-md hidden-lg">
                                        <span>年代：</span>'.$row['vod_year'].'</li>
                                    <li class="col-md-6 col-sm-6 col-xs-12 text hidden-xs">
                                        <span>导演：</span>'.$row['vod_director'].'</li>
                                    <li class="col-md-6 col-sm-12 col-xs-4 text hidden-xs">
                                        <span>国家/地区：</span>'.$row['vod_area'].'</li>
                                    <li class="col-md-6 col-sm-6 col-xs-12 text hidden-xs">
                                        <span>时长：</span>'.$row['vod_duration'].'</li>
                                    <li class="col-md-6 col-sm-12 col-xs-6 text hidden-xs">
                                        <span>语言/字幕：</span>'.$row['vod_lang'].'</li>
                                    <li class="col-md-6 col-sm-6 col-xs-6 text hidden-xs">
                                        <span>年代：</span>'.$row['vod_year'].'</li>
                                    <li class="col-md-6 col-sm-12 col-xs-12 text hidden-xs">
                                        <span>更新时间：</span>'.date('yy-m-d',$row['vod_time']).'</li>
                                    <li class="col-md-12 col-sm-12 col-xs-12">
                                        <span>详细介绍：</span>
                                        <span class="details-content-default">'.$row['vod_content'].'</span></li>
                                    <div class="col-l">
                                        <div class="p_bottom hidden-md">
                                            <a href="'.AppService::getLink(4,$p).'" class="v_yellow_btn">
                                                <i class="iconfont">&#xe630;</i>立即播放</a>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>';
            $dataList_html .=$item;
        }
        $page_url = '/search/'.$key.'_{page}.html';
        $page = new PageUtils($total, 10, $page, $page_url, 2);
        $page_str = $page->myde_write();
        AppService::assign('pages',$page_str);
        AppService::assign('search_result',$dataList_html);
        $this->setHot();
    }

    /**
     * @throws Exception
     */
    public function setHot(): void
    {
        $key = App::$domain['cacheId'].':page_search_hot';
        $hot_html = Cache::get($key);
        if (!empty($hot_html)){
            AppService::assign('search_hot',$hot_html);
            return;
        }
        $hot_html = file_get_contents('template/film/html/public/search_hot.html');
        $rows = Db::selectAll('select * from mac_vod where type_id<6 order by vod_hits desc limit 20');
        $count = 1;
        $item_html = '';
        foreach ($rows as $row){
            $p=[
                'type_id'=>$row['type_id'],
                'vod_id'=>$row['vod_id'],
                'vod_en'=>$row['vod_en'],
            ];
            $item = '';
            if ($count ==1){
                $first = '<div class="col-md-6 col-sm-12 col-xs-12 p-0">
                    <a class="video-pic" href="'.AppService::getLink(4,$p).'" title="'.$row['vod_name'].'" style="background: url('.$row['vod_pic'].') no-repeat top center;background-size:cover;">
                      <span class="note text-bg-r">'.$row['vod_remarks'].'</span></a>
                  </div>
                  <div class="col-md-6 col-sm-12 col-xs-12" style=" padding-top:0px; padding-right:0px;">
                    <div class="col-md-12 p-0 text-overflow">
                      <strong>'.$row['vod_name'].'</strong></div>
                    <div class="col-md-12 pg-0 text-overflow">'.$row['vod_area'].'</div>
                    <div class="col-md-12 p-0 text-overflow">'.$row['vod_remarks'].'</div>
                    <div class="col-md-12 pg-0 text-overflow">
                      <span>人气：</span>
                      <span class="hits text-color">
                                <em>'.$row['vod_hits'].'</em></span>
                    </div>
                  </div>';
                $hot_html = str_replace('{mv:hot_first}', $first,$hot_html);
            }else if ($count<4&&$count>1){
                $item = '<li class="list p-0">
                      <a class="pull-left" href="/" title="'.$row['vod_name'].'">
                        <em class="num active" >'.$count.'</em>'.$row['vod_name'].'</a>
                      <span class="hits text-color">'.$row['vod_hits'].'</span></li>';

            }else{
                $item = '<li class="list p-0">
                      <a class="pull-left" href="/" title="'.$row['vod_name'].'">
                        <em class="num">'.$count.'</em>'.$row['vod_name'].'</a>
                      <span class="hits text-color">'.$row['vod_hits'].'</span></li>';
            }
            $item_html .=$item;
            $count++;
        }
        $hot_html = str_replace('{mv:hot_list}', $item_html,$hot_html);
        Cache::set($key,$hot_html,App::$redis['timeout_l']);
        AppService::assign('search_hot',$hot_html);
    }
}