<div id="tab-account" class="tab-panel bg-cyber-surface border-4 border-black pixel-shadow p-10 z-[3] relative hidden">
    <div class="max-w-2xl space-y-10">
        <div>
            <h3 class="text-2xl font-bold text-white uppercase italic">Account Management</h3>
            <p class="text-text-secondary uppercase text-xs tracking-widest mt-1 pixel-text">
                <i class="fas fa-user-cog mr-1"></i> Control your account status
            </p>
        </div>

        <!-- ===== DEACTIVATE ACCOUNT ===== -->
        <div class="bg-yellow-400/5 border-2 border-yellow-400/50 p-8 space-y-6">
            <div class="flex items-start gap-4">
                <div
                    class="w-14 h-14 bg-yellow-400/20 border-2 border-yellow-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-pause-circle text-yellow-400 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-yellow-400 uppercase pixel-text">Deactivate Account</h4>
                    <p class="text-text-secondary text-sm mt-2 leading-relaxed">
                        Temporarily disable your account. Your profile, courses, and data will be hidden
                        from other users. You can reactivate your account at any time by logging back in.
                    </p>
                </div>
            </div>

            <div class="bg-black/30 border border-yellow-400/30 p-4 flex items-start gap-3">
                <i class="fas fa-info-circle text-yellow-400 mt-0.5"></i>
                <div class="text-xs text-text-secondary space-y-1">
                    <p><strong class="text-yellow-400">What happens when you deactivate:</strong></p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Your profile will be hidden from search results</li>
                        <li>Students will not be able to see your courses</li>
                        <li>All active subscriptions will be paused</li>
                        <li>Your data will be preserved for reactivation</li>
                    </ul>
                </div>
            </div>

            <form action="#" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-yellow-400 uppercase font-bold text-sm tracking-widest pixel-text">
                            <i class="fas fa-lock mr-1"></i> Confirm Password:
                        </label>
                        <input
                            class="w-full bg-black border-4 border-yellow-400/50 p-4 text-yellow-400 font-mono focus:ring-0 focus:border-yellow-400 transition-colors"
                            type="password" name="password" placeholder="Enter your password to confirm..." />
                    </div>
                    <button type="submit"
                        class="w-full bg-yellow-400 border-4 border-black py-4 text-black font-black text-xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover">
                        <i class="fas fa-pause-circle mr-2"></i> Deactivate My Account
                    </button>
                </div>
            </form>
        </div>

        <!-- ===== DELETE ACCOUNT PERMANENTLY ===== -->
        <div class="bg-red-500/5 border-2 border-red-500/50 p-8 space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-red-500/20 border-2 border-red-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-skull-crossbones text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-red-500 uppercase pixel-text">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Delete Account Permanently
                    </h4>
                    <p class="text-text-secondary text-sm mt-2 leading-relaxed">
                        Once you delete your account, there is no going back. All of your data,
                        courses, earnings, and progress will be permanently erased.
                    </p>
                </div>
            </div>

            <div class="bg-red-500/10 border border-red-500/30 p-4 flex items-start gap-3">
                <i class="fas fa-radiation text-red-500 mt-0.5"></i>
                <div class="text-xs text-text-secondary space-y-1">
                    <p><strong class="text-red-400">DANGER ZONE — This action will:</strong></p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Permanently delete your profile and personal data</li>
                        <li>Remove all courses you have created</li>
                        <li>Delete all certificates and achievements</li>
                        <li>Forfeit any remaining balance in your account</li>
                        <li>Cancel all active subscriptions immediately</li>
                    </ul>
                </div>
            </div>

            <form action="#" method="POST">
                @csrf
                @method('DELETE')
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-red-400 uppercase font-bold text-sm tracking-widest pixel-text">
                            <i class="fas fa-lock mr-1"></i> Confirm Password:
                        </label>
                        <input
                            class="w-full bg-black border-4 border-red-500/50 p-4 text-red-400 font-mono focus:ring-0 focus:border-red-500 transition-colors"
                            type="password" name="password" placeholder="Enter your password to confirm..." />
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" id="confirm-delete"
                            class="text-red-500 focus:ring-red-500 border-2 border-red-500 bg-black" required />
                        <span class="text-sm text-text-secondary group-hover:text-red-400 transition-colors">
                            I understand that this action is <strong class="text-red-400">irreversible</strong>
                            and
                            all my data will be permanently deleted.
                        </span>
                    </label>

                    <button type="submit" id="delete-btn"
                        class="w-full bg-red-500 border-4 border-black py-4 text-white font-black text-xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        disabled>
                        <i class="fas fa-trash-alt mr-2"></i> Delete Account Forever
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
