<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const heroOffset = ref(0);
const brandLogo = '/images/logo.png';

const navigation = [
    { label: 'Главная', href: '#hero' },
    { label: 'Наши работы', href: '#works' },
    { label: 'Услуги', href: '#services' },
    { label: 'До / После', href: '#before-after' },
    { label: 'Контакты', href: '#contacts' },
];

const heroScenes = [
    {
        title: 'Кровельные работы',
        image:
            'https://images.unsplash.com/photo-1517581177682-a085bb7ffb15?auto=format&fit=crop&w=1200&q=80',
    },
    {
        title: 'Промышленный альпинизм',
        image:
            'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80',
    },
    {
        title: 'Фасадные работы',
        image:
            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80',
    },
    {
        title: 'Благоустройство',
        image:
            'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1200&q=80',
    },
];

const workGallery = [
    {
        title: 'Объект 01',
        category: 'Фасад',
        image:
            'https://images.unsplash.com/photo-1541976590-713941681591?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-tall',
    },
    {
        title: 'Объект 02',
        category: 'Кровля',
        image:
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-medium',
    },
    {
        title: 'Объект 03',
        category: 'Высотные работы',
        image:
            'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-medium',
    },
    {
        title: 'Объект 04',
        category: 'Благоустройство',
        image:
            'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-short',
    },
    {
        title: 'Объект 05',
        category: 'Ремонт',
        image:
            'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-tall',
    },
    {
        title: 'Объект 06',
        category: 'Фасад',
        image:
            'https://images.unsplash.com/photo-1502005097973-6a7082348e28?auto=format&fit=crop&w=1200&q=80',
        height: 'photo-medium',
    },
];

const caseStudies = [
    {
        title: 'Фасад многоквартирного дома',
        service: 'Реставрация фасадов',
        result: 'Обновили внешний контур здания, устранили дефекты покрытия и вернули аккуратный архитектурный вид.',
        image:
            'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1400&q=80',
    },
    {
        title: 'Кровельный узел коммерческого объекта',
        service: 'Кровельные и высотные работы',
        result: 'Выполнили работы на высоте, усилили проблемные участки и подготовили объект к дальнейшей эксплуатации.',
        image:
            'https://images.unsplash.com/photo-1508450859948-4e04fabaa4ea?auto=format&fit=crop&w=1400&q=80',
    },
    {
        title: 'Двор и пешеходные зоны',
        service: 'Брусчатка и благоустройство',
        result: 'Пересобрали покрытие, выстроили читаемую геометрию территории и сделали пространство визуально чище.',
        image:
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1400&q=80',
    },
];

const beforeAfter = [
    {
        title: 'Фасад до и после обновления',
        before:
            'https://images.unsplash.com/photo-1523413651479-597eb2da0ad6?auto=format&fit=crop&w=1200&q=80',
        after:
            'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1200&q=80',
    },
    {
        title: 'Покрытие территории до и после благоустройства',
        before:
            'https://images.unsplash.com/photo-1511818966892-d7d671e672a2?auto=format&fit=crop&w=1200&q=80',
        after:
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80',
    },
];

const services = [
    {
        title: 'Ремонт и внутренняя отделка',
        description: 'От квартир и коммерческих помещений до точной отделки функциональных пространств.',
    },
    {
        title: 'Реставрация фасадов',
        description: 'Обновление, защита и визуальное восстановление фасадов жилых и коммерческих объектов.',
    },
    {
        title: 'Кровельные и высотные работы',
        description: 'Безопасная работа на высоте, обслуживание сложных участков и промышленный альпинизм.',
    },
    {
        title: 'Брусчатка и благоустройство',
        description: 'Покрытия, дорожки, дворы и окружающая территория с акцентом на геометрию и долговечность.',
    },
    {
        title: 'Обслуживание недвижимости',
        description: 'Регулярный уход за объектами, сезонные работы и поддержка в эксплуатации.',
    },
];

const stats = [
    { value: 100, suffix: '+', label: 'объектов и этапов работ' },
    { value: 10, suffix: '+', label: 'лет практического опыта' },
    { value: 4, suffix: '', label: 'ключевых направления работ' },
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
                'fixed inset-x-0 top-0 z-50 transition-all duration-500',
                isScrolled
                    ? 'border-b border-[#06402B]/8 bg-white/92 shadow-[0_18px_60px_rgba(6,64,43,0.12)] backdrop-blur-xl'
                    : 'bg-transparent',
            ]"
        >
            <div class="mx-auto flex max-w-[1320px] items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <button class="flex items-center gap-3" @click="smoothScroll('#hero')">
                    <img :src="brandLogo" alt="Top Care Group" class="h-10 w-auto sm:h-11" />
                    <div class="text-left">
                        <p class="font-display text-sm tracking-[0.28em] text-[#06402B]">TOP CARE</p>
                        <p class="text-xs uppercase tracking-[0.22em] text-[#60716a]">Group Latvia</p>
                    </div>
                </button>

                <nav class="hidden items-center gap-7 lg:flex">
                    <button
                        v-for="item in navigation"
                        :key="item.href"
                        class="text-sm font-medium text-[#244338] transition hover:text-[#06402B]"
                        @click="smoothScroll(item.href)"
                    >
                        {{ item.label }}
                    </button>
                    <button
                        class="rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                        @click="smoothScroll('#contacts')"
                    >
                        Получить предложение
                    </button>
                </nav>

                <button
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[#06402B]/12 bg-white/80 text-[#06402B] lg:hidden"
                    @click="isMenuOpen = !isMenuOpen"
                >
                    <span class="sr-only">Открыть меню</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div
                v-if="isMenuOpen"
                class="border-t border-[#06402B]/8 bg-white/96 px-5 py-4 shadow-[0_18px_60px_rgba(6,64,43,0.12)] backdrop-blur-xl lg:hidden"
            >
                <div class="mx-auto flex max-w-[1320px] flex-col gap-2">
                    <button
                        v-for="item in navigation"
                        :key="item.href"
                        class="rounded-2xl px-4 py-3 text-left text-sm font-medium text-[#244338] transition hover:bg-[#f3f7f2]"
                        @click="smoothScroll(item.href)"
                    >
                        {{ item.label }}
                    </button>
                </div>
            </div>
        </header>

        <main>
            <section id="hero" class="relative isolate min-h-screen overflow-hidden bg-[#0b1a16]">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.14),transparent_30%),linear-gradient(180deg,rgba(4,16,12,0.08),rgba(4,16,12,0.78))]"
                />

                <div class="mx-auto grid min-h-screen max-w-[1320px] px-5 pb-8 pt-28 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:px-10 lg:pb-10">
                    <div class="relative z-10 flex flex-col justify-center py-8">
                        <div data-reveal class="reveal">
                            <p class="section-kicker text-[#BFD730]">Реальные направления работ</p>
                            <h1 class="font-display mt-5 max-w-[720px] text-4xl leading-[0.92] text-white sm:text-6xl lg:text-7xl">
                                Кровля, высота, фасады и благоустройство без лишней витрины
                            </h1>
                            <p class="mt-6 max-w-[560px] text-base leading-8 text-white/74 sm:text-lg">
                                Сайт теперь построен вокруг объектов и визуального результата. Больше крупных кадров, меньше корпоративной упаковки и больше ощущения реальной работы на площадке.
                            </p>
                            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                                <button
                                    class="rounded-full bg-[#BFD730] px-7 py-4 text-sm font-semibold text-[#0f241d] transition hover:-translate-y-0.5 hover:bg-[#d0ea3f]"
                                    @click="smoothScroll('#works')"
                                >
                                    Смотреть работы
                                </button>
                                <button
                                    class="rounded-full border border-white/16 bg-white/8 px-7 py-4 text-sm font-semibold text-white backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/14"
                                    @click="smoothScroll('#contacts')"
                                >
                                    Обсудить объект
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-8 lg:mt-0">
                        <div class="hero-grid" :style="heroStyle">
                            <article
                                v-for="scene in heroScenes"
                                :key="scene.title"
                                data-reveal
                                class="reveal hero-card group"
                            >
                                <img :src="scene.image" :alt="scene.title" class="hero-card__image" loading="lazy" />
                                <div class="hero-card__overlay" />
                                <div class="hero-card__caption">
                                    <span>{{ scene.title }}</span>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section id="works" class="py-24 sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="section-kicker">Наши работы</p>
                            <h2 class="section-title mt-4 max-w-[620px]">
                                Большая галерея объектов вместо длинных абзацев о компании
                            </h2>
                        </div>
                        <p class="max-w-[460px] text-base leading-8 text-[#56665f]">
                            Здесь лучше всего работают реальные фото Top Care. Секция уже перестроена под крупные кадры и кейсовое восприятие.
                        </p>
                    </div>

                    <div class="photo-masonry mt-14">
                        <article
                            v-for="item in workGallery"
                            :key="item.title"
                            data-reveal
                            :class="['reveal photo-card', item.height]"
                        >
                            <img :src="item.image" :alt="item.title" class="photo-card__image" loading="lazy" />
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
                            <p class="section-kicker">Реальные кейсы</p>
                            <h2 class="section-title mt-4 max-w-[640px]">
                                Проекты поданы как выполненные объекты, а не как новостные карточки
                            </h2>
                        </div>
                        <p class="max-w-[480px] text-base leading-8 text-[#56665f]">
                            Визуальный фокус на результате: один объект, один кадр, одна короткая суть выполненной работы.
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
                                <img :src="item.image" :alt="item.title" class="h-full w-full object-cover" loading="lazy" />
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
                            <p class="section-kicker">Услуги</p>
                            <h2 class="section-title mt-4 max-w-[460px]">
                                Основные направления без перегруженной корпоративной подачи
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

            <section id="before-after" class="bg-[#0d1f19] py-24 text-white sm:py-28">
                <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
                    <div data-reveal class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="section-kicker text-[#BFD730]">До / После</p>
                            <h2 class="mt-4 max-w-[620px] font-display text-3xl leading-tight sm:text-5xl">
                                Самый понятный формат для демонстрации эффекта выполненных работ
                            </h2>
                        </div>
                        <p class="max-w-[480px] text-base leading-8 text-white/70">
                            Здесь особенно важно заменить временные кадры на реальные фото Top Care с одинаковых ракурсов объектов.
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
                                    <div class="before-after-label">До</div>
                                    <img :src="item.before" :alt="`${item.title} до`" class="before-after-image" loading="lazy" />
                                </div>
                                <div class="overflow-hidden rounded-[1.5rem]">
                                    <div class="before-after-label before-after-label--accent">После</div>
                                    <img :src="item.after" :alt="`${item.title} после`" class="before-after-image" loading="lazy" />
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
                            <p class="section-kicker">Контакты</p>
                            <h2 class="section-title mt-4 max-w-[520px]">Если у вас есть объект, можем обсудить задачу и запросить фото сразу на старте</h2>
                            <p class="mt-6 max-w-[480px] text-base leading-8 text-[#56665f]">
                                Контактный блок стал компактнее, чтобы главный акцент страницы оставался на фотографиях выполненных работ.
                            </p>

                            <div class="mt-10 grid gap-4">
                                <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">Телефон</p>
                                    <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="tel:+37120000000">+371 20 000 000</a>
                                </div>
                                <div class="rounded-[1.8rem] border border-[#06402B]/8 bg-[#f7faf7] p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#06402B]">Email</p>
                                    <a class="mt-3 block text-lg font-semibold text-[#12261f]" href="mailto:info@topcaregroup.lv">info@topcaregroup.lv</a>
                                </div>
                            </div>
                        </div>

                        <div data-reveal class="reveal rounded-[2rem] border border-[#06402B]/8 bg-white p-6 shadow-[0_20px_65px_rgba(6,64,43,0.08)] sm:p-8">
                            <form class="grid gap-4 sm:grid-cols-2">
                                <label class="form-field">
                                    <span>Имя</span>
                                    <input placeholder="Ваше имя" type="text" />
                                </label>
                                <label class="form-field">
                                    <span>Телефон</span>
                                    <input placeholder="+371 ..." type="tel" />
                                </label>
                                <label class="form-field">
                                    <span>Email</span>
                                    <input placeholder="name@company.lv" type="email" />
                                </label>
                                <label class="form-field">
                                    <span>Услуга</span>
                                    <select>
                                        <option>Выберите услугу</option>
                                        <option>Ремонт и внутренняя отделка</option>
                                        <option>Реставрация фасадов</option>
                                        <option>Кровельные и высотные работы</option>
                                        <option>Брусчатка и благоустройство</option>
                                        <option>Обслуживание недвижимости</option>
                                    </select>
                                </label>
                                <label class="form-field sm:col-span-2">
                                    <span>Сообщение</span>
                                    <textarea placeholder="Кратко опишите объект, задачу и желаемые сроки." rows="5" />
                                </label>
                                <label class="form-field sm:col-span-2">
                                    <span>Фотография объекта</span>
                                    <input type="file" />
                                </label>
                                <div class="sm:col-span-2">
                                    <button
                                        class="inline-flex rounded-full bg-[#06402B] px-7 py-4 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]"
                                        type="button"
                                    >
                                        Отправить заявку
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
                        <img :src="brandLogo" alt="Top Care Group" class="h-10 w-auto" />
                        <div>
                            <p class="font-display text-sm tracking-[0.28em]">TOP CARE</p>
                            <p class="text-xs uppercase tracking-[0.22em] text-white/62">Group Latvia</p>
                        </div>
                    </div>
                    <p class="mt-5 max-w-[360px] text-sm leading-7 text-white/64">
                        Страница перестроена под реальную фотогалерею объектов и кейсовую подачу работ.
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Навигация</p>
                    <div class="mt-5 flex flex-col gap-3 text-sm text-white/72">
                        <button v-for="item in navigation" :key="item.href" class="text-left transition hover:text-white" @click="smoothScroll(item.href)">
                            {{ item.label }}
                        </button>
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Контакты</p>
                    <div class="mt-5 space-y-3 text-sm leading-7 text-white/72">
                        <p>Телефон: +371 20 000 000</p>
                        <p>Email: info@topcaregroup.lv</p>
                        <p>Рига, Латвия</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-[1320px] border-t border-white/10 px-5 pt-6 text-sm text-white/44 sm:px-8 lg:px-10">
                © 2026 Top Care Group. All rights reserved.
            </div>
        </footer>
    </div>
</template>
