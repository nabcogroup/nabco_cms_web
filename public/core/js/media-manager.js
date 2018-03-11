var MM = (function () {

    var metaMedia = {};

    function createDialog(args) {
        return bootbox.confirm({
            title: "Media Manager",
            size: "large",
            message: "Loading...",
            closeButton: true,
            buttons: {
                confirm: {
                    label: 'Ok',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: args.callback
        });
    }

    function single(args) {

        var dialog = createDialog({
                callback: function (result) {
                    if (result) {

                        //override output
                        $(".js-input-profile").val(metaMedia.meta);
                    }
                }
            })
            .init(function () {
                $.get(args.options, function (data) {
                    dialog.find('.bootbox-body').html(data);
                    //piece of code
                    var mediaThumbnail = dialog.find('.js-media-manager-thumbnail');
                    if (mediaThumbnail.length > 0) {
                        mediaThumbnail.on("click", function (e) {
                            e.preventDefault();
                            metaMedia = {
                                meta: $(this).data("imgName"),
                                thumbnail: $(this).data("thumbnail"),
                            };
                            console.log($(this).data("preview"));
                            //preview
                            $(".js-img-profile").attr('src', $(this).data("preview"));

                        });
                    }
                });
            });
    }

    function multiple(args) {

        var dialog = createDialog({
                callback: function (result) {
                    if(result) {
                        var values = ($(".js-input-galleries").val().length > 0) ? JSON.parse($(".js-input-galleries").val()) : [];
                        values.push(metaMedia);
                        console.log(values);
                        //override output
                        $(".js-input-galleries").val(JSON.stringify(values));
                    }
                }
            })
            .init(function () {
                $.get(args.options, function (data) {
                    dialog.find('.bootbox-body').html(data);

                    var mediaThumbnail = dialog.find('.js-media-manager-thumbnail');
                    if (mediaThumbnail.length > 0) {
                        mediaThumbnail.on("click", function (e) {
                            e.preventDefault();
                            metaMedia = { meta: $(this).data("imgName")};
                        });
                    }
                });
            })
    }

    return {
        single: single,
        multiple: multiple
    }
})();