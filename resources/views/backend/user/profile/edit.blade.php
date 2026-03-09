@extends('backend.user.master')
@section('content')
    <!-- ===== TITLE ===== -->
    <h2 class="text-brand text-3xl lg:text-5xl font-bold pixel-text tracking-tight mb-2 font-pixel">
        SETTINGS
    </h2>

    <!-- ===== TABS CONTAINER ===== -->
    <div class="flex flex-col relative z-0">
        <!-- Tab Headers -->
        <div class="flex items-end -space-x-1">
            <button onclick="switchTab('profile')" id="tab-btn-profile"
                class="tab-btn px-8 py-3 bg-cyber-surface border-x-4 border-t-4 border-black z-[2] relative transition-colors">
                <span class="text-white font-bold uppercase tracking-widest text-sm pixel-text">Profile</span>
            </button>
            <button onclick="switchTab('password')" id="tab-btn-password"
                class="tab-btn px-8 py-2 bg-cyber-dark border-x-4 border-t-4 border-black text-text-secondary z-[1] hover:bg-white/10 cursor-pointer transition-colors">
                <span class="font-bold uppercase tracking-widest text-sm pixel-text">Password</span>
            </button>
            <button onclick="switchTab('email')" id="tab-btn-email"
                class="tab-btn px-8 py-2 bg-cyber-dark border-x-4 border-t-4 border-black text-text-secondary z-0 hover:bg-white/10 cursor-pointer transition-colors">
                <span class="font-bold uppercase tracking-widest text-sm pixel-text">Email</span>
            </button>
            <button onclick="switchTab('withdraw')" id="tab-btn-withdraw"
                class="tab-btn px-8 py-2 bg-cyber-dark border-x-4 border-t-4 border-black text-text-secondary hover:bg-white/10 cursor-pointer transition-colors">
                <span class="font-bold uppercase tracking-widest text-sm pixel-text">Withdraw</span>
            </button>
            <button onclick="switchTab('account')" id="tab-btn-account"
                class="tab-btn px-8 py-2 bg-cyber-dark border-x-4 border-t-4 border-black text-text-secondary hover:bg-white/10 cursor-pointer transition-colors">
                <span class="font-bold uppercase tracking-widest text-sm pixel-text">Account</span>
            </button>
        </div>

        <!-- ============================================================ -->
        <!-- TAB: PROFILE -->
        <!-- ============================================================ -->
        @include('backend.user.profile.profile-tab')

        <!-- ============================================================ -->
        <!-- TAB: PASSWORD -->
        <!-- ============================================================ -->
        @include('backend.user.profile.password-tab')

        <!-- ============================================================ -->
        <!-- TAB: EMAIL -->
        <!-- ============================================================ -->
        @include('backend.user.profile.change-email-tab')

        <!-- ============================================================ -->
        <!-- TAB: WITHDRAW -->
        <!-- ============================================================ -->
        @include('backend.user.profile.withdraw-tab')

        <!-- ============================================================ -->
        <!-- TAB: ACCOUNT -->
        <!-- ============================================================ -->
        @include('backend.user.profile.account-tab')
    </div>
@endsection

@push('scripts')
    <!-- ===== TAB SWITCHING & INTERACTIVE JS ===== -->
    <script>
        function switchTab(tabName) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-cyber-surface', 'z-[2]');
                b.classList.add('bg-cyber-dark', 'text-text-secondary', 'hover:bg-white/10');
                b.querySelector('span').classList.remove('text-white');
                b.querySelector('span').classList.add('text-text-secondary');
                b.style.paddingTop = '0.5rem';
                b.style.paddingBottom = '0.5rem';
            });
            // Show selected panel
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            // Activate selected button
            const btn = document.getElementById('tab-btn-' + tabName);
            btn.classList.remove('bg-cyber-dark', 'text-text-secondary', 'hover:bg-white/10');
            btn.classList.add('bg-cyber-surface', 'z-[2]');
            btn.querySelector('span').classList.remove('text-text-secondary');
            btn.querySelector('span').classList.add('text-white');
            btn.style.paddingTop = '0.75rem';
            btn.style.paddingBottom = '0.75rem';
            // Save to localStorage
            localStorage.setItem('activeProfileTab', tabName);
        }

        // Restore active tab on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('activeProfileTab');
            if (savedTab && document.getElementById('tab-' + savedTab)) {
                switchTab(savedTab);
            }
        });

        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentElement.querySelector('button i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength meter
        const newPwField = document.getElementById('new_password');
        if (newPwField) {
            newPwField.addEventListener('input', function() {
                const val = this.value;
                let strength = 0;
                if (val.length >= 6) strength++;
                if (val.length >= 10) strength++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength++;
                if (/[0-9!@#$%^&*]/.test(val)) strength++;

                const colors = ['bg-red-500', 'bg-yellow-400', 'bg-cyber-cyan', 'bg-brand'];
                const labels = ['WEAK', 'FAIR', 'GOOD', 'STRONG'];
                for (let i = 1; i <= 4; i++) {
                    const bar = document.getElementById('str-' + i);
                    bar.className = 'h-1 flex-1 border border-black ' + (i <= strength ? colors[strength - 1] :
                        'bg-black');
                }
                document.getElementById('str-text').textContent = 'PASSWORD STRENGTH: ' + (strength > 0 ? labels[
                    strength - 1] : 'UNKNOWN');
            });
        }

        // Password match check
        const confirmPwField = document.getElementById('confirm_password');
        if (confirmPwField) {
            confirmPwField.addEventListener('input', function() {
                const mismatch = document.getElementById('password-mismatch');
                if (this.value && this.value !== document.getElementById('new_password').value) {
                    mismatch.classList.remove('hidden');
                } else {
                    mismatch.classList.add('hidden');
                }
            });
        }

        // Photo preview
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.closest('.flex').querySelector('.overflow-hidden');
                    container.innerHTML = '<img src="' + e.target.result +
                        '" alt="Avatar Preview" class="w-full h-full object-cover" />';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Delete account confirmation checkbox
        const confirmDeleteCheckbox = document.getElementById('confirm-delete');
        const deleteBtn = document.getElementById('delete-btn');
        if (confirmDeleteCheckbox && deleteBtn) {
            confirmDeleteCheckbox.addEventListener('change', function() {
                deleteBtn.disabled = !this.checked;
            });
        }
    </script>
@endpush
