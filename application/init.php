<?php
require 'application/App.php';
require 'application/core/AppException.php';
require 'config/Domain.php';
require 'application/service/ErrorService.php';
require 'application/common/Common.php';

@header('Content-Type:text/html;Charset=utf-8');
@date_default_timezone_set('Etc/GMT-8');
@ini_set('display_errors','On');
@error_reporting(7);
@ob_start();

app::$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
app::$domain['domain'] = common::getReqDomain($_SERVER['HTTP_HOST']);

$domain = $_SERVER['HTTP_HOST'];
App::$site_url = $domain;
if(str_contains($domain, 'www.')){
    $domain = str_replace('www.', '' ,$domain);
}
Domain::setInfo($domain);