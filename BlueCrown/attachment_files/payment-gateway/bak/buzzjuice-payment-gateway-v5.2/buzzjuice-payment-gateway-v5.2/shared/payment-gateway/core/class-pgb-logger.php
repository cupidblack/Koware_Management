<?php
if (!defined('ABSPATH')) exit;
class BZJ_PGB_Logger {
    private $enabled,$level;
    private $rank=array('debug'=>0,'info'=>1,'warning'=>2,'error'=>3,'critical'=>4);
    public function __construct(){
        $this->enabled=(bool)get_option('bzj_pgb_logging_enabled',true);
        $this->level=get_option('bzj_pgb_log_level','info');
    }
    public function log($level,$message,$context=array(),$source='class-pgb-logger.php'){
        if(!$this->enabled || ($this->rank[$level]??1)<($this->rank[$this->level]??1)) return;
        $safe=$this->redact($context);
        $file=basename($source,'.php').'.log';
        if(!is_dir(BZJ_PGB_LOG_DIR)) @wp_mkdir_p(BZJ_PGB_LOG_DIR);
        $line=wp_json_encode(array('time'=>gmdate('c'),'level'=>$level,'message'=>(string)$message,'context'=>$safe),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
        @file_put_contents(BZJ_PGB_LOG_DIR.$file,$line,FILE_APPEND|LOCK_EX);
    }
    public function debug($m,$c=array(),$s=''){ $this->log('debug',$m,$c,$s?:'class-pgb-logger.php'); }
    public function info($m,$c=array(),$s=''){ $this->log('info',$m,$c,$s?:'class-pgb-logger.php'); }
    public function warning($m,$c=array(),$s=''){ $this->log('warning',$m,$c,$s?:'class-pgb-logger.php'); }
    public function error($m,$c=array(),$s=''){ $this->log('error',$m,$c,$s?:'class-pgb-logger.php'); }
    public function critical($m,$c=array(),$s=''){ $this->log('critical',$m,$c,$s?:'class-pgb-logger.php'); }
    private function redact($v){
        if(is_array($v)){ foreach($v as $k=>$x){ if(preg_match('/pass|secret|token|cookie|authorization|consumer_key|consumer_secret|signature/i',(string)$k)) $v[$k]='[REDACTED]'; else $v[$k]=$this->redact($x); } return $v; }
        if(is_object($v)) return $this->redact((array)$v);
        return is_string($v)&&strlen($v)>1000?substr($v,0,997).'...':$v;
    }
}
