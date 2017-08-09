<?php
namespace UiControl;

class Tabs extends \Widget
{
    public function OnBegin($args) {
        echo "{";
    }

    public function OnEnd($args,$inner) {
     echo $inner."}";   
    }

    public function OnTabsItem($args,$inner) {
        echo "3";
    }
}

return 'UiControl\Tabs';