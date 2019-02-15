<?php
/**
 * Created by PhpStorm.
 * User: jett
 * Date: 2018/12/26 0026
 * Time: 下午 05:53
 */
include_once __DIR__."/init.php";

try{

    ElasticSearch::setServerUrl('http://192.168.0.59:9200');
    ElasticSearch::setSqlValue('SELECT * FROM search/goods');
    print_r(ElasticSearch::select());
}catch (Exception $e){

    echo $e->getMessage();
}


//$http_client = new \GuzzleHttp\Client(['timeout'  => 5.0]);
//$url = "http://192.168.0.59:9200/_sql?sql=SELECT * FROM search/goods";
//$value = $http_client->get($url)->getBody();
//print_r(json_decode($value, true));
//Socket::setServerHost('192.168.142.130');
//
//try {
////    Socket::startServer();
//    Socket::connectServer();
//}catch (Exception $e) {
//    die($e->getMessage());
//}
