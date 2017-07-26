<?php
call_user_func(function(){
    spl_autoload_register(function($Class) {
        $Class = str_replace('\\','/',strtolower($Class));
        ($exist = file_exists($filename = (__DIR__ERP_FRAMEWORK__.$Class.'.php')) && (include_once($filename)));
        !$exist && \Sys\Autoload::Import($Class);
    });
});
