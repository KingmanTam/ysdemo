<?php
require 'application/service/AppService.php';
require 'application/core/SolrClient.php';
require 'application/common/PageUtils.php';
class AppController
{
    /**
     * @throws Exception
     */
    public function index(): void
    {
        require 'application/service/IndexService.php';
        try {
            $key = App::$domain['cacheId'].':page_home';
            $page_html = Cache::get($key);
            if (!empty($page_html)){
                echo $page_html;
                exit();
            }
            $service = new IndexService();
            $service->load();
            Cache::set($key,App::$content,App::$redis['timeout']);
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function type(): void
    {
        require 'application/service/TypeService.php';
        try {
            $service = new TypeService();
            $service->load();
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function detail(): void
    {
        require 'application/service/DetailService.php';
        try {
            $service = new DetailService();
            $service->load();
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function play(): void
    {
        require 'application/service/PlayService.php';
        try {
            $service = new PlayService();
            $service->load();
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function rank(): void
    {
        require 'application/service/RankService.php';
        try {
            $key = App::$domain['cacheId'].':page_rank_'.App::$id;
            $page_html = Cache::get($key);
            if (!empty($page_html)){
                echo $page_html;
                exit();
            }
            $service = new RankService();
            $service->load();
            Cache::set($key,App::$content,App::$redis['timeout_l']);
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function actor(): void
    {
        require 'application/service/ActorService.php';
        try {
            $service = new actorService();
            $service->load();
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function star(): void
    {
        require 'application/service/StarService.php';
        try {
            $service = new StarService();
            $service->load();
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function info(): void
    {
        require 'application/service/InfoService.php';
        try {
            $service = new InfoService();
            $service->load();
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function story(): void
    {
        require 'application/service/StoryService.php';
        try {
            $service = new StoryService();
            $service->load();
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function last(): void
    {
        require 'application/service/LastService.php';
        try {
            $key = App::$domain['cacheId'].':page_last';
            $page_html = Cache::get($key);
            if (!empty($page_html)){
                echo $page_html;
                exit();
            }
            $service = new LastService();
            $service->load();
            Cache::set($key,App::$content,App::$redis['timeout_l']);
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function search(): void
    {
        require 'application/service/SearchService.php';
        try {
            $service = new SearchService();
            $service->load();
            echo App::$content;
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function robots(): void
    {
        require 'application/service/SeoService.php';
        try {
            header("Content-Type:text/plain;charset=UTF-8");
            $key = App::$domain['cacheId'].':page_robots';
            $page_html = Cache::get($key);
            if (!empty($page_html)){
                echo $page_html;
                exit();
            }
            $service = new SeoService();
            $service->robots();
            Cache::set($key, App::$content,App::$redis['timeout_l']);
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }

    /**
     * @throws Exception
     */
    public function sitemap(): void
    {
        require 'application/service/SeoService.php';
        try {
            $key = App::$domain['cacheId'].':page_sitemap';
            $page_html = Cache::get($key);
            if (!empty($page_html)){
                echo $page_html;
                exit();
            }
            $service = new SeoService();
            $service->sitemap();
            Cache::set($key, App::$content,App::$redis['timeout_l']);
            echo App::$content;
            exit();
        } catch (Exception $e) {
            $this->error();
        }
    }
    public function error(): void
    {
        ErrorService::load();
    }
}