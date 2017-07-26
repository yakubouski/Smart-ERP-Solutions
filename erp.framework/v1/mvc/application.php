<?php
namespace MVC
{
    !defined('APP_DEFAULT_CONTROLLER') && define('APP_DEFAULT_CONTROLLER','index');
    !defined('APP_DIR_MODULES') && define('APP_DIR_MODULES','modules/');

	class Application {
        protected static function __include($Name,$Space,...$PathList) {
            if(!class_exists("{$Name}{$Space}", false)) {
                $Name = strtolower($Name);
                $Space = strtolower($Space);
                foreach($PathList as $p) {
                    if(file_exists($filename = self::Path($p . $Name . '/' . $Name . '.' . $Space. '.php'))) {
                        include_once($filename);
                        return true;
                    }
                }
                return false;
            }
            return true;
        }

        static public function Path($PathName='') {
            return \Globals\Server::Path($PathName);
        }

        static public function Run($VirtualHost=null) {
            echo 'dde';
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

            ($ControllerName !== APP_DEFAULT_CONTROLLER) && self::__include(APP_DEFAULT_CONTROLLER, 'controller', APP_DIR_MODULES);

            if(!isset(self::$ApplicationObjects[(($className = $ControllerName.'Controller').Application::$VirtualHost)])) {
                if(self::__include($ControllerName, 'controller',APP_DIR_MODULES,'/'.APP_DIR_MODULES) !== false) {
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
                if(self::__include($ModuleName, 'module',APP_DIR_MODULESy,'/'.APP_DIR_MODULES) !== false) {
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