        <div class="relative group/cart">
            <button
                class="relative w-12 h-12 bg-cyber-surface border-2 border-black pixel-shadow-sm pixel-button-hover flex items-center justify-center">
                <i class="fas fa-shopping-cart"></i>
                <span
                    class="absolute -top-1 -right-1 bg-red-600 text-white text-[9px] font-bold w-5 h-5 flex items-center justify-center border border-black">2</span>
            </button>
            <!-- Cart Dropdown -->
            <div class="absolute top-full right-0 pt-2 w-80 hidden group-hover/cart:block z-50">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                    <div class="px-4 py-3 border-b-2 border-black">
                        <h4 class="font-bold text-sm pixel-text text-brand">My Cart</h4>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        <div
                            class="flex items-center gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                            <div
                                class="w-14 h-10 bg-cyber-dark border border-black flex items-center justify-center shrink-0">
                                <i class="fab fa-laravel text-red-500/50"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate">Laravel Masterclass</p>
                                <p class="text-brand text-sm font-bold">$49.99</p>
                            </div>
                            <button class="text-red-500 hover:text-red-400"><i class="fas fa-times"></i></button>
                        </div>
                        <div
                            class="flex items-center gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                            <div
                                class="w-14 h-10 bg-cyber-dark border border-black flex items-center justify-center shrink-0">
                                <i class="fab fa-react text-cyber-cyan/50"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate">Design Patterns</p>
                                <p class="text-brand text-sm font-bold">$54.99</p>
                            </div>
                            <button class="text-red-500 hover:text-red-400"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t-2 border-black flex justify-between items-center">
                        <span class="font-bold text-sm">Total: <span class="text-brand">$104.98</span></span>
                        <a href="#"
                            class="bg-brand text-black px-4 py-2 text-xs font-bold uppercase border-2 border-black pixel-shadow-sm pixel-button-hover">Checkout</a>
                    </div>
                </div>
            </div>

        </div>
