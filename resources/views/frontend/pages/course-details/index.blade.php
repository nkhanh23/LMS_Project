@extends('frontend.master')
@section('content')
    <main class="mx-auto w-full max-w-7xl px-6 py-8">
        <!-- 3) Breadcrumb / Hero course info -->
        @include('frontend.pages.course-details.breadcrumb')
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <!-- 4) Main content area – cột trái -->
            <div class="lg:col-span-8 flex flex-col gap-8 flex-1 course-details-content-wrap">
                <!-- Hero Title -->
                @include('frontend.pages.course-details.hero-title')

                <!-- 4.1. What you'll learn -->
                @include('frontend.pages.course-details.learn-section')




                <!-- 4.6. Course content / curriculum -->
                @include('frontend.pages.course-details.course-content')


                <!-- 4.7. Students also bought -->
                @include('frontend.pages.course-details.similar-course')


                <!-- 4.8. About the instructor -->
                @include('frontend.pages.course-details.instructor-about')


                <!-- 4.9. Student feedback -->
                @include('frontend.pages.course-details.student-feedback')


                {{-- 4.10 den 4.11 --}}
                <!-- 4.10. Reviews -->
                @include('frontend.pages.course-details.reviews')

            </div>

            <!-- 5) Right sidebar – cột phải -->
            @include('frontend.pages.course-details.right-sidebar')

        </div>

        <!-- 6) Related course area riêng ở dưới -->
        {{-- Modal --}}
        @include('frontend.pages.course-details.related-courses')


        <!-- 7) CTA area -->
        @include('frontend.pages.course-details.become-teacher')
        <section
            class="mt-16 bg-gradient-to-r from-brand/20 via-cyber-cyan/10 to-brand/20 border-y-4 border-black p-12 text-center overflow-hidden relative">
            <div class="absolute -top-10 -left-10 w-32 h-32 border-4 border-brand/20 rounded-full animate-spin"
                style="animation-duration:20s"></div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 border-4 border-cyber-cyan/15 rounded-full animate-spin"
                style="animation-duration:30s"></div>
            <h2 class="font-pixel text-xl lg:text-3xl text-brand mb-6 relative z-10 pixel-text">Become an Instructor</h2>
            <p class="text-slate-300 max-w-2xl mx-auto mb-8 relative z-10">Top instructors from around the world teach
                millions of students on StackLearn. We provide the tools and skills to teach what you love.</p>
            <button
                class="relative z-10 bg-brand text-black font-bold py-4 px-10 text-lg uppercase border-4 border-black pixel-shadow pixel-button-hover">Start
                Teaching Today <i class="fas fa-arrow-right ml-2"></i></button>
        </section>
    </main>

    <!-- 10) Modal components -->
    <!-- Share Modal -->
    <div class="modal fade hidden" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-lg mx-auto">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-cyber-surface outline-none focus:outline-none border-4 border-black pixel-shadow p-6">
                <!-- Modal header -->
                <div class="flex items-start justify-between border-b border-slate-700 pb-4 mb-4">
                    <h3 class="font-bold uppercase tracking-tighter text-brand text-xl">Share this course</h3>
                    <button class="bg-transparent text-slate-400 hover:text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative p-flex-auto text-sm text-slate-300">
                    <div class="flex items-center border border-slate-600 bg-black p-2 gap-2 mb-6">
                        <input type="text" class="w-full bg-transparent border-none text-slate-300 outline-none"
                            value="https://stacklearn.com/course/java-masterclass" readonly>
                        <button
                            class="bg-brand text-black font-bold px-4 py-2 text-xs uppercase hover:bg-white pixel-border">Copy</button>
                    </div>
                    <div class="flex justify-center gap-4 text-2xl">
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-cyan-500 text-white flex items-center justify-center hover:bg-cyan-600"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-blue-800 text-white flex items-center justify-center hover:bg-blue-900"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    @include('frontend.pages.course-details.preview-modal')

    <!-- Report Abuse Modal -->
    <div class="modal fade hidden" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-lg mx-auto">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-cyber-surface outline-none focus:outline-none border-4 border-black pixel-shadow p-6">
                <!-- Modal header -->
                <div class="flex items-start justify-between border-b border-slate-700 pb-4 mb-4">
                    <h3 class="font-bold uppercase tracking-tighter text-red-500 text-xl">Report Abuse</h3>
                    <button class="bg-transparent text-slate-400 hover:text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative text-sm text-slate-300">
                    <p class="mb-4 text-xs">Flagged content is reviewed by StackLearn staff to determine whether it
                        violates <a href="#" class="text-cyber-cyan underline">Terms of Service</a> or <a
                            href="#" class="text-cyber-cyan underline">Community Guidelines</a>.</p>
                    <form class="space-y-4">
                        <div>
                            <label class="block mb-2 font-bold text-slate-400 text-xs">Issue Type</label>
                            <select
                                class="w-full bg-black border border-slate-700 p-2 text-slate-300 outline-none focus:border-red-500">
                                <option>Inappropriate Course Content</option>
                                <option>Misleading Info</option>
                                <option>Spam or scam</option>
                                <option>Copyright violation</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-slate-400 text-xs">Issue Details</label>
                            <textarea rows="4"
                                class="w-full bg-black border border-slate-700 p-2 text-slate-300 outline-none focus:border-red-500"
                                placeholder="Please provide specific details..."></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="text-slate-400 mr-4 font-bold text-xs uppercase hover:text-white"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button"
                                class="bg-red-500 text-white font-bold px-6 py-2 text-xs uppercase hover:bg-red-600 pixel-border">Submit
                                Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
