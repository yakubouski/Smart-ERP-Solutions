<?php
class IndexController extends Controller {

    public function OnDefault() {
        $tpl = new Template\Php();
        $tpl->Display('index/tpls/default.tpl',['Value'=>Module::Index()->GetValue()]);
    }
    public function OnTest() {
        echo __METHOD__;
    }
}
