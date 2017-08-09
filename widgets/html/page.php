<?php
namespace Html 
{
    class Page extends \Widget
    {
        public function OnBegin($args) {
            
        }

        public function OnEnd($args,$inner) {
            $args['__HTML__INNER__'] = $inner;
            print $this->tpl(__DIR__.'/page/page.tpl',$args);
        }
        
        public function OnSkip($args) {
            return $args['url'] === '/tnx/';
        }
        
        public function OnPageLayout($args,$inner) {
            
        }
    }
}