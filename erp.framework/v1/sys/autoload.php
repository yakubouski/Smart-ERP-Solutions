<?php
namespace Sys;
final class Autoload {
    static private $AutoloadMap = [];
    private function __construct() {}
    static public function Import($Class) {
        list($ns,$class) = explode('/',$Class,2);
        isset(self::$AutoloadMap[$ns]) && (file_exists($filename = (self::$AutoloadMap[$ns].$class.'.php')) && (include_once($filename)));
    }
    static public function Using($BaseDir,$Ns) {
        $Ns = strtolower($Ns);
        !isset(self::$AutoloadMap[$Ns]) && self::$AutoloadMap[$Ns] = $BaseDir;
    }
}
