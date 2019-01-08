$(function () {

    $(window).data('ajaxready', true);
    filterChoice = $("#listing-choice").val()
    lastElement = $('#element-container table:last')
    spinner = '<div style="display:none;" id="spinner" class="spinner-border" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>'

    /**
     * execute le controller qui accèpte l'utilisateur
     * @param id
     * return boolean
     */
    function acceptUser(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("user_accept", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * execute le controller qui refuse l'utilisateur
     * @param id
     * return boolean
     */
    function refuseUser(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("user_refuse", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * execute le controller qui passe un user en admin
     * @param id
     * return boolean
     */
    function setAdmin(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("set_admin", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * execute le controller qui enlève les privilège d'un admin
     * @param id
     * return boolean
     */
    function unsetAdmin(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("unset_admin", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * execute le controller qui accepte un article
     * @param id
     * return boolean
     */
    function acceptArticle(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("article_accept", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * execute le controller qui refuse un article
     * @param id
     * return boolean
     */
    function refuseArticle(id){
        if($(window).data('ajaxready', true)){
            $(window).data('ajaxready', false)
            $.ajax({
                url: Routing.generate("article_refuse", {'id': id}),
                success: function () {
                    $(window).data('ajaxready', true);
                    $('[data-id='+id+']').slideUp();
                },
                error: function () {
                    $(window).data('ajaxready', true);
                }
            })
        }
    }

    /**
     * récupère le choix selectionné par l'utilisateur
     *
     * param string
     * return Json data
     */
    $("#listing-choice").change(function () {
        filterChoice = $(this).val()
        $('#element-container').prepend(spinner)
        $('#spinner').fadeIn(400);
        $.ajax({
            url: Routing.generate(filterChoice),
            type: "POST",
            data: 'lastElementDate=' + lastElement.attr('data-date'),
            success: function (data) {
                $("#element-container").html(data)
                $('#spinner').fadeOut(400);
                $(".accept-user").bind( "click", function() {
                    acceptUser(
                        $(this).attr("data-id")
                    );
                });
                $(".refuse-user").bind( "click", function() {
                    refuseUser(
                        $(this).attr("data-id")
                    );
                });
                $(".set-admin").bind( "click", function() {
                    setAdmin(
                        $(this).attr("data-id")
                    );
                });
                $(".unset-admin").bind( "click", function() {
                    unsetAdmin(
                        $(this).attr("data-id")
                    );
                });
                $(".accept-article").bind( "click", function() {
                    acceptArticle(
                        $(this).attr("data-id")
                    );
                });
                $(".refuse-article").bind( "click", function() {
                    refuseArticle(
                        $(this).attr("data-id")
                    );
                });
            },
            error: function (data) {
                console.log(data)
            }
        })
    })

    /**
     * vérifie que le scroll est en bas de page
     *
     * param scroll
     * return Json data
     */
    $(window).scroll(function (event) {
        lastElement = $('#element-container table:last')
        lastElementDate = lastElement.attr('data-date')
        lastElementId = lastElement.attr('data-id')
        lastElementType = lastElement.attr('data-type')
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        if (($(window).scrollTop() + $(window).height()) == ($(document).height())
            && ($(window).data('ajaxready')) == true
            || ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()
            && $(window).data('ajaxready') == true
        ) {
            $(window).data('ajaxready', false);
            //lastElement.after(spinner)
            $('#element-container').append(spinner)
            $('#spinner').fadeIn(400);
            $.ajax({
                url: Routing.generate(filterChoice),
                type: "POST",
                data: 'lastElementDate=' + lastElementDate,
                success: function (data) {
                    lastElement.after(data)
                    $('#spinner').fadeOut(400);
                    $(window).data('ajaxready', true);
                },
                error: function (data) {
                    $(window).data('ajaxready', true);
                }
            })
        }
    })
});