<div id="tab-withdraw" class="tab-panel bg-cyber-surface border-4 border-black pixel-shadow p-10 z-[3] relative hidden">
    <div class="max-w-3xl space-y-10">
        <div>
            <h3 class="text-2xl font-bold text-white uppercase italic">Withdraw Funds</h3>
            <p class="text-text-secondary uppercase text-xs tracking-widest mt-1 pixel-text">
                <i class="fas fa-wallet mr-1"></i> Manage your earnings and payouts
            </p>
        </div>

        <!-- Balance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-cyber-dark border-2 border-black p-6 text-center pixel-button-hover">
                <p class="text-[10px] text-text-secondary pixel-text font-bold mb-2">AVAILABLE BALANCE</p>
                <p class="text-3xl font-black text-brand font-pixel">$2,450</p>
            </div>
            <div class="bg-cyber-dark border-2 border-black p-6 text-center pixel-button-hover">
                <p class="text-[10px] text-text-secondary pixel-text font-bold mb-2">PENDING PAYOUT</p>
                <p class="text-3xl font-black text-yellow-400 font-pixel">$350</p>
            </div>
            <div class="bg-cyber-dark border-2 border-black p-6 text-center pixel-button-hover">
                <p class="text-[10px] text-text-secondary pixel-text font-bold mb-2">TOTAL WITHDRAWN</p>
                <p class="text-3xl font-black text-cyber-cyan font-pixel">$8,200</p>
            </div>
        </div>

        <!-- Withdraw Form -->
        <form action="#" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Withdraw Amount -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-money-bill-wave mr-1"></i> Withdraw Amount ($):
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono text-2xl focus:ring-0 focus:border-brand transition-colors"
                        type="number" name="amount" min="10" step="0.01" placeholder="0.00" />
                    <p class="text-[10px] text-text-secondary pixel-text">
                        <i class="fas fa-info-circle mr-1"></i> MINIMUM WITHDRAWAL: $10.00
                    </p>
                </div>

                <!-- Payment Method -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-credit-card mr-1"></i> Payment Method:
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label
                            class="bg-black border-4 border-slate-600 p-4 flex items-center gap-3 cursor-pointer hover:border-cyber-cyan transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/10">
                            <input type="radio" name="payment_method" value="paypal"
                                class="text-brand focus:ring-brand" />
                            <i class="fab fa-paypal text-cyber-cyan text-xl"></i>
                            <span class="text-sm font-bold pixel-text">PayPal</span>
                        </label>
                        <label
                            class="bg-black border-4 border-slate-600 p-4 flex items-center gap-3 cursor-pointer hover:border-cyber-cyan transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/10">
                            <input type="radio" name="payment_method" value="bank"
                                class="text-brand focus:ring-brand" />
                            <i class="fas fa-university text-cyber-cyan text-xl"></i>
                            <span class="text-sm font-bold pixel-text">Bank</span>
                        </label>
                        <label
                            class="bg-black border-4 border-slate-600 p-4 flex items-center gap-3 cursor-pointer hover:border-cyber-cyan transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/10">
                            <input type="radio" name="payment_method" value="crypto"
                                class="text-brand focus:ring-brand" />
                            <i class="fab fa-bitcoin text-yellow-400 text-xl"></i>
                            <span class="text-sm font-bold pixel-text">Crypto</span>
                        </label>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-id-card mr-1"></i> Account Details:
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="text" name="account_details" placeholder="Enter PayPal email or bank account..." />
                </div>
            </div>

            <div class="pt-8">
                <button type="submit"
                    class="w-full bg-brand border-4 border-black py-4 text-black font-black text-2xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover">
                    <i class="fas fa-paper-plane mr-2"></i> Request Withdrawal
                </button>
            </div>
        </form>

        <!-- Recent Transactions -->
        <div>
            <h4 class="text-brand font-bold pixel-text text-sm mb-4">
                <i class="fas fa-history mr-1"></i> RECENT TRANSACTIONS
            </h4>
            <div class="bg-black/50 border-2 border-black overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-black text-text-secondary text-[10px] pixel-text">
                            <th class="text-left p-3">DATE</th>
                            <th class="text-left p-3">METHOD</th>
                            <th class="text-left p-3">AMOUNT</th>
                            <th class="text-left p-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono">
                        <tr class="border-b border-black/30 hover:bg-white/5 transition-colors">
                            <td class="p-3 text-text-secondary">2026-02-28</td>
                            <td class="p-3"><i class="fab fa-paypal text-cyber-cyan mr-1"></i>PayPal</td>
                            <td class="p-3 text-brand font-bold">$500.00</td>
                            <td class="p-3">
                                <span
                                    class="bg-brand/20 text-brand text-[10px] px-2 py-0.5 border border-brand font-bold">COMPLETED</span>
                            </td>
                        </tr>
                        <tr class="border-b border-black/30 hover:bg-white/5 transition-colors">
                            <td class="p-3 text-text-secondary">2026-02-15</td>
                            <td class="p-3"><i class="fas fa-university text-cyber-cyan mr-1"></i>Bank</td>
                            <td class="p-3 text-brand font-bold">$1,200.00</td>
                            <td class="p-3">
                                <span
                                    class="bg-brand/20 text-brand text-[10px] px-2 py-0.5 border border-brand font-bold">COMPLETED</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-3 text-text-secondary">2026-03-01</td>
                            <td class="p-3"><i class="fab fa-bitcoin text-yellow-400 mr-1"></i>Crypto</td>
                            <td class="p-3 text-yellow-400 font-bold">$350.00</td>
                            <td class="p-3">
                                <span
                                    class="bg-yellow-400/20 text-yellow-400 text-[10px] px-2 py-0.5 border border-yellow-400 font-bold">PENDING</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
