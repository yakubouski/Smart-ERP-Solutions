<?php
namespace Globals {
    final class Server {
        /**
         * Document root
         * @var string
         */
        static public $BaseDir;
        /**
         * Server domain name
         * @var string
         */
        static public $Domain;

        /**
         * Возвращает абсолютный путь
         * @param string $RelativePathName
         */
        static public function Path($RelativePathName='') {
            return empty($RelativePathName) ? self::$BaseDir : (self::$BaseDir.ltrim($RelativePathName,'/\\'));
        }
    }
    final class Request {
        /**
         * Request method (POST|GET|PUT ... etc.)
         * @var string
         */
        static public $Method;
        /**
         * Request protocol name http|https
         * @var string
         */
        static public $Protocol;
    }
}
namespace {
    call_user_func(function(){
        \Globals\Server::$BaseDir = filter_input(INPUT_SERVER,'DOCUMENT_ROOT',FILTER_SANITIZE_URL) . DIRECTORY_SEPARATOR;
        \Globals\Server::$Domain = filter_input(INPUT_SERVER,'SERVER_NAME',FILTER_SANITIZE_URL);
        \Globals\Request::$Method = filter_input(INPUT_SERVER,'REQUEST_METHOD',FILTER_SANITIZE_STRING);
        \Globals\Request::$Protocol = !empty(filter_input(INPUT_SERVER,'HTTPS',FILTER_SANITIZE_STRING)) ? 'https':'http';
        spl_autoload_register(function($Class) {
            $Class = str_replace('\\','/',strtolower($Class));
            ($exist = file_exists($filename = (__DIR__.DIRECTORY_SEPARATOR.$Class.'.php')) && (include_once($filename)));
            !$exist && \Sys\Autoload::Import($Class);
        });
    });
}