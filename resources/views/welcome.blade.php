<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Top Care Group | Строительство, ремонт и обслуживание недвижимости в Латвии</title>
        <meta
            name="description"
            content="Top Care Group - строительная компания в Латвии. Ремонт, фасадные работы, кровля, высотные работы, благоустройство и обслуживание недвижимости."
        >
        <meta
            name="keywords"
            content="строительная компания Латвия, ремонт, фасадные работы, высотные работы, благоустройство, обслуживание недвижимости"
        >
        <meta name="theme-color" content="#06402B">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Top Care Group">
        <meta
            property="og:description"
            content="Надёжный партнёр в строительстве, ремонте и обслуживании недвижимости по всей Латвии."
        >
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:locale" content="ru_RU">
        <meta name="twitter:card" content="summary_large_image">
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
