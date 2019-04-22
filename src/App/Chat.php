<?php 
namespace Sai\Swoole;

use swoole_websocket_server;

class Chat
{
    protected $ws;
    // 进程名称
    protected $taskName = 'Chat';
    // PID路径
    protected $pidFile = '/run/swooleChat.pid';
    // 设置运行时参数
    protected $options = [
        'worker_num' => 4, //worker进程数,一般设置为CPU数的1-4倍  
        'daemonize' => true, //启用守护进程
        'log_file' => '/data/logs/swoole-chat.log', //指定swoole错误日志文件
        'log_level' => 3, //日志级别 范围是0-5，0-DEBUG，1-TRACE，2-INFO，3-NOTICE，4-WARNING，5-ERROR
        'dispatch_mode' => 1, //数据包分发策略,1-轮询模式
    ];
 

    public function __construct($options = [], $host = '0.0.0.0', $port = 9500)
    {
        $this->ws = new swoole_websocket_server($host, $port);

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->ws->set($this->options);

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("close", [$this, 'onClose']);
    }

    public function start()
    {
        // Run worker
        $this->ws->start();
    }

    public function onOpen(swoole_websocket_server $ws, $request)
    {
        // 设置进程名
        cli_set_process_title($this->taskName);
        //记录进程id,脚本实现自动重启
        $pid = "{$ws->master_pid}\n{$ws->manager_pid}";
        file_put_contents($this->pidFile, $pid);
        $ws->push($request->fd, '欢迎登录！');

        if (date('Y-m-d') == '2019-03-08') {
            $ws->push($request->fd, '女神节快乐，今天上班辛苦了！');
        }

        echo "server: handshake success with fd{$request->fd}\n";
    }

    public function onMessage(swoole_websocket_server $ws, $frame)
    {
        //$ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
        $cons = $ws->connections;
        echo count($cons)."\n";
        echo $frame->data. "\n";
        $ws->push($frame->fd, $this->reply($frame->data));
        
    }

    public function onClose($ws, $fid)
    {
        echo "client {$fid} closed\n";
        foreach ($ws->connections as $fd) {
            $ws->push($fd, $fid. '下线了！');
        }
    }

    private function reply($str) {
        $str = mb_strtolower($str);
        switch ($str) {
            case 'hello':
                $res = 'Hello, 小仙女！';
                break;
            case '小可爱':
                $res = '小仙女';
                break;
            case '小仙女':
                $res = '小可爱';
                break;
            case 'ping':
                $res = 'pong';
                break;
            case 'time':
                $res = date('H:i:s');
                break;
            default:
                break;
        }

        if (!empty($res)) {
            return $res;
        }

        if (strpos($str, '天气') !== false) {
            return Weather::getInfoLikeName(str_replace("天气","", $str));
        }

        return $this->rand(rand(0, 6), $str);
    }

    private function rand($key, $str)
    {
        $arr = [
            '恭喜发财',
            '身体健康',
            'wow!',
            '你真棒',
        ];

        return $arr[$key] ?? $str;
    }
}
