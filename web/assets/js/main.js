$(document).ready(function () {
    $("#edit_form :input").attr("disabled", true);
    $("#edit_form input[name=birthday]").hide()
    // $("#birthday").hide();
});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img-upload').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function openToEdit() {
    $("#edit_form :input").attr("disabled", false);
    $("#hide-birthday").hide();
    $("#birthday").show();
}