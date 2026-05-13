
// //Frontend Wishlist

// $(document).ready(function () {
//     getWishlist();

// });


$(document).on('click', '.wishlist-icon', function () {



    var courseId = $(this).data('course-id');
    var iconElement = $(this).find('i'); // Find the icon inside the clicked element
    var url = '/wishlist/add';

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            course_id: courseId,
            _token: $('meta[name="csrf-token"]').attr('content')


        },
        success: function (response) {
            console.log(response)
            Swal.fire({
                icon: response.status === 'success' ? 'success' : 'error',
                title: response.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                backdrop: false
            });

            // Cập nhật số lượng wishlist nếu status là success
            if (response.status === 'success') {
                updateWishlistCount(response.wishlist_count);

                getWishlist();

                // Thay đổi icon thành trái tim đỏ 'fas' nếu status là success
                iconElement.removeClass('far text-white').addClass('fas text-red-600');


            }


        },
        error: function (xhr) {
            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                backdrop: false
            });
        }
    });
});

// Hàm cập nhật số lượng wishlist
function updateWishlistCount(count) {
    $('#wishlist-count').text(count);
}

//getwishlist

function getWishlist() {

    var url = '/wishlist/all';

    $.ajax({
        url: url,
        type: 'GET',
        data: {

            _token: $('meta[name="csrf-token"]').attr('content')


        },
        success: function (response) {

            if (response.status === 'success') {

                $('#wishlist-course').html(response.html);
            }


        },
        error: function (xhr) {

            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            console.error(message);


        }
    });




}






