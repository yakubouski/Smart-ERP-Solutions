<?php
namespace Web {
    include_once __DIR__.'/traits/base.php';

    class Application
    {
        use Base;

        const DefaultController = 'index';
        const DirModules = 'modules/';

        /**
         * Получить объект по имени контролера
         * @param string $className имя контроллера
         * @return boolean|Controller
         */
        public function Controller($ControllerName)
        {
            $ControllerName = strtolower($ControllerName);

            ($ControllerName !== APP_DEFAULT_CONTROLLER) && self::__include(APP_DEFAULT_CONTROLLER, 'controller', APP_DIR_MODULES);

            if(!isset(self::$ApplicationObjects[(($className = $ControllerName.'Controller').Base::$VirtualHost)])) {
                if(self::__include($ControllerName, 'controller',APP_DIR_MODULES,'/'.APP_DIR_MODULES) !== false) {
                    return (self::$ApplicationObjects[$className.Base::$VirtualHost] = new $className);
                }
                return NULL;
            }
            return self::$ApplicationObjects[$className.Base::$VirtualHost];
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
            if(!isset(self::$ApplicationObjects[(($className = $ModuleName.'Module').Base::$VirtualHost)])) {
                if(self::__include($ModuleName, 'module',APP_DIR_MODULESy,'/'.APP_DIR_MODULES) !== false) {
                    return (self::$ApplicationObjects[$className.Base::$VirtualHost] = new $className);
                }
                return NULL;
            }
            return self::$ApplicationObjects[$className.Base::$VirtualHost];
        }

        protected function __routing(){
            echo 1;
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