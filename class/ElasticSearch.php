<?php
/**
 * Created by PhpStorm.
 * User: jett
 * Date: 2019/01/04
 * Time: 下午 03:54
 */

/**
 * Class ElasticSearch
 * ElasticSearch-sql封装类
 * 依赖:GuzzleHttp,APP_PATH=应用根目录
 */
class ElasticSearch {

    private static $server_url;
    private static $sql;
    private static $table;
    private static $where;
    private static $order;
    private static $group;
    private static $limit;
    private static $http_client;
    private static $config = [
        'logs_path' => '/logs'
    ];

    /**
     * ElasticSearch constructor.
     * @param array $config
     * [
     *  'server_url' = 'http://192.168.0.59:9200/'
     * ]
     * @throws Exception
     */
    public function __construct(array $config = []) {

        self::$http_client = new \GuzzleHttp\Client(['timeout'  => 10.0]);
        if(!empty($config)){
            self::setServerUrl($config['server_url']);
        }
    }

    /**
     * 设置ElasticSearch地址
     * @param string $server_url
     * @throws Exception
     */
    public static function setServerUrl(string $server_url){

        if(empty(self::$http_client)){
            self::$http_client = new \GuzzleHttp\Client(['timeout'  => 10.0]);
        }
        $response_code = self::$http_client->get($server_url)->getStatusCode();
        $response_code == 200 ?: self::newException('兄dei， 你这季白地址都ping不通的');
        self::$server_url = $server_url.'/_sql?sql=';
    }

    /**
     * 设置sql值
     * @param string $sql
     * @return bool
     */
    public static function setSqlValue(string $sql) :bool {

        self::$sql = $sql;
        return true;
    }

    /**
     * 查询
     * @return array
     * @throws Exception
     */
    public static function select() {

        if(!empty(self::$sql)){
             return self::getData(self::$sql);
        }
    }

    /**
     * 请求数据
     * @param string $sql
     * @return array
     * @throws Exception
     */
    private static function getData(string $sql) :array {

        $http_request = self::$http_client->get(self::$server_url.$sql);
        ($http_request->getStatusCode() == 200) ?: self::newException('兄dei， 你这季白地址都ping不通的');
        $data = json_decode($http_request->getBody(), true);
        $log = "sql:{$sql},\nhost:".self::$server_url.",\nreturn:";
        !empty($data) ?: self::newExceptionPrintLog($log.$data,'兄dei，报错了啊，日志写了自己看去');
        return $data;
    }


    /**
     * @param string $msg
     * @param int $code
     * @throws Exception
     */
    static private function newException(string $msg = "", int $code = -1) {

        throw new Exception($msg, $code);
    }

    /**
     * @param string $log
     * @param string $msg
     * @param int $code
     * @throws Exception
     */
    static private function newExceptionPrintLog(string $log, string $msg = "", int $code = -1){

        $date = date('Y-m-d');
        error_log($log."\n",3,APP_PATH.self::$config['logs_path']."/{$date}.log");
        throw new Exception($msg, $code);
    }

}