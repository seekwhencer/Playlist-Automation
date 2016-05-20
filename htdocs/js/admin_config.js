$(document).ready(function() {
    Config.addFormBehavior();
    
    $('a').on('mouseup.uglyborder',function(){
        $(this).blur();
    });
    
});

var Config = {
    addFormBehavior : function(){
        
    }
};
