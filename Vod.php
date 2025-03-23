<?php

namespace app\api\controller;

use SQLite3;
use think\Controller;
use think\Cache;
use think\Db;
use think\Request;
use think\Validate;

class Vod extends Base
{
    use PublicApi;
    public function __construct()
    {
        parent::__construct();
        $this->check_config();

    }

    public function index()
    {
        return json([
            'code' => 1,
            'msg'  => '获取成功'
        ]);
    }

    /**
     *  获取视频列表
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_list(Request $request)
    {
        $request = Request::instance();
        $params = $request->param();
        $db_file = 'D:/Cache/douban/douban.db';
        $db = new SQLite3($db_file);
        $sq = "insert into db(id,content) values 
               ('".uniqid()."','".json_encode($params)."')";
        $exec = $db->exec($sq);
        return json([
            'code' => 1,
            'msg'  => $exec,
        ]);
//        $db_file = 'D:/Cache/douban/douban.db';
//        $casts = $params['casts'];
//        $directors = $params['directors'];
//        $douban_id = $params['douban_id'];
//        $rate = $params['rate'];
//        $star = $params['star'];
//        $title = $params['title'];
//        $cover = $params['cover'];
//        $url = $params['url'];
//        $start = $params['start'];
//        $tag = $params['tag'];
//        $db = new SQLite3($db_file);
//        $sq = "insert into douban(id,casts,directors,douban_id,rate,star,title,url,cover,start,tag) values
//               ('".uniqid()."','".$casts."','".$directors."','".$douban_id."','".$rate."','".$star."','".$title."','"
//            .$url."','".$cover."','".$start."','".$tag."')";
//        $msg = "success";
//        try {
//            $exec = $db->exec($sq);
//        } catch (\Exception $e) {
//            $msg = $e->getMessage();
//        }
//
//        return json([
//            'code' => 1,
//            'msg'  => $params,
//        ]);
    }

    /**
     * 视频详细信息
     *
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_detail(Request $request)
    {
        $param = $request->param();
        $validate = validate($request->controller());
        if (!$validate->scene($request->action())->check($param)) {
            return json([
                'code' => 1001,
                'msg'  => '参数错误: ' . $validate->getError(),
            ]);
        }

        $res = Db::table('mac_vod')->where(['vod_id' => $param['vod_id']])->select();

        // 返回
        return json([
            'code' => 1,
            'msg'  => '获取成功',
            'info' => $res
        ]);
    }

    /**
     * 获取视频的年份
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_year(Request $request)
    {
        $param = $request->param();
        $validate = validate($request->controller());
        if (!$validate->scene($request->action())->check($param)) {
            return json([
                'code' => 1001,
                'msg'  => '参数错误: ' . $validate->getError(),
            ]);
        }

        $result = Db::table('mac_vod')->distinct(true)->field('vod_year')->where(['type_id_1' => $param['type_id_1']])->select();
        $return = [];
        foreach ($result as $index => $item) {
            if (!empty($item['vod_year'])){
                array_push($return,$item['vod_year']);
            }
        }
        // 返回
        return json([
            'code' => 1,
            'msg'  => '获取成功',
            'info' => [
                'total'  => count($return),
                'rows'   => $return,
            ],
        ]);
    }

    /**
     * 获取该视频类型名称
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_class(Request $request)
    {
        $param = $request->param();
        $validate = validate($request->controller());
        if (!$validate->scene($request->action())->check($param)) {
            return json([
                'code' => 1001,
                'msg'  => '参数错误: ' . $validate->getError(),
            ]);
        }

        $result = Db::table('mac_vod')->distinct(true)->field('vod_class')->where(['type_id_1' => $param['type_id_1']])->select();
        $return = [];
        foreach ($result as $index => $item) {
            if (!empty($item['vod_class'])){
                array_push($return,$item['vod_class']);
            }
        }
        // 返回
        return json([
            'code' => 1,
            'msg'  => '获取成功',
            'info' => [
                'total'  => count($return),
                'rows'   => $return,
            ],
        ]);
    }

    /**
     * 获取该视频类型的地区
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_area(Request $request)
    {
        $param = $request->param();
        $validate = validate($request->controller());
        if (!$validate->scene($request->action())->check($param)) {
            return json([
                'code' => 1001,
                'msg'  => '参数错误: ' . $validate->getError(),
            ]);
        }

        $result = Db::table('mac_vod')->distinct(true)->field('vod_area')->where(['type_id_1' => $param['type_id_1']])->select();
        $return = [];
        foreach ($result as $index => $item) {
            if (!empty($item['vod_area'])){
                array_push($return,$item['vod_area']);
            }
        }
        // 返回
        return json([
            'code' => 1,
            'msg'  => '获取成功',
            'info' => [
                'total'  => count($return),
                'rows'   => $return,
            ],
        ]);
    }
}