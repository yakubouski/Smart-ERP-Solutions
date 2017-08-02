<?php
namespace Web;
/**
 * Overload OnUnhandledException method for process unhandled exceptions
 */
trait UnhandledException
{
    protected function OnUnhandledException($e) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
}

trait Session {

    protected function SessionCookieParams($lifetime=0, $path='/', $domain = null, $secure = false , $httponly = false) {
        return session_set_cookie_params ($lifetime,$path,$domain,$secure,$httponly);
    }

    protected function SessionStart($SessionName=null,$SessionPath=null,$SessionCustomHandle=false,$SessionId=null) {
        if(!empty($SessionPath)) {
            $SessionPath = $this->Path(rtrim($SessionPath,'\\/'));
            !file_exists($SessionPath) && @\mkdir($SessionPath,true,0770);
            !file_exists($SessionPath.'/.htaccess') && \file_put_contents($SessionPath.'/.htaccess', "order deny,allow\ndeny from all");
            @session_save_path($SessionPath);
        }
        !is_null($SessionId) && @session_id($SessionId);

        if($SessionCustomHandle) {
            $CustomHandler = new class ($this) implements \SessionHandlerInterface {
                private $ParentThis;
                public function __construct($Parent) { $this->ParentThis = $Parent; }
                public function open($savePath, $sessionName){ return $this->ParentThis->OnSessionOpen($savePath, $sessionName); }
                public function close() { return $this->ParentThis->OnSessionClose(); }
                public function read($id) { return $this->ParentThis->OnSessionRead($id); }
                public function write($id, $data) { return $this->ParentThis->OnSessionWrite($id,$data); }
                public function destroy($id) { return $this->ParentThis->OnSessionDestroy($id); }
                public function gc($maxlifetime) { return $this->ParentThis->OnSessionGC($maxlifetime); }
            };
            @\session_set_save_handler($CustomHandler, true);
        }
        @session_start();
    }
    public function OnSessionOpen($SavePath,$SessionName) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
    public function OnSessionClose() {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
    public function OnSessionRead($id) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
    public function OnSessionWrite($id,$data) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
    public function OnSessionDestroy($id) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
    public function OnSessionGC($maxlifetime) {
        trigger_error(__METHOD__.' must be overloaded', E_USER_WARNING);
    }
}