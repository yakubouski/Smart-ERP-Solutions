<?php
!defined('__DIR__ERP_ENGINE__') && define('__DIR__ERP_ENGINE__',__DIR__.'/erp.engine/v1/');
!defined('__DIR__ERP_FRAMEWORK__') && define('__DIR__ERP_FRAMEWORK__',__DIR__.'/erp.framework/v1/');
!defined('DEBUG') && define ('DEBUG',getenv('DEBUG')??0);

include_once __DIR__ERP_FRAMEWORK__.'/core.php';

ERP\Bus::Call('User.Auth.IsAuthorized');

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