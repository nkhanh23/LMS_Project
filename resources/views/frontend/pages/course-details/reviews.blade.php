<section class="py-6 space-y-6">
    <h3 class="text-xl font-bold uppercase tracking-tighter text-brand">Reviews</h3>
    <div class="space-y-4">
        <div class="border border-slate-700 bg-cyber-surface p-4 flex gap-4">
            <div
                class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center font-bold text-white shrink-0">
                JD</div>
            <div>
                <h4 class="font-bold text-sm text-slate-100">John Doe</h4>
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <div class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <span>2 months ago</span>
                </div>
                <p class="text-sm text-slate-300">Excellent course, very comprehensive.</p>
                <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                    <span>Helpful?</span>
                    <button class="hover:text-white"><i class="far fa-thumbs-up"></i></button>
                    <button class="hover:text-white"><i class="far fa-thumbs-down"></i></button>
                    <button class="hover:text-white ml-2 underline">Report</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4.11. Add a Review -->
<section class="py-6 border-t border-slate-700">
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Add a Review</h3>
    <form class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Your Rating</label>
            <div class="text-yellow-400 text-lg cursor-pointer star-rating-input">
                <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i
                    class="far fa-star"></i><i class="far fa-star"></i>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" placeholder="Name"
                class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand">
            <input type="email" placeholder="Email"
                class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand">
        </div>
        <textarea placeholder="Message" rows="4"
            class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand"></textarea>
        <label class="flex items-center gap-2 text-sm text-slate-400">
            <input type="checkbox" class="bg-black border-slate-600 text-brand focus:ring-brand">
            Save my info for next time
        </label>
        <button type="button"
            class="bg-brand text-black font-bold py-3 px-6 uppercase tracking-widest text-sm hover:bg-white transition-colors pixel-border pixel-button-hover mt-4">
            Submit Review
        </button>
    </form>
</section>
