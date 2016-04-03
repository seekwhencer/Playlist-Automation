$(document).ready(function() {   
    $('a').on('mouseup.uglyborder',function(){
        $(this).blur();
    });
    
    $('#open-main-menu, #button-close-mainmenu').on('click',function(e){
        e.preventDefault();
        if($('#main-navigation:visible').length>0){
            $('#main-navigation').hide();
        } else {
            $('#main-navigation').show();
        }
    });
});
