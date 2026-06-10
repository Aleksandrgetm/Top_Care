<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const heroOffset = ref(0);

const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
const brandLogo = '/images/logo.png';
const teamPhoto = '/images/topcare-komanda.png';
const heroImage = '/images/fasades-mazgasana-darba-process.png';

const navigation = [
    { label: 'Sākums', path: '/' },
    { label: 'Pakalpojumi', path: '/pakalpojumi' },
    { label: 'Par mums', path: '/par-mums' },
    { label: 'Pirms / Pēc', path: '/pirms-pec' },
    { label: 'Kontakti', path: '/kontakti' },
];

const serviceCards = [
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
        title: 'Metāla jumtu tīrīšana',
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

const previewServices = computed(() => serviceCards.slice(0, 5));

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

const pageMeta = {
    '/': {
        title: 'TopCare | Fasāžu, jumtu un teritoriju tīrīšana Latvijā',
        description: 'TopCare nodrošina profesionālu fasāžu, jumtu un teritoriju tīrīšanu visā Latvijā.',
    },
    '/pakalpojumi': {
        title: 'TopCare pakalpojumi | Fasādes, jumti un bruģis',
        description: 'Apskatiet visus TopCare pakalpojumus: fasāžu, jumtu, bruģa un metāla jumtu tīrīšanu, malkas pakalpojumus un īpašumu uzturēšanu.',
    },
    '/par-mums': {
        title: 'Par TopCare | Pieredzējusi komanda visā Latvijā',
        description: 'TopCare ir Latvijas uzņēmums ar pieredzējušu komandu, kas nodrošina fasāžu, jumtu, bruģa un teritoriju tīrīšanu visā Latvijā.',
    },
    '/pirms-pec': {
        title: 'TopCare pirms un pēc | Redzami tīrīšanas rezultāti',
        description: 'Salīdziniet objektus pirms un pēc TopCare fasāžu un jumtu tīrīšanas darbiem.',
    },
    '/kontakti': {
        title: 'TopCare kontakti | Saņemt piedāvājumu',
        description: 'Sazinieties ar TopCare, lai saņemtu piedāvājumu fasāžu, jumtu, bruģa un teritoriju tīrīšanas darbiem.',
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

const closeMenu = () => {
    isMenuOpen.value = false;
};

const updateViewport = () => {
    const scrollY = window.scrollY;

    isScrolled.value = scrollY > 24;
    heroOffset.value = Math.min(scrollY, 240);
};

let revealObserver;

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
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateViewport);
    revealObserver?.disconnect();
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
                <a class="flex items-center gap-3" href="/">
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
                </a>

                <nav class="hidden items-center gap-7 lg:flex">
                    <a
                        v-for="item in navigation"
                        :key="item.path"
                        :href="item.path"
                        :class="[
                            'text-sm font-medium transition-colors duration-300 ease-in-out',
                            currentPath === item.path
                                ? isScrolled
                                    ? 'text-[#06402B]'
                                    : 'text-[#BFD730]'
                                : isScrolled
                                  ? 'text-[#244338] hover:text-[#06402B]'
                                  : 'text-white hover:text-[#BFD730]',
                        ]"
                    >
                        {{ item.label }}
                    </a>
                    <a
                        :class="[
                            'rounded-full px-5 py-3 text-sm font-semibold text-white transition-all duration-300 ease-in-out hover:-translate-y-0.5',
                            isScrolled
                                ? 'bg-[#06402B] hover:bg-[#0b5c3f] shadow-[0_10px_24px_rgba(6,64,43,0.18)]'
                                : 'bg-[#BFD730] text-[#0f241d] hover:bg-[#d0ea3f] shadow-[0_10px_24px_rgba(0,0,0,0.18)]',
                        ]"
                        href="/kontakti"
                    >
                        Saņemt piedāvājumu
                    </a>
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
                        class="mt-2 rounded-full bg-[#06402B] px-5 py-3 text-center text-sm font-semibold text-white transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
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
                        <div data-reveal class="reveal max-w-[720px]">
                            <h1 class="font-display text-4xl leading-[0.94] text-white sm:text-6xl lg:text-7xl">
                                Profesionāla fasāžu un jumtu tīrīšana visā Latvijā
                            </h1>
                            <p class="mt-6 max-w-[620px] text-base leading-8 text-white/82 sm:text-xl">
                                Atbrīvojam fasādes, jumtus un bruģi no netīrumiem, sūnām un aplikuma.
                            </p>

                            <div class="mt-8 grid gap-3 text-sm font-medium text-white/88 sm:max-w-[420px]">
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
                        <div data-reveal class="reveal grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                            <div>
                                <p class="section-kicker">Īsi par TopCare</p>
                                <h2 class="section-title mt-4 max-w-[480px]">
                                    Tīrs, lakonisks un profesionāls risinājums īpašumu sakopšanai
                                </h2>
                            </div>
                            <div class="rounded-[2rem] border border-[#06402B]/8 bg-[#f7faf7] p-8 shadow-[0_16px_45px_rgba(6,64,43,0.05)] sm:p-10">
                                <p class="text-base leading-8 text-[#56665f]">
                                    TopCare nodrošina fasāžu, jumtu, bruģa un teritoriju tīrīšanas pakalpojumus privātpersonām un uzņēmumiem. Strādājam rūpīgi, atbildīgi un ar skaidru mērķi panākt kvalitatīvu rezultātu katrā objektā.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="services" class="bg-[#f7faf7] py-24 sm:py-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                            <div>
                                <p class="section-kicker">Pakalpojumu priekšskatījums</p>
                                <h2 class="section-title mt-4 max-w-[560px]">
                                    Galvenie TopCare pakalpojumi vienuviet
                                </h2>
                            </div>
                        </div>

                        <div class="mt-14 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                            <article
                                v-for="service in previewServices"
                                :key="service.title"
                                data-reveal
                                class="reveal rounded-[1.8rem] border border-[#06402B]/8 bg-white p-6 shadow-[0_16px_45px_rgba(6,64,43,0.05)]"
                            >
                                <h3 class="text-lg font-semibold text-[#12261f]">{{ service.title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-[#5b6a63]">{{ service.description }}</p>
                            </article>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isServicesPage">
                <section class="bg-[#f7faf7] pt-36 pb-24 sm:pt-40 sm:pb-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[760px]">
                            <p class="section-kicker">Pakalpojumi</p>
                            <h1 class="section-title mt-4">
                                Visi TopCare pakalpojumi
                            </h1>
                            <p class="mt-6 text-base leading-8 text-[#56665f]">
                                Izvēlieties sev piemērotāko tīrīšanas vai uzturēšanas pakalpojumu. Katrs virziens ir veidots kā atsevišķa skaidra un saprotama karte.
                            </p>
                        </div>

                        <div class="mt-14 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <article
                                v-for="service in serviceCards"
                                :key="service.title"
                                data-reveal
                                class="reveal rounded-[1.9rem] border border-[#06402B]/8 bg-white p-7 shadow-[0_16px_45px_rgba(6,64,43,0.05)]"
                            >
                                <h2 class="text-2xl font-semibold text-[#12261f]">{{ service.title }}</h2>
                                <p class="mt-4 text-sm leading-7 text-[#5b6a63]">{{ service.description }}</p>
                            </article>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isAboutPage">
                <section class="bg-[#f7faf7] pt-36 pb-24 sm:pt-40 sm:pb-28">
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
                                <h1 class="section-title mt-4 max-w-[520px]">
                                    Par TopCare
                                </h1>
                                <p class="mt-6 text-base leading-8 text-[#56665f]">
                                    TopCare ir Latvijas uzņēmums, kas nodrošina fasāžu, jumtu, bruģa un teritoriju tīrīšanas pakalpojumus privātpersonām un uzņēmumiem. Mūsu komandu veido pieredzējuši speciālisti, kuri ikdienā strādā ar dažāda veida objektiem visā Latvijā.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isBeforeAfterPage">
                <section class="bg-[#0d1f19] pt-36 pb-24 text-white sm:pt-40 sm:pb-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[760px]">
                            <p class="section-kicker text-[#BFD730]">Pirms / Pēc</p>
                            <h1 class="mt-4 font-display text-4xl leading-tight sm:text-6xl">
                                Rezultāts, ko var redzēt
                            </h1>
                            <p class="mt-6 text-base leading-8 text-white/72">
                                Salīdziniet objektu pirms un pēc tīrīšanas darbiem.
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
                                <p class="section-kicker">Kontakti</p>
                                <h1 class="section-title mt-4 max-w-[520px]">Sazinieties ar mums</h1>
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
