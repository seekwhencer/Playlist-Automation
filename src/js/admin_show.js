$(document).ready(function() {
    Show.addListingBehavior();
    Show.addFormBehavior();
    
    $('a').on('mouseup.uglyborder',function(){
        $(this).blur();
    });
    
});

var Show = {

    addListingBehavior : function() {
        $('#ListingShow a').on('click', function(e) {
            e.preventDefault();
            var fileName = $(this).attr('data-filename');
            
            $('#ListingShow a').removeClass('active');
            $(this).addClass('active');
            
            $.ajax({
                'url' : HOME_URL + 'admin/show/edit?show=' + fileName,
                'method' : 'POST'
            }).done(function(e) {
                $('#FormShow').html(e);
                Show.addFormBehavior();
            });

        });

    },

    //
    addFormBehavior : function() {
        $('#button-save').on('click', function(e) {
            var fileName = $('form input[name="showForm[file_name]"]').val();
            e.preventDefault();
            var title = $('form input[name="showForm[name]"]').val();

            if (title !== '')

                $.ajax({
                    'method' : 'POST',
                    'url' : HOME_URL + 'admin/show/save?show=' + fileName,
                    'data' : $('form').serialize()
                }).done(function(e) {
                    Show.getListing();
                    Show.flushForm();
                });
        });
        
        $('#button-delete').on('click',function(e){
            e.preventDefault();
            Show.deleteShow();
        });
        
        $('#button-duplicate').on('click',function(e){
            e.preventDefault();
            Show.duplicateShow();
        });
        
        $('#button-preview').on('click',function(e){
            e.preventDefault();
            Show.previewShow();
        });
        
         $('[data-toggle="tooltip"]').tooltip();
    },

    //
    getListing : function() {
        $.ajax({
            'url' : HOME_URL + 'admin/show/getlisting',
        }).done(function(e) {
            $('#ListingShow').html(e);
            Show.addListingBehavior();
        });
    },
    
    deleteShow : function(){
        var fileName = $('form input[name="showForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/show/delete?show=' + fileName,
        }).done(function(e) {
            Show.getListing();
            Show.flushForm();
        });
    },
    
    flushForm : function(){
        $('form input, form textarea').val('');
        $('#button-delete').hide();
        $('#button-duplicate').hide();
        $('#button-preview').hide();
        $('select').each(function(){
           $(this).find('option').eq(0).attr('selected',true);
        });
    },
    
    duplicateShow : function(){
        var fileName = $('form input[name="showForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/show/duplicate?show=' + fileName,
        }).done(function(e) {
            Show.getListing();
            Show.flushForm();
        });
    },
    
    previewShow : function(){
        var fileName = $('form input[name="showForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/show/preview?show=' + fileName,
        }).done(function(html) {
            Show.View.showPreview(html);
        });
    },
    
    /**
     * 
     *  
     */
    View : {
        
        showPreview : function(html){
              $('#overlay').html(html);
              $('#overlay').show();
        },
            
    },
    
    
};
