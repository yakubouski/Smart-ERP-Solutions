(function(d){
    "use strict";
    $(d).on('click','button.header-side-menu',function(e){
        e.preventDefault();
        $('body').toggleClass('side-view');
        $(this).toggleClass('a');
    });
})(document);