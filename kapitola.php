<html>
<head>
<?
    if ($_P[0] == "kapitoly") {
        $title = "- Obsah";
    } elseif ($_P[0] == "faq") {
        $title = "- Často kladené dotazy";
    } else {
        $title = "- " . $contents[$_P[0]]['title'];
    }
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Naučte se Haskell! <?=$title?></title>
<? include('base.php') ?>
<style type="text/css">
	@import url('reset.css');
	@import url('style.css');
</style>
<meta name="language" content="cs">
<meta name="robots" content="index,follow">
<link rel="shortcut icon" href="favicon.png" type="image/png">
<link type="text/css" rel="stylesheet" href="sh/Styles/SyntaxHighlighter.css">
<link rel="alternate" href="rss.php" type="application/rss+xml" title="Naučte se Haskell!">
</head>
<body class="introcontent">
<div class="bgwrapper">
    <div id="content">
    <img src="beta.png" style="position:absolute;top:10px;right:15px" alt="beta">
    <? if ($previous || $next): ?>
            <div class="footdiv" style="margin-bottom:25px;">
                <ul>
                    <li style="text-align:left">
                        <? if($previous):?>
                                <a href="<?=$previous?>" class="prevlink"><?=$contents[$previous]['title']?></a>
                        <? else: echo "&nbsp;"; endif; ?>
                    </li>
                    <li style="text-align:center">
                        <a href="kapitoly">Obsah</a>
                    </li>
                    <li style="text-align:right">
                        <? if($next):?>
                                <a href="<?=$next?>" class="nxtlink"><?=$contents[$next]['title']?></a>
                        <? else: echo "&nbsp;"; endif; ?>
                    </li>
                </ul>
            </div>
    <? endif; ?>
    <?=$page?>
    <? if ($previous || $next): ?>
            <div class="footdiv">
                <ul>
                    <li style="text-align:left">
                        <? if($previous):?>
                                <a href="<?=$previous?>" class="prevlink"><?=$contents[$previous]['title']?></a>
                        <? else: echo "&nbsp;"; endif; ?>
                    </li>
                    <li style="text-align:center">
                        <a href="kapitoly">Obsah</a>
                    </li>
                    <li style="text-align:right">
                        <? if($next):?>
                                <a href="<?=$next?>" class="nxtlink"><?=$contents[$next]['title']?></a>
                        <? else: echo "&nbsp;"; endif; ?>
                    </li>
                </ul>
            </div>
    <? endif; ?>
    </div>
    <script type="text/javascript" src="sh/Scripts/shCore.js"></script>
    <script type="text/javascript" src="shBrushHaskell.js"></script>
    <script type="text/javascript" src="shBrushPlain.js"></script>
    <script type="text/javascript">
    dp.SyntaxHighlighter.ClipboardSwf = '/sh/Scripts/clipboard.swf';
    dp.SyntaxHighlighter.HighlightAll('code', false, false, false, 1, false);
    </script>
</div>
<? include('analytics.php'); ?>
</body>
</html>
