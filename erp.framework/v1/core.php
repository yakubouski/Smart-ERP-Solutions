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

        /**
         * Server request uri
         * @var string
         */
        static public $Uri;
    }
}
namespace {

    call_user_func(function(){
        \Globals\Server::$BaseDir = filter_input(INPUT_SERVER,'DOCUMENT_ROOT',FILTER_SANITIZE_URL);
        \Globals\Server::$Domain = filter_input(INPUT_SERVER,'SERVER_NAME',FILTER_SANITIZE_URL);
        \Globals\Request::$Method = filter_input(INPUT_SERVER,'REQUEST_METHOD',FILTER_SANITIZE_STRING);
        \Globals\Request::$Protocol = !empty(filter_input(INPUT_SERVER,'HTTPS',FILTER_SANITIZE_STRING)) ? 'https':'http';
        \Globals\Request::$Uri = parse_url(filter_input(INPUT_SERVER,'REQUEST_URI',FILTER_SANITIZE_URL),PHP_URL_PATH);

        spl_autoload_register(function($Class) {
            $Class = str_replace('\\','/',strtolower($Class));
            if(file_exists($filename = (__DIR__.DIRECTORY_SEPARATOR.$Class.'.php'))) {
                include_once ($filename);
            }
            else {
                \Sys\Autoload::Import($Class);
            }
        });
    });

    /**
     * Get absolute file pathname
     * @param string $PathName if name begin from DIRECTOR_SEPARATOR char, result SERVER_ROOT directory, otherwise virtual host directory
     * @return string
     */
    function Path($PathName) {
        return Application()->Path($PathName);
    }

    function Directory($PathName) {
        return $PathName[($Len = (mb_strlen($PathName)-1))] === '/' || $PathName[$Len] === '\\' ? $PathName : ($PathName.DIRECTORY_SEPARATOR);
    }
    function ReadFileContent($FileName,$Flags=null,$DefaultContent=null) {
        return \Sys\File\ReadFile($FileName,$Flags,$DefaultContent);
    }

    function WriteFileContent($FilePathName,$Content,$Flags=null)  {
        return Sys\File\WriteFile($FilePathName,$Content,$Flags);
    }

    function Upload($FormVarName,$UploadDirectoryPath,$AllowedTypes=null,$MaxFileSize=0,$UploadFileNamePrefix='',$UploadHandler=null) {
        return \Sys\File\Upload($FormVarName,$UploadDirectoryPath,$AllowedTypes,$MaxFileSize,$UploadFileNamePrefix,$UploadHandler);
    }

    function Download($FileUid,$UploadDirectoryPath,$DownloadFileName=null) {
        return \Sys\File\Download($FileUid,$UploadDirectoryPath,$DownloadFileName);
    }

    function DownloadFiles($FileUids,$UploadDirectoryPath,$ArchiveName='archive.zip') {
        return \Sys\File\DownloadFiles($FileUids,$UploadDirectoryPath,$ArchiveName);
    }

    function DownloadContent($Name,$Content,$Mime,$Headers=[]) {
        return \Sys\File\DownloadContent($Name,$Content,$Mime,$Headers);
    }

    function MakeDir($DirectoryPathName,$htaccess=false,$Mode=0774) {
        return \Sys\File\MkDir($DirectoryPathName,$htaccess,$Mode);
    }

    function MetaInfo($Uid,$UploadDirectoryPath) {
        return \Sys\File\MetaInfo($Uid,$UploadDirectoryPath);
    }

    function FileExists($FilePathName) {
        return \Sys\File\Exists($FilePathName);
    }

    function FileTime($FilePathName,$TouchTime=null,$ATime=null) {
        return \Sys\File\Time($FilePathName,$TouchTime,$ATime);
    }
}