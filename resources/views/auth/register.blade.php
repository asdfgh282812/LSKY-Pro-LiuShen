<x-guest-layout>
    <x-auth-card>
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-slate-800">建立帳號</h3>
            <p class="text-slate-500 text-sm mt-1">填寫以下資訊完成註冊</p>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">使用者名稱</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-user text-sm"></i></span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="您的暱稱">
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">電子郵件位址</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope text-sm"></i></span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="your@email.com">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">密碼</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="至少 8 位字元">
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">確認密碼</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="再次輸入密碼">
                </div>
            </div>

            <!-- Register Button -->
            <div class="pt-1">
                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-lg text-white text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
                    style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 4px 14px rgba(16,185,129,0.35);">
                    <i class="fas fa-user-plus mr-2"></i>立即註冊
                </button>
            </div>

            <p class="text-center text-sm text-slate-500">
                已有帳號？
                <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                    <i class="fas fa-arrow-left text-xs mr-0.5"></i> 返回登入
                </a>
            </p>
        </form>
    </x-auth-card>
</x-guest-layout>
