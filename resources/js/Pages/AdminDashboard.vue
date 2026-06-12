<script setup>
defineProps({
    user: {
        type: Object,
        required: true,
    },
    stats: {
        type: Array,
        required: true,
    },
});

const quickLinks = [
    {
        title: 'Services',
        description: 'Manage website service categories and homepage content blocks.',
        status: 'Planned module',
    },
    {
        title: 'Pirms/Pec',
        description: 'Track completed before/after projects and gallery updates.',
        status: 'Planned module',
    },
    {
        title: 'Site settings',
        description: 'Central place for future global content and configuration.',
        status: 'Next step',
    },
];
</script>

<template>
    <div class="min-h-screen bg-[#edf3ef] text-[#12261f]">
        <div class="grid min-h-screen lg:grid-cols-[290px_1fr]">
            <aside class="relative overflow-hidden bg-[#042c1f] px-6 py-8 text-white">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.18),transparent_36%)]"></div>
                <div class="relative flex h-full flex-col">
                    <div class="flex items-center gap-3">
                        <img src="/images/logo.png" alt="Top Care Group" class="h-12 w-auto rounded-lg bg-white/95 p-1.5" />
                        <div>
                            <div class="text-sm font-semibold tracking-[0.24em]">TOP CARE GROUP</div>
                            <div class="text-xs uppercase tracking-[0.2em] text-white/60">Admin</div>
                        </div>
                    </div>

                    <nav class="mt-12 space-y-2">
                        <a href="/admin" class="flex items-center justify-between rounded-[1.25rem] bg-white/10 px-4 py-3 text-sm font-medium backdrop-blur-sm">
                            <span>Dashboard</span>
                            <span class="rounded-full bg-[#BFD730] px-2 py-0.5 text-[11px] font-semibold text-[#0f241d]">Live</span>
                        </a>
                        <div class="rounded-[1.25rem] border border-white/8 px-4 py-3 text-sm text-white/60">
                            Services
                        </div>
                        <div class="rounded-[1.25rem] border border-white/8 px-4 py-3 text-sm text-white/60">
                            Pirms / Pec
                        </div>
                    </nav>

                    <div class="mt-auto rounded-[1.5rem] border border-white/10 bg-white/6 p-5 backdrop-blur-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#BFD730]">Authorized user</p>
                        <p class="mt-4 text-lg font-semibold">{{ user.name }}</p>
                        <p class="mt-1 text-sm text-white/64">{{ user.email }}</p>

                        <form method="POST" action="/admin/logout" class="mt-5">
                            <input type="hidden" name="_token" :value="document.querySelector('meta[name=csrf-token]')?.content">
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-white/14 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="px-5 py-6 sm:px-8 lg:px-10 lg:py-8">
                <div class="mx-auto max-w-[1280px]">
                    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Dashboard</p>
                        <div class="mt-4 flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                            <div class="max-w-[720px]">
                                <h1 class="text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">Top Care Group administration panel</h1>
                                <p class="mt-4 text-sm leading-7 text-[#5c6d66] sm:text-base">
                                    A central place to monitor website content and prepare the next management modules for services, projects, and settings.
                                </p>
                            </div>

                            <div class="rounded-[1.4rem] border border-[#06402B]/8 bg-[#f3f7f3] px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Environment</p>
                                <p class="mt-2 text-sm font-medium text-[#244338]">Laravel + Vue + MySQL admin workspace</p>
                            </div>
                        </div>
                    </header>

                    <section class="mt-8 grid gap-5 xl:grid-cols-3">
                        <article
                            v-for="item in stats"
                            :key="item.label"
                            class="rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)]"
                        >
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#60716a]">{{ item.label }}</p>
                            <p class="mt-5 text-4xl font-semibold leading-none text-[#12261f]">{{ item.value }}</p>
                            <p class="mt-3 text-sm leading-7 text-[#5c6d66]">{{ item.caption }}</p>
                        </article>
                    </section>

                    <section class="mt-8 grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
                        <div class="rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)]">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#60716a]">Modules roadmap</p>
                                    <h2 class="mt-3 text-2xl font-semibold text-[#12261f]">Next management areas</h2>
                                </div>
                                <div class="rounded-full bg-[#edf7d3] px-3 py-1 text-xs font-semibold text-[#5b6f11]">
                                    Phase 1
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4">
                                <article
                                    v-for="link in quickLinks"
                                    :key="link.title"
                                    class="rounded-[1.35rem] border border-[#06402B]/8 bg-[#f8fbf8] px-5 py-4"
                                >
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-[#12261f]">{{ link.title }}</h3>
                                            <p class="mt-2 text-sm leading-7 text-[#5c6d66]">{{ link.description }}</p>
                                        </div>
                                        <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#60716a]">
                                            {{ link.status }}
                                        </span>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/70 bg-[#042c1f] p-6 text-white shadow-[0_20px_55px_rgba(6,64,43,0.10)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#BFD730]">System note</p>
                            <h2 class="mt-3 text-2xl font-semibold">Admin foundation is ready</h2>
                            <p class="mt-4 text-sm leading-7 text-white/72">
                                Authentication, protected admin access, and the first dashboard metrics are in place. This gives us a clean base for adding CRUD modules next.
                            </p>

                            <div class="mt-6 rounded-[1.35rem] border border-white/10 bg-white/6 px-5 py-4">
                                <p class="text-sm font-medium text-white">Available now</p>
                                <ul class="mt-3 space-y-2 text-sm text-white/68">
                                    <li>Secure login at <code>/admin/login</code></li>
                                    <li>Protected dashboard at <code>/admin</code></li>
                                    <li>Live counters for services and projects</li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</template>
