<?php

class ErrorService
{
    /**
     * @throws Exception
     */
    public static function load(): void
    {
        try {
            $page_error = Cache::get(App::$domain['cacheId'] . ':page_error');
            if (!empty($page_error)) {
                echo $page_error;
                exit();
            }
            App::$method = 'error';
            AppService::load();
            $params = array();
            $params['title'] = '错误提示_错误页面_404页面-' . App::$domain['name'];
            $params['keywords'] = '错误提示,错误页面,404页面,' . App::$domain['name'];
            $params['des'] = App::$domain['name'] . '未找到相关资源，请确认地址是否有误重新输入地址，或者浏览其他视频！';
            AppService::setSEO($params);
            Cache::set(App::$domain['cacheId'] . ':page_error', App::$content);
            echo App::$content;
            exit();
        } catch (Exception $e) {
            throw new Exception();
        }
    }
}