$(document).ready(function () {
    $('#image').change(function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#photoReview').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files['0']);
    });
});
