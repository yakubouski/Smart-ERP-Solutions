<?php
!defined('DEBUG') && define ('DEBUG',getenv('DEBUG')??0);
include_once (__DIR__.'/erp.framework/v1/core.php');
\Sys\Autoload::Using(__DIR__.'/erp.engine/v1/','ERP');

use \Globals\Request as Request;
use \Globals\Server as Server;

class SaaSApplication extends \Web\Application {
    use \Web\UnhandledException;

    protected function __construct() {
        echo $this->Path('/.log/'.Server::$Domain.'saas.log');
        ini_set('error_log', $this->Path('/.log/'.Server::$Domain.'-saas.log'));
    }

    protected function OnUnhandledException($Ex) {
        exit;
    }
}

SaaSApplication::Run();

Application();

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