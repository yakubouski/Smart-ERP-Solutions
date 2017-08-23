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
    
    trait HtmlAssetsJs {
        public function OnAssetsJs($args,$inner) {
            if(isset($args['name'])) {
                self::$HTML_JS[] = '<link rel="stylesheet" href="'.$this->AssetsUrl($args['name'],'.css').'">';
            }
            elseif(isset($args['src'])) {
                self::$HTML_JS[] = '<script src="'.$args['src'].'" crossorigin="anonymous"></script>';
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
        
        public function OnHeaderTitle($args,$inner) {
            $sub = isset($args['after']) ? (' after="'.$args['after'].'"') : (isset($args['before']) ? (' before="'.$args['before'].'"') :'');
            print <<<"HTML"
<h1{$sub}>$inner</h1>
HTML;
        }
        
        public function OnHeaderMenu($args,$inner) {
            print <<<"HTML"
<button class="header-side-menu"><i class="material-icons">&#xE5D2;</i></button>
HTML;
        }
    }
    
    class Page extends \Widget
    {
        use HtmlAssetsStyle;
        use HtmlAssetsJs;
        use HtmlPageHeader;
        use HtmlPageLayout;
        
        static protected $HTML_PAGE_GLOBALS = [];
        static protected $HTML_STYLES = [];
        static protected $HTML_JS = [
            '<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>'
        ];
        

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
            return (!empty($HeadList) ? ("\t\t".implode(PHP_EOL."\t\t",$HeadList).PHP_EOL):NULL) . (!empty(self::$HTML_STYLES) ? ("\t\t".implode(PHP_EOL."\t\t",self::$HTML_STYLES).PHP_EOL):NULL) . (!empty(self::$HTML_JS) ? ("\t\t".implode(PHP_EOL."\t\t",self::$HTML_JS).PHP_EOL):NULL);
        }

        public function OnBegin($args) {}

        public function OnEnd($args,$inner) {
            $args['__HTML__INNER__'] = $inner.'<aside>werfwer</aside>';
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
