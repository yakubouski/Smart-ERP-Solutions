<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?=$this->__head() ?>
        <title></title>
    </head>

    <head>
    <meta charset="utf-8" />
    $__HTML__INNER__

    <title>Page title</title>

    <!-- Robots -->
    <meta name="title" content="">
    <meta name="description" content="">
    <meta name="keywords" content=<?=_h($META_KEYWORDS)?>/>
    <link rel="canonical" href="<?=$HTML_CANONICAL?>" />
    <link rel="author" href="<?=$HTML_AUTHOR?>" />
    <link rel="publisher" href="<?=$HTML_AUTHOR?>" />

    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex, nofollow">
    <meta name="google-site-verification" content="">

    <!-- Humans -->
    <meta name="author" content="Company">
    <meta name="Copyright" content="Copyright <?=date('Y')?>. All Rights Reserved.">
    <meta name="viewport" content="width=device-width; height=device-height; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />  
    
    <meta http-equiv="x-ua-compatible" content="IE=10">

    <link rel="icon" type="image/vnd.microsoft.icon" href="/img/icon/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/img/icon/favicon.ico">
    <link rel="apple-touch-icon" href="/img/icon/apple-touch-icon-57-precomposed.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="cleartype" content="on">
    
	<meta property="og:title" content="<?=$FB_TITLE?>"/>
	<meta property="og:image" content="<?=$FB_IMAGE?>"/>
	<meta property="og:url" content="<?=$FB_URL?>"/>
	<meta property="og:description" content="<?=$FB_DESCRIPTION?>"/>
	<meta property="og:admins" content="<?=$FB_ADMINS?>"/>
	<meta property="twitter:title" content="<?=$TW_TITLE?>"/>
	<meta property="twitter:image" content="<?=$TW_IMAGE?>"/>
	<meta property="twitter:url" content="<?=$TW_URL?>"/>
	<meta property="twitter:description" content="<?=$TW_DESCRIPTION?>"/>
	<meta property="twitter:card" content="<?=$TW_CARD?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
</head>

    <body>
        <?=$__HTML__INNER__?>
    </body>
</html>