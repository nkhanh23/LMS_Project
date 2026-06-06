/* course name to slug */
$(document).ready(function () {
    $('#name').on('input', function () {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
        $('#slug').val(slug);
    });
});

/* dynamic dependent jquery */

$(document).ready(function () {
    $('#category').on('change', function () {
        var categoryId = $(this).val();

        if (categoryId) {
            $.ajax({
                url: '/instructor/get-subcategories/' + categoryId,
                type: 'GET',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Dynamically fetch CSRF token
                },

                success: function (data) {

                    console.log(data);
                    $('#subcategory').empty(); // Clear previous options
                    $('#subcategory').append('<option value="" disabled selected>Chọn danh mục con</option>');

                    var selectedSubcategory = $('#subcategory').data('selected');

                    $.each(data, function (key, value) {
                        var selected = String(selectedSubcategory) === String(value.id) ? ' selected' : '';
                        $('#subcategory').append('<option value="' + parseInt(value.id) + '"' + selected + '>' + value.name + '</option>');
                    });
                },
                error: function () {
                    alert('Lỗi khi tải danh mục con');
                }
            });
        } else {
            $('#subcategory').empty();
            $('#subcategory').append('<option value="" disabled selected>Chọn danh mục con</option>');
        }
    });

    if ($('#category').val()) {
        $('#category').trigger('change');
    }
});

/* Course goal */

$(document).ready(function () {
    // Add new input field for course goal
    $('#addGoalInput').on('click', function (e) {
        e.preventDefault(); // Prevent default behavior

        $('#goalContainer').append(`
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <input type="text" class="form-control" name="course_goals[]" placeholder="Enter Course Goal" />
                <button type="button" class="btn btn-danger removeGoalInput">-</button>
            </div>
        `);
    });

    // Remove an input field
    $(document).on('click', '.removeGoalInput', function () {
        $(this).closest('div').remove();
    });
});
