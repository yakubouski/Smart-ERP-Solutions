<html:page url='/'
           meta-title="Генератор страницы" meta-description="Пример генерации страниц" 
           meta-keywords="" meta-author="" meta-copyright="" meta-charset="" meta-viewport=""
           cache-max-age="604800"
           rel-canonical="" rel-author="" rel-publisher="" rel-icon="" rel-icon-128x128="">
    
    <assets:style src="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <assets:style src="https://fonts.googleapis.com/css?family=Roboto:100,300,400&subset=cyrillic"/>
    <assets:style name="default.css"/>
    
    <page:header>
        <header:title logo-phone="" logo-tablet="" logo-desktop="">Страница</header:title>
        <header:menu menu-icon="&#xE5D2;">
            <menu:item action-link="" action-dialog="" icon-svg="">wedwe</menu:item>
        </header:menu>
    </page:header>
    
    Hello world
</html:page>


<html:page url='/tnx/'>
    
</html:page>

       
<html:assets> 
    <style:less name="default.css" import="html.less,mixin.less">
    @HEADER-HEIGHT: 3rem;
    body {
        font-family: 'Roboto', sans-serif;
        padding-top:  @HEADER-HEIGHT;
        &>header {
            height: @HEADER-HEIGHT;
            background-color: white;
            .drop-shadow();
        }
    }
    </style:less>
</html:assets>