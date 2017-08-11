<?php
namespace WidgetsV1
{
    class Page extends \Widget
    {
        static public $HTML_PAGE_GLOBALS = [];

        protected function __head(&$args) {
            $HeadList = [
                'meta-viewport'=>'<meta name="viewport" content="width=device-width; height=device-height; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />',
            ];

            foreach($args as $a=>$v) {
                @list($type,$name) = ($arg = explode('-',$a));
                switch($type) {
                    case 'meta':
                        $HeadList[$a] = '<meta name="'.$name.'" content="'.$v.'">';
                        if(
                            ==='title') {
                            $HeadList['title'] = '<title>'.$v.'</title>';
                        }
                        break;
                    case 'rel':
                        $HeadList[$a] = '<link rel="shortcut icon" type="image/x-icon" href="/img/icon/favicon.ico">';
                        break;
                }
            }

            return !@empty(self::$HTML_PAGE_GLOBALS['head']) ? (implode(PHP_EOL,self::$HTML_PAGE_GLOBALS['head']).PHP_EOL) : '';
        }

        public function OnBegin($args) {

        }

        public function OnEnd($args,$inner) {
            $args['__HTML__INNER__'] = $inner;
            print $this->tpl(__DIR__.'/page/default.tpl',$args);
        }

        public function OnSkip($args) {
            return $args['url'] === '/tnx/';
        }

        public function OnPageLayout($args,$inner) {

        }
    }

}

namespace {

    Web\Template::Register([
        'meta\title' => '\WidgetsV1\HeadMetaTitle',
    ]);

    return "\WidgetsV1\Page";
}
