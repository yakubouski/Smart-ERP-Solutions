<?php
namespace WidgetsV1;
class HtmlAssets extends \Widget
{
    const AssetsDirName = '.assets/';

    public function OnBegin($args) {}

    public function OnEnd($args,$inner) {
    }
    
    public function OnStyleLess($args,$inner) {
        if(isset($args['name'])) {
            $file = $this->AssetsFile($args['name'],'.css');
            if(isset($args['debug']) || $this->IsFileOld($file)) {
                $use = [];
                if(isset($args['import'])) {
                    foreach(explode(',',$args['import'])?:[] as $fn) {
                        if(file_exists($lfi = $this->AssetsUse(trim($fn)))) {
                            $use[] = file_get_contents($lfi);
                        }
                    }
                }
                $less = new \Lib\Less();
                $less->setFormatter("compressed");
                $less->setImportDir(['/'.self::AssetsDirName]);
                isset($args['less-vars']) && $less->setVariables(json_decode($args['less-vars'],true));
                file_put_contents($file, $less->compile(implode(PHP_EOL,$use).PHP_EOL.$inner));
            }
        }
    }
    public function OnStyleCss($args,$inner) {
        if(isset($args['name'])) {
            $file = $this->AssetsFile($args['name'],'.css');
            if($this->IsFileOld($file)) {
                file_put_contents($file, $inner);
            }
        }
    }
}

return '\WidgetsV1\HtmlAssets';