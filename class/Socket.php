<?php
/**
 * Created by PhpStorm.
 * User: jett
 * Date: 2018/12/27 0027
 * Time: 下午 02:38
 */

/**
 * Class Socket
 * 操作socket封装类
 *
 */
class Socket {

    private static $server_host;
    private static $client_host;
    private static $port = 27777;
    private static $socket;


    public function __construct() {

    }

    public function init() :bool {

        return true;
    }

    static public function getServerHost() :string {

        return self::$server_host;
    }

    static public function setServerHost(string $host) :bool {

        self::$server_host = $host;
        return true;
    }

    static public function getClientHost() :string {

        return self::$client_host;
    }

    static public function setClientHost(string $host) :bool {

        self::$client_host = $host;
        return true;
    }

    static public function setPort(int $port) :bool {

        self::$port = $port;
        return true;
    }

    static public function getSocketObj() {

        return self::$socket;
    }


    /**
     * 检查成员变量是否满足要求
     * @throws Exception
     */
    static private function checkValue() {

        !empty(self::$server_host) ?: self::newException("未设置服务端ip！");
        set_time_limit(0);
    }

    /**
     * 创建socket
     * @throws Exception
     */
    static private function createTcp(){

        self::$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        !empty(self::$socket) ?: self::newException("创建socket失败！");
    }

    /**
     * 开启socket服务端，接收并写入文件
     * @return bool
     * @throws Exception
     */
    static public function startServer() :bool {

        self::checkValue();
        // 创建socket并监听
        self::createTcp();
        socket_bind(self::$socket, self::$server_host, self::$port) ?: self::newException("socket绑定失败！");
        socket_listen(self::$socket, 5) ?: self::newException("socket监听失败");
        // 循环获取接收到数据
        while (true) {

            // 如果获取数据失败则抛出失败信息的异常
            $accept_data = socket_accept(self::$socket);
            if($accept_data === FALSE){
                $msg = socket_strerror(socket_last_error(self::$socket));
                self::newException($msg);
            }

            $string = socket_read($accept_data,1024);
            print_r(date("Y-m-d H:i:s")."接收到连接:\n{$string}\n");

            sleep(5);
            socket_write($accept_data, "已收到", strlen ("已收到"));

            socket_close($accept_data);
        }
        return true;
    }

    /**
     * @throws Exception
     */
    static public function connectServer(){

        self::checkValue();
        // 创建socket
        self::createTcp();
        socket_connect(self::$socket, self::$server_host, self::$port) ?:
            self::newException(socket_strerror(socket_last_error(self::$socket)));
//        socket_connect(self::$socket, self::$server_host, self::$port);

        $output="你好，服务器！";
        socket_write(self::$socket, $output, strlen ($output));
        $input = socket_read(self::$socket, 1024);
        echo($input);
    }

    /**
     * @param string $msg
     * @param int $code
     * @throws Exception
     */
    static private function newException(string $msg = "", int $code = -1) {

        throw new Exception($msg, $code);
    }

}