$(document).ready(function () {
    $('#Photo').change(function (e) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photoPreview').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(e.target.files[0]);
    });
});