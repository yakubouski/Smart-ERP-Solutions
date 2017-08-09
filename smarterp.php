<?php
!defined('DEBUG') && define ('DEBUG',getenv('DEBUG')??0);
include_once (__DIR__.'/erp.framework/v1/core.php');

use Globals\Request;
use Globals\Server;

class SaaSApplication extends \Web\Application {
    use Web\Core\Session;

    protected function OnInitalize() {
        //echo $this->Path('.log/'.Server::$Domain.'-saas.log');
        //ini_set('error_log', $this->Path('.log/'.Server::$Domain.'-saas.log'));
        Web\Template::Using(Path('/widgets/'));
    }

    protected function OnException($Ex) {
        
        exit;
    }
    
    protected function On404($Context = null) {
        echo $Context;
    }
}

SaaSApplication::Run('tests/virtual');


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