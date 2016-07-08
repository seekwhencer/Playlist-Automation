$(document).ready(function() {
    Config.addFormBehavior();
    
    $('a').on('mouseup.uglyborder',function(){
        $(this).blur();
    });
    
});

var Config = {
    addFormBehavior : function(){
        $('#button-setdate').on('click',function(e){
            e.preventDefault();
            
            $.ajax({
                'url' : HOME_URL + 'admin/config/setdate',
                'method' : 'POST',
                'data' : $('#form-date').serialize()
            }).done(function(e) {
                
            });
        });
    }
};