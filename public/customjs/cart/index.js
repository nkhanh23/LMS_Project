


$(document).ready(function () {
    getCart();
});



$(document).on('click', '.add-to-cart-btn', function () {
    var courseId = $(this).data('course-id'); // lấy course id từ button
    var quantity = 1; // quantity mặc định là 1

    //Gửi yêu cầu ngầm (AJAX) lên Server
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            course_id: courseId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')// Bắt buộc để tránh lỗi bảo mật CSRF
        },
        success: function (response) {
            if (response.status === 'success') {
                //Nếu Server báo thành công: 
                getCart(); // Cập nhật lại giao diện cái Mini Cart
                // thông báo thành công
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: false
                });

                // câp nhật số lượng trên icon giỏ hàng
                if (response.cart_item) {
                    $('.cart-count').text(response.cart_item.quantity);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: response.message,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: false
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi xảy ra!',
                text: xhr.responseJSON?.message || error,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                backdrop: false
            });
        }
    });
});

//getwishlist

function getCart() {

    var url = '/cart/all';

    $.ajax({
        url: url,
        type: 'GET',
        data: {

            _token: $('meta[name="csrf-token"]').attr('content')


        },
        success: function (response) {

            if (response.status === 'success') {

                $('#cart').html(response.html);
                if (response.cart_count !== undefined) {
                    $('#cart-count').text(response.cart_count);
                }
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




//fetch cart

$(document).ready(function () {

    // gọi tới hàm để load cart
    fetchCart();

    function fetchCart() {

        var url = '/fetch/cart';

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')

            },
            success: function (response) {

                if (response.status === 'success') {
                    // cập nhật #cart-main-content
                    $('#cart-main-content').html(response.html);
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

    $(document).on('click', '.remove-course-btn', function () {

        var id = $(this).data('id');
        var url = '/remove/cart';

        $.ajax({

            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id
            },

            success: function (response) {
                if (response.status === 'success') {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Xóa sản phẩm khỏi giỏ hàng thành công!',
                        showConfirmButton: false,
                        timer: 3000,
                        backdrop: false
                    });
                    fetchCart(); // Refresh cart

                    getCart();  //Refresh getCart

                }
            },
            error: function (xhr) {
                let message = 'Có lỗi xảy ra!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    backdrop: false
                });
                console.error(message);
            }


        })

    })






});







