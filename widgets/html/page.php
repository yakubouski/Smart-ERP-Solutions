<?php
namespace WidgetsV1
{
    trait HtmlAssetsStyle {
        public function OnAssetsStyle($args,$inner) {
            if(isset($args['name'])) {
                self::$HTML_STYLES[] = '<link rel="stylesheet" href="'.$this->AssetsUrl($args['name'],'.css').'">';
            }
            elseif(isset($args['src'])) {
                self::$HTML_STYLES[] = '<link rel="stylesheet" href="'.$args['src'].'">';
            }
        }
    }
    trait HtmlPageLayout {
        public function OnPageLayout($args,$inner) {

        }
    }
        
    trait HtmlPageHeader {    
        public function OnPageHeader($args,$inner) {
            print <<<"HTML"
<header>$inner</header>
HTML;
        }
    }
    
    class Page extends \Widget
    {
        use HtmlAssetsStyle;
        use HtmlPageHeader;
        use HtmlPageLayout;
        
        static protected $HTML_PAGE_GLOBALS = [];
        static protected $HTML_STYLES = [];

        private function __head(&$args) {
            $HeadList = [
                'meta-charset'=>'<meta name="charset" content="utf-8"/>',
                'meta-viewport'=>'<meta name="viewport" content="width=device-width; height=device-height; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />',
            ];

            foreach($args as $a=>$v) {
                @list($type,$name) = ($arg = explode('-',$a,2));
                switch($type) {
                    case 'meta':
                        if(!empty($v)) {
                            $HeadList[$a] = '<meta name="'.$name.'" content="'.$v.'">';
                            ($name==='title') && $HeadList['title'] = '<title>'.$v.'</title>';
                        }
                        break;
                    case 'cache':
                        if($name=='max-age') {
                            header('Cache-Control: max-age='.intval($v)); 
                        }
                        break;
                    case 'rel':
                        !empty($v) && ($HeadList[$a] = '<link rel="'.$type.'" href="'.$v.'">');
                        break;
                }
            }
            return (!empty($HeadList) ? ("\t\t".implode(PHP_EOL."\t\t",$HeadList).PHP_EOL):NULL) . (!empty(self::$HTML_STYLES) ? ("\t\t".implode(PHP_EOL."\t\t",self::$HTML_STYLES).PHP_EOL):NULL);
        }

        public function OnBegin($args) {}

        public function OnEnd($args,$inner) {
            $args['__HTML__INNER__'] = $inner;
            $args['__HTML__HEAD__'] = $this->__head($args);
            print $this->tpl(__DIR__.'/page/default.tpl',$args);
        }

        public function OnSkip($args) {
            return $args['url'] === '/tnx/';
        }
    }
}

namespace {

    Web\Template::Register([
        'meta\title' => '\WidgetsV1\HeadMetaTitle',
    ]);

    return "\WidgetsV1\Page";
}
