@if ($seo)

    @php
        $pageTitleText = gs()->siteName(__($pageTitle));

        $finalSeoImage = $seoImage ?? getImage(getFilePath('seo') . '/' . $seo->image);

        $imagePath = parse_url($finalSeoImage, PHP_URL_PATH);
        $imageInfo = pathinfo($imagePath ?? '');
        $imageExtension = $imageInfo['extension'] ?? 'jpeg';

        $socialImageSize = explode('x', getFileSize('seo'));
        $imageWidth = $socialImageSize[0] ?? 1200;
        $imageHeight = $socialImageSize[1] ?? 630;
    @endphp

    {{-- Basic Meta --}}
    <meta name="title" content="{{ $pageTitleText }}">
    <meta name="description" content="{{ $seoContents->description ?? $seo->description }}">
    <meta name="keywords" content="{{ implode(',', $seoContents->keywords ?? $seo->keywords ?? []) }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Robots --}}
    @if (!empty($seoContents->meta_robots ?? $seo->meta_robots))
        <meta name="robots" content="{{ $seoContents->meta_robots ?? $seo->meta_robots }}">
    @endif

    {{-- Apple --}}
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $pageTitleText }}">

    {{-- Google / Schema --}}
    <meta itemprop="name" content="{{ $pageTitleText }}">
    <meta itemprop="description" content="{{ $seoContents->description ?? $seo->description }}">
    <meta itemprop="image" content="{{ $finalSeoImage }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoContents->social_title ?? $seo->social_title }}">
    <meta property="og:description" content="{{ $seoContents->social_description ?? $seo->social_description }}">
    <meta property="og:image" content="{{ $finalSeoImage }}">
    <meta property="og:image:type" content="image/{{ $imageExtension }}">
    <meta property="og:image:width" content="{{ $imageWidth }}">
    <meta property="og:image:height" content="{{ $imageHeight }}">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $finalSeoImage }}">

@endif
