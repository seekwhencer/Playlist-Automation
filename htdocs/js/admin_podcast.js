$(document).ready(function() {
    Podcast.addListingBehavior();
    Podcast.addFormBehavior();
    
    $('.button-add-podcast').on('click', function(e) {
        e.preventDefault();
        Podcast.editPodcast('');
    });
    
});

var Podcast = {

    addListingBehavior : function() {
        $('#ListingPodcast a').on('click', function(e) {
            e.preventDefault();
            var fileName = $(this).attr('data-filename');
            
            $('#ListingPodcast a').removeClass('active');
            $(this).addClass('active');
            Podcast.editPodcast(fileName);
        });

    },
    
    editPodcast : function(fileName){
        $.ajax({
            'url' : HOME_URL + 'admin/podcast/edit?podcast=' + fileName,
            'method' : 'POST'
        }).done(function(e) {
            $('#FormPodcast').html(e);
            Podcast.addFormBehavior();
        });
    },

    //
    addFormBehavior : function() {
        $('#button-save').on('click', function(e) {
            var fileName = $('form input[name="podcastForm[file_name]"]').val();
            e.preventDefault();
            var title = $('form input[name="podcastForm[name]"]').val();

            if (title != '')

                $.ajax({
                    'method' : 'POST',
                    'url' : HOME_URL + 'admin/podcast/save?podcast=' + fileName,
                    'data' : $('form').serialize()
                }).done(function(e) {
                    Podcast.getListing();
                    Podcast.flushForm();
                });
        });
        
        $('#button-delete').on('click',function(e){
            e.preventDefault();
            Podcast.deletePodcast();
        });
        
        $('#button-duplicate').on('click',function(e){
            e.preventDefault();
            Podcast.duplicatePodcast();
        });
        
        $('#button-preview').on('click',function(e){
            e.preventDefault();
            Podcast.previewPodcast();
        });
    },

    //
    getListing : function() {
        $.ajax({
            'url' : HOME_URL + 'admin/podcast/getlisting',
        }).done(function(e) {
            $('#ListingPodcast').html(e);
            Podcast.addListingBehavior();
        });
    },
    
    deletePodcast : function(){
        var fileName = $('form input[name="podcastForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/podcast/delete?podcast=' + fileName,
        }).done(function(e) {
            Podcast.getListing();
            Podcast.flushForm();
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
    
    duplicatePodcast : function(){
        var fileName = $('form input[name="podcastForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/podcast/duplicate?podcast=' + fileName,
        }).done(function(e) {
            Podcast.getListing();
            Podcast.flushForm();
        });
    },
    
    previewPodcast : function(){
        var fileName = $('form input[name="podcastForm[file_name]"]').val();
        $.ajax({
            'url' : HOME_URL + 'admin/podcast/preview?podcast=' + fileName,
        }).done(function(html) {
            Podcast.View.podcastPreview(html);
        });
    },
    
    /**
     * 
     *  
     */
    View : {
        
        podcastPreview : function(html){
              $('#overlay').html(html);
              $('#overlay').show();
        },
            
    },
    
    
};
