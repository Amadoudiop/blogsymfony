$(function () {

    $(window).data('ajaxready', true);
    filterChoice = $("#listing-choice").val()
    lastElement = $('#element-container table:last')
    lastElementDate = lastElement.attr('data-date')
    spinner = '<div style="display:none;" id="spinner" class="spinner-border" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>'

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
            data: 'lastElementDate=' + lastElementDate,
            success: function (data) {
                $("#element-container").html(data)
                $('#spinner').fadeOut(400);
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
        ){
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

})