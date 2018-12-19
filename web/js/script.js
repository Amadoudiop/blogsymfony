$(function () {

    $("#listingChoice").change(function(){
        console.log($(this).val())
        $.ajax({
            url: Routing.generate($(this).val()),
            type: "POST",
            data: "",
            success: function (state) {
                console.log(state)
            },
            error: function (state) {
                console.log(state)
            }
        })
    })
})