<?php
class Logger {
    private static $instance;
    private $logFilePath;

    private function __construct() {
        $this->logFilePath = 'logger/log.txt';
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }

        return self::$instance;
    }

    public function log($message) {
        $ip = $_SERVER['REMOTE_ADDR']; // Client IP
        $time = date("Y-m-d H:i:s", strtotime("+1 hours"));

        $contents = file_get_contents($this->logFilePath);
        $contents .= "$ip\t$time\t$message\r";
        file_put_contents($this->logFilePath, $contents);
    }
}
 ?>
