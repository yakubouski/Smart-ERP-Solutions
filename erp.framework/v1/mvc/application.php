<?php
namespace MVC
{

	class Application {

        const DefaultController = defined('APP_DEFAULT_CONTROLLER') ? APP_DEFAULT_CONTROLLER : 'index';
        const DefaultModulesDir = defined('APP_DIR_MODULES') ? APP_DIR_MODULES : 'modules/';
        const ServerRoot = dirname($_SERVER['']);

        protected static function __include($Name,$Space,...$PathList) {
            if(!class_exists("{$Name}{$Space}", false)) {
                $filename = null;
                $Name = strtolower($Name);
                $Space = strtolower($Space);
                foreach($PathList as $p) {
                    $filename = self::Path($p . $Name . '/' . $Name . '.' . $Space. '.php');
                    if(file_exists($filename)) { break; }
                    $filename = null;
                }
                return !is_null($filename) ? include_once($filename) : false;
            }
        }

        static public function Path() {

        }

        static public function Run($VirtualHost=null) {
        }

        static private $ApplicationObjects;
        /**
         * Получить объек по имени контролера
         * @param string $className имя контроллера
         * @return boolean|Controller
         */
        static public function Controller($ControllerName)
        {
            $ControllerName = strtolower($ControllerName);

            ($ControllerName !== \MVC\DefaultController) && self::__include(\MVC\DefaultController, 'controller', Application::ModulesDirectory);

            if(!isset(self::$ApplicationObjects[(($className = $ControllerName.'Controller').Application::$VirtualHost)])) {
                if(self::__include($ControllerName, 'controller',Application::ModulesDirectory,'/'.Application::ModulesDirectory) !== false) {
                    return (self::$ApplicationObjects[$className.Application::$VirtualHost] = new $className);
                }
                return NULL;
            }
            return self::$ApplicationObjects[$className.Application::$VirtualHost];
        }

        /**
         * Получает объект модуля.
         *
         * @param string $ModuleName имя модуля
         * @return boolean|Module объект контроллера или NULL если контроллер не существует либо нет доступа
         */
        static public function Module($ModuleName)
        {
            $ModuleName = strtolower($ModuleName);
            if(!isset(self::$ApplicationObjects[(($className = $ModuleName.'Module').Application::$VirtualHost)])) {
                if(self::__include($ModuleName, 'module',Application::ModulesDirectory,'/'.Application::ModulesDirectory) !== false) {
                    return (self::$ApplicationObjects[$className.Application::$VirtualHost] = new $className);
                }
                return NULL;
            }
            return self::$ApplicationObjects[$className.Application::$VirtualHost];
        }
	}

    class Module {

    }

    class Controller {

    }
}