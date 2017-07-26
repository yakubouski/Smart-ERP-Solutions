<?php
define ("DEBUG",1);
require_once './#core/core.php';

#require './vendor/autoload.php';

class Sceleton extends WebApplication {
#    use \Application\FileSession;
#    use \Application\CSRF\Synchronizer;
    
    protected function Sceleton() {
#        $this->DeclareGlobal('SQL',new \Db\MySqli('p:localhost','root','zsq@!wax','test','ru_RU'));
#        $this->SessionStart();
    }
    
    protected function OnException(Exception $ex) {
#        var_export($ex);
    }
}

Sceleton::Run();


/*$SQL->Statement("INSERT INTO `tbl`
    VALUES(?:i,?:string(100),?:d(2),?:blob,?:enum(a|b|c,e),?0:i,?,?1,?:datetime(%d.%m.%Y %H:30:00), ?:url, ?:email,?:phone,?:json )
    ")->Execute('de424','<ddd>test string провотестирование</ddd>','1 056,9094','ssssdd<br>qdwerdwer','a','qwdwerdwerc','2017-12-12','https://ot.by/infrx.html?g=33','irokez@tut.by','+375 (44) 747-65-39',['a'=>3242,'b'=>'qwedwedq','c'=>['e'=>'www','dd'=>null]]);*/
/*

$Cursor = $SQL->Cursor("INSERT INTO `byn_offers` (OfferId,Name,Type) VALUES(?:i,?:string,?:enum(Offer|AdditionalOffer,Offer)) ON DUPLICATE KEY UPDATE Name=VALUES(Name),Type=VALUES(Type)");

for($i=1;$i<=15;$i++) {
    $d = $i*10;
    $Cursor->Insert($i,"Name-{$d}","Offer1");
}
$Cursor->Execute();

$Rs = $SQL("SELECT * FROM test.byn_offers WHERE OfferId BETWEEN ?:i AND ?0:i+5",3)->Recordset();
foreach ($Rs as $n=>$r) {
    echo $n,' => ',var_export($r,true);
}*/


