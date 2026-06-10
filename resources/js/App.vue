<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const heroOffset = ref(0);
const brandLogo = '/images/logo.png';
const teamPhoto = '/images/topcare-komanda.png';
const heroImage = '/images/fasades-mazgasana-darba-process.png';

const navigation = [
    { label: 'Sākums', href: '#hero' },
    { label: 'Mūsu darbi', href: '#works' },
    { label: 'Pakalpojumi', href: '#services' },
    { label: 'Par mums', href: '#about' },
    { label: 'Pirms / Pēc', href: '#before-after' },
    { label: 'Kontakti', href: '#contacts' },
];

const workGallery = [
    {
        title: 'Fasādes mazgāšana pirms un pēc',
        category: 'Galvenais objekts',
        image: '/images/fasada-mazgasana-pirms-pec.png',
        width: 640,
        height: 640,
        alt: 'Fasādes mazgāšana pirms un pēc',
        featured: true,
    },
    {
        title: 'Jumta tīrīšana pirms un pēc',
        category: 'Pirms / pēc',
        image: '/images/jumta-tirisana-pirms-pec.png',
        width: 512,
        height: 358,
        alt: 'Jumta tīrīšana pirms un pēc',
    },
    {
        title: 'Metāla jumts pirms tīrīšanas',
        category: 'Jumti',
        image: '/images/metala-jumts-pirms-tirisanas.png',
        width: 1152,
        height: 2048,
        alt: 'Metāla jumts pirms tīrīšanas',
    },
    {
        title: 'Malkas kraušana',
        category: 'Teritorijas darbi',
        image: '/images/malkas-krausana.png',
        width: 1536,
        height: 2040,
        alt: 'Malkas kraušana',
    },
];

const caseStudies = [
    {
        title: 'Fasādes mazgāšana pirms un pēc',
        service: 'Fasāžu mazgāšana',
        result: 'Redzams skaidrs un uzskatāms rezultāts pēc tīrīšanas darbiem, kas atjauno ēkas vizuālo kvalitāti.',
        image: '/images/fasada-mazgasana-pirms-pec.png',
        width: 640,
        height: 640,
        alt: 'Fasādes mazgāšana pirms un pēc',
    },
    {
        title: 'Jumta tīrīšana pirms un pēc',
        service: 'Jumtu tīrīšana',
        result: 'Tīrīšanas darbi palīdz atjaunot jumta izskatu un sagatavot to turpmākai apkopei.',
        image: '/images/jumta-tirisana-pirms-pec.png',
        width: 512,
        height: 358,
        alt: 'Jumta tīrīšana pirms un pēc',
    },
    {
        title: 'Metāla jumts pirms tīrīšanas',
        service: 'Metāla jumtu apkope',
        result: 'Objekta sākotnējā stāvokļa fiksācija ļauj skaidri novērtēt nepieciešamo darbu apjomu.',
        image: '/images/metala-jumts-pirms-tirisanas.png',
        width: 1152,
        height: 2048,
        alt: 'Metāla jumts pirms tīrīšanas',
    },
];

const reasons = [
    'Pieredzējusi komanda',
    'Reāli paveikti darbi',
    'Bezmaksas objekta novērtējums',
    'Darbs visā Latvijā',
    'Individuāla pieeja katram objektam',
];

const beforeAfter = [
    {
        title: 'Fasādes mazgāšana',
        before: '/images/fasada-mazgasana-pirms-pec.png',
        after: '/images/fasada-mazgasana-pirms-pec.png',
        beforeAlt: 'Fasādes mazgāšana pirms un pēc',
        afterAlt: 'Fasādes mazgāšana pirms un pēc',
        beforeWidth: 640,
        beforeHeight: 640,
        afterWidth: 640,
        afterHeight: 640,
    },
    {
        title: 'Jumta tīrīšana',
        before: '/images/jumta-tirisana-pirms-pec.png',
        after: '/images/jumta-tirisana-pirms-pec.png',
        beforeAlt: 'Jumta tīrīšana pirms un pēc',
        afterAlt: 'Jumta tīrīšana pirms un pēc',
        beforeWidth: 512,
        beforeHeight: 358,
        afterWidth: 512,
        afterHeight: 358,
    },
];

const services = [
    {
        title: 'Fasāžu mazgāšana',
        description: 'Profesionāla fasāžu tīrīšana no netīrumiem, putekļiem un aplikuma.',
    },
    {
        title: 'Jumtu tīrīšana',
        description: 'Jumtu attīrīšana no sūnām, netīrumiem un bioloģiskā aplikuma.',
    },
    {
        title: 'Bruģa tīrīšana',
        description: 'Bruģa un celiņu tīrīšana, lai atjaunotu sakoptu teritorijas izskatu.',
    },
    {
        title: 'Metāla jumtu apkope',
        description: 'Metāla jumtu tīrīšana un sagatavošana turpmākai apkopei.',
    },
    {
        title: 'Malkas pakalpojumi',
        description: 'Malkas skaldīšana, kraušana un piegādes risinājumi klientiem.',
    },
    {
        title: 'Īpašumu uzturēšana',
        description: 'Regulāri īpašumu uzturēšanas un sakopšanas darbi.',
    },
];

const stats = [
    { value: 5, suffix: '', label: 'reāli darbu piemēri vienā lapā' },
    { value: 6, suffix: '', label: 'galvenie pakalpojumu virzieni' },
    { value: 1, suffix: '', label: 'komandas foto sadaļā Par mums' },
];

const statValues = ref(stats.map(() => 0));

const heroStyle = computed(() => ({
    transform: `translateY(${heroOffset.value * 0.14}px) scale(${1 + Math.min(heroOffset.value / 7000, 0.02)})`,
}));

const smoothScroll = (href) => {
    const target = document.querySelector(href);

    if (!target) {
        return;
    }

    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    isMenuOpen.value = false;
};

const updateViewport = () => {
    const scrollY = window.scrollY;

    isScrolled.value = scrollY > 24;
    heroOffset.value = Math.min(scrollY, 240);
};

let revealObserver;
let counterObserver;
let counterAnimationFrame = 0;

const startCounters = () => {
    if (counterAnimationFrame) {
        return;
    }

    const startedAt = performance.now();
    const duration = 1400;

    const tick = (timestamp) => {
        const progress = Math.min((timestamp - startedAt) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);

        statValues.value = stats.map((item) => Math.round(item.value * eased));

        if (progress < 1) {
            counterAnimationFrame = window.requestAnimationFrame(tick);
        } else {
            counterAnimationFrame = 0;
        }
    };

    counterAnimationFrame = window.requestAnimationFrame(tick);
};

onMounted(() => {
    updateViewport();
    window.addEventListener('scroll', updateViewport, { passive: true });

    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.16 },
    );

    document.querySelectorAll('[data-reveal]').forEach((element) => {
        revealObserver.observe(element);
    });

    counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    startCounters();
                    counterObserver.disconnect();
                }
            });
        },
        { threshold: 0.3 },
    );

    const statsSection = document.querySelector('#services');

    if (statsSection) {
        counterObserver.observe(statsSection);
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateViewport);
    revealObserver?.disconnect();
    counterObserver?.disconnect();

    if (counterAnimationFrame) {
        window.cancelAnimationFrame(counterAnimationFrame);
    }
});
</script>

<template>
    <div class="bg-white text-[#1f2933]">
        <header
            :class="[
                'fixed inset-x-0 top-0 z-50 transition-all duration-300 ease-in-out',
                isScrolled
                    ? 'border-b border-[#06402B]/8 bg-white/95 shadow-[0_12px_36px_rgba(6,64,43,0.10)] backdrop-blur-xl'
                    : 'bg-transparent backdrop-blur-[2px]',
            ]"
        >
            <div class="mx-auto flex max-w-[1320px] items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <button class="flex items-center gap-3" @click="smoothScroll('#hero')">
                    <img :src="brandLogo" alt="TopCare logotips" class="h-10 w-auto sm:h-11" height="283" width="360" />
                    <div class="text-left">
                        <p
                            :class="[
                                'font-display text-sm tracking-[0.28em] transition-colors duration-300 ease-in-out',
                                isScrolled ? 'text-[#06402B]' : 'text-white',
                            ]"
                        >
                            TOP CARE
                        </p>
                        <p
                            :class="[
                                'text-xs uppercase tracking-[0.22em] transition-colors duration-300 ease-in-out',
                                isScrolled ? 'text-[#60716a]' : 'text-white/72',
                            ]"
                        >
                            Group Latvia
                        </p>
                    </div>
                </button>

                <nav class="hidden items-center gap-7 lg:flex">
                    <button
                        v-for="item in navigation"
                        :key="item.href"
                        :class="[
                            'text-sm font-medium transition-colors duration-300 ease-in-out',
                            isScrolled ? 'text-[#244338] hover:text-[#06402B]' : 'text-white hover:text-[#BFD730]',
                        ]"
                        @click="smoothScroll(item.href)"
                    >
                        {{ item.label }}
                    </button>
                    <button
                        :class="[
                            'rounded-full px-5 py-3 text-sm font-semibold text-white transition-all duration-300 ease-in-out hover:-translate-y-0.5',
                            isScrolled
                                ? 'bg-[#06402B] hover:bg-[#0b5c3f] shadow-[0_10px_24px_rgba(6,64,43,0.18)]'
                                : 'bg-[#BFD730] text-[#0f241d] hover:bg-[#d0ea3f] shadow-[0_10px_24px_rgba(0,0,0,0.18)]',
                        ]"
                        @click="smoothScroll('#contacts')"
                    >
                        Saņemt piedāvājumu
                    </button>
                </nav>

                <button
                    :class="[
                        'inline-flex h-11 w-11 items-center justify-center rounded-full transition-all duration-300 ease-in-out lg:hidden',
                        isScrolled
                            ? 'border border-[#06402B]/12 bg-white/80 text-[#06402B]'
                            : 'border border-white/16 bg-white/8 text-white backdrop-blur-sm',
                    ]"
                    @click="isMenuOpen = !isMenuOpen"
                >
                    <span class="sr-only">Atvērt izvēlni</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div
                v-if="isMenuOpen"
                class="border-t border-[#06402B]/8 bg-white/96 px-5 py-4 shadow-[0_18px_60px_rgba(6,64,43,0.12)] backdrop-blur-xl transition-all duration-300 ease-in-out lg:hidden"
            >
                <div class="mx-auto flex max-w-[1320px] flex-col gap-2">
                    <button
                        v-for="item in navigation"
                        :key="item.href"
                        class="rounded-2xl px-4 py-3 text-left text-sm font-medium text-[#244338] transition-all duration-300 ease-in-out hover:bg-[#f3f7f2] hover:text-[#06402B]"
                        @click="smoothScroll(item.href)"
                    >
                        {{ item.label }}
                    </button>
                    <button
                        class="mt-2 rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                        @click="smoothScroll('#contacts')"
                    >
                        Saņemt piedāvājumu
                    </button>
                </div>
            </div>
        </header>

        <main>
            <section id="hero" class="relative isolate flex min-h-screen overflow-hidden bg-[#0b1a16]">
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    :style="[
                        heroStyle,
                        {
                            backgroundImage: `url(${heroImage})`,
                        },
                    ]"
                />
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(2,10,8,0.56),rgba(6,64,43,0.62))]" />
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.10),transparent_30%)]" />

                <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-[1320px] items-center px-5 py-28 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal max-w-[720px]">
                        <h1 class="font-display text-4xl leading-[0.94] text-white sm:text-6xl lg:text-7xl">
                            Profesionāla fasāžu un jumtu tīrīšana visā Latvijā
                        </h1>
                        <p class="mt-6 max-w-[620px] text-base leading-8 text-white/82 sm:text-xl">
                            Atbrīvojam fasādes, jumtus un bruģi no netīrumiem, sūnām un aplikuma.
                        </p>

                        <div class="mt-8 grid gap-3 text-sm font-medium text-white/88 sm:max-w-[420px] sm:grid-cols-1">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 text-[#BFD730]">✓</span>
                                <span>Darbs visā Latvijā</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 text-[#BFD730]">✓</span>
                                <span>Bezmaksas objekta novērtējums</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 text-[#BFD730]">✓</span>
                                <span>Reāli paveikti darbi</span>
                            </div>
                        </div>

                        <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                            <button
                                class="rounded-full bg-[#BFD730] px-7 py-4 text-sm font-semibold text-[#0f241d] transition hover:-translate-y-0.5 hover:bg-[#d0ea3f]"
                                @click="smoothScroll('#contacts')"
                            >
                                Saņemt cenu
                            </button>
                            <a
                                class="inline-flex items-center justify-center rounded-full border border-white/18 bg-white/8 px-7 py-4 text-sm font-semibold text-white backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/14"
                                href="https://wa.me/37120000000"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Sazināties WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="works" class="py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="section-kicker">Mūsu darbi</p>
                            <h2 class="section-title mt-4 max-w-[620px]">
                                Mūsu paveiktie darbi
                            </h2>
                        </div>
                        <p class="max-w-[460px] text-base leading-8 text-[#56665f]">
                            Apskatiet reālus objektus, kuros veikta fasāžu, jumtu un teritoriju tīrīšana.
                        </p>
                    </div>

                    <div class="photo-grid mt-14">
                        <article
                            v-for="item in workGallery"
                            :key="item.title"
                            data-reveal
                            :class="['reveal photo-card', { 'photo-card--featured': item.featured }]"
                        >
                            <img
                                :src="item.image"
                                :alt="item.alt"
                                class="photo-card__image"
                                :height="item.height"
                                :width="item.width"
                                decoding="async"
                                loading="lazy"
                                :sizes="item.featured ? '(min-width: 1280px) 56vw, (min-width: 768px) 62vw, 100vw' : '(min-width: 1280px) 28vw, (min-width: 768px) 31vw, 100vw'"
                            />
                            <div class="photo-card__shade" />
                            <div class="photo-card__meta">
                                <p class="photo-card__category">{{ item.category }}</p>
                                <h3 class="photo-card__title">{{ item.title }}</h3>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="projects" class="bg-[#f7faf7] py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="section-kicker">Reāli objekti</p>
                            <h2 class="section-title mt-4 max-w-[640px]">
                                Darbi, kuros rezultāts ir redzams uzreiz
                            </h2>
                        </div>
                        <p class="max-w-[480px] text-base leading-8 text-[#56665f]">
                            Katrs piemērs rāda skaidru, saprotamu un profesionāli paveiktu darbu uz reāla objekta.
                        </p>
                    </div>

                    <div class="mt-14 grid gap-6 lg:grid-cols-3">
                        <article
                            v-for="item in caseStudies"
                            :key="item.title"
                            data-reveal
                            class="reveal overflow-hidden rounded-[2rem] bg-white shadow-[0_18px_55px_rgba(6,64,43,0.08)]"
                        >
                            <div class="relative h-[420px] overflow-hidden">
                                <img
                                    :src="item.image"
                                    :alt="item.alt"
                                    class="h-full w-full object-cover"
                                    :height="item.height"
                                    :width="item.width"
                                    decoding="async"
                                    loading="lazy"
                                    sizes="(min-width: 1024px) 30vw, 100vw"
                                />
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(8,20,16,0.06),rgba(8,20,16,0.58))]" />
                                <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">{{ item.service }}</p>
                                    <h3 class="mt-3 text-2xl font-semibold">{{ item.title }}</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-sm leading-7 text-[#56665f]">{{ item.result }}</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="services" class="py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div class="grid gap-12 lg:grid-cols-[0.7fr_1.3fr]">
                        <div data-reveal class="reveal">
                            <p class="section-kicker">Pakalpojumi</p>
                            <h2 class="section-title mt-4 max-w-[460px]">
                                Mūsu pakalpojumi
                            </h2>

                            <div class="mt-10 grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                                <div
                                    v-for="(item, index) in stats"
                                    :key="item.label"
                                    class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5"
                                >
                                    <p class="font-display text-4xl text-[#06402B]">{{ statValues[index] }}{{ item.suffix }}</p>
                                    <p class="mt-2 text-sm leading-6 text-[#56665f]">{{ item.label }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            <article
                                v-for="service in services"
                                :key="service.title"
                                data-reveal
                                class="reveal rounded-[1.9rem] border border-[#06402B]/8 bg-white p-6 shadow-[0_16px_45px_rgba(6,64,43,0.05)]"
                            >
                                <h3 class="text-xl font-semibold text-[#12261f]">{{ service.title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-[#5b6a63]">{{ service.description }}</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-[#06402B] py-24 text-white sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                        <div>
                            <p class="section-kicker text-[#BFD730]">Kāpēc izvēlēties mūs</p>
                            <h2 class="mt-4 max-w-[560px] font-display text-3xl leading-tight sm:text-5xl">
                                Kāpēc izvēlēties TopCare?
                            </h2>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div
                                v-for="reason in reasons"
                                :key="reason"
                                class="rounded-[1.8rem] border border-white/10 bg-white/8 p-5 text-base font-medium text-white/88 backdrop-blur-sm"
                            >
                                {{ reason }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="about" class="bg-[#f7faf7] py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                        <div data-reveal class="reveal overflow-hidden rounded-[2rem] shadow-[0_20px_60px_rgba(6,64,43,0.08)]">
                            <img
                                :src="teamPhoto"
                                alt="TopCare komanda"
                                class="h-full w-full object-cover"
                                width="1440"
                                height="958"
                                decoding="async"
                                loading="lazy"
                                sizes="(min-width: 1024px) 48vw, 100vw"
                            />
                        </div>

                        <div data-reveal class="reveal rounded-[2rem] border border-[#06402B]/8 bg-white p-8 shadow-[0_18px_55px_rgba(6,64,43,0.05)] sm:p-10">
                            <p class="section-kicker">Par mums</p>
                            <h2 class="section-title mt-4 max-w-[520px]">
                                Par TopCare
                            </h2>
                            <div class="mt-6 space-y-5 text-base leading-8 text-[#56665f]">
                                <p>
                                    TopCare ir Latvijas uzņēmums, kas nodrošina fasāžu, jumtu, bruģa un teritoriju tīrīšanas pakalpojumus privātpersonām un uzņēmumiem. Mūsu komandu veido pieredzējuši speciālisti, kuri ikdienā strādā ar dažāda veida objektiem visā Latvijā.
                                </p>
                                <p>
                                    Mēs strādājam rūpīgi, atbildīgi un ar mērķi panākt kvalitatīvu rezultātu katram klientam.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="before-after" class="bg-[#0d1f19] py-24 text-white sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="section-kicker text-[#BFD730]">Pirms / Pēc</p>
                            <h2 class="mt-4 max-w-[620px] font-display text-3xl leading-tight sm:text-5xl">
                                Rezultāts, ko var redzēt
                            </h2>
                        </div>
                        <p class="max-w-[480px] text-base leading-8 text-white/70">
                            Salīdziniet objektu pirms un pēc tīrīšanas darbiem.
                        </p>
                    </div>

                    <div class="mt-14 grid gap-6 xl:grid-cols-2">
                        <article
                            v-for="item in beforeAfter"
                            :key="item.title"
                            data-reveal
                            class="reveal rounded-[2rem] border border-white/10 bg-white/6 p-4 backdrop-blur-sm sm:p-5"
                        >
                            <h3 class="px-2 pb-4 text-2xl font-semibold">{{ item.title }}</h3>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="overflow-hidden rounded-[1.5rem]">
                                    <div class="before-after-label">Pirms</div>
                                    <img
                                        :src="item.before"
                                        :alt="item.beforeAlt"
                                        class="before-after-image"
                                        :height="item.beforeHeight"
                                        :width="item.beforeWidth"
                                        decoding="async"
                                        loading="lazy"
                                        sizes="(min-width: 768px) 24vw, 100vw"
                                    />
                                </div>
                                <div class="overflow-hidden rounded-[1.5rem]">
                                    <div class="before-after-label before-after-label--accent">Pēc</div>
                                    <img
                                        :src="item.after"
                                        :alt="item.afterAlt"
                                        class="before-after-image"
                                        :height="item.afterHeight"
                                        :width="item.afterWidth"
                                        decoding="async"
                                        loading="lazy"
                                        sizes="(min-width: 768px) 24vw, 100vw"
                                    />
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="contacts" class="py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div class="grid gap-8 xl:grid-cols-[0.8fr_1.2fr]">
                        <div data-reveal class="reveal">
                            <p class="section-kicker">Kontakti</p>
                            <h2 class="section-title mt-4 max-w-[520px]">Sazinieties ar mums</h2>
                            <p class="mt-6 max-w-[480px] text-base leading-8 text-[#56665f]">
                                Ja jums ir objekts, kuru nepieciešams notīrīt vai sakopt, sazinieties ar mums un mēs sagatavosim piedāvājumu.
                            </p>

                            <div class="mt-10 grid gap-4">
                                <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">Telefons</p>
                                    <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="tel:+37120000000">+371 20 000 000</a>
                                </div>
                                <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">E-pasts</p>
                                    <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="mailto:info@topcaregroup.lv">info@topcaregroup.lv</a>
                                </div>
                            </div>
                        </div>

                        <div data-reveal class="reveal rounded-[2rem] border border-[#06402B]/8 bg-white p-6 shadow-[0_20px_65px_rgba(6,64,43,0.08)] sm:p-8">
                            <form class="grid gap-4 sm:grid-cols-2">
                                <label class="form-field">
                                    <span>Vārds</span>
                                    <input placeholder="Ievadiet savu vārdu" type="text" />
                                </label>
                                <label class="form-field">
                                    <span>Telefons</span>
                                    <input placeholder="Ievadiet tālruņa numuru" type="tel" />
                                </label>
                                <label class="form-field">
                                    <span>E-pasts</span>
                                    <input placeholder="Ievadiet e-pastu" type="email" />
                                </label>
                                <label class="form-field">
                                    <span>Pakalpojums</span>
                                    <select>
                                        <option>Izvēlieties pakalpojumu</option>
                                        <option>Fasāžu mazgāšana</option>
                                        <option>Jumtu tīrīšana</option>
                                        <option>Bruģa tīrīšana</option>
                                        <option>Metāla jumtu apkope</option>
                                        <option>Malkas pakalpojumi</option>
                                        <option>Īpašumu uzturēšana</option>
                                    </select>
                                </label>
                                <label class="form-field sm:col-span-2">
                                    <span>Ziņojums</span>
                                    <textarea placeholder="Aprakstiet objektu vai nepieciešamos darbus" rows="5" />
                                </label>
                                <label class="form-field sm:col-span-2">
                                    <span>Augšupielādēt objekta foto</span>
                                    <input type="file" />
                                </label>
                                <div class="sm:col-span-2">
                                    <button
                                        class="inline-flex rounded-full bg-[#06402B] px-7 py-4 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                                        type="button"
                                    >
                                        Nosūtīt pieteikumu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-[#042c1f] py-10 text-white">
            <div class="mx-auto grid max-w-[1320px] gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_0.8fr_0.8fr] lg:px-10">
                <div>
                    <div class="flex items-center gap-3">
                        <img :src="brandLogo" alt="TopCare logotips" class="h-10 w-auto" height="283" width="360" loading="lazy" decoding="async" />
                        <div>
                            <p class="font-display text-sm tracking-[0.28em]">TOP CARE</p>
                            <p class="text-xs uppercase tracking-[0.22em] text-white/62">Group Latvia</p>
                        </div>
                    </div>
                    <p class="mt-5 max-w-[360px] text-sm leading-7 text-white/64">
                        Fasāžu, jumtu un teritoriju tīrīšanas pakalpojumi Latvijā
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Navigācija</p>
                    <div class="mt-5 flex flex-col gap-3 text-sm text-white/72">
                        <button v-for="item in navigation" :key="item.href" class="text-left transition hover:text-white" @click="smoothScroll(item.href)">
                            {{ item.label }}
                        </button>
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Kontakti</p>
                    <div class="mt-5 space-y-3 text-sm leading-7 text-white/72">
                        <p>Telefons: +371 20 000 000</p>
                        <p>E-pasts: info@topcaregroup.lv</p>
                        <p>Rīga, Latvija</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-[1320px] border-t border-white/10 px-5 pt-6 text-sm text-white/44 sm:px-8 lg:px-10">
                © 2026 TopCare. Visas tiesības aizsargātas
            </div>
        </footer>
    </div>
</template>
