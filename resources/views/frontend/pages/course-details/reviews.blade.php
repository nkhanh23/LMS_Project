<section class="py-6 space-y-6">
    <h3 class="text-xl font-bold uppercase tracking-tighter text-brand">
        Đánh giá ({{ $ratingCount ?? 0 }})
    </h3>

    <div id="reviewList" class="space-y-4">
        @forelse ($reviews as $review)
            @include('frontend.pages.course-details.partials.review-item', ['review' => $review])
        @empty
            <div class="text-slate-400">Chưa có đánh giá nào cho khóa học này.</div>
        @endforelse
    </div>

    {{ $reviews->links() }}
</section>

<!-- 4.11. Add a Review -->
<section class="py-10 border-t border-slate-700/50">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-2 h-8 bg-brand"></div>
        <h3 class="text-2xl font-bold uppercase tracking-tighter text-slate-100 font-display">
            Gửi đánh giá của bạn
        </h3>
    </div>

    @auth
        @if ($hasPurchased)
            <div class="bg-cyber-surface border-2 border-slate-700 p-6 md:p-8 pixel-shadow relative overflow-hidden group">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-brand/5 rotate-45 translate-x-8 -translate-y-8"></div>

                <form id="reviewForm" action="{{ route('course-review.store', $course->course_name_slug) }}" method="POST"
                    class="relative z-10 space-y-6">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    @if ($userReview)
                        <div class="text-yellow-400 font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Bạn đã đánh giá khóa học này rồi.
                        </div>
                    @else
                        <!-- Star Rating -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold uppercase tracking-wider text-slate-400">
                                Xếp hạng khóa học *
                            </label>

                            <div class="flex flex-row-reverse justify-end items-center gap-2">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating"
                                        value="{{ $i }}" class="hidden peer" required>
                                    <label for="star{{ $i }}"
                                        class="text-3xl cursor-pointer text-slate-600 hover:text-yellow-400 peer-checked:text-yellow-400 transition-colors duration-150">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>

                            <p id="ratingError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Comment -->
                        <div class="space-y-3">
                            <label for="comment" class="block text-sm font-bold uppercase tracking-wider text-slate-400">
                                Nội dung đánh giá *
                            </label>
                            <textarea id="comment" name="comment" rows="5" required
                                placeholder="Chia sẻ cảm nghĩ của bạn về khóa học này..."
                                class="w-full bg-slate-900/50 border-2 border-slate-700 text-slate-100 p-4 focus:border-brand focus:ring-0 transition-colors placeholder:text-slate-600 resize-none font-sans"></textarea>

                            <p id="commentError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" id="reviewSubmitBtn"
                                class="group relative px-8 py-3 bg-brand text-black font-bold uppercase tracking-widest text-sm pixel-button-hover overflow-hidden">
                                <span class="relative z-10">Gửi đánh giá</span>
                                <div
                                    class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                                </div>
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @else
            <div class="bg-slate-800/30 border border-slate-700 p-6 flex items-center gap-4">
                <i class="fas fa-lock text-slate-500 text-xl"></i>
                <p class="text-slate-400">Bạn cần mua khóa học này để có thể để lại đánh giá.</p>
            </div>
        @endif
    @else
        <div class="bg-brand/10 border border-brand/30 p-6 flex flex-col items-center text-center space-y-4">
            <p class="text-slate-300">Vui lòng đăng nhập để chia sẻ trải nghiệm của bạn với cộng đồng.</p>
            <a href="{{ route('login') }}"
                class="px-6 py-2 border-2 border-brand text-brand hover:bg-brand hover:text-black transition-all font-bold uppercase text-xs tracking-widest">
                Đăng nhập ngay
            </a>
        </div>
    @endauth
</section>

<style>
    /* Display Font if available */
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }

    /* Star Rating Hover Effect */
    .flex-row-reverse i:hover~i,
    .flex-row-reverse i:hover {
        color: #fbbf24 !important;
        /* text-yellow-400 */
    }

    /* Peer checked logic for stars */
    input:checked~label {
        color: #fbbf24 !important;
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                const submitBtn = document.getElementById('reviewSubmitBtn');
                const ratingError = document.getElementById('ratingError');
                const commentError = document.getElementById('commentError');

                reviewForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (ratingError) {
                    ratingError.classList.add('hidden');
                    ratingError.textContent = '';
                }

                if (commentError) {
                    commentError.classList.add('hidden');
                    commentError.textContent = '';
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.querySelector('span').textContent = 'Đang gửi...';
                }

                try {
                    const formData = new FormData(reviewForm);

                    const response = await fetch(reviewForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (data.errors) {
                            if (data.errors.rating && ratingError) {
                                ratingError.textContent = data.errors.rating[0];
                                ratingError.classList.remove('hidden');
                            }

                            if (data.errors.comment && commentError) {
                                commentError.textContent = data.errors.comment[0];
                                commentError.classList.remove('hidden');
                            }
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message || 'Có lỗi xảy ra',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#2A2A3C',
                            color: '#F8F8F2',
                            iconColor: '#ff4d4f'
                        });

                        return;
                    }

                    const reviewList = document.getElementById('reviewList');
                    const noReviewText = document.getElementById('noReviewText');
                    const studentFeedbackWrapper = document.getElementById('studentFeedbackWrapper');

                    if (noReviewText) {
                        noReviewText.remove();
                    }

                    if (reviewList && data.review_html) {
                        reviewList.insertAdjacentHTML('afterbegin', data.review_html);
                    }

                    if (studentFeedbackWrapper && data.student_feedback_html) {
                        studentFeedbackWrapper.innerHTML = data.student_feedback_html;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Đánh giá của bạn đã được gửi thành công',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        background: '#2A2A3C',
                        color: '#F8F8F2',
                        iconColor: '#A6E22E'
                    });

                    // Ẩn form sau khi review thành công
                    reviewForm.innerHTML = `
                <div class="text-yellow-400">
                    Bạn đã đánh giá khóa học này rồi.
                </div>
            `;

                } catch (error) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Không thể gửi đánh giá lúc này',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#2A2A3C',
                        color: '#F8F8F2',
                        iconColor: '#ff4d4f'
                    });
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (submitBtn.querySelector('span')) {
                            submitBtn.querySelector('span').textContent = 'Gửi đánh giá';
                        }
                    }
                    }
                });
            }

            // Report Review Form Handling
            document.addEventListener('submit', async function(e) {
                const reportForm = e.target.closest('.report-review-form');
                if (!reportForm) return;

                e.preventDefault();

                const submitBtn = reportForm.querySelector('button[type="submit"]');
                const reviewId = reportForm.dataset.reviewId;
                const url = reportForm.getAttribute('action') || `/reports/reviews/${reviewId}`;
                const formData = new FormData(reportForm);

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Đang gửi...';
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok && data.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || 'Báo cáo của bạn đã được gửi',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#2A2A3C',
                            color: '#F8F8F2',
                            iconColor: '#A6E22E'
                        });
                        reportForm.reset();
                        reportForm.classList.add('hidden');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message || 'Gửi báo cáo thất bại',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#2A2A3C',
                            color: '#F8F8F2',
                            iconColor: '#ff4d4f'
                        });
                    }
                } catch (error) {
                    console.error('Report submission error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Có lỗi xảy ra',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#2A2A3C',
                        color: '#F8F8F2',
                        iconColor: '#ff4d4f'
                    });
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Gửi report';
                    }
                }
            });
        });
    </script>
@endpush
