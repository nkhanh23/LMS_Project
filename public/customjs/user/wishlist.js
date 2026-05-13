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

                }

                if (response.wishlist.data.length === 0) {
                    wishlistContainer.html(`
                        <div class="col-span-full py-16 px-4 text-center border-2 border-black border-dashed bg-cyber-surface/50">
                            <div class="w-16 h-16 bg-cyber-dark border-2 border-black flex items-center justify-center mx-auto mb-4 pixel-shadow-sm">
                                <i class="far fa-heart text-2xl text-text-secondary opacity-30"></i>
                            </div>
                            <h3 class="text-lg font-bold font-pixel text-brand mb-2">DANH_SACH_TRONG</h3>
                            <p class="text-[10px] text-text-secondary mb-6 max-w-md mx-auto uppercase">Bạn chưa có khóa học nào trong danh sách yêu thích.</p>
                            <a href="/khoa-hoc" class="inline-block px-6 py-3 bg-brand text-black font-bold uppercase transition hover:-translate-y-1 border-2 border-black pixel-shadow text-xs">
                                <i class="fas fa-search mr-2"></i> Khám phá ngay
                            </a>
                        </div>
                    `);
                } else {
                    wishlistContainer.html(`
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="course-grid"></div>
                    `);

                    response.wishlist.data.forEach(item => {
                        let courseName = item.course.course_name;
                        
                        let html = `
                        <div class="course-item group">
                            <article class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-all p-3 flex flex-col h-full">
                                <div class="relative aspect-video bg-cyber-dark border border-black mb-3 overflow-hidden">
                                    <a href="/chi-tiet/${item.course.course_name_slug}" class="block w-full h-full">
                                        <img loading="lazy" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all duration-500 group-hover:scale-105" src="${item.course.course_image}" alt="${courseName}">
                                    </a>
                                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                                        <span class="bg-yellow-400 text-black text-[8px] font-bold px-1.5 py-0.5 border border-black uppercase">
                                            ${getBadge(item.course)}
                                        </span>
                                        ${item.course.discount_price ? `
                                        <span class="bg-brand text-black text-[8px] font-bold px-1.5 py-0.5 border border-black uppercase">
                                            -${calculateDiscount(item.course.selling_price, item.course.discount_price)}%
                                        </span>` : ''}
                                    </div>
                                    <button class="absolute top-2 right-2 w-8 h-8 bg-black/80 border border-black text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors delete-wishlist-item" data-id="${item.id}">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                                
                                <div class="flex-1 flex flex-col">
                                    <div class="mb-3">
                                        <h3 class="font-bold text-sm text-white line-clamp-2 leading-tight group-hover:text-brand transition-colors mb-1">
                                            <a href="/chi-tiet/${item.course.course_name_slug}">
                                                ${courseName}
                                            </a>
                                        </h3>
                                        <p class="text-[10px] text-text-secondary uppercase font-mono">
                                            By <span class="text-cyber-cyan font-bold">${item.course.user ? item.course.user.name : 'Instructor'}</span>
                                        </p>
                                    </div>

                                    <div class="mt-auto pt-3 border-t border-black/10 flex items-center justify-between">
                                        <div class="flex flex-col">
                                            ${getPriceHtml(item.course)}
                                        </div>
                                        <a href="/chi-tiet/${item.course.course_name_slug}" class="w-8 h-8 bg-brand border border-black text-black flex items-center justify-center pixel-shadow-sm hover:brightness-110">
                                            <i class="fas fa-play text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>`;
                        wishlistContainer.find('#course-grid').append(html);
                    });

                    // Pagination
                    if (response.wishlist.links && response.wishlist.links.length > 3) {
                        paginationBox.addClass('flex gap-2 justify-center mt-10 border-t-2 border-black/20 pt-8');
                        response.wishlist.links.forEach(link => {
                            if (!link.url) return;
                            const activeClass = link.active ? 'bg-brand text-black border-black' : 'bg-cyber-surface text-text-secondary border-black/30 hover:border-brand hover:text-brand';
                            let label = link.label;
                            if (label.includes('Previous')) label = '<i class="fas fa-chevron-left text-[10px]"></i>';
                            if (label.includes('Next')) label = '<i class="fas fa-chevron-right text-[10px]"></i>';
                            const urlParams = new URL(link.url);
                            const pageNumber = urlParams.searchParams.get('page');

                            paginationBox.append(`
                                <a class="page-link w-8 h-8 flex items-center justify-center border-2 font-bold transition-all pixel-shadow-sm ${activeClass}" href="#" data-page="${pageNumber}">
                                    ${label}
                                </a>
                            `);
                        });
                    }

                    // Results Info
                    resultsInfo.html(
                        `<span class="text-[10px] text-text-secondary pixel-text uppercase">Showing ${response.wishlist.from}-${response.wishlist.to} of ${response.wishlist.total} modules</span>`
                    );ishlist.total} results`
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