@if ($seo)

    @php
        /*
        |--------------------------------------------------------------------------
        | Resolve Page Title Safely
        |--------------------------------------------------------------------------
        */
        $rawTitle =
            $pageTitle
            ?? trim($__env->yieldContent('title'))
            ?? $seoContents->title
            ?? $seo->title
            ?? '';

        $pageTitleText = $rawTitle
            ? gs()->siteName(__($rawTitle))
            : gs()->siteName();

        /*
        |--------------------------------------------------------------------------
        | Resolve SEO Image
        |--------------------------------------------------------------------------
        */
        $finalSeoImage = $seoImage
            ?? getImage(getFilePath('seo') . '/' . $seo->image);

        $imagePath = parse_url($finalSeoImage, PHP_URL_PATH);
        $imageInfo = pathinfo($imagePath ?? '');
        $imageExtension = $imageInfo['extension'] ?? 'jpeg';

        $socialImageSize = explode('x', getFileSize('seo'));
        $imageWidth  = $socialImageSize[0] ?? 1200;
        $imageHeight = $socialImageSize[1] ?? 630;

        /*
        |--------------------------------------------------------------------------
        | Handle Keywords (string OR array)
        |--------------------------------------------------------------------------
        */
        $keywords = [];

        $rawKeywords = $seoContents->keywords ?? $seo->keywords ?? null;

        if (is_array($rawKeywords)) {
            $keywords = $rawKeywords;
        } elseif (is_string($rawKeywords)) {
            $keywords = array_map('trim', explode(',', $rawKeywords));
        }

        $keywordsString = implode(',', $keywords);
    @endphp

    {{-- Basic Meta --}}
    <meta name="title" content="{{ $pageTitleText }}">
    <meta name="description" content="{{ $seoContents->description ?? $seo->description ?? '' }}">
    <meta name="keywords" content="{{ $keywordsString }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Robots --}}
    @if (!empty($seoContents->meta_robots ?? $seo->meta_robots ?? ''))
        <meta name="robots" content="{{ $seoContents->meta_robots ?? $seo->meta_robots }}">
    @endif

    {{-- Apple --}}
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $pageTitleText }}">

    {{-- Schema / Google --}}
    <meta itemprop="name" content="{{ $pageTitleText }}">
    <meta itemprop="description" content="{{ $seoContents->description ?? $seo->description ?? '' }}">
    <meta itemprop="image" content="{{ $finalSeoImage }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title"
          content="{{ $seoContents->social_title ?? $seo->social_title ?? $pageTitleText }}">
    <meta property="og:description"
          content="{{ $seoContents->social_description
              ?? $seo->social_description
              ?? ($seoContents->description ?? $seo->description ?? '') }}">
    <meta property="og:image" content="{{ $finalSeoImage }}">
    <meta property="og:image:type" content="image/{{ $imageExtension }}">
    <meta property="og:image:width" content="{{ $imageWidth }}">
    <meta property="og:image:height" content="{{ $imageHeight }}">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
          content="{{ $seoContents->social_title ?? $seo->social_title ?? $pageTitleText }}">
    <meta name="twitter:description"
          content="{{ $seoContents->social_description
              ?? $seo->social_description
              ?? ($seoContents->description ?? $seo->description ?? '') }}">
    <meta name="twitter:image" content="{{ $finalSeoImage }}">

@endif
