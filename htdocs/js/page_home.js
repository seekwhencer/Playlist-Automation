$(document).ready(function() {

    $('a, button').on('mouseup.uglyborder', function() {
        $(this).blur();
    });
    
    Home.init();

});

var Home = {

    hb : '',

    init : function() {
        Home.heartbeat();
        Home.hb = setInterval(function() {
            Home.heartbeat();
        }, 5000);
        
        $('#buttonNextSong').on('click', function(){
            $.get(HOME_URL + 'nextsong');
        });
    },

    heartbeat : function() {
        $.ajax({
            'url' : HOME_URL + 'heartbeat'
        }).done(function(e) {
            Home.View.updateHeartbeat(e);
        });
    },
    
    
    
    View : {
        updateHeartbeat : function(e){
            $('#showTitle').html($.parseJSON(e).show);
            $('#songTitle').html($.parseJSON(e).song.song);
            
            var seek = parseInt($.parseJSON(e).song.seek);
            var duration;
            var dt = new Date();
            var sec = dt.getSeconds() + (60 * (dt.getMinutes() + (60 * dt.getHours())));
            var p = sec/86400*100;
            var percent =  parseInt(parseInt($('#songMarker').css('marginLeft').replace('px','')) / $('body').width() * 100);
            
            console.log(seek + ' ' + percent);
            
            
            $('#scheduleMarker').animate({
                marginLeft:p+'%'
            },5000,'linear');
                       
            if(seek < percent){
                duration = 250;
            } else {
                duration = 5000;
            }
            $('#songMarker').animate({
                marginLeft:seek+'%'
            },duration,'linear'
            );
        },
    }
    
    
    
};
