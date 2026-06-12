<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const heroOffset = ref(0);
const showCookieBanner = ref(false);
const showCookiePreferences = ref(false);
const cookiePreferences = ref({
    analytics: false,
    marketing: false,
});
const savedCookieConsent = ref(null);

const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
const brandLogo = '/images/logo.png';
const teamPhoto = '/images/topcare-komanda.png';
const heroImage = '/images/fasades-mazgasana-darba-process.png';
const featurePhoto = '/images/fasades-mazgasana-darba-process.png';
const beforeAfterHeroPhoto = '/images/jumta-tirisana-latvija.jpg';
const privacyPolicyPath = '/privatuma-politika';
const cookieConsentStorageKey = 'topcare_cookie_consent';
const defaultCookieConsent = {
    necessary: true,
    analytics: false,
    marketing: false,
    acceptedAt: null,
};

const privacySections = [
    {
        number: '1.',
        title: 'Vispārīga informācija',
        paragraphs: [
            'SIA “Top Care Group” (turpmāk – Uzņēmums) rūpējas par Jūsu personas datu aizsardzību un apstrādā personas datus saskaņā ar Eiropas Parlamenta un Padomes Regulu (ES) 2016/679 (Vispārīgā datu aizsardzības regula jeb GDPR) un Latvijas Republikas normatīvajiem aktiem.',
        ],
        contactCard: true,
    },
    {
        number: '2.',
        title: 'Kādus personas datus mēs apstrādājam',
        intro: 'Mēs varam apstrādāt šādus personas datus:',
        items: [
            'vārdu un uzvārdu;',
            'tālruņa numuru;',
            'e-pasta adresi;',
            'objekta adresi;',
            'informāciju par pieprasītajiem pakalpojumiem;',
            'sarakstes saturu;',
            'informāciju, kuru Jūs brīvprātīgi iesniedzat, aizpildot kontaktformu vai sazinoties ar mums.',
        ],
    },
    {
        number: '3.',
        title: 'Personas datu apstrādes mērķi',
        intro: 'Personas dati tiek apstrādāti, lai:',
        items: [
            'sniegtu informāciju par pakalpojumiem;',
            'sagatavotu piedāvājumus un tāmes;',
            'noslēgtu un izpildītu līgumus;',
            'nodrošinātu pakalpojumu sniegšanu;',
            'izrakstītu rēķinus;',
            'izpildītu normatīvajos aktos noteiktās prasības;',
            'uzlabotu mājaslapas darbību un lietotāju pieredzi.',
        ],
    },
    {
        number: '4.',
        title: 'Personas datu glabāšana',
        paragraphs: [
            'Personas dati tiek glabāti tikai tik ilgi, cik tas nepieciešams attiecīgo mērķu sasniegšanai vai cik to paredz Latvijas Republikas normatīvie akti.',
        ],
    },
    {
        number: '5.',
        title: 'Personas datu nodošana trešajām personām',
        intro: 'Uzņēmums nenodod personas datus trešajām personām, izņemot gadījumus, kad:',
        items: [
            'to paredz normatīvie akti;',
            'tas nepieciešams grāmatvedības, IT vai citu pakalpojumu nodrošinātāju darbībai;',
            'informāciju pieprasa kompetentas valsts vai pašvaldību iestādes.',
        ],
    },
    {
        number: '6.',
        title: 'Personas datu drošība',
        paragraphs: [
            'SIA “Top Care Group” veic atbilstošus organizatoriskus un tehniskus drošības pasākumus, lai aizsargātu personas datus pret nesankcionētu piekļuvi, izpaušanu, nozaudēšanu vai iznīcināšanu.',
        ],
    },
    {
        number: '7.',
        title: 'Sīkdatnes (Cookies)',
        paragraphs: [
            'Mājaslapā var tikt izmantotas sīkdatnes, lai nodrošinātu tās darbību, analizētu lietotāju paradumus un uzlabotu vietnes funkcionalitāti.',
            'Lietotājs jebkurā laikā var mainīt sīkdatņu iestatījumus savā interneta pārlūkprogrammā.',
        ],
    },
    {
        number: '8.',
        title: 'Jūsu tiesības',
        intro: 'Jums ir tiesības:',
        items: [
            'pieprasīt informāciju par savu personas datu apstrādi;',
            'pieprasīt datu labošanu;',
            'pieprasīt datu dzēšanu, ja tam ir tiesisks pamats;',
            'ierobežot datu apstrādi;',
            'iebilst pret datu apstrādi;',
            'atsaukt piekrišanu datu apstrādei;',
            'iesniegt sūdzību Datu valsts inspekcijā.',
        ],
    },
];

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

const companyValues = [
    {
        title: 'Kvalitāte',
        description: 'Strādājam rūpīgi un koncentrējamies uz ilgtermiņa rezultātu.',
    },
    {
        title: 'Atbildība',
        description: 'Ievērojam termiņus un uzņemamies atbildību par paveikto darbu.',
    },
    {
        title: 'Uzticēšanās',
        description: 'Veidojam ilgtermiņa sadarbību ar klientiem visā Latvijā.',
    },
];

const workApproachSteps = [
    {
        number: '01',
        title: 'Objekta novērtēšana',
        description: 'Bezmaksas konsultācija un apskate',
    },
    {
        number: '02',
        title: 'Piedāvājuma sagatavošana',
        description: 'Precīzs darbu plāns un izmaksas',
    },
    {
        number: '03',
        title: 'Darbu izpilde',
        description: 'Kvalitatīva un savlaicīga realizācija',
    },
    {
        number: '04',
        title: 'Rezultāta nodošana klientam',
        description: 'Objekta pārbaude un nodošana klientam',
    },
];

const beforeAfterItems = [
    {
        title: 'Fasādes mazgāšana',
        description: 'Privātmājas fasādes attīrīšana no netīrumiem un bioloģiskā aplikuma.',
        image: '/images/fasada-mazgasana-pirms-pec.png',
        width: 640,
        height: 640,
        alt: 'Fasādes mazgāšana pirms un pēc',
    },
    {
        title: 'Jumta tīrīšana',
        description: 'Jumta seguma attīrīšana no nosēdumiem, sūnām un uzkrātajiem netīrumiem.',
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
        canonical: '/',
    },
    '/pakalpojumi': {
        title: 'Top Care Group pakalpojumi | Renovācija, fasādes, jumti un labiekārtošana',
        description: 'Apskatiet Top Care Group pakalpojumus: renovācijas un iekšdarbus, fasāžu atjaunošanu, jumtu darbus, bruģēšanu un īpašumu uzturēšanu.',
        canonical: '/pakalpojumi',
    },
    '/par-mums': {
        title: 'Par Top Care Group | Pieredzējusi komanda visā Latvijā',
        description: 'Top Care Group ir Latvijas uzņēmums ar pieredzējušu komandu, kas strādā pie būvniecības, renovācijas un īpašumu uzturēšanas projektiem visā Latvijā.',
        canonical: '/par-mums',
    },
    '/pirms-pec': {
        title: 'Top Care Group pirms un pēc | Redzami rezultāti objektos',
        description: 'Apskatiet redzamus fasāžu un jumtu darbu rezultātus pirms un pēc Top Care Group paveiktajiem darbiem.',
        canonical: '/pirms-pec',
    },
    '/kontakti': {
        title: 'Top Care Group kontakti | Sazinieties un saņemiet piedāvājumu',
        description: 'Sazinieties ar Top Care Group par būvniecības, renovācijas, jumtu, fasāžu un īpašumu uzturēšanas darbiem visā Latvijā.',
        canonical: '/kontakti',
    },
    '/privatuma-politika': {
        title: 'Privātuma politika | Top Care Group',
        description: 'Privātuma politika un informācija par personas datu apstrādi uzņēmumā Top Care Group.',
        canonical: '/privatuma-politika',
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
const isPrivacyPage = computed(() => currentPath === privacyPolicyPath);
const useLightHeader = computed(() => isHomePage.value && !isScrolled.value);

const readCookieConsent = () => {
    try {
        const storedConsent = window.localStorage.getItem(cookieConsentStorageKey);

        if (!storedConsent) {
            return null;
        }

        const parsedConsent = JSON.parse(storedConsent);

        return {
            necessary: true,
            analytics: Boolean(parsedConsent.analytics),
            marketing: Boolean(parsedConsent.marketing),
            acceptedAt: parsedConsent.acceptedAt ?? null,
        };
    } catch {
        return null;
    }
};

const syncCookiePreferenceDraft = (consent = defaultCookieConsent) => {
    cookiePreferences.value = {
        analytics: Boolean(consent.analytics),
        marketing: Boolean(consent.marketing),
    };
};

const lockScrollForCookieModal = (locked) => {
    document.body.style.overflow = locked ? 'hidden' : '';
};

const persistCookieConsent = (consent) => {
    const normalizedConsent = {
        necessary: true,
        analytics: Boolean(consent.analytics),
        marketing: Boolean(consent.marketing),
        acceptedAt: new Date().toISOString(),
    };

    window.localStorage.setItem(cookieConsentStorageKey, JSON.stringify(normalizedConsent));
    savedCookieConsent.value = normalizedConsent;
    syncCookiePreferenceDraft(normalizedConsent);
    showCookieBanner.value = false;
    showCookiePreferences.value = false;
    lockScrollForCookieModal(false);
};

const hasAnalyticsConsent = () => Boolean(savedCookieConsent.value?.analytics);
const hasMarketingConsent = () => Boolean(savedCookieConsent.value?.marketing);

const openCookiePreferences = () => {
    syncCookiePreferenceDraft(savedCookieConsent.value ?? defaultCookieConsent);
    showCookiePreferences.value = true;
    lockScrollForCookieModal(true);
};

const acceptAllCookies = () => {
    persistCookieConsent({
        necessary: true,
        analytics: true,
        marketing: true,
    });
};

const rejectAllCookies = () => {
    persistCookieConsent({
        necessary: true,
        analytics: false,
        marketing: false,
    });
};

const saveCookiePreferences = () => {
    persistCookieConsent({
        necessary: true,
        analytics: cookiePreferences.value.analytics,
        marketing: cookiePreferences.value.marketing,
    });
};

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
    savedCookieConsent.value = readCookieConsent();
    syncCookiePreferenceDraft(savedCookieConsent.value ?? defaultCookieConsent);
    showCookieBanner.value = !savedCookieConsent.value;
    window.TopCareCookieConsent = {
        hasAnalyticsConsent,
        hasMarketingConsent,
        openCookiePreferences,
    };

    const meta = pageMeta[currentPath];

    if (meta) {
        document.title = meta.title;

        const descriptionTag = document.querySelector('meta[name="description"]');
        const ogDescriptionTag = document.querySelector('meta[property="og:description"]');
        const ogTitleTag = document.querySelector('meta[property="og:title"]');
        const canonicalTag = document.querySelector('link[rel="canonical"]');

        descriptionTag?.setAttribute('content', meta.description);
        ogDescriptionTag?.setAttribute('content', meta.description);
        ogTitleTag?.setAttribute('content', meta.title);
        canonicalTag?.setAttribute('href', new URL(meta.canonical, window.location.origin).toString());
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
    lockScrollForCookieModal(false);
    delete window.TopCareCookieConsent;
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
                                    href="https://wa.me/37128842265"
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

                <section class="bg-white py-24 sm:py-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[620px]">
                            <p class="section-kicker">MŪSU VĒRTĪBAS</p>
                            <h2 class="section-title mt-4">Principi, uz kuriem balstām katru projektu</h2>
                        </div>

                        <div class="mt-12 grid gap-y-10 border-y border-[#06402B]/10 py-8 lg:grid-cols-3 lg:gap-x-10 lg:py-10">
                            <div
                                v-for="(value, index) in companyValues"
                                :key="value.title"
                                data-reveal
                                class="reveal relative lg:px-8"
                                :style="{ transitionDelay: `${index * 80}ms` }"
                            >
                                <span
                                    v-if="index < companyValues.length - 1"
                                    class="absolute right-0 top-1/2 hidden h-16 w-px -translate-y-1/2 bg-[#06402B]/10 lg:block"
                                />
                                <p class="text-[1.7rem] font-semibold text-[#12261f]">
                                    {{ value.title }}
                                </p>
                                <p class="mt-5 max-w-[19rem] text-base leading-8 text-[#5a6b64]">
                                    {{ value.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-[#f4f7f4] py-24 sm:py-28">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div data-reveal class="reveal max-w-[620px]">
                            <p class="section-kicker">MŪSU PIEEJA DARBAM</p>
                            <h2 class="section-title mt-4">Skaidrs process no pirmās sarunas līdz rezultāta nodošanai</h2>
                        </div>

                        <div class="relative mt-14">
                            <span class="absolute left-0 right-0 top-5 hidden h-px bg-[#06402B]/18 xl:block" />
                            <div class="grid gap-y-10 md:grid-cols-2 md:gap-x-10 xl:grid-cols-4 xl:gap-x-0">
                            <div
                                v-for="(step, index) in workApproachSteps"
                                :key="step.number"
                                data-reveal
                                class="reveal relative xl:pr-10"
                                :style="{ transitionDelay: `${index * 110}ms`, transitionDuration: '760ms' }"
                            >
                                <div class="relative pb-1">
                                    <span class="absolute left-0 right-0 top-5 h-px bg-[#06402B]/18 xl:right-10" />
                                    <span class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-[#06402B]/14 bg-white text-[0.95rem] font-semibold text-[#06402B]">
                                        <span class="sr-only">Solis</span>
                                    </span>
                                </div>
                                <span
                                    v-if="index < workApproachSteps.length - 1"
                                    class="absolute right-0 top-5 hidden h-px w-10 bg-[#06402B]/18 xl:block"
                                />
                                <p class="mt-7 font-display text-[2.3rem] font-extrabold leading-none tracking-[0.04em] text-[#06402B] sm:text-[2.55rem]">
                                    {{ step.number }}
                                </p>
                                <h3 class="mt-4 max-w-[220px] text-[1.55rem] font-semibold leading-tight text-[#12261f]">
                                    {{ step.title }}
                                </h3>
                                <p class="mt-4 max-w-[240px] text-sm leading-7 text-[#5a6b64]">
                                    {{ step.description }}
                                </p>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-else-if="isBeforeAfterPage">
                <section class="bg-[#f7faf7] pt-32 pb-10 sm:pt-36 sm:pb-12">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div class="grid gap-10 lg:grid-cols-[0.82fr_1.18fr] lg:items-center lg:gap-12">
                            <div data-reveal class="reveal max-w-[640px]">
                                <p class="section-kicker">PIRMS / PĒC</p>
                                <h1 class="mt-4 font-display text-4xl font-bold leading-[0.96] text-[#12261f] sm:text-6xl lg:text-[4.2rem]">
                                    Redzami rezultāti
                                    <br />
                                    reālos objektos
                                </h1>
                                <p class="mt-6 max-w-[620px] text-base leading-8 text-[#5a6b64] sm:text-lg">
                                    Salīdziniet mūsu paveikto darbu rezultātus fasāžu, jumtu un teritoriju tīrīšanā visā Latvijā.
                                </p>
                            </div>

                            <div data-reveal class="reveal">
                                <div class="relative overflow-hidden rounded-[24px] border border-[#06402B]/8 bg-white shadow-[0_24px_55px_rgba(6,64,43,0.08)]">
                                    <div class="absolute inset-y-0 left-0 z-10 hidden w-32 bg-gradient-to-r from-[#f7faf7] via-[#f7faf7]/65 to-transparent lg:block" />
                                    <img
                                        :src="beforeAfterHeroPhoto"
                                        alt="Jumta tīrīšanas objekts Latvijā"
                                        class="h-[380px] w-full object-cover sm:h-[460px]"
                                        width="1600"
                                        height="1200"
                                        decoding="async"
                                        loading="lazy"
                                        sizes="(min-width: 1024px) 52vw, 100vw"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-[#f7faf7] pb-24 pt-12 sm:pb-28 sm:pt-14">
                    <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                        <div class="space-y-8 lg:space-y-10">
                            <article
                                v-for="(item, index) in beforeAfterItems"
                                :key="item.title"
                                data-reveal
                                class="reveal overflow-hidden rounded-[24px] border border-[#06402B]/8 bg-white shadow-[0_18px_40px_rgba(6,64,43,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(6,64,43,0.08)]"
                                :style="{ transitionDelay: `${index * 90}ms` }"
                            >
                                <div
                                    :class="[
                                        'grid gap-8 p-5 sm:p-6 lg:items-center lg:gap-10 lg:p-8',
                                        index % 2 === 0 ? 'lg:grid-cols-[1.15fr_0.85fr]' : 'lg:grid-cols-[0.85fr_1.15fr]',
                                    ]"
                                >
                                    <div
                                        :class="[
                                            'overflow-hidden rounded-[1.5rem] border border-[#06402B]/8 bg-[#edf3ef]',
                                            index % 2 === 0 ? 'lg:order-1' : 'lg:order-2',
                                        ]"
                                    >
                                        <img
                                            :src="item.image"
                                            :alt="item.alt"
                                            class="before-after-image h-auto min-h-[360px]"
                                            :height="item.height"
                                            :width="item.width"
                                            decoding="async"
                                            loading="lazy"
                                            sizes="(min-width: 1024px) 50vw, 100vw"
                                        />
                                    </div>

                                    <div
                                        :class="[
                                            'flex flex-col justify-center',
                                            index % 2 === 0 ? 'lg:order-2' : 'lg:order-1 lg:pl-4',
                                        ]"
                                    >
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#06402B]/55">
                                            Top Care Group
                                        </p>
                                        <h2 class="mt-5 text-[2rem] font-semibold leading-tight text-[#12261f] sm:text-[2.2rem]">
                                            {{ item.title }}
                                        </h2>
                                        <p class="mt-5 max-w-[420px] text-base leading-8 text-[#5a6b64]">
                                            {{ item.description }}
                                        </p>
                                    </div>
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
                                        <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="tel:+37128842265">+371 28 842 265</a>
                                    </div>
                                    <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">E-pasts</p>
                                        <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="mailto:topcare.lv@gmail.com">topcare.lv@gmail.com</a>
                                    </div>
                                    <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5 text-sm leading-7 text-[#56665f]">
                                        <p>✔ Renovācija un apdares darbi</p>
                                        <p>✔ Fasāžu un jumtu darbi</p>
                                        <p>✔ Bruģēšana un labiekārtošana</p>
                                        <p>✔ Industriālais alpīnisms</p>
                                        <p>✔ Īpašumu uzturēšana</p>
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
                                            <option v-for="service in serviceCards" :key="service.title">{{ service.title }}</option>
                                        </select>
                                    </label>
                                    <label class="form-field sm:col-span-2">
                                        <span>Ziņojums</span>
                                        <textarea placeholder="Aprakstiet objektu vai nepieciešamos darbus" rows="5" />
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

            <template v-else-if="isPrivacyPage">
                <section class="bg-[#f7faf7] pt-32 pb-3 sm:pt-36 sm:pb-4">
                    <div class="mx-auto max-w-[1200px] px-5 sm:px-8">
                        <div data-reveal class="reveal rounded-[2rem] border border-[#06402B]/8 bg-white px-6 py-9 shadow-[0_18px_50px_rgba(6,64,43,0.05)] sm:px-10 sm:py-10 lg:px-14 lg:py-11">
                            <p class="section-kicker">PRIVĀTUMA POLITIKA</p>
                            <h1 class="section-title mt-4">Privātuma politika</h1>
                            <p class="mt-5 max-w-[760px] text-base leading-8 text-[#56665f] sm:text-lg">
                                Informācija par personas datu apstrādi un aizsardzību.
                            </p>
                            <div class="mt-8 h-px w-full bg-[#06402B]/10" />
                            <p class="mt-5 text-xs font-medium uppercase tracking-[0.14em] text-[#06402B]/50 sm:text-[0.82rem]">
                                Pēdējo reizi atjaunināta: 12.06.2026.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="bg-[#f7faf7] pb-24 pt-8 sm:pb-28 sm:pt-10">
                    <div class="mx-auto max-w-[1200px] px-5 sm:px-8">
                        <div class="rounded-[2rem] border border-[#06402B]/8 bg-white px-6 py-8 shadow-[0_18px_50px_rgba(6,64,43,0.05)] sm:px-10 sm:py-10 lg:px-14 lg:py-12">
                            <div class="text-[#42534c]">
                                <section data-reveal class="reveal">
                                    <div class="space-y-0">
                                        <article
                                            v-for="(section, index) in privacySections"
                                            :key="section.title"
                                            :class="[
                                                'py-10 sm:py-12',
                                                index < privacySections.length - 1 ? 'border-b border-[#06402B]/10' : '',
                                            ]"
                                        >
                                            <div class="min-w-0">
                                                <h2 class="text-[1.65rem] font-semibold leading-tight text-[#12261f] sm:text-[2rem]">
                                                    {{ section.number }} {{ section.title }}
                                                </h2>

                                                <div v-if="section.paragraphs" class="mt-5 space-y-4">
                                                    <p
                                                        v-for="paragraph in section.paragraphs"
                                                        :key="paragraph"
                                                        class="max-w-[980px] text-base leading-8 text-[#42534c] sm:text-[1.05rem]"
                                                    >
                                                        {{ paragraph }}
                                                    </p>
                                                </div>

                                                <p v-if="section.intro" class="mt-5 max-w-[980px] text-base leading-8 text-[#42534c] sm:text-[1.05rem]">
                                                    {{ section.intro }}
                                                </p>

                                                <ul v-if="section.items" class="mt-5 space-y-3">
                                                    <li
                                                        v-for="item in section.items"
                                                        :key="item"
                                                        class="flex items-start gap-3 text-base leading-8 text-[#42534c] sm:text-[1.05rem]"
                                                    >
                                                        <span class="mt-3 h-1.5 w-1.5 shrink-0 rounded-full bg-[#06402B]" />
                                                        <span>{{ item }}</span>
                                                    </li>
                                                </ul>

                                                <div
                                                    v-if="section.contactCard && section.title === 'Vispārīga informācija'"
                                                    class="mt-7 rounded-[20px] border border-[#06402B]/8 bg-[#f4f7f4] p-5 sm:p-6"
                                                >
                                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#06402B]/65">Datu pārzinis</p>
                                                    <div class="mt-4 space-y-2 text-base leading-8 text-[#244338] sm:text-[1.05rem]">
                                                        <p class="font-semibold text-[#12261f]">SIA “Top Care Group”</p>
                                                        <p>Reģ. Nr. 40203667648</p>
                                                        <p>PVN Nr. LV40203667648</p>
                                                        <p>Juridiskā adrese: Raiņa iela 79-48, Jūrmala, LV-2011</p>
                                                        <p>
                                                            Tālrunis:
                                                            <a class="font-semibold text-[#06402B] transition hover:text-[#0b5c3f]" href="tel:+37128842265">+371 28842265</a>
                                                        </p>
                                                        <p>
                                                            E-pasts:
                                                            <a class="font-semibold text-[#06402B] transition hover:text-[#0b5c3f]" href="mailto:topcare.lv@gmail.com">topcare.lv@gmail.com</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>

                                        <article class="border-t border-[#06402B]/10 py-10 sm:py-12">
                                            <h2 class="text-[1.65rem] font-semibold leading-tight text-[#12261f] sm:text-[2rem]">9. Kontaktinformācija</h2>
                                            <p class="mt-5 max-w-[980px] text-base leading-8 text-[#42534c] sm:text-[1.05rem]">
                                                Ja Jums rodas jautājumi par personas datu apstrādi, lūdzu, sazinieties ar:
                                            </p>
                                            <div class="mt-7 rounded-[20px] border border-[#06402B]/8 bg-[#f4f7f4] p-5 sm:p-6">
                                                <div class="space-y-3 text-base leading-8 text-[#244338] sm:text-[1.05rem]">
                                                    <p class="font-semibold text-[#12261f]">SIA “Top Care Group”</p>
                                                    <p>Reģ. Nr. 40203667648</p>
                                                    <p>Raiņa iela 79-48, Jūrmala, LV-2011</p>
                                                    <p>
                                                        Tālrunis:
                                                        <a class="font-semibold text-[#06402B] transition hover:text-[#0b5c3f]" href="tel:+37128842265">+371 28842265</a>
                                                    </p>
                                                    <p>
                                                        E-pasts:
                                                        <a class="font-semibold text-[#06402B] transition hover:text-[#0b5c3f]" href="mailto:topcare.lv@gmail.com">topcare.lv@gmail.com</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <div data-reveal class="reveal mt-8 rounded-[24px] border border-[#BFD730]/30 bg-[#edf7d3] px-6 py-5 text-base leading-8 text-[#244338] sm:mt-10 sm:px-8 sm:py-6 sm:text-[1.05rem]">
                            Izmantojot mājaslapu, Jūs apliecināt, ka esat iepazinies ar šo privātuma politiku un piekrītat tajā noteiktajiem nosacījumiem.
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
                        <p>Telefons: +371 28 842 265</p>
                        <p>E-pasts: topcare.lv@gmail.com</p>
                        <p>Darbi tiek veikti visā Latvijā</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 flex max-w-[1320px] flex-wrap items-center gap-x-2 gap-y-3 border-t border-white/10 px-5 pt-6 text-sm text-white/44 sm:px-8 lg:px-10">
                <span>© 2026 Top Care Group. Visas tiesības aizsargātas</span>
                <span aria-hidden="true">·</span>
                <a
                    :class="[
                        'transition',
                        isPrivacyPage ? 'text-white nav-link nav-link--active' : 'text-white/64 hover:text-white',
                    ]"
                    :href="privacyPolicyPath"
                >
                    Privātuma politika
                </a>
                <span aria-hidden="true">·</span>
                <button
                    class="cursor-pointer text-white/64 transition hover:text-white"
                    type="button"
                    @click="openCookiePreferences"
                >
                    Sīkdatņu iestatījumi
                </button>
                <span aria-hidden="true">·</span>
                <a
                    class="text-white/36 transition hover:text-white/52"
                    href="https://getmanenko.lv"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Izstrādāja Getmanenko
                </a>
            </div>
        </footer>

        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="translate-y-5 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-5 opacity-0"
        >
            <div v-if="showCookieBanner" class="fixed inset-x-0 bottom-0 z-[70] px-4 pb-4 sm:px-6 sm:pb-6">
                <div class="mx-auto w-full max-w-[1180px] rounded-[24px] border border-[rgba(0,60,40,0.10)] bg-white px-5 py-5 text-[#12261f] shadow-[0_24px_70px_rgba(0,40,25,0.14)] sm:px-7 sm:py-6">
                    <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <p class="text-[0.72rem] font-semibold uppercase tracking-[0.26em] text-[#06402B]/62">
                                SĪKDATŅU PAZIŅOJUMS
                            </p>
                            <p class="mt-3 max-w-[760px] text-sm leading-7 text-[#244338] sm:text-base">
                                Mēs izmantojam sīkdatnes, lai uzlabotu vietnes darbību, analizētu apmeklējumu un nodrošinātu labāku lietošanas pieredzi.
                            </p>
                            <a
                                class="mt-3 inline-flex text-sm font-semibold text-[#06402B] underline decoration-[#BFD730] decoration-2 underline-offset-4 transition hover:text-[#0b5c3f]"
                                :href="privacyPolicyPath"
                            >
                                Privātuma politika
                            </a>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center lg:justify-end">
                            <button
                                class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#06402B] bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                                type="button"
                                @click="acceptAllCookies"
                            >
                                Pieņemt visas
                            </button>
                            <button
                                class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#06402B]/24 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:border-[#06402B]/40 hover:bg-[#f7faf7]"
                                type="button"
                                @click="rejectAllCookies"
                            >
                                Noraidīt
                            </button>
                            <button
                                class="inline-flex min-h-12 items-center justify-center text-sm font-semibold text-[#06402B] decoration-[#BFD730] decoration-2 underline-offset-4 transition hover:text-[#0b5c3f] hover:underline sm:px-2"
                                type="button"
                                @click="openCookiePreferences"
                            >
                                Sīkāka informācija
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showCookiePreferences" class="fixed inset-0 z-[80] flex items-end justify-center bg-[rgba(10,30,22,0.35)] p-4 backdrop-blur-md sm:items-center sm:p-6">
                <div class="max-h-[92vh] w-full max-w-[860px] overflow-y-auto rounded-[28px] border border-[rgba(0,60,40,0.10)] bg-white p-6 text-[#12261f] shadow-[0_40px_100px_rgba(0,40,25,0.18)] sm:p-10">
                    <div class="border-b border-[#06402B]/10 pb-6">
                        <p class="text-[0.72rem] font-semibold uppercase tracking-[0.26em] text-[#06402B]/62">
                            PIEKRIŠANAS IESTATĪJUMI
                        </p>
                        <h2 class="mt-3 text-[1.75rem] font-semibold leading-tight text-[#12261f] sm:text-[2.25rem]">
                            Pārvaldiet sīkdatņu kategorijas
                        </h2>
                        <p class="mt-4 max-w-[680px] text-sm leading-7 text-[#42534c] sm:text-base">
                            Jūs jebkurā brīdī varat izvēlēties, kurām papildu sīkdatnēm piekrītat. Nepieciešamās sīkdatnes vienmēr paliek aktīvas.
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-[20px] border border-[rgba(0,60,40,0.08)] bg-[#F4F7F4] px-5 py-5 sm:px-6 sm:py-[22px]">
                            <div class="flex items-start justify-between gap-5">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#12261f]">Nepieciešamās sīkdatnes</h3>
                                    <p class="mt-2 max-w-[600px] text-sm leading-7 text-[#42534c] sm:text-base">
                                        Šīs sīkdatnes nodrošina vietnes pamatfunkcijas un vienmēr ir aktīvas.
                                    </p>
                                </div>
                                <span class="cookie-toggle cookie-toggle--on cookie-toggle--disabled" aria-hidden="true">
                                    <span class="cookie-toggle__thumb" />
                                </span>
                            </div>
                        </div>

                        <div class="rounded-[20px] border border-[rgba(0,60,40,0.08)] bg-[#F4F7F4] px-5 py-5 sm:px-6 sm:py-[22px]">
                            <div class="flex items-start justify-between gap-5">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#12261f]">Analītikas sīkdatnes</h3>
                                    <p class="mt-2 max-w-[600px] text-sm leading-7 text-[#42534c] sm:text-base">
                                        Palīdz saprast, kā apmeklētāji izmanto vietni, lai varētu uzlabot saturu un darbību.
                                    </p>
                                </div>
                                <button
                                    :class="['cookie-toggle', cookiePreferences.analytics ? 'cookie-toggle--on' : 'cookie-toggle--off']"
                                    :aria-pressed="cookiePreferences.analytics"
                                    type="button"
                                    @click="cookiePreferences.analytics = !cookiePreferences.analytics"
                                >
                                    <span class="sr-only">Pārslēgt analītikas sīkdatnes</span>
                                    <span class="cookie-toggle__thumb" />
                                </button>
                            </div>
                        </div>

                        <div class="rounded-[20px] border border-[rgba(0,60,40,0.08)] bg-[#F4F7F4] px-5 py-5 sm:px-6 sm:py-[22px]">
                            <div class="flex items-start justify-between gap-5">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#12261f]">Mārketinga sīkdatnes</h3>
                                    <p class="mt-2 max-w-[600px] text-sm leading-7 text-[#42534c] sm:text-base">
                                        Tiek izmantotas personalizētai saziņai un turpmākām reklāmas integrācijām pēc piekrišanas.
                                    </p>
                                </div>
                                <button
                                    :class="['cookie-toggle', cookiePreferences.marketing ? 'cookie-toggle--on' : 'cookie-toggle--off']"
                                    :aria-pressed="cookiePreferences.marketing"
                                    type="button"
                                    @click="cookiePreferences.marketing = !cookiePreferences.marketing"
                                >
                                    <span class="sr-only">Pārslēgt mārketinga sīkdatnes</span>
                                    <span class="cookie-toggle__thumb" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:justify-end">
                        <button
                            class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#06402B]/24 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:border-[#06402B]/40 hover:bg-[#f7faf7]"
                            type="button"
                            @click="rejectAllCookies"
                        >
                            Noraidīt
                        </button>
                        <button
                            class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#06402B]/24 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:border-[#06402B]/40 hover:bg-[#f7faf7]"
                            type="button"
                            @click="saveCookiePreferences"
                        >
                            Saglabāt iestatījumus
                        </button>
                        <button
                            class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#06402B] bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                            type="button"
                            @click="acceptAllCookies"
                        >
                            Pieņemt visas
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
