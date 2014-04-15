<?  $request_uri = $_SERVER['REQUEST_URI'];
    $params = substr($request_uri,strlen(dirname($_SERVER['SCRIPT_NAME'])));
    $params = strtolower(trim($params, '/'));
    $_P = $params != "" ? explode('/',$params) : array();

    $contents = array(
        'uvod' => array(
            'title' => 'Úvod',
            'subchapters' => array
                ('o-tomto-tutorialu' => 'O tomto tutoriálu'
                ,'takze-co-je-to-haskell' => "Takže co je to Haskell?"
                ,'co-bude-potreba' => "Co bude potřeba"
                )
        ),
        'zaciname' => array(
            'title' => 'Začínáme',
            'subchapters' => array
                ('pripravit-pozor-ted' => 'Připravit, pozor, teď!'
                ,'miminko-ma-svou-prvni-funkci' => "Miminko má svou první funkci"
                ,'uvod-do-seznamu' => "Úvod do seznamů"
                ,'serifovy-rozsahy' => "Šerifovy rozsahy"
                ,'jsem-generator-seznamu' => "Jsem generátor seznamu"
                ,'n-tice' => "N-tice"
                )
        ),
        'typy-a-typove-tridy' => array(
            'title' => 'Typy a typové třídy',
            'subchapters' => array
                ('verte-typum' => 'Věřte typům'
                ,'typove-promenne' => 'Typové proměnné'
                ,'zaklady-typovych-trid' => "Základy typových tříd",
                )
        ),
        'syntaxe-ve-funkcich' => array(
            'title' => 'Syntaxe ve funkcích',
            'subchapters' => array
                ('vzory' => 'Vzory'
                ,'straze-straze' => 'Stráže, stráže!'
                ,'lokalni-definice-where' => 'Lokální definice pomocí where'
                ,'lokalni-definice-let' => "&hellip; a pomocí let"
                ,'podmineny-vyraz-case' => "Podmíněný výraz case"
                )
        ),
        'rekurze' => array(
            'title' => 'Rekurze',
            'subchapters' => array
                ('ahoj-rekurze' => 'Ahoj, rekurze!'
                ,'maximalni-skvelost' => 'Maximální skvělost'
                ,'nekolik-dalsich-rekurzivnich-funkci' => "Několik dalších rekurzivních funkcí"
                ,'rychle-rad' => "Rychle, řaď!"
                ,'myslime-rekurzivne' => "Myslíme rekurzivně"
                )
        ),
        'funkce-vyssiho-radu' => array(
            'title' => 'Funkce vyššího řádu',
            'subchapters' => array
                ('curryfikovane-funkce' => 'Curryfikované funkce'
                ,'vyssi-rad' => 'Trocha vyššího řádu je v pořádku'
                ,'mapy-a-filtry' => 'Mapy a filtry'
                ,'lambdy' => 'Lambdy'
                ,'akumulacni-funkce-fold' => 'Akumulační funkce fold'
                ,'aplikace-funkce' => 'Aplikace funkce pomocí $'
                ,'skladani-funkci' => 'Skládání funkcí'
                )
        ),
        'moduly' => array(
            'title' => 'Moduly',
            'subchapters' => array
                ('nacitani-modulu' => 'Načítání modulů'
                ,'data-list' => 'Data.List'
                ,'data-char' => 'Data.Char'
                ,'data-map' => 'Data.Map'
                ,'data-set' => 'Data.Set'
                ,'vytvareni-vlastnich-modulu' => 'Vytváření vlastních modulů'
            )
        ),
        'vytvarime-si-vlastni-typy-a-typove-tridy' => array(
            'title' => 'Vytváříme si vlastní typy a typové třídy',
            'subchapters' => array
                ('algebraicke-datove-typy' => 'Úvod do algebraických datových typů'
                ,'zaznamy' => 'Záznamy'
                ,'typove-parametry' => 'Typové parametry'
                ,'odvozene-instance' => 'Odvozené instance'
                ,'typova-synonyma' => 'Typová synonyma'
                ,'rekurzivni-datove-struktury' => 'Rekurzivní datové struktury'
                ,'typove-tridy-pro-pokrocile' => 'Typové třídy pro pokročilé'
                ,'typova-trida-ano-ne' => 'Typová třída ano/ne'
                ,'typova-trida-funktor' => 'Typová třída funktor'
                ,'druhy-a-nejake-to-typove-kung-fu' => 'Druhy a nějaké to typové kung-fu'
                )
        ),
        'input-and-output' => array(
            'title' => 'Input and Output',
            'subchapters' => array
                ('hello-world' => 'Hello, world!'
                ,'files-and-streams' => 'Files and streams'
                ,'command-line-arguments' => 'Command line arguments'
                ,'randomness' => 'Randomness'
                ,'bytestrings' => 'Bytestrings'
                ,'exceptions' => 'Exceptions'
                )
        ),
        'functionally-solving-problems' => array(
            'title' => 'Functionally Solving Problems',
            'subchapters' => array
                ('reverse-polish-notation-calculator' => 'Reverse Polish notation calculator'
                ,'heathrow-to-london' => 'Heathrow to London'
                )
        ),
        'functors-applicative-functors-and-monoids' => array(
            'title' => "Functors, Applicative Functors and Monoids",
            'subchapters' => array
                ('functors-redux' => 'Functors redux'
                ,'applicative-functors' => 'Applicative functors'
                ,'newtype' => 'Newtype'
                //monoids
                //foldable
                )
        )
    );

    if ($_P[0]=='uvod') {
        $previous = false;
        next($contents);
        $next = key($contents);
    } else {
    $continue = true;
        $t = true;
        while($t) {
            $k = key($contents);
            if ($k == $_P[0]) {
                prev($contents);
                $previous = key($contents);
                next($contents);
                next($contents);
                $next = key($contents);
                prev($contents);
            }
            $t = next($contents);
        }
        $previous = file_exists($previous . '.php') ? $previous : "";
        $next = file_exists($next . '.php') ? $next : "";
    }

    ob_start();
        $file = (!$_P) ? 'main.php' : $_P[0].'.php';
        if (file_exists($file))
            include($file);
        else {
            header("HTTP/1.1 404 Not Found");
            include('404.php');
        }
    $page = ob_get_clean();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<? if (!$_P): ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Naučte se Haskell!</title>
<? include('base.php') ?>
<style type="text/css">
	@import url('reset.css');
	@import url('style.css');
    body {
        padding-top:20px;
        background-color:#5abdc5;
    }
</style>
<meta name="language" content="cs">
<meta name="robots" content="index,follow">
<link rel="shortcut icon" href="images/favicon.png" type="image/png">
<link rel="alternate" href="rss.php" type="application/rss+xml" title="Naučte se Haskell!">
</head>
<body>
    <a href="kapitoly" class="index-splash">ZAČÍT ČÍST!!!</a>
    <div class="splashdiv">
        <div class="introtext">
            <p>Vítejte! Tohle je příručka programovacího jazyka Haskell pro začátečníky! Autora příručky můžete nalézt na IRC kanálu <a href="irc://irc.freenode.net/haskell">#haskell</a>, kde se vyskytuje pod přezdívkou <em>BONUS</em>.</p>
            <p>Původní příručku v angličtině můžete najít na webu <a href="http://learnyouahaskell.com/">learnyouahaskell.com</a>. Případné chyby v překladu a připomínky k němu lze nahlásit na IRC kanálu <a href="irc://irc.freenode.net/haskell.cz">#haskell.cz</a>. Do češtiny překládá <a href="http://dqd.cz/">dqd</a>.</p>
            <p>Kromě toho se můžete podívat na autorův <a href="http://twitter.com/bonus500">twitter</a>!</p>
        </div>
        <div class="go">
            <a href="kapitoly" class="index-read">Začít číst!</a>
            <a href="faq" class="index-faq" title="Často kladené dotazy">FAQ</a>
            <a href="rss.php" class="index-rss">RSS</a>
        </div>
    </div>
<? include('analytics.php') ?>
</body>
</html>

<? else: ?>
<? include('kapitola.php'); ?>
<? endif; ?>
