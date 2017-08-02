<?php
namespace Web {
    include_once __DIR__.'/traits/base.php';
    include_once __DIR__.'/traits/on.php';

    class Application
    {
        use Base;

        const DefaultController = 'index';
        const DirModules = 'modules/';
        const DirModels = 'models/';

        /**
         * Objects intances cache
         * @var array
         */
        private $ObjectInstances = [];

        /**
         * Получить объект по имени контролера
         * @param string $className имя контроллера
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
         * Получает объект модуля.
         *
         * @param string $ModuleName имя модуля
         * @return boolean|Module объект контроллера или NULL если контроллер не существует либо нет доступа
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
                $class = strtolower($class);
                (file_exists($filename = ($this->VirtualHost.DIRECTORY_SEPARATOR.self::DirModels.$class.'.model.php')) || ($filename = null));
                !is_null($filename) && include_once($filename);
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
                    default:
                        throw new \Exception($ControllerName . '.controller'. '.php not found in modules folder',E_USER_ERROR);
                }
                ob_get_level() && ob_end_clean();
                ob_start();
                call_user_func_array([$Controller,"On{$MethodName}"], array_slice($PathRoute, $ArgsSlice));
                ob_end_flush();
            }
            catch (\Exception $ex) {
                (method_exists($this, 'OnUnhandledException')) && call_user_func([$this, 'OnUnhandledException'],$ex);
                @ob_clean();
                header("HTTP/1.0 404 Not Found");
                header("Status: 404 Page not found");
                @trigger_error($ex->getFile().PHP_EOL."\t".$ex->getMessage(),E_USER_ERROR);
                exit;
            }
        }
    }

    class Module {

    }

    class Controller {

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
    function Controller($ControllerName = \Web\Application\DefaultController) {
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
}