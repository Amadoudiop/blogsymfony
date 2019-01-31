$(function () {
    $(window).data('ajaxready', true);
    scrollOk = true;
    elementContainer = $('#element-container')
    spinnerTop = '<div style="display:none;" id="spinner-top" class="spinner-border" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>'
    spinnerBot = '<div style="display:none;" id="spinner-bot" class="spinner-border" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>'

    $('#element-container').prepend(spinnerTop);
    $('#spinner-top').fadeIn(400);
    $.ajax({

        url: Routing.generate("list_article_home"),
        type: "POST",
        data: 'lastElementDate=list_article_home',
        success: function (data) {
            if (data == "end") {
                scrollOk = false;
            }else{
                elementContainer.append(data);
            }
            $('#spinner-top').fadeOut(400);
            $(window).data('ajaxready', true);
        },
        error: function (data) {
            lastElement.after('error');
            $('#spinner-top').fadeOut(400);
            $(window).data('ajaxready', true);
        }
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
            && scrollOk == true
            || ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()
            && $(window).data('ajaxready') == true
            && scrollOk == true
        ) {
            $(window).data('ajaxready', false);
            //lastElement.after(spinner)
            $('#element-container').append(spinnerBot)
            $('#spinner-bot').fadeIn(400);
            $.ajax({
                url: Routing.generate("list_article_home"),
                type: "POST",
                data: 'lastElementDate=' + lastElementDate,
                success: function (data) {
                    if (data == "end") {
                        scrollOk = false;
                    }
                    lastElement.after(data);
                    $('#spinner-bot').fadeOut(400);
                    $(window).data('ajaxready', true);
                },
                error: function (data) {
                    lastElement.after('error');
                    $('#spinner-bot').fadeOut(400);
                    $(window).data('ajaxready', true);
                }
            })
        }
    })
})