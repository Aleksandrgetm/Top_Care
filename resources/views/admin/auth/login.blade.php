<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Top Care Group</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#f4f7f4] text-[#12261f]">
    <div class="grid min-h-screen lg:grid-cols-[1.1fr_0.9fr]">
        <section class="relative hidden overflow-hidden bg-[#042c1f] lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.22),transparent_32%),linear-gradient(160deg,rgba(4,44,31,0.96),rgba(7,62,45,0.88))]"></div>
            <div class="relative flex h-full flex-col justify-between p-12 text-white">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Top Care Group" class="h-14 w-auto">
                    <div>
                        <p class="text-sm font-semibold tracking-[0.28em]">TOP CARE GROUP</p>
                        <p class="text-xs uppercase tracking-[0.24em] text-white/60">Admin Panel</p>
                    </div>
                </div>

                <div class="max-w-[520px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#BFD730]">Administration</p>
                    <h1 class="mt-5 text-5xl font-semibold leading-[1.02]">
                        Manage your website from one modern workspace.
                    </h1>
                    <p class="mt-6 text-base leading-8 text-white/74">
                        Secure access to Top Care Group content, key statistics, and future content management modules.
                    </p>
                </div>

                <div class="grid max-w-[520px] gap-4">
                    <div class="rounded-[1.8rem] border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                        <p class="text-sm font-medium text-white">Protected access</p>
                        <p class="mt-2 text-sm leading-7 text-white/65">Only authorized administrators can enter the panel.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-[460px] rounded-[2rem] border border-[#06402B]/8 bg-white p-8 shadow-[0_24px_70px_rgba(6,64,43,0.08)] sm:p-10">
                <div class="mb-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Top Care Group</p>
                    <h2 class="mt-3 text-3xl font-semibold text-[#12261f]">Admin login</h2>
                    <p class="mt-3 text-sm leading-7 text-[#5c6d66]">Enter your administrator credentials to continue to the dashboard.</p>
                </div>

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-[#244338]">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-2xl border border-[#06402B]/12 bg-[#f9fbf8] px-4 py-3.5 text-sm outline-none transition focus:border-[#06402B] focus:bg-white">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-[#244338]">Password</label>
                        <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-[#06402B]/12 bg-[#f9fbf8] px-4 py-3.5 text-sm outline-none transition focus:border-[#06402B] focus:bg-white">
                    </div>

                    <label class="flex items-center gap-3 text-sm text-[#5c6d66]">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[#06402B]/20 text-[#06402B] focus:ring-[#06402B]">
                        <span>Remember me</span>
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-[#BFD730] px-6 py-3.5 text-sm font-semibold text-[#0f241d] transition hover:-translate-y-0.5 hover:bg-[#cde63e]">
                        Log in to admin
                    </button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
