$(function () {

    $("#listingChoice").change(function(){
        console.log($(this).val())
        $.ajax({
            url: Routing.generate($(this).val()),
            type: "POST",
            data: "",
            success: function (data) {
                console.log(data)
                console.log(data[0].user)

            },
            error: function (data) {
                console.log(data)
            }
        })
    })
})