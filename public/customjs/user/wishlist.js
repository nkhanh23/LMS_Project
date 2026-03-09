//User show wishlist and delete process

$(document).ready(function () {

    // Initial load
    loadWishlist();

    const wishlistContainer = $('#wishlist-container');
    const paginationBox = $('#pagination-box');
    const resultsInfo = $('.results-info');

    // Function to load wishlist items
    function loadWishlist(page = 1) {
        $.ajax({
            url: `/user/wishlist-data?page=${page}`,
            type: 'GET',
            success: function (response) {
                wishlistContainer.empty(); // Clear existing items
                paginationBox.empty(); // Clear pagination
                resultsInfo.empty(); // Clear results info

                console.log(response.wishlist);

                if (response.status === 'success') {
                    // #wishlist-course 
                    $('#wishlist-course').html(response.html);
                }

                if (response.wishlist.data.length === 0) {
                    wishlistContainer.html(`
                        <div class="col-span-full py-16 px-4 text-center border-2 border-black border-dashed bg-cyber-surface/50">
                            <div class="w-16 h-16 bg-cyber-dark border-2 border-black rounded-full flex items-center justify-center mx-auto mb-4 pixel-shadow-sm">
                                <i class="far fa-heart text-2xl text-text-secondary"></i>
                            </div>
                            <h3 class="text-xl font-bold font-pixel text-brand mb-2">Danh sách trống</h3>
                            <p class="text-text-secondary mb-6 max-w-md mx-auto">Bạn chưa có khóa học nào trong danh sách yêu thích. Hãy khám phá các khóa học hấp dẫn của chúng tôi!</p>
                            <a href="/" class="inline-block px-6 py-3 bg-brand text-black font-bold uppercase transition hover:-translate-y-1 block border-2 border-black pixel-shadow hover:pixel-shadow-lg">
                                <i class="fas fa-search mr-2"></i> Khám phá ngay
                            </a>
                        </div>
                    `);
                } else {
                    wishlistContainer.html(`
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="course-grid"></div>
                    `);

                    response.wishlist.data.forEach(item => {
                        let courseName = item.course.course_name;
                        if (courseName.length > 50) {
                            courseName = courseName.substring(0, 50) + '...';
                        }

                        let html = `
                        <div class="course-item course-card-wrap relative group">
                            <article class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform cursor-pointer h-full flex flex-col">
                                <div class="h-44 bg-cyber-dark border-b-2 border-black relative overflow-hidden">
                                    <a href="/course-details/${item.course.course_name_slug}" class="block w-full h-full">
                                        <img loading="lazy" class="w-full h-full object-cover" src="${item.course.course_image}" alt="${courseName}">
                                    </a>
                                    <span class="absolute top-2 left-2 bg-yellow-400 text-black text-[9px] font-bold px-2 py-0.5 border border-black">
                                        ${getBadge(item.course)}
                                    </span>
                                    <span class="absolute top-2 left-24 bg-brand text-black text-[9px] font-bold px-2 py-0.5 border border-black">
                                        -${calculateDiscount(item.course.selling_price, item.course.discount_price)}%
                                    </span>
                                </div>
                                <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                                    <div class="space-y-3">
                                        <h5 class="text-sm text-text-secondary">
                                            <i class="fas fa-user mr-1 text-cyber-cyan"></i>
                                            ${item.course.label || (item.course.user ? item.course.user.name : '')}
                                        </h5>
                                        <h3 class="font-bold text-lg leading-snug min-h-[56px]">
                                            <a href="/course-details/${item.course.course_name_slug}">
                                                ${courseName}
                                            </a>
                                        </h3>
                                        <a href="/instructor/${item.course.user ? item.course.user.name : ''}/${item.course.user ? item.course.user.id : ''}" class="text-sm text-text-secondary hover:text-brand mt-1 block">
                                            <i class="fas fa-chalkboard-teacher mr-1 text-cyber-cyan"></i>
                                            ${item.course.user ? item.course.user.name : ''}
                                        </a>
                                        <div class="text-yellow-400 text-sm">
                                            ★★★★☆ <span class="text-text-secondary">(4.4)</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            ${getPriceHtml(item.course)}
                                        </div>
                                        <button class="text-lg hover:scale-110 transition-transform wishlist-icon p-2 delete-wishlist-item" title="Xóa khỏi danh sách yêu thích" data-id="${item.id}">
                                            <i class="fas fa-heart text-red-600"></i>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </div>`;
                        wishlistContainer.find('#course-grid').append(html);
                    });

                    // Pagination
                    if (response.wishlist.links && response.wishlist.links.length > 3) {
                        paginationBox.addClass('flex gap-2 justify-center mt-8');
                        response.wishlist.links.forEach(link => {
                            if (!link.url) return;
                            const activeClass = link.active ? 'bg-brand text-black pixel-shadow-sm' : 'bg-cyber-surface text-text-primary hover:bg-brand hover:text-black hover:pixel-shadow-sm';
                            let label = link.label;
                            if (label.includes('Previous')) label = '&laquo;';
                            if (label.includes('Next')) label = '&raquo;';
                            const urlParams = new URL(link.url);
                            const pageNumber = urlParams.searchParams.get('page');

                            paginationBox.append(`
                        <a class="page-link px-3 py-1.5 border-2 border-black font-bold transition-all ${activeClass}" href="#" data-page="${pageNumber}">
                            ${label}
                        </a>
                        `);
                        });
                    }

                    // Results Info
                    resultsInfo.html(
                        `Showing ${response.wishlist.from} - ${response.wishlist.to} of ${response.wishlist.total} results`
                    );
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load wishlist items. Try again.',
                });
            }
        });
    }

    // Event listener for pagination
    paginationBox.on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadWishlist(page);
    });



    // Hàm tính phần trăm giảm giá
    function calculateDiscount(sellingPrice, discountPrice) {
        return ((sellingPrice - discountPrice) / sellingPrice * 100).toFixed(0);
    }

    // Hàm lấy nhãn (Bestseller/Featured/HighestRated)
    function getBadge(course) {
        if (course.bestseller === 'yes') return 'Bestseller';
        if (course.featured === 'yes') return 'Featured';
        if (course.highestrated === 'yes') return 'HighestRated';
        return 'New';
    }

    // Hàm lấy HTML giá
    function getPriceHtml(course) {
        if (course.discount_price) {
            return `
                        <span class="text-brand font-bold text-xl">
                            VNĐ ${course.discount_price}
                        </span>
                        <span class="text-sm text-text-secondary line-through">
                            VNĐ ${course.selling_price}
                        </span>`;
        }
        return `
                        <span class="text-brand font-bold text-xl">
                            VNĐ ${course.selling_price}
                        </span>`;
    }



    // Xóa wishlist item
    wishlistContainer.on('click', '.delete-wishlist-item', function () {
        let wishlistId = $(this).data('id');
        let url = `/user/wishlist/${wishlistId}`;

        // SweetAlert confirmation dialog
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            text: "Bạn sẽ không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Chắc chắn xóa!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tiến hành AJAX request
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        // _token: '{{ csrf_token() }}' // CSRF token
                        _token: $('meta[name="csrf-token"]').attr('content')

                    },
                    success: function (response) {
                        console.log(response)
                        if (response.status === 'success') {



                            // Toast success alert
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });

                            loadWishlist(); // Reload wishlist after deletion
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.message,
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể xóa mục này. Vui lòng thử lại.',
                        });
                    }
                });
            }
        });
    });




    // Initial load
    loadWishlist();


});


//End