<?php
namespace Web
{
    trait TemplateParser {

        private $__DOM = [];
        private static $WidgetsPaths;
        private static $WidgetsClass;

        static public function Using(...$WidgetsPaths) {
            self::$WidgetsPaths = array_merge(!empty(self::$WidgetsPaths) ? self::$WidgetsPaths : [], $WidgetsPaths);
        }

        static public function Register(array $WidgetsClasses) {
            self::$WidgetsClass = array_merge(!empty(self::$WidgetsClass) ? self::$WidgetsClass : [], $WidgetsClasses);
        }

        protected function __class($class) {

            if(isset(self::$WidgetsClass[$class])) return (new self::$WidgetsClass[$class] ($this));

            foreach(self::$WidgetsPaths as $p) {
                if(file_exists($filename = Directory($p).$class.'.php')) {

                    $class_name = include_once ($filename);
                    (empty($class_name) || $class_name === 1) && $class_name = $class;
                    self::$WidgetsClass[$class] = $class_name;

                    return (new $class_name ($this));
                }
            }

            return null;
        }

        private function __escape(&$str) {
            return str_replace(["'","\n","\t","\r","\0",'>'],["\'",'\n','\t','\r','','|||'], $str);
        }
        private function __unescape(&$str) {
            return str_replace(["\'",'\n','\t','\r','|||'],["'","\n","\t","\r",'>'], $str);
        }

        protected function __parse($Content) {
            return empty(self::$WidgetsPaths) ? $Content : preg_replace_callback_array([
                #'%<(/?)(\w+):([\w:]+)(?:\s+(.*?))?\s*(/?)(?!>[^<]+>)>%s'=> function($m) {
                '%<(/?)(\w+):([\w:]+)(?:\s+(.*?))?\s*(/?)>%s'=> function($m) {
                        if(isset($m[4]))
                            $m[4] = preg_replace(array('/([\w-]+)\s*=\s*("|\')(.*?)\2\s*/s','/([\w-]+)\s*=\s*((?:\w+::)?\$[^\s\/]+)\s*/s'), array('\'\1\'=>\2\3\2,','\'\1\'=>\2,'), $m[4]);
                        else
                            $m[4] = '';
                        $m[5] = (isset($m[5]) && $m[5]=='/')?0:($m[1]=='/'?-1:1);
                        $m[0] = $this->__escape($m[0]);
                        return '<?php '.($m[5]==-1 ? '} ':'')."\$this->__(['".strtolower($m[2])."','".implode("','",explode(':',strtolower($m[3])))."'],[$m[4]],$m[5],'$m[0]');".($m[5]==1 ? 'if(empty($this->__skip())){ ':'').' ?>';
                    },
                '/\?>(\s*)<\?php/s'=>function($m) {
                        return $m[1];
                }], $Content);
        }

        private function __pop()
        {
            return array_pop($this->__DOM);
        }
        private function __push($object,$args,$method)
        {
            $this->__DOM[] = [$object,$args,$method];
        }
        private function __current()
        {
            return @end($this->__DOM);
        }

        private function __skip() {
            if(!empty($object = $this->__current())) {
                $method = $object[2];
                if(method_exists($object[0],$fn = $method.'Skip')) {
                    return call_user_func([$object[0],$fn], $object[1]);
                }
            }
            return 0;
        }

        private function __render($method,&$Object,&$Args,&$State) {
            if($State == 0) {
                if(method_exists($Object,$fn = $method) || method_exists($Object,$fn = $method.'End')) {
                    call_user_func([$Object,$fn], $Args,'');
                    return true;
                }
            }
            elseif($State == 1) {
                if(method_exists($Object,$method)) {
                    $this->__push($Object, $Args,$method);
                    ob_start();
                    return true;
                }
                elseif(method_exists($Object,$method.'Begin')) {
                    call_user_func([$Object,$method.'Begin'], $Args);
                    $this->__push($Object, $Args,$method);
                    ob_start();
                    return true;
                }
            }
            elseif($State == -1) {
                if(method_exists($Object,$fn = $method) || method_exists($Object,$fn = $method.'End')) {
                    @list(,$ObjArg) = $this->__pop();
                    $inner = ob_get_clean();
                    if(!method_exists($Object,$method.'Skip') || empty(call_user_func([$Object,$method.'Skip'], $ObjArg))) {
                        call_user_func([$Object,$fn], $ObjArg, $inner);
                    }
                    return true;
                }
            }
            return false;
        }

        protected function __($Class,$Args,$State,$Src) {
            if(empty($object = $this->__current()) || !$this->__render('On'.implode('',$Class),$object[0],$Args,$State)) {
                if(empty($object = $this->__class(implode('\\',$Class))) || !$this->__render('On',$object,$Args,$State)) {
                    print $this->__unescape($Src).PHP_EOL;
                }
            }
        }
    }

    class Template
    {
        use TemplateParser;

        private $TplArgs,$TplFileName;
        public function __construct($TplFileName=null,$TplArgs=[]) {
            $this->TplArgs = $TplArgs;
            $this->TplFileName = $TplFileName;
        }

        public function Display($TplFileName=null,$TplArgs=[]) {
            print $this->Fetch($TplFileName,$TplArgs);
        }
        public function Fetch($TplFileName=null,$TplArgs=[]) {
            return $this->__compile($TplFileName??$this->TplFileName, empty($TplArgs) ? $this->TplArgs :  array_merge($this->TplArgs??[],$TplArgs) );
        }
        public function Export($ExportFileName,$ExportMimeType,$TplFileName=null,$TplArgs=[]){
            ini_set('short_open_tag', 0);
            try {
                $Content = (string)$this->Fetch($TplFileName,$TplArgs);
            } catch (Exception $ex) {

            }
            ini_set('short_open_tag', 'on');
            while(ob_get_level()) {ob_end_clean();}
            header("Content-Type: $ExportMimeType; charset=utf-8");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Transfer-Encoding: binary");
            header('Content-Length: ' . strlen($Content));
            header('Content-Disposition: attachment; filename="'.$ExportFileName.'";filename*=utf-8\'\''.rawurlencode($ExportFileName));
            print $Content;
            exit;
        }
        public function __toString() { return $this->Fetch($this->TplFileName,$this->TplArgs); }

        protected function __compile($TplFileName,$TplArgs) {
            if(($ModTime = $this->ModTime = FileTime($SrcFile = Application::DirModules.$TplFileName))) {
                if($ModTime >= FileTime($CompileFileName = Application::DirTemplateCompile.preg_replace('/[\W-]+/u', '_', $TplFileName))) {
                    !MakeDir(Application::DirTemplateCompile, true) && trigger_error(__METHOD__.' cannot create output compile directory '.\Path(Application::DirTemplateCompile),E_USER_WARNING);
                    WriteFileContent($CompileFileName, $this->__parse(ReadFileContent($SrcFile)));
                }
                ob_start();
                try {
                    @extract($TplArgs,EXTR_REFS);
                    include (Path($CompileFileName));
                } catch (\Exception $e) {
                    ob_clean();
                    var_export($e);
                }
                return ob_get_clean();
            }
            trigger_error(__METHOD__.' temlate file '.$SrcFile.' not exist',E_USER_ERROR);
        }
    }
}

namespace
{
    abstract class Widget
    {
        const AssetsDirName = '.assets/';
        
        private $Onwer;
        public function __construct(\Web\Template $tpl) {
            $this->Onwer = $tpl;
        }
        protected function IsFileOld($assetFile) {
            return !($AssetTime = @filemtime($assetFile)) || $this->Onwer->ModTime > $AssetTime;
        }
        
        private function AssetsDir() {
            if(!file_exists($base = \Globals\Server::$BaseDir.DIRECTORY_SEPARATOR.self::AssetsDirName)) {
                @mkdir($base, 0775);
            }
            return $base;
        }
        private function AssetsHashName($assetName,$Ext) {
            return decoct(crc32(\Globals\Server::$Domain)).'-'.sha1(\Globals\Server::$Domain.'-'.$assetName).$Ext;
        }
        
        protected function AssetsUrl($assetName,$Ext) {
            return '/'.self::AssetsDirName.$this->AssetsHashName($assetName,$Ext);
        }
        protected function AssetsFile($assetName,$Ext) {
            return $this->AssetsDir().$this->AssetsHashName($assetName,$Ext);
        }
        protected function AssetsUse($assetName) {
            return $this->AssetsDir().$assetName;
        }
        
        
        protected function tpl($tpl,$args=[]) {
            ob_start();
            try {
                @extract($args,EXTR_REFS);
                include ($tpl);
            } catch (\Exception $e) {
                ob_clean();
            }
            return ob_get_clean();
        }
        abstract public function OnBegin($args);
        abstract public function OnEnd($args,$inner);
        public function OnSkip($args){return 0;}
    }
}
