<?php
namespace Web\Core;

trait On {
    protected function OnException($e) { trigger_error(__METHOD__.' must be overloaded', E_USER_NOTICE); }
    
    protected function On404($Context=null) { trigger_error(__METHOD__.' must be overloaded', E_USER_NOTICE); }
    
    protected function OnInitalize() { trigger_error(__METHOD__.' must be overloaded', E_USER_NOTICE); }
}

trait Base {
    
    use On;
    
    protected $VirtualHost;

    protected function __include($Name,$Space,...$PathList) {
        if(!class_exists("{$Name}{$Space}", false)) {
            $Name = strtolower($Name);
            $Space = strtolower($Space);
            foreach($PathList as $p) {
                if(file_exists($filename = $this->Path($p . $Name . '/' . $Name . '.' . $Space. '.php'))) {
                    include_once($filename);
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    protected function __pathname(&$PathName) {
        return !empty($PathName) ?
            ($PathName[0]==='/'||$PathName[0]==='\\' ?
                (\Globals\Server::$BaseDir.$PathName) :
                (\Globals\Server::$BaseDir.(!empty($this->VirtualHost)?(DIRECTORY_SEPARATOR.$this->VirtualHost.DIRECTORY_SEPARATOR):DIRECTORY_SEPARATOR).$PathName)) :
            (\Globals\Server::$BaseDir.(!empty($this->VirtualHost)?(DIRECTORY_SEPARATOR.$this->VirtualHost):''));
    }

    protected function __routing() {
        throw new \Exception('Application not configured properly. Method __routing must be overload');
    }
    
    public function Path($PathName='') {
        return $this->__pathname($PathName);
    }

    /**
     * Get application instance
     * @return Base|null
     */
    static public function Instance() {
        static $Instance; is_null($Instance) && (__CLASS__ !== get_called_class()) && ($Instance = new static());
        return $Instance;
    }

    /**
     * Create application instance an run it
     * @param string $VirtualHost directory to virtual host
     * @throws \Exception
     */
    static public function Run($VirtualHost=null) {
        if(is_null($App = self::Instance())) {
            throw new \Exception('Application not running. Run app __CLASS__::Run(...)');
        }
        $App->VirtualHost = !empty($VirtualHost) ? (trim($VirtualHost,'\\/')) : '';
        $App->OnInitalize();
        $App->__routing();
    }
}