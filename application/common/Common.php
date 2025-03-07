<?php

class Common
{

    public static function validUrl(): bool
    {
        try {
            $m = '';
            if (isset($_GET['m'])) {
                $m = $_GET['m'];
            }
            if (empty($m)) {
                app::$method = 'index';
                return app::$valid_url;
            }
            $m = str_replace('.html', '', $m);
            $params = explode('/', $m);
            if ($params[1] === 'search') {
                App::$method = 'search';
                $args = explode('_', $params[2]);
                App::$searchKey = $args[0];
                if (!empty($args[1])) {
                    App::$page = $args[1];
                }
                return true;
            }
            if ($params[1] === 'robots.txt') {
                App::$method = 'robots';
                return true;
            }
            if ($params[1] === 'content') {
                App::$method = 'content';
                App::$id = $params[2];
                return true;
            }
            if ($params[1] === 'sitemap.xml') {
                App::$method = 'sitemap';
                return true;
            }
            if (empty(App::$rute_match[$params[1]])) {
                return false;
            }
            if (App::$rute_match[$params[1]] === 'story') {
                App::$method = 'story';
                App::$typeId = 10;
                App::$args['page'] = 1;
                if (!empty($params[2])) {
                    $args = explode('_', $params[2]);
                    if ($args[0] == 'index') {
                        App::$args['story_method'] = $args[0];
                        App::$args['page'] = $args[1];
                    }
                    if ($args[0] == 'detail') {
                        App::$method = 'story_index';
                        App::$args['story_method'] = $args[0];
                        App::$args['vod_id'] = $args[1];
                        App::$args['story_id'] = $args[2];
                    }
                    if ($args[0] == 'all') {
                        App::$method = 'story_index';
                        App::$args['story_method'] = $args[0];
                        App::$args['vod_id'] = $args[1];
                    }
                }

                return true;
            }
            if (App::$rute_match[$params[1]] === 'type') {
                App::$args['dir'] = $params[1];
                app::$typeId = App::$rute[$params[1]]['type_id'];
                app::$method = 'type';
                App::$args['type'] = $params[1];
                if (empty($params[2])) {
                    App::$args['class'] = '';
                    App::$args['area'] = '';
                    App::$args['year'] = '';
                    App::$args['page'] = 1;
                    return app::$valid_url;
                } else {
                    $args = explode('_', $params[2]);
                    App::$args['class'] = $args[0];
                    App::$args['area'] = $args[1];
                    App::$args['year'] = $args[2];
                    if (empty($args[3])) {
                        App::$args['page'] = 1;
                    } else {
                        App::$args['page'] = $args[3];
                    }
                    app::$method = 'type';
                }
                return true;
            }
            if (App::$rute_match[$params[1]] === 'detail') {
                App::$method = 'detail';
                App::$id = $params[2];
                return true;
            }
            if (App::$rute_match[$params[1]] === 'play') {
                App::$method = 'play';
                $args = explode('_', $params[2]);
                App::$id = $args[0];
                App::$args['play_id'] = $args[0];
                App::$args['sid'] = $args[1];
                App::$args['nid'] = $args[2];
                return true;
            }
            if (App::$rute_match[$params[1]] === 'rank') {
                App::$method = 'rank';
                App::$id = $params[2];
                return true;
            }
            if (App::$rute_match[$params[1]] === 'last') {
                App::$method = 'last';
                return true;
            }
            if (App::$rute_match[$params[1]] === 'actor') {
                App::$method = 'actor';
                if (empty($params[2])) {
                    App::$args['page'] = 1;
                } else {
                    App::$args['page'] = $params[2];
                }
                App::$typeId = 7;
                return true;
            }
            if (App::$rute_match[$params[1]] === 'info') {
                App::$method = 'info';
                $args = explode('_', $params[2]);
                App::$args['method'] = $args[0];
                App::$args['id'] = $args[1];
                return true;
            }
            if (App::$rute_match[$params[1]] === 'star') {
                App::$method = 'star';
                App::$typeId = 7;
                $args = explode('_', $params[2]);
                App::$args['method'] = $args[0];
                App::$args['id'] = $args[1];
                if ($args[0] == 'works') {
                    App::$args['page'] = $args[2];
                }
                return true;
            }
            return false;
        } catch (Exception $e) {
            app::$valid_url = false;
        }

        return app::$valid_url;
    }

    public static function getReqDomain($HTTP_HOST)
    {
        if (str_contains($HTTP_HOST, 'www.')) {
            return str_replace('www.', '', $_SERVER['HTTP_HOST']);
        }
        return $HTTP_HOST;
    }

    public static function isSpider(): int
    {
        $ip = self::getIP();
        $key = 'as:' . $ip;
        $ip = Cache::get($key);
        if (!empty($ip)) {
            return 1;
        }
        $spiders = array(
            '360Spider',
            'Googlebot',
            'SogouSpider',
            'bingbot',
            'bytespider',
            'Baiduspider',
            'YisouSpider'
        );
        if (!empty($ua)) {
            foreach ($spiders as $spider) {
                if (stripos($ua, $spider) !== false) {
                    return 1;
                }
            }
        }
        return 0;
    }

    public static function getIP(): string
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            $ip = trim(current($arr));
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        return $long ? $ip : '0.0.0.0';
    }

    public static function getMillisecond(): string
    {
        list($mse, $sec) = explode(' ', microtime());
        $mse_time = (float)sprintf('%.0f', (floatval($mse) + floatval($sec)) * 1000);
        return substr($mse_time, 0, 13);
    }
}