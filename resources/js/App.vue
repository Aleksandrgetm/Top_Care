<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const heroOffset = ref(0);

const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
const brandLogo = '/images/logo.png';
const teamPhoto = '/images/topcare-komanda.png';
const heroImage = '/images/fasades-mazgasana-darba-process.png';
const featurePhoto = '/images/fasades-mazgasana-darba-process.png';

const navigation = [
    { label: 'Sākums', path: '/' },
    { label: 'Pakalpojumi', path: '/pakalpojumi' },
    { label: 'Par mums', path: '/par-mums' },
    { label: 'Pirms / Pēc', path: '/pirms-pec' },
    { label: 'Kontakti', path: '/kontakti' },
];

const serviceIcons = {
    renovation:
        'M4 20h16M6.5 20v-7.5L12 7l5.5 5.5V20M10 20v-4h4v4',
    facade:
        'M4.5 21V7.5L12 3l7.5 4.5V21M9 21v-6h6v6M8 9h.01M12 9h.01M16 9h.01M8 12h.01M12 12h.01M16 12h.01',
    roof: 'm3 20 7.5-13.5L14 12l3-4.5L21 20M14 12 9.5 20M17 7.5h.01',
    paving: 'M4.5 7.5h15M4.5 12h15M4.5 16.5h15M7.5 4.5v15M12 4.5v15M16.5 4.5v15',
    property: 'M12 3c2.5 2 5.75 3 9 3 0 8.25-3.75 12.75-9 15-5.25-2.25-9-6.75-9-15 3.25 0 6.5-1 9-3Zm-3.75 9 2.25 2.25 5.25-5.25',
};

const serviceCards = [
    {
        title: 'Renovācijas un iekšdarbi',
        description: 'No reģipša konstrukcijām un špaktelēšanas līdz flīzēšanai, grīdu ieklāšanai un apdares darbiem.',
        icon: 'renovation',
        items: [
            'Reģipša konstrukciju izbūve',
            'Špaktelēšana un krāsošana',
            'Flīzēšanas darbi',
            'Grīdas segumu ieklāšana',
            'Durvju montāža',
            'Dažādi remonta un apdares darbi',
        ],
    },
    {
        title: 'Fasāžu un koka konstrukciju atjaunošana',
        description: 'Fasāžu, logu un koka elementu atjaunošana, saglabājot ēkas estētiku un ilgmūžību.',
        icon: 'facade',
        items: [
            'Fasāžu krāsošana',
            'Koka fasāžu un žogu apstrāde',
            'Logu restaurācija un krāsošana',
            'Silikona šuvju atjaunošana',
            'Dekoratīvo elementu remonts',
        ],
    },
    {
        title: 'Jumtu un augstuma darbi',
        description: 'Augstuma darbi un industriālais alpīnisms objektos, kur nepieciešama precizitāte un operativitāte.',
        icon: 'roof',
        items: [
            'Industriālais alpīnisms',
            'Jumtu mazgāšana',
            'Noteku tīrīšana un remonts',
            'Jumta seguma remontdarbi',
            'Avārijas remontdarbi',
        ],
    },
    {
        title: 'Bruģēšana un labiekārtošana',
        description: 'Teritoriju izbūve un sakārtošana ar pārdomātiem risinājumiem privātiem un komerciāliem objektiem.',
        icon: 'paving',
        items: [
            'Bruģa ieklāšana',
            'Apmaļu uzstādīšana',
            'Pamatnes izbūve',
            'Drenāžas risinājumi',
            'Ceļu un stāvlaukumu izbūve',
            'Terases un āra konstrukcijas',
        ],
    },
    {
        title: 'Īpašumu uzturēšana',
        description: 'Ikdienas uzturēšanas, apsaimniekošanas un operatīvo izsaukumu darbi dažāda mēroga īpašumiem.',
        icon: 'property',
        items: [
            'Sīkie remonta darbi',
            'Daudzdzīvokļu māju apsaimniekošanas darbi',
            'Avārijas izsaukumi',
            'Regulāra īpašumu uzturēšana',
        ],
    },
];

const previewServices = computed(() => serviceCards);

const reasons = [
    'Plašs pakalpojumu klāsts vienuviet',
    'Pieredze dažādos būvniecības un renovācijas projektos',
    'Kvalitatīvi materiāli un profesionāla pieeja',
    'Atbildīga attieksme pret termiņiem un darbu izpildi',
    'Individuāli risinājumi katram klientam',
    'Darbi tiek veikti visā Latvijā',
];

const aboutHighlights = [
    'Pieredzējusi komanda',
    'Darbi visā Latvijā',
    'Individuāla pieeja',
    'Kvalitatīvs rezultāts',
];

const aboutStats = [
    {
        value: '100+',
        label: 'Pabeigti objekti',
    },
    {
        value: '5+',
        label: 'Pakalpojumu veidi',
    },
    {
        value: 'Visa',
        label: 'Latvija',
    },
];

const beforeAfterItems = [
    {
        title: 'Fasādes mazgāšana pirms un pēc',
        image: '/images/fasada-mazgasana-pirms-pec.png',
        width: 640,
        height: 640,
        alt: 'Fasādes mazgāšana pirms un pēc',
    },
    {
        title: 'Jumta tīrīšana pirms un pēc',
        image: '/images/jumta-tirisana-pirms-pec.png',
        width: 512,
        height: 358,
        alt: 'Jumta tīrīšana pirms un pēc',
    },
];

const companyStats = [
    {
        label: 'Pabeigti objekti',
        value: 100,
        suffix: '+',
        type: 'number',
    },
    {
        label: 'Pakalpojumu veidi',
        value: 5,
        suffix: '+',
        type: 'number',
    },
    {
        label: 'Individuāla pieeja',
        value: 100,
        suffix: '%',
        type: 'number',
    },
    {
        label: 'Latvija',
        value: 'Visa',
        type: 'text',
    },
];

const displayedStats = ref(
    companyStats.map((stat) => (stat.type === 'number' ? 0 : stat.value)),
);

const pageMeta = {
    '/': {
        title: 'Top Care Group | Būvniecība, renovācija un īpašumu uzturēšana',
        description: 'SIA Top Care Group nodrošina būvniecības, renovācijas un īpašumu uzturēšanas pakalpojumus privātpersonām, uzņēmumiem un namu apsaimniekotājiem visā Latvijā.',
    },
    '/pakalpojumi': {
        title: 'Top Care Group pakalpojumi | Renovācija, fasādes, jumti un labiekārtošana',
        description: 'Apskatiet Top Care Group pakalpojumus: renovācijas un iekšdarbus, fasāžu atjaunošanu, jumtu darbus, bruģēšanu un īpašumu uzturēšanu.',
    },
    '/par-mums': {
        title: 'Par Top Care Group | Pieredzējusi komanda visā Latvijā',
        description: 'Top Care Group ir Latvijas uzņēmums ar pieredzējušu komandu, kas strādā pie būvniecības, renovācijas un īpašumu uzturēšanas projektiem visā Latvijā.',
    },
    '/pirms-pec': {
        title: 'Top Care Group pirms un pēc | Redzami rezultāti objektos',
        description: 'Apskatiet redzamus fasāžu un jumtu darbu rezultātus pirms un pēc Top Care Group paveiktajiem darbiem.',
    },
    '/kontakti': {
        title: 'Top Care Group kontakti | Sazinieties un saņemiet piedāvājumu',
        description: 'Sazinieties ar Top Care Group par būvniecības, renovācijas, jumtu, fasāžu un īpašumu uzturēšanas darbiem visā Latvijā.',
    },
};

const heroStyle = computed(() => ({
    transform: `translateY(${heroOffset.value * 0.14}px) scale(${1 + Math.min(heroOffset.value / 7000, 0.02)})`,
}));

const isHomePage = computed(() => currentPath === '/');
const isServicesPage = computed(() => currentPath === '/pakalpojumi');
const isAboutPage = computed(() => currentPath === '/par-mums');
const isBeforeAfterPage = computed(() => currentPath === '/pirms-pec');
const isContactsPage = computed(() => currentPath === '/kontakti');
const useLightHeader = computed(() => isHomePage.value && !isScrolled.value);

const closeMenu = () => {
    isMenuOpen.value = false;
};

const updateViewport = () => {
    const scrollY = window.scrollY;
    isScrolled.value = scrollY > 24;
    heroOffset.value = Math.min(scrollY, 240);
};

let revealObserver;
let statsObserver;
let statsAnimationStarted = false;
const statsAnimationFrames = [];

const animateStatValue = (index, targetValue) => {
    const duration = 1400;
    const startTime = performance.now();

    const tick = (currentTime) => {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const easedProgress = 1 - Math.pow(1 - progress, 3);

        displayedStats.value[index] = Math.round(targetValue * easedProgress);

        if (progress < 1) {
            const frameId = requestAnimationFrame(tick);
            statsAnimationFrames[index] = frameId;
            return;
        }

        displayedStats.value[index] = targetValue;
    };

    const frameId = requestAnimationFrame(tick);
    statsAnimationFrames[index] = frameId;
};

const startStatsAnimation = () => {
    if (statsAnimationStarted) {
        return;
    }

    statsAnimationStarted = true;

    companyStats.forEach((stat, index) => {
        if (stat.type === 'number') {
            animateStatValue(index, stat.value);
        }
    });
};

onMounted(() => {
    updateViewport();
    window.addEventListener('scroll', updateViewport, { passive: true });

    const meta = pageMeta[currentPath];

    if (meta) {
        document.title = meta.title;

        const descriptionTag = document.querySelector('meta[name="description"]');
        const ogDescriptionTag = document.querySelector('meta[property="og:description"]');
        const ogTitleTag = document.querySelector('meta[property="og:title"]');

        descriptionTag?.setAttribute('content', meta.description);
        ogDescriptionTag?.setAttribute('content', meta.description);
        ogTitleTag?.setAttribute('content', meta.title);
    }

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

    statsObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    startStatsAnimation();
                    statsObserver?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.35 },
    );

    document.querySelectorAll('[data-stats-section]').forEach((element) => {
        statsObserver.observe(element);
    });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateViewport);
    revealObserver?.disconnect();
    statsObserver?.disconnect();
    statsAnimationFrames.forEach((frameId) => {
        if (frameId) {
            cancelAnimationFrame(frameId);
        }
    });
});
</script>

<template>
    <div class="bg-white text-[#1f2933]">
        <header
            :class="[
                'fixed inset-x-0 top-0 z-50 transition-all duration-300 ease-in-out',
                useLightHeader
                    ? 'bg-transparent backdrop-blur-[2px]'
                    : 'border-b border-[#06402B]/8 bg-white/95 shadow-[0_12px_36px_rgba(6,64,43,0.10)] backdrop-blur-xl',
            ]"
        >
            <div class="mx-auto flex max-w-[1320px] items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a class="flex items-center gap-3" href="/">
                    <img :src="brandLogo" alt="Top Care Group logotips" class="h-10 w-auto sm:h-11" height="283" width="360" />
                    <div class="text-left">
                        <p
                            :class="[
                                'font-display text-sm tracking-[0.28em] transition-colors duration-300 ease-in-out',
                                useLightHeader ? 'text-white' : 'text-[#06402B]',
                            ]"
                        >
                            TOP CARE GROUP
                        </p>
                        <p
                            :class="[
                                'text-xs uppercase tracking-[0.22em] transition-colors duration-300 ease-in-out',
                                useLightHeader ? 'text-white/72' : 'text-[#60716a]',
                            ]"
                        >
                            Latvia
                        </p>
                    </div>
                </a>

                <nav class="hidden items-center gap-7 lg:flex">
                    <a
                        v-for="item in navigation"
                        :key="item.path"
                        :href="item.path"
                        :class="[
                            'nav-link text-sm font-medium transition-all duration-300 ease-in-out',
                            currentPath === item.path
                                ? useLightHeader
                                    ? 'nav-link--active text-[#BFD730]'
                                    : 'nav-link--active text-[#06402B]'
                                : useLightHeader
                                  ? 'text-white hover:text-[#BFD730]'
                                  : 'text-[#244338] hover:text-[#06402B]',
                        ]"
                    >
                        {{ item.label }}
                    </a>
                    <a
                        :class="[
                            'rounded-full border border-[rgba(6,64,43,0.12)] px-5 py-3 text-sm font-semibold text-white transition-all duration-[250ms] ease-in-out',
                            useLightHeader
                                ? 'bg-[#BFD730] text-[#0f241d] shadow-[0_8px_20px_rgba(6,64,43,0.18)] hover:translate-y-[-2px] hover:bg-[#d0ea3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]'
                                : 'bg-[#06402B] shadow-[0_8px_20px_rgba(6,64,43,0.18)] hover:translate-y-[-2px] hover:bg-[#0b5c3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]',
                        ]"
                        href="/kontakti"
                    >
                        Saņemt piedāvājumu
                    </a>
                </nav>

                <button
                    :class="[
                        'inline-flex h-11 w-11 items-center justify-center rounded-full transition-all duration-300 ease-in-out lg:hidden',
                        useLightHeader
                            ? 'border border-white/16 bg-white/8 text-white backdrop-blur-sm'
                            : 'border border-[#06402B]/12 bg-white/80 text-[#06402B]',
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
                    <a
                        v-for="item in navigation"
                        :key="item.path"
                        :href="item.path"
                        :class="[
                            'rounded-2xl px-4 py-3 text-left text-sm font-medium transition-all duration-300 ease-in-out hover:bg-[#f3f7f2] hover:text-[#06402B]',
                            currentPath === item.path ? 'bg-[#f3f7f2] text-[#06402B]' : 'text-[#244338]',
                        ]"
                        @click="closeMenu"
                    >
                        {{ item.label }}
                    </a>
                    <a
                        class="mt-2 rounded-full border border-[rgba(6,64,43,0.12)] bg-[#06402B] px-5 py-3 text-center text-sm font-semibold text-white shadow-[0_8px_20px_rgba(6,64,43,0.18)] transition-all duration-[250ms] ease-in-out hover:translate-y-[-2px] hover:bg-[#0b5c3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]"
                        href="/kontakti"
                        @click="closeMenu"
                    >
                        Saņemt piedāvājumu
                    </a>
                </div>
            </div>
        </header>

        <main>
            <template v-if="isHomePage">
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
                        <div data-reveal class="reveal max-w-[820px]">
                            <h1 class="font-display text-4xl leading-[0.94] text-white sm:text-6xl lg:text-7xl">
                                Uzticams partneris būvniecībā, renovācijā un īpašumu uzturēšanā
                            </h1>
                            <p class="mt-6 max-w-[700px] text-base leading-8 text-white/82 sm:text-xl">
                                SIA Top Care Group ir Latvijas uzņēmums, kas nodrošina plašu būvniecības, renovācijas un apsaimniekošanas pakalpojumu klāstu privātpersonām, uzņēmumiem un namu apsaimniekotājiem.
                            </p>

                            <div class="mt-8 grid gap-3 text-sm font-medium text-white/88 sm:max-w-[520px]">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 text-[#BFD730]">✓</span>
                                    <span>Plašs pakalpojumu klāsts vienuviet</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 text-[#BFD730]">✓</span>
                                    <span>Pieredzējusi komanda dažāda mēroga projektos</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 text-[#BFD730]">✓</span>
                                    <span>Darbi tiek veikti visā Latvijā</span>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                                <a
                                    class="inline-flex items-center justify-center rounded-full bg-[#BFD730] px-7 py-4 text-sm font-semibold text-[#0f241d] transition hover:-translate-y-0.5 hover:bg-[#d0ea3f]"
                                    href="/kontakti"
                                >
                                    Saņemt cenu
                                </a>
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

                <section class="py-24 sm:py-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal grid gap-12 lg:grid-cols-[0.82fr_1.18fr] lg:items-center">
                            <div class="relative order-2 lg:order-1">
                                <span class="section-kicker">PAR TOP CARE GROUP</span>
                                <h2 class="section-title mt-4 max-w-[560px]">
                                    Kvalitatīvs darbs, atbildība un godīga attieksme
                                </h2>
                                <p class="mt-6 max-w-[560px] text-base leading-8 text-[#56665f]">
                                    Mūsu komandu veido pieredzējuši speciālisti, kuri ikdienā strādā pie dažāda mēroga projektiem visā Latvijā. Mēs ticam, ka kvalitatīvs darbs, atbildība un godīga attieksme ir pamats ilgtermiņa sadarbībai ar klientiem.
                                </p>

                                <div class="mt-8 space-y-4">
                                    <div class="flex items-start gap-3 text-sm font-medium text-[#244338]">
                                        <span class="mt-0.5 text-[#BFD730]">✓</span>
                                        <span>Pieredzējusi komanda</span>
                                    </div>
                                    <div class="flex items-start gap-3 text-sm font-medium text-[#244338]">
                                        <span class="mt-0.5 text-[#BFD730]">✓</span>
                                        <span>Darbs visā Latvijā</span>
                                    </div>
                                    <div class="flex items-start gap-3 text-sm font-medium text-[#244338]">
                                        <span class="mt-0.5 text-[#BFD730]">✓</span>
                                        <span>Reāli paveikti darbi</span>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-wrap gap-3">
                                    <span class="service-pill"><span class="service-pill__dot" />Renovācija un iekšdarbi</span>
                                    <span class="service-pill"><span class="service-pill__dot" />Fasādes un jumti</span>
                                    <span class="service-pill"><span class="service-pill__dot" />Bruģēšana</span>
                                    <span class="service-pill"><span class="service-pill__dot" />Darbs visā Latvijā</span>
                                </div>
                            </div>

                            <div data-reveal class="reveal order-1 lg:order-2">
                                <div class="relative ml-auto max-w-[620px]">
                                    <div class="absolute -left-4 -top-4 h-28 w-28 rounded-[24px] bg-[#BFD730] lg:-left-6 lg:-top-6" />
                                    <div class="relative overflow-hidden rounded-[24px] shadow-[0_24px_60px_rgba(6,64,43,0.16)]">
                                        <img
                                            :src="featurePhoto"
                                            alt="Fasādes mazgāšanas process"
                                            class="h-[420px] w-full object-cover sm:h-[520px]"
                                            width="1600"
                                            height="1200"
                                            decoding="async"
                                            loading="lazy"
                                            sizes="(min-width: 1024px) 44vw, 100vw"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    data-stats-section
                    class="border-y border-white/12 bg-[#042c1f] py-14 sm:py-15 lg:py-16"
                >
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal text-center">
                            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-white/75 sm:text-[0.82rem]">
                                MŪSU PIEREDZE
                            </p>
                        </div>

                        <div class="mt-8 grid min-h-[180px] content-center gap-y-10 sm:grid-cols-2 sm:gap-x-10 md:min-h-[196px] lg:grid-cols-4 lg:gap-x-14">
                            <div
                                v-for="(stat, index) in companyStats"
                                :key="stat.label"
                                data-reveal
                                class="reveal relative flex min-h-[80px] flex-col justify-center text-center"
                                :style="{ transitionDelay: `${index * 100}ms`, transitionDuration: '720ms' }"
                            >
                                <span
                                    v-if="index < companyStats.length - 1"
                                    class="absolute right-[-1.75rem] top-1/2 hidden h-14 w-px -translate-y-1/2 bg-white/12 lg:block"
                                />
                                <p
                                    :class="[
                                        'font-display font-extrabold leading-none tracking-normal text-white',
                                        stat.type === 'number'
                                            ? 'text-[3rem] sm:text-[3.35rem] lg:text-[3.8rem]'
                                            : 'text-[2.65rem] sm:text-[2.9rem] lg:text-[3.15rem]',
                                    ]"
                                >
                                    <template v-if="stat.type === 'number'">
                                        {{ displayedStats[index] }}{{ stat.suffix }}
                                    </template>
                                    <template v-else>
                                        {{ stat.value }}
                                    </template>
                                </p>
                                <p class="mt-3 text-sm font-medium leading-6 text-white/82 sm:text-[0.95rem]">
                                    {{ stat.label }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="services" class="bg-[#f7faf7] py-24 sm:py-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                            <div>
                                <p class="section-kicker">MŪSU PAKALPOJUMI</p>
                                <h2 class="section-title mt-4 max-w-[620px]">
                                    Būtiskākie Top Care Group virzieni
                                </h2>
                            </div>
                        </div>

                        <div class="mt-14 grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                            <article
                                v-for="(service, index) in previewServices"
                                :key="service.title"
                                data-reveal
                                :class="[
                                    'reveal service-preview-card group flex min-h-[300px] flex-col rounded-[1.9rem] border border-[#06402B]/8 bg-white p-7 shadow-[0_16px_45px_rgba(6,64,43,0.05)] transition-all duration-300',
                                    [0, 2, 4].includes(index) ? 'lg:translate-y-5' : 'lg:-translate-y-4',
                                ]"
                            >
                                <div class="service-preview-card__line" />
                                <div class="service-preview-card__icon flex h-14 w-14 items-center justify-center rounded-2xl bg-[#06402B] text-white transition-all duration-300 group-hover:bg-[#BFD730] group-hover:text-[#0f241d]">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewBox="0 0 24 24">
                                        <path :d="serviceIcons[service.icon]" />
                                    </svg>
                                </div>
                                <h3 class="mt-6 text-lg font-semibold text-[#12261f]">{{ service.title }}</h3>
                                <p class="mt-4 text-sm leading-7 text-[#5b6a63]">{{ service.description }}</p>
                            </article>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isServicesPage">
                <section class="bg-[#f7faf7] pt-36 pb-24 sm:pt-40 sm:pb-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[820px]">
                            <p class="section-kicker">MŪSU PAKALPOJUMI</p>
                            <h1 class="section-title mt-4">
                                Pakalpojumi dažāda mēroga objektiem
                            </h1>
                            <p class="mt-6 text-base leading-8 text-[#56665f]">
                                No nelieliem remonta darbiem līdz pilna cikla būvniecības un renovācijas projektiem. Katra pakalpojumu grupa ir veidota kā atsevišķa karte ar konkrētu darbu piemēriem.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <article
                                v-for="service in serviceCards"
                                :key="service.title"
                                data-reveal
                                class="reveal rounded-[1.9rem] border border-[#06402B]/8 bg-white p-7 shadow-[0_16px_45px_rgba(6,64,43,0.05)]"
                            >
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#06402B] text-white">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewBox="0 0 24 24">
                                        <path :d="serviceIcons[service.icon]" />
                                    </svg>
                                </div>
                                <h2 class="mt-6 text-2xl font-semibold text-[#12261f]">{{ service.title }}</h2>
                                <p class="mt-4 text-sm leading-7 text-[#5b6a63]">{{ service.description }}</p>
                                <ul class="mt-5 space-y-3 text-sm leading-7 text-[#56665f]">
                                    <li v-for="item in service.items" :key="item" class="flex gap-3">
                                        <span class="mt-1 text-[#BFD730]">•</span>
                                        <span>{{ item }}</span>
                                    </li>
                                </ul>
                            </article>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isAboutPage">
                <section class="bg-[#f7faf7] pt-32 pb-32 sm:pt-36 sm:pb-36 lg:pt-[7.75rem] lg:pb-[7.75rem]">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div class="grid gap-12 lg:grid-cols-[1.02fr_0.98fr] lg:items-center lg:gap-14">
                            <div data-reveal class="reveal">
                                <div class="relative">
                                    <div class="absolute -left-5 -top-5 h-24 w-24 rounded-[22px] bg-[#BFD730] sm:-left-6 sm:-top-6 sm:h-28 sm:w-28" />
                                    <div class="relative overflow-hidden rounded-[24px] shadow-[0_24px_60px_rgba(6,64,43,0.14)]">
                                        <img
                                            :src="teamPhoto"
                                            alt="Top Care Group komanda"
                                            class="h-full w-full object-cover"
                                            width="1440"
                                            height="958"
                                            decoding="async"
                                            loading="lazy"
                                            sizes="(min-width: 1024px) 48vw, 100vw"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div data-reveal class="reveal lg:pl-10">
                                <div class="flex gap-6 lg:gap-8">
                                    <span class="mt-1 hidden w-px shrink-0 bg-[#BFD730] lg:block" />
                                    <div>
                                        <p class="section-kicker">PAR MUMS</p>
                                        <h1 class="mt-4 max-w-[560px] font-display text-5xl font-bold leading-[0.96] text-[#12261f] sm:text-[3.5rem] lg:text-[4rem]">
                                            Top Care Group
                                        </h1>
                                        <div class="mt-8 max-w-[560px] space-y-4 text-base leading-8 text-[#56665f]">
                                            <p>
                                                SIA Top Care Group nodrošina būvniecības, renovācijas un īpašumu uzturēšanas pakalpojumus privātpersonām, uzņēmumiem un namu apsaimniekotājiem visā Latvijā.
                                            </p>
                                            <p>
                                                Mūsu komandu veido pieredzējuši speciālisti, kuri strādā ar precizitāti, atbildību un skaidru izpratni par kvalitāti katrā projekta posmā.
                                            </p>
                                            <p>
                                                Mēs veidojam ilgtermiņā uzticamus risinājumus un pieeju, kas pielāgota klienta objektam, termiņiem un darba apjomam.
                                            </p>
                                        </div>

                                        <div class="mt-10 grid gap-4 sm:grid-cols-2">
                                            <div
                                                v-for="item in aboutHighlights"
                                                :key="item"
                                                class="flex items-start gap-3 text-sm font-medium text-[#244338]"
                                            >
                                                <span class="mt-0.5 text-[#BFD730]">✓</span>
                                                <span>{{ item }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-10 grid gap-6 border-t border-[#06402B]/10 pt-8 sm:grid-cols-3 sm:gap-8">
                                            <div v-for="item in aboutStats" :key="item.label">
                                                <p class="font-display text-[2.15rem] font-extrabold leading-none text-[#06402B] sm:text-[2.4rem]">
                                                    {{ item.value }}
                                                </p>
                                                <p class="mt-3 text-sm font-medium leading-6 text-[#5a6b64]">
                                                    {{ item.label }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isBeforeAfterPage">
                <section class="bg-[#0d1f19] pt-36 pb-24 text-white sm:pt-40 sm:pb-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[760px]">
                            <p class="section-kicker text-[#BFD730]">PIRMS / PĒC</p>
                            <h1 class="mt-4 font-display text-4xl leading-tight sm:text-6xl">
                                Redzami rezultāti reālos objektos
                            </h1>
                            <p class="mt-6 text-base leading-8 text-white/72">
                                Salīdziniet fasāžu un jumtu darbu rezultātus pirms un pēc Top Care Group paveiktajiem darbiem.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-6 xl:grid-cols-2">
                            <article
                                v-for="item in beforeAfterItems"
                                :key="item.title"
                                data-reveal
                                class="reveal overflow-hidden rounded-[2rem] border border-white/10 bg-white/6 p-4 backdrop-blur-sm sm:p-5"
                            >
                                <h2 class="px-2 pb-4 text-2xl font-semibold">{{ item.title }}</h2>
                                <div class="overflow-hidden rounded-[1.5rem]">
                                    <img
                                        :src="item.image"
                                        :alt="item.alt"
                                        class="before-after-image h-auto min-h-[360px]"
                                        :height="item.height"
                                        :width="item.width"
                                        decoding="async"
                                        loading="lazy"
                                        sizes="(min-width: 1024px) 44vw, 100vw"
                                    />
                                </div>
                            </article>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isContactsPage">
                <section class="pt-36 pb-24 sm:pt-40 sm:pb-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div class="grid gap-8 xl:grid-cols-[0.8fr_1.2fr]">
                            <div data-reveal class="reveal">
                                <p class="section-kicker">SAZINIETIES AR MUMS</p>
                                <h1 class="section-title mt-4 max-w-[520px]">Top Care Group</h1>
                                <p class="mt-6 max-w-[520px] text-base leading-8 text-[#56665f]">
                                    Ja jums nepieciešami renovācijas, apdares, fasāžu, jumtu, bruģēšanas vai īpašumu uzturēšanas darbi, sazinieties ar mums un mēs sagatavosim piemērotu risinājumu.
                                </p>

                                <div class="mt-10 space-y-5">
                                    <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">Telefons</p>
                                        <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="tel:+37120000000">+371 20 000 000</a>
                                    </div>
                                    <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">E-pasts</p>
                                        <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="mailto:info@topcaregroup.lv">info@topcaregroup.lv</a>
                                    </div>
                                    <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5 text-sm leading-7 text-[#56665f]">
                                        <p>✔ Renovācija un apdares darbi</p>
                                        <p>✔ Fasāžu un jumtu darbi</p>
                                        <p>✔ Bruģēšana un labiekārtošana</p>
                                        <p>✔ Industriālais alpīnisms</p>
                                        <p>✔ Īpašumu uzturēšana</p>
                                    </div>
                                    <a
                                        class="inline-flex items-center justify-center rounded-[1.8rem] bg-[#BFD730] px-6 py-4 text-sm font-semibold text-[#0f241d] transition hover:-translate-y-0.5 hover:bg-[#d0ea3f]"
                                        href="https://wa.me/37120000000"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        Sazināties WhatsApp
                                    </a>
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
                                            <option v-for="service in serviceCards" :key="service.title">{{ service.title }}</option>
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
            </template>
        </main>

        <footer class="bg-[#042c1f] py-10 text-white">
            <div class="mx-auto grid max-w-[1320px] gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_0.8fr_0.8fr] lg:px-10">
                <div>
                    <div class="flex items-center gap-3">
                        <img :src="brandLogo" alt="Top Care Group logotips" class="h-10 w-auto" height="283" width="360" loading="lazy" decoding="async" />
                        <div>
                            <p class="font-display text-sm tracking-[0.28em]">TOP CARE GROUP</p>
                            <p class="text-xs uppercase tracking-[0.22em] text-white/62">Latvia</p>
                        </div>
                    </div>
                    <p class="mt-5 max-w-[420px] text-sm leading-7 text-white/64">
                        Top Care Group – no nelieliem remontdarbiem līdz pilna cikla būvniecības un renovācijas projektiem.
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Navigācija</p>
                    <div class="mt-5 flex flex-col gap-3 text-sm text-white/72">
                        <a v-for="item in navigation" :key="item.path" :href="item.path" class="transition hover:text-white">
                            {{ item.label }}
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Kontakti</p>
                    <div class="mt-5 space-y-3 text-sm leading-7 text-white/72">
                        <p>Telefons: +371 20 000 000</p>
                        <p>E-pasts: info@topcaregroup.lv</p>
                        <p>Darbi tiek veikti visā Latvijā</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-[1320px] border-t border-white/10 px-5 pt-6 text-sm text-white/44 sm:px-8 lg:px-10">
                © 2026 Top Care Group. Visas tiesības aizsargātas
            </div>
        </footer>
    </div>
</template>
