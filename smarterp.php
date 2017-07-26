<?php
!defined('DEBUG') && define ('DEBUG',getenv('DEBUG')??0);

include_once (__DIR__.'/erp.framework/v1/core.php');

var_export([Globals\Server::$BaseDir,Globals\Server::$Domain,Globals\Request::$Method,Globals\Request::$Protocol]);

\Sys\Autoload::Using(__DIR__.'/erp.engine/v1/','ERP');

class SaaSApplication extends \MVC\Application {

}

SaaSApplication::Run();

#ERP\Bus::Call('User.Auth.IsAuthorized');

/*
define ("DEBUG",1);
require_once './#core/core.php';
#require './vendor/autoload.php';

class smarterp extends WebApplication {
    use \Application\FileSession;
    use \Application\CSRF\Synchronizer;

    protected function Sceleton() {
#        $this->DeclareGlobal('SQL',new \Db\MySqli('p:localhost','root','pwd','test','ru_RU'));
#        $this->SessionStart();
    }

    protected function OnException(Exception $ex) {
#        var_export($ex);
    }
}

smarterp::Run();
 * *
 */