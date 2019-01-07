$(function () {

    filterChoice = $("#listing-choice").val()
    lastElement = $('#element-container table:last')
    lastElementDate = lastElement.attr('data-date')
    spinner = '<div style="display:none;" id="spinner" class="spinner-border" role="status">\n' +
                '<span class="sr-only">Loading...</span>\n' +
                '</div>'
    $(window).data('ajaxready', true);

    // choice listing

    $("#listing-choice").change(function(){
        filterChoice = $(this).val()
        console.log(filterChoice)
        $('#element-container').prepend(spinner)
        $('#spinner').fadeIn(400);
        $.ajax({
            url: Routing.generate(filterChoice),
            type: "POST",
            data:'lastElementDate=' +lastElementDate,
            success: function (data) {
                $("#element-container").html(data)
                $('#spinner').fadeOut(400);
            },
            error: function (data) {
                console.log(data)
            }
        })
    })

    // scroll

    $(window).scroll(function (event) {
        /*var st = $(this).scrollTop()
        console.log("st= "+st)
        var windowHeight = $(this).height()
        console.log("windowHeight: " + windowHeight)

        var elementHeight = lastElement.height()
        console.log("elementHeight" + elementHeight)*/

        /* if(st >= (st - elementHeight)){

        } */

        lastElement = $('#element-container table:last')
        lastElementDate = lastElement.attr('data-date')
        lastElementId = lastElement.attr('data-id')
        lastElementType= lastElement.attr('data-type')

        console.log('last element ID = '+ lastElementId)
        console.log('last element date = '+ lastElementDate)
        console.log('last element type = '+ lastElementType)
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        console.log("valeur du scrolle ="+ ((scrollHeight - scrollPosition) / scrollHeight))
        if(($(window).scrollTop() + $(window).height()) == ($(document).height())
            && ($(window).data('ajaxready'))== true
            || ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()
            && $(window).data('ajaxready')== true){
                $(window).data('ajaxready', false);
                //lastElement.after(spinner)
                $('#element-container').append(spinner)
                $('#spinner').fadeIn(400);

                console.log("ajax")
                /*lastElement.after('<table data-date="2018-12-31 09:13:05" data-type="user" data-id="209">\n' +
                    '    <thead>\n' +
                    '    <tr>\n' +
                    '        <th>ajax Id</th>\n' +
                    '        <th>Datecreate</th>\n' +
                    '        <th>Promotion</th>\n' +
                    '        <th>Firstname</th>\n' +
                    '        <th>Lastname</th>\n' +
                    '        <th>Alias</th>\n' +
                    '        <th>Mail</th>\n' +
                    '        <th>Status</th>\n' +
                    '        <th>Validation</th>\n' +
                    '    </tr>\n' +
                    '    </thead>\n' +
                    '    <tbody>\n' +
                    '    <tr>\n' +
                    '        <td><a href="/blogsymfony/web/app_dev.php/user/209">209</a></td>\n' +
                    '        <td>2018-12-31 09:13:05</td>\n' +
                    '        <td>1999</td>\n' +
                    '        <td>cigarillos39</td>\n' +
                    '        <td>cigar39</td>\n' +
                    '        <td>cig39</td>\n' +
                    '        <td>39@gmail.com</td>\n' +
                    '                    <td>\n' +
                    '                <a href="/blogsymfony/web/app_dev.php/user/209/refuse">Suprimer</a>\n' +
                    '            </td>\n' +
                    '                            <td>\n' +
                    '                    <a href="/blogsymfony/web/app_dev.php/user/209/setAdmin">ADMIN</a>\n' +
                    '                </td>\n' +
                    '                        </tr>\n' +
                    '    </tbody>\n' +
                    '</table>')*/
                $.ajax({
                    url: Routing.generate(filterChoice),
                    type: "POST",
                    data:'lastElementDate=' +lastElementDate,
                    // data:
                    //     {
                    //     lastElementDate: "lastElementDate"
                    //     },
                    success: function (data) {
                        //console.log(data)
                        //console.log(data[0].user)
                        lastElement.after(data)
                        $('#spinner').fadeOut(400);
                        $(window).data('ajaxready', true);
                    },
                    error: function (data) {
                        //console.log(data)
                        $(window).data('ajaxready', true);
                    }
                })
        }
    })

    function spinnerFade(fade){

        if(fade == 'out'){
            $('#spinner').fadeOut(400);
        }

        if(fade == 'in'){
            $('#spinner').fadeIn(400);
        }

    }
})