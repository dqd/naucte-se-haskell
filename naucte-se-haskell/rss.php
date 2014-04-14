<?header('Content-Type: application/rss+xml');?>
<?
$lines = explode("\n", file_get_contents("rss_content.txt"));
$last_modified = gmdate("D, d M Y H:i:s \G\M\T", filemtime("rss_content.txt"));
header("Last-modified: $last_modified");
header("Cache-control: public");
?><?print '<?xml version="1.0" encoding="utf-8"?>';?>
<rss version="2.0">
<channel>
<title>Naučte se Haskell!</title>
<link>http://naucte-se.haskell.cz/</link>
<description>Změny v překladu tutoriálu o Haskellu.</description>
<pubDate><?=$last_modified?></pubDate>
<language>cs</language>

<? foreach($lines as $line): ?>
    <? if($line): ?>
        <item>
        <title><?=$line?></title>
        <link>http://naucte-se.haskell.cz/kapitoly</link>
        </item>
    <? endif; ?>
<? endforeach; ?>
</channel>
</rss>
