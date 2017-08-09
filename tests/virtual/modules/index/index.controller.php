<?php
class IndexController extends Web\Controller {
    public function OnDefault() {
        Template()->Display('index/tpls/default.index.tpl');
    }
}
