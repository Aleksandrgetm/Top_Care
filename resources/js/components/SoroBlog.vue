<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';

const embedUrl = 'https://app.trysoro.com/api/embed/196e495d-baa3-42d0-8199-d834317c2cf0';
const container = ref(null);
const failed = ref(false);
const isArticle = ref(false);
let observer;
let metaObserver;
let pageCanonical;
let timeout;
let script;
let disposed = false;
let articleNavigationPending = false;
let navigationTimer;
let syncCanonical;

const hasPost = () => new URLSearchParams(window.location.search).has('post');
const normalizeBlogUrl = () => {
    const url = new URL(window.location.href);
    if (url.pathname !== '/blogs') return;
    // Remove only known attribution parameters; keep post and unknown options.
    for (const key of [...url.searchParams.keys()]) {
        if (/^utm_/i.test(key) || /^(gclid|dclid|fbclid|msclkid|gbraid|wbraid)$/i.test(key)) {
            url.searchParams.delete(key);
        }
    }
    if (url.href !== window.location.href) window.history.replaceState(window.history.state, '', url);
};
const prepareHistoryNavigation = () => {
    normalizeBlogUrl();
    if (hasPost()) pageCanonical?.remove();
};
const prepareArticleNavigation = (event) => {
    const link = event.target instanceof Element ? event.target.closest('a[href]') : null;
    if (link && new URL(link.href).searchParams.has('post')) {
        // MutationObserver can run between capture and Soro's target handler.
        // Do not restore the list canonical during that gap.
        articleNavigationPending = true;
        pageCanonical?.remove();
        clearTimeout(navigationTimer);
        navigationTimer = window.setTimeout(() => {
            articleNavigationPending = false;
            syncCanonical?.();
        }, 0);
    }
};

const hasContent = () => Boolean(container.value?.textContent.trim() || container.value?.querySelector('iframe, img'));
const checkContent = () => {
    const articleView = Boolean(container.value?.querySelector('.soro-blog-article, .soro-blog-loading, .soro-blog-error'));
    if (isArticle.value !== articleView) {
        isArticle.value = articleView;
        nextTick(() => {
            if (!disposed) container.value?.scrollIntoView({ behavior: 'instant', block: 'start' });
        });
    }
    if (hasContent()) {
        failed.value = false;
        clearTimeout(timeout);
    }
};
const handleError = () => { failed.value = !hasContent(); };

onMounted(async () => {
    // Let the parent finish setting page metadata before Soro captures it.
    await nextTick();
    if (disposed) return;

    normalizeBlogUrl();
    // An article response deliberately has no server canonical. Soro owns it.
    // Keep a separate list canonical ready for returning from a deep link.
    pageCanonical = document.querySelector('link[rel="canonical"]:not([data-soro])');
    if (!pageCanonical) {
        pageCanonical = document.createElement('link');
        pageCanonical.rel = 'canonical';
        pageCanonical.href = new URL('/blogs', window.location.origin).href;
    }
    syncCanonical = () => {
        if (articleNavigationPending || hasPost() || document.querySelector('link[rel="canonical"][data-soro]')) {
            if (pageCanonical.isConnected) pageCanonical.remove();
        } else if (!pageCanonical.isConnected) {
            document.head.appendChild(pageCanonical);
        }
    };
    metaObserver = new MutationObserver(syncCanonical);
    metaObserver.observe(document.head, { childList: true });
    syncCanonical();
    // Run before Soro's click/popstate handlers so the list canonical is gone
    // before Soro appends an article canonical (no transient duplicate).
    container.value.addEventListener('click', prepareArticleNavigation, true);
    window.addEventListener('popstate', prepareHistoryNavigation);

    observer = new MutationObserver(checkContent);
    observer.observe(container.value, { childList: true, subtree: true, characterData: true });
    timeout = window.setTimeout(handleError, 20000);

    script = document.querySelector(`script[src="${embedUrl}"]`);
    if (!script) {
        script = document.createElement('script');
        script.src = embedUrl;
        script.defer = true;
        script.addEventListener('error', handleError);
        document.body.appendChild(script);
    }
    checkContent();
});

onBeforeUnmount(() => {
    disposed = true;
    observer?.disconnect();
    metaObserver?.disconnect();
    container.value?.removeEventListener('click', prepareArticleNavigation, true);
    window.removeEventListener('popstate', prepareHistoryNavigation);
    clearTimeout(timeout);
    clearTimeout(navigationTimer);
    script?.removeEventListener('error', handleError);
    // Soro owns its styles, metadata and listeners. Site navigation uses full
    // document loads; do not delete vendor elements or execute the script twice.
});
</script>

<template>
    <section class="topcare-blog" :class="{ 'topcare-blog--article': isArticle }">
        <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
            <div v-show="!isArticle" class="blog-intro">
                <p class="section-kicker">TOP CARE GROUP / BLOGS</p>
                <h1 class="blog-title">Jaunumi un<br><span>noderīgi raksti</span></h1>
                <p class="blog-description">
                    Padomi, aktualitātes un noderīga informācija par būvniecību, renovāciju un īpašumu uzturēšanu.
                </p>
            </div>
            <div class="blog-embed">
                <p v-if="failed" role="status" class="mb-6 rounded-2xl bg-[#f3f7f2] p-5 text-sm text-[#244338]">
                    Rakstus pašlaik neizdevās ielādēt. Lūdzu, mēģiniet vēlreiz, pārlādējot lapu.
                    <a href="/blogs" class="font-semibold underline">Pārlādēt lapu</a>
                </p>
                <div id="soro-blog" ref="container" class="min-h-40 scroll-mt-28"></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.topcare-blog {
    background: #f7faf7;
    padding: 10rem 0 6rem;
    min-height: 75vh;
}
.blog-intro {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    column-gap: 4rem;
    align-items: end;
    padding-bottom: 3rem;
    margin-bottom: 2.5rem;
    border-bottom: 1px solid #06402b1f;
}
.blog-intro .section-kicker { grid-column: 1 / -1; margin-bottom: 1.5rem; }
.blog-title { font-size: clamp(2.6rem, 4.7vw, 4.5rem); line-height: 1.06; color: #06402b; }
.blog-title span { color: #60716a; }
.blog-description { max-width: 28rem; margin: 0 0 .3rem; font-size: 1.05rem; line-height: 1.85; color: #60716a; }
.blog-embed { min-width: 0; }
.topcare-blog--article { padding-top: 8rem; }

/* Use Soro's named presentation classes, never positional DOM selectors.
   These overrides are confined to this page; vendor markup and events stay intact. */
.blog-embed :deep(.soro-blog) { max-width: none; font-family: inherit; color: #12261f; }
.blog-embed :deep(.soro-blog-list) { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.75rem; }
.blog-embed :deep(.soro-blog-card) {
    display: flex; flex-direction: column; gap: 0; min-width: 0;
    padding: 0; overflow: hidden; border: 1px solid #06402b14;
    border-radius: 1.5rem; background: #fff;
    box-shadow: 0 6px 24px #06402b05;
    transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
}
.blog-embed :deep(.soro-blog-card-image) { width: 100%; height: auto; aspect-ratio: 16 / 10; object-fit: cover; border-radius: 0; }
.blog-embed :deep(.soro-blog-card-content) { display: flex; flex-direction: column; padding: 1.75rem; }
.blog-embed :deep(.soro-blog-card-title) { font-size: 1.4rem; font-weight: 700; line-height: 1.3; letter-spacing: -.035em; color: #06402b; margin: 0 0 1rem; }
.blog-embed :deep(.soro-blog-card-excerpt) { font-size: .95rem; line-height: 1.75; color: #60716a; margin-bottom: 1.5rem; -webkit-line-clamp: 3; }
.blog-embed :deep(.soro-blog-card-date) { margin-top: auto; font-size: .78rem; letter-spacing: .03em; color: #60716a; }
.blog-embed :deep(.soro-blog-card-content)::after {
    content: 'Lasīt rakstu ↗'; display: block; align-self: flex-start;
    margin-top: 1.25rem; padding-top: 1rem; border-top: 2px solid #bfd730;
    color: #06402b; font-size: .85rem; font-weight: 700;
}
.blog-embed :deep(.soro-blog-card:only-child) { grid-column: 1 / -1; display: grid; grid-template-columns: 1.15fr 1fr; }
.blog-embed :deep(.soro-blog-card:only-child .soro-blog-card-image) { height: 100%; min-height: 23rem; }
.blog-embed :deep(.soro-blog-card:only-child .soro-blog-card-content) { padding: clamp(1.75rem, 4vw, 3.5rem); justify-content: center; }
.blog-embed :deep(.soro-blog-card:only-child .soro-blog-card-title) { font-size: clamp(1.6rem, 2.6vw, 2.5rem); }
.blog-embed :deep(.soro-blog-card:only-child .soro-blog-card-date) { margin-top: 0; }
.blog-embed :deep(.soro-blog-back) { color: #06402b; border-color: #06402b26; border-radius: 999px; padding: .75rem 1.25rem; font-weight: 600; }
.blog-embed :deep(.soro-blog-header) { margin-bottom: 2rem; }
.topcare-blog--article .blog-embed { max-width: 840px; margin-inline: auto; }
.blog-embed :deep(.soro-blog-article-title) { font-size: clamp(2rem, 4vw, 3.5rem); line-height: 1.13; letter-spacing: -.045em; color: #06402b; margin-bottom: 1.25rem; }
.blog-embed :deep(.soro-blog-article-date) { color: #60716a; }
.blog-embed :deep(.soro-blog-article-content) { font-size: 1.0625rem; line-height: 1.85; overflow-wrap: anywhere; }
.blog-embed :deep(.soro-blog-article-content img) { border-radius: 1rem; }
.blog-embed :deep(.soro-blog-card:focus-visible),
.blog-embed :deep(.soro-blog-back:focus-visible) { outline: 3px solid #06402b; outline-offset: 5px; }
@media (hover: hover) {
    .blog-embed :deep(.soro-blog-card:hover) { transform: translateY(-4px); border-color: #06402b40; box-shadow: 0 18px 42px #06402b12; }
    .blog-embed :deep(.soro-blog-back:hover) { background: #bfd730; border-color: #bfd730; }
}
@media (max-width: 1100px) {
    .blog-embed :deep(.soro-blog-list) { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .blog-intro { column-gap: 2rem; }
}
@media (max-width: 700px) {
    .topcare-blog { padding: 8rem 0 4rem; }
    .topcare-blog--article { padding-top: 7rem; }
    .blog-intro { grid-template-columns: 1fr; gap: 1.5rem; padding-bottom: 2rem; margin-bottom: 2rem; }
    .blog-intro .section-kicker { margin-bottom: 0; font-size: .65rem; }
    .blog-description { font-size: 1rem; }
    .blog-embed :deep(.soro-blog-list) { grid-template-columns: minmax(0, 1fr); }
    .blog-embed :deep(.soro-blog-card:only-child) { display: flex; }
    .blog-embed :deep(.soro-blog-card:only-child .soro-blog-card-image) { height: auto; min-height: 0; }
    .blog-embed :deep(.soro-blog-card-content) { padding: 1.5rem; }
}
@media (prefers-reduced-motion: reduce) {
    .blog-embed :deep(.soro-blog-card) { transition: none; }
    .blog-embed :deep(.soro-blog-card:hover) { transform: none; }
}
</style>
