<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">
        <meta name="keywords" content="buvnieciba, renovacija, ieksdarbi, fasazu darbi, jumtu darbi, industriālais alpīnisms, brugesana, labiekartosana, ipasumu uzturesana, Latvija, Top Care Group">
        <meta name="theme-color" content="#06402B">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:locale" content="lv_LV">
        <meta name="twitter:card" content="summary_large_image">
        <link rel="canonical" href="{{ url($canonical ?? request()->getPathInfo()) }}">
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <script>
            window.TopCarePageSlug = @json($pageSlug ?? null);
            window.TopCarePageContent = @json($pageContent ?? []);
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
