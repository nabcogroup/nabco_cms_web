
var MM = (function() {
    function single(dialog,args) {
        dialog.init(function() {
            setTimeout(function() {
                $.get(args, function(data){
                    dialog.find('.bootbox-body').html(data);
                    
                    //piece of code
                    var mediaThumbnail = dialog.find('.js-media-manager-thumbnail');
                    if (mediaThumbnail.length > 0) {
                        mediaThumbnail.on("click", function (e) {
                            e.preventDefault();
                            var metaMedia = {
                                meta:   $(this).data("imgName"),
                                thumbnail: $(this).data("thumbnail"),
                                fullpath: $(this).data("fullpath")
                            };

                            $("#meta_media").val(JSON.stringify(metaMedia));
                            $("#meta-name").html(metaMedia.meta);
                        });
                    }
                });
            },1000);
        })
    }

    return {
        single: single
    }
})();
