<html:page url='/'
           meta-title="Генератор страницы" meta-description="Пример генерации страниц" 
           meta-keywords="" meta-author="" meta-copyright="" meta-charset="" meta-viewport=""
           cache-max-age="604800"
           rel-canonical="" rel-author="" rel-publisher="" rel-icon="" rel-icon-128x128="">
    
    <assets:style src="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <assets:style src="https://fonts.googleapis.com/css?family=Roboto:100,300,400&subset=cyrillic"/>
    <assets:style name="default.css"/>
    <assets:js src="/.assets/ui.js"/>
    
    <page:header>
        <header:title after="пост заголовок">Страница</header:title>
        <header:menu>
            <menu:item action-link="" action-dialog="" icon-svg="">wedwe</menu:item>
        </header:menu>
    </page:header>
    <div style="height: 1000px;">
    Hello world
    </div>
</html:page>


<html:page url='/tnx/'>
    
</html:page>

       
<html:assets> 
    <style:less name="default.css" import="html.less,mixin.less" debug="1" less-vars='{"HEADER-HEIGHT": "3rem"}'>
    body {
        font-family: 'Roboto', sans-serif;
        max-width: 1024px;
        
        
        &>header {
            
        }
    }
    </style:less>
</html:assets>