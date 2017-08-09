<?php
namespace Web {
    class Application
    {
        use Core\Base;

        const DefaultController = 'index';
        const DirModules = 'modules/';
        const DirModels = 'models/';
        const DirTemplateCompile = '.compile/';

        /**
         * Objects intances cache
         * @var array
         */
        private $ObjectInstances = [];

        /**
         * Get controller class instance
         * @param string $ControllerName controller name
         * @return boolean|Controller
         */
        public function Controller($ControllerName)
        {
            $ControllerName = strtolower($ControllerName);
            ($ControllerName !== self::DefaultController) && $this->__include(self::DefaultController, 'controller', self::DirModules);
            if(!isset($this->ObjectInstances[(($className = $ControllerName.'Controller').$this->VirtualHost)])) {
                if($this->__include($ControllerName, 'controller',self::DirModules) !== false) {
                    return ($this->ObjectInstances[$className.$this->VirtualHost] = new $className);
                }
                return NULL;
            }
            return $this->ObjectInstances[$className.$this->VirtualHost];
        }

        /**
         * Get module class instance
         *
         * @param string $ModuleName module name
         * @return boolean|Module 
         */
        public function Module($ModuleName)
        {
            $ModuleName = strtolower($ModuleName);
            if(!isset($this->ObjectInstances[(($className = $ModuleName.'Module').$this->VirtualHost)])) {
                if(self::__include($ModuleName, 'module',self::DirModules) !== false) {
                    return ($this->ObjectInstances[$className.$this->VirtualHost] = new $className);
                }
                return NULL;
            }
            return $this->ObjectInstances[$className.$this->VirtualHost];
        }

        protected function __routing(){
            @list(,$ControllerName,$MethodName) = ($PathRoute = explode('/',\Globals\Request::$Uri));

            $Controller = $this->Controller($ControllerName = !empty($ControllerName) ? $ControllerName : self::DefaultController);

            empty($MethodName) && $MethodName = 'Default';

            spl_autoload_register(function($class) {
                $this->__include(strtolower($class), 'model', self::DirModels);
            });

            try {
                $ArgsSlice = 3;
                switch(1) {
                    case is_null($Controller) && method_exists($this->Controller(self::DefaultController), "On{$ControllerName}") :
                        $Controller = $this->Controller(self::DefaultController);
                        $MethodName = $ControllerName;
                        $ArgsSlice = 2;
                    case !is_null($Controller) && method_exists($Controller, "On{$MethodName}"): break;
                    case !is_null($Controller) && !method_exists($Controller, "On{$MethodName}"):
                        $MethodName = 'Default';
                        $ArgsSlice = 2;
                        break;
                    default: {
                        @ob_clean();
                        header("HTTP/1.0 404 Not Found");
                        header("Status: 404 Page not found");
                        (method_exists($this, 'On404')) && call_user_func([$this, 'On404'],'Controller not found');
                        @trigger_error('Controller '.$ControllerName.' not found',E_USER_ERROR);
                        exit;
                    }
                }
                ob_get_level() && ob_end_clean();
                ob_start();
                call_user_func_array([$Controller,"On{$MethodName}"], array_slice($PathRoute, $ArgsSlice));
                ob_end_flush();
            }
            catch (\Exception $ex) {
                @ob_clean();
                header("HTTP/1.0 404 Not Found");
                header("Status: 404 Page not found");
                (method_exists($this, 'OnException')) && call_user_func([$this, 'OnException'],$ex);
                @trigger_error($ex->getFile().PHP_EOL."\t".$ex->getMessage(),E_USER_ERROR);
                exit;
            }
        }
    }

    class Module {

    }

    abstract class Controller {
        abstract public function OnDefault();
        
        protected function template($TplFile=null,$TplArgs = []) {
            return \Template($TplFile,$TplArgs);
        }
    }
}

namespace {
    /**
     * Get running application instance
     * @return Web\Application|null
     */
    function Application() {
        if(is_null($Instance = Web\Application::Instance())) {
            throw new Exception('Application not running. Run app __CLASS__::Run(...)');
        }
        return $Instance;
    }
    /**
     * Get application controller by own name
     * @param string $ControllerName
     * @return boolean|Web\Controller
     */
    function Controller($ControllerName = Web\Application\DefaultController) {
        return Application()->Controller($ControllerName);
    }
    /**
     * Get application module by own name
     * @param string $ModuleName
     * @return boolean|Web\Module
     */
    function Module($ModuleName)  {
        return Application()->Module($ModuleName);
    }
    
    /**
     * Get Template render engine
     * @param string $TplName template pathname
     * @param array $TplArgs template args
     * @return \Web\Template
     */
    function Template($TplName=null,$TplArgs=[]) {
        return new \Web\Template($TplName, $TplArgs);
    }
}