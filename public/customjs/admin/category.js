$(document).ready(function () {
    $('#name').on('input', function () {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
        $('#slug').val(slug);
    });
});
