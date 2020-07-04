<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

class Database extends MySQLi {
     private static $instance = null ;
     private $shopdata = null;

     private function __construct($host, $user, $password, $database){
        parent::__construct($host, $user, $password, $database);
     }

    public function __destruct() {
        parent::close();
    }

    public static function getInstance($host, $user, $password, $database){
        if (self::$instance == null){
            self::$instance = new self($host, $user, $password, $database);
        }
        return self::$instance;
    }

    private function LogMySQL($text, $typ = "Info")
    {
        if($typ != "Error" && $typ != "Info" && $typ != "Warning")
            $typ = "Info";
        $filename = __DIR__.'/../logs/MySQL.log';
        $fh = fopen($filename, "a") or die("Could not open log file.");
        fwrite($fh, "[".strtoupper($typ)." - ".date("d-m-Y, H:i")." - ".session_id()."]: ".$text."\n");     
        fclose($fh);
    }
    public function query($sql){   
        $starttime = microtime(true);     
        $result = parent::query($sql); 
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        //Send Querys if Response Time over 5 Sec
        if($duration > 5) {
            $this->LogMySQL($duration." | ".var_export(debug_backtrace(),true));
        }    
        //Send Error Querys
        if(!$result || self::$instance->error) {
            $this->LogMySQL(var_export(self::$instance->error,true)." | ".var_export(debug_backtrace(),true));
        }        
        return $result; 
         
    } 
}

?>