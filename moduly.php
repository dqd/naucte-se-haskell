<div class="english-version"><a href="http://learnyouahaskell.com/modules">English version</a></div>
<h1><?=$contents['moduly']['title']?></h1>
<a name="nacitani-modulu"></a><h2><?=$contents['moduly']['subchapters']['nacitani-modulu']?></h2>
<img src="modules.png" alt="moduly" class="right" width="230" height="162">
<p>
Modul je v Haskellu kolekce souvisejících funkcí, typů a typových tříd. Program je v Haskellu kolekce modulů, kde hlavní modul načte ostatní moduly a poté použije funkce, které jsou v nich definované a něco pomocí nic udělá. Rozdělení kódu do několika modulů má celkem dost výhod. Jestliže je modul dostatečně obecný, funkce v něm mohou být použity ve velkém množství odlišných programů. Pokud je váš kód oddělený do samostatných modulů, které na sobě příliš nezávisí (říkáme jim také volně vázané), můžeme je později použít znovu. To dělá celou záležitost psaní kódu zvládnutelnější, když je kód rozdělený do více částí a každá část má svůj účel.
</p>
<p>
Haskellová standardní knihovna je rozdělená do modulů a každý z nich obsahuje funkce a typy, které spolu souvisí a slouží ke společnému účelu. Existuje modul pro zacházení se seznamy, modul pro souběžné programování, modul, jež se zabývá komplexními čísly atd. Všechny funkce, typy a typové třídy, se kterými jsme se zatím potkali, byly součástí modulu <span class="fixed">Prelude</span>, jenž se importuje automaticky. V této kapitole prozkoumáme několik užitečných modulů a v nich obsažených funkcí. Ale nejprve se podíváme na importování modulů.
</p>
<p>
Syntax pro importování modulů v haskellovém skriptu je <span class="fixed">import &lt;název modulu&gt;</span>. Musí se to napsat před definice jakýchkoliv funkcí, takže se importy většinou nachází na začátku souboru. Jeden skript může importovat samozřejmě více modulů &mdash; stačí každý import umístit na samostatný řádek. Naimportujme si modul <span class="fixed">Data.List</span> obsahující několik užitečných funkcí pro práci se seznamy a použijme funkci, kterou exportuje, na vytvoření funkce, co nám řekne, kolik unikátních prvků seznam má.
</p>
<pre name="code" class="haskell:hs">
import Data.List

numUniques :: (Eq a) =&gt; [a] -&gt; Int
numUniques = length . nub
</pre>
<p>
Když do nějakého souboru napíšete <span class="fixed">import Data.List</span>, všechny funkce, které exportuje modul <span class="fixed">Data.List</span>, se stanou dostupné v globálním jmenném prostoru, což znamená, že je můžete zavolat kdekoliv v daném skriptu. Funkce <span class="fixed">nub</span> je definovaná v <span class="fixed">Data.List</span> tak, že vezme seznam a vyřadí z něj duplicitní prvky. Složením funkcí <span class="fixed">length</span> a <span class="fixed">nub</span> napsáním <span class="fixed">length . nub</span> vytvoří funkci, jež je ekvivalentní funkci <span class="fixed">\xs -&gt; length (nub xs)</span>.
</p>
<p>
Můžete také vložit funkce z modulů do globálního jmenného prostoru GHCi. Když máte spuštěné GHCi a chcete mít možnost zavolat funkce, které exportuje modul <span class="fixed">Data.List</span>, napište tohle:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; :m + Data.List
</pre>
<p>
Jestliže chceme načíst více modulů v GHCi, nemusíme psát <span class="fixed">:m +</span> několikrát, můžeme prostě načíst několik modulů najednou.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; :m + Data.List Data.Map Data.Set
</pre>
<p>
Nicméně pokud načítáte skript, který už importuje nějaké moduly, nemusíte používat <span class="fixed">:m +</span>, abyste k nim přistupovali.
</p>
<p>
Pokud potřebujete jenom pár funkcí z modulu, můžete si selektivně importovat jenom tyto funkce. Kdybychom chtěli importovat pouze funkce <span class="fixed">nub</span> a <span class="fixed">sort</span> z modulu <span class="fixed">Data.List</span>, udělali bychom tohle:
</p>
<pre name="code" class="haskell:hs">
import Data.List (nub, sort)
</pre>
<p>
Můžete také chtít importovat všechny funkce z modulu kromě několika vybraných. To je často užitečné, když několik modulů exportuje funkci se stejným názvem a vy se chcete zbavit těch nepotřebných. Řekněme, že už máme svou vlastní funkci nazvanou <span class="fixed">nub</span> a budeme chtít importovat všechny funkce z modulu <span class="fixed">Data.List</span> kromě funkce <span class="fixed">nub</span>:
<pre name="code" class="haskell:hs">
import Data.List hiding (nub)
</pre>
<p>
Jiný způsob, jak se vypořádávat s kolizemi, je používat kvalifikované importy. Modul <span class="fixed">Data.Map</span>, nabízející datovou strukturu pro vyhledávání hodnot podle klíče, exportuje hromadu funkcí se stejným názvem jako mají funkce v <span class="fixed">Prelude</span>, jako třeba <span class="fixed">filter</span> nebo <span class="fixed">null</span>. Takže jakmile importujeme <span class="fixed">Data.Map</span> a poté zavoláme funkci <span class="fixed">filter</span>, Haskell si nebude jistý, kterou z těch dvou funkcí použít. Tady je ukázané, jak to vyřešit:
</p>
<pre name="code" class="haskell:hs">
import qualified Data.Map
</pre>
<p>
Tohle zajistí, že když budeme chtít použít funkci <span class="fixed">filter</span> z modulu <span class="fixed">Data.Map</span>, musíme napsat <span class="fixed">Data.Map.filter</span>, kdežto pouhé <span class="fixed">filter</span> stále odkazuje na normální funkci <span class="fixed">filter</span>, jak ji známe a milujeme. Vypisování <span class="fixed">Data.Map</span> před každou funkci z toho modulu je celkem jednotvárné. To je důvod proč máme možnost přejmenovat kvalifikovaný import na něco kratšího:
</p>
<pre name="code" class="haskell:hs">
import qualified Data.Map as M
</pre>
<p>
Teď stačí pro použití funkce <span class="fixed">filter</span> z modulu <span class="fixed">Data.Map</span> napsat <span class="fixed">M.filter</span>.
</p>
<p>
Rozhodně nahlédněte do <a href="http://haskell.org/ghc/docs/latest/html/libraries/">této užitečné refereční dokumentace</a>, abyste viděli, které moduly jsou ve standardní knihovně. Dobrý způsob, jak pochytit nové znalosti o Haskellu, je proklikávat se dokumentací a prozkoumávat moduly a jejich funkce. Můžete si také prohlížet zdrojový kód jednotlivých modulů Haskellu. Čtení kódu některých modulů je vážně dobrý způsob jak se naučit Haskell a získat pro něj náležitý cit.
</p>
<p>
Na hledání funkcí nebo zjišťování, kde jsou umístěné, používejte <a href="http://haskell.org/hoogle">Hoogle</a>. Je to opravdu skvělý haskellový vyhledávač. Můžete hledat podle názvu funkcí, modulů, nebo dokonce podle typu funkce.
</p>
<a name="data-list"></a><h2><?=$contents['moduly']['subchapters']['data-list']?></h2>
<p>
Modul <span class="fixed">Data.List</span> se kupodivu celý věnuje seznamům. Poskytuje několik velice užitečných funkcí pro zacházení s nimi. Některé funkce jsme už potkali (jako <span class="fixed">map</span> a <span class="fixed">filter</span>), protože modul <span class="fixed">Prelude</span> nechává příhodně exportovat některé funkce z <span class="fixed">Data.List</span>. Nemusíte importovat <span class="fixed">Data.List</span> kvalifikovaně, protože nekoliduje s žádným názvem z modulu <span class="fixed">Prelude</span>, kromě těch, které <span class="fixed">Prelude</span> už nakradlo z modulu <span class="fixed">Data.List</span>. Pojďme se podívat na nějaké z funkcí se kterými jsme se zatím nesetkali.
</p>
<p>
Funkce <span class="label function">intersperse</span> vezme prvek a seznam a poté vloží ten prvek mezi každý pár prvků v seznamu. Tady je ukázka:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; intersperse '.' "OPIČÁK"
"O.P.I.Č.Á.K"
ghci&gt; intersperse 0 [1,2,3,4,5,6]
[1,0,2,0,3,0,4,0,5,0,6]
</pre>
<p>
Funkce <span class="label function">intercalate</span> vezme seznam seznamů a seznam. Ten poté vloží mezi všechny ty seznamy a poté zarovná výsledek.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; intercalate " " ["hej","nazdar","kluci"]
"hej nazdar kluci"
ghci&gt; intercalate [0,0,0] [[1,2,3],[4,5,6],[7,8,9]]
[1,2,3,0,0,0,4,5,6,0,0,0,7,8,9]
</pre>
<p>
Funkce <span class="label function">transpose</span> prohodí (transponuje) seznam seznamů. Pokud si představíte seznam seznamů jako 2D matici, řádky se stanou sloupci a naopak.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; transpose [[1,2,3],[4,5,6],[7,8,9]]
[[1,4,7],[2,5,8],[3,6,9]]
ghci&gt; transpose ["hej","nazdar","kluci"]
["hnk","eal","jzu","dc","ai","r"]
</pre>
<p>
Řekněme, že máme polynomy <i>3x<sup>2</sup> + 5x + 9</i>, <i>10x<sup>3</sup> + 9</i> a <i>8x<sup>3</sup> + 5x<sup>2</sup> + x - 1</i> a chceme je spojit dohromady. Můžeme je v Haskellu reprezentovat pomocí seznamů <span class="fixed">[0,3,5,9]</span>, <span class="fixed">[10,0,0,9]</span> a <span class="fixed">[8,5,1,-1]</span>. A teď, abychom je sečetli, jediné, co musíme udělat, je tohle:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map sum $ transpose [[0,3,5,9],[10,0,0,9],[8,5,1,-1]]
[18,8,6,17]
</pre>
<p>
Když prohazujeme tyhle tři seznamy, třetí mocniny jsou pak v prvním řádku, druhé ve druhém a tak dále. Namapováním funkce <span class="fixed">sum</span> na tento transponovaný seznam seznamů dosáhneme požadovaného výsledku.
</p>
<img src="legolists.png" alt="nákupní seznamy" class="left" width="230" height="212">
<p>
Funkce <span class="label function">foldl'</span>, <span class="label function">foldl1'</span>, <span class="label function">foldr'</span> a <span class="label function">foldr1'</span> jsou striktní verze svých příslušných líných podob. Když použijeme líný fold na opravdu velký seznam, může nastat chyba přetečení zásobníku. Viník této chyby je líná podstata foldů, protože hodnota akumulátoru se ve skutečnosti při skládání neaktualizuje. Co se děje ve skutečnosti je to, že akumulátor tak nějak slibuje, že spočítá svou hodnotu, jakmile se to po něm bude chtít. Takhle je to u každého akumulátoru s mezivýsledkem a všechny tyhle nahromaděné sliby přetečou váš zásobník. Striktní foldy nejsou líní lemplové a ve skutečnosti vypočítají mezivýsledky hned jak k nim přicházejí místo aby plnily zásobník sliby. Takže jestliže někdy dostanete chybu přetečení zásobníku při skládání seznamů, zkuste přejít na striktní verzi foldů.
</p>
<p>
Funkce <span class="label function">concat</span> (zřetězení) zarovná seznam seznamů na prostý seznam prvků.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; concat ["foo","bar","baz"]
"foobarbaz"
ghci&gt; concat [[3,4,5],[2,3,4],[2,1,1]]
[3,4,5,2,3,4,2,1,1]
</pre>
<p>
Jenom to prostě odstraní jednu úroveň zanoření. Takže pokud chceme zarovnat výraz <span class="fixed">[[[2,3],[3,4,5],[2]],[[2,3],[3,4]]]</span>, který je seznam seznamů seznamů, musíme použít funkci <span class="label function">concat</span> dvakrát.
</p>
<p>
Napsáním funkce <span class="label function">concatMap</span> uděláme stejnou věc, jako kdybychom nejprve namapovali funkci na seznam a poté ji zřetězili pomocí funkce <span class="fixed">concat</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; concatMap (replicate 4) [1..3]
[1,1,1,1,2,2,2,2,3,3,3,3]
</pre>
<p>
Funkce <span class="label function">and</span> vezme seznam booleovských hodnot a vrátí <span class="fixed">True</span> pouze pokud jsou <i>všechny</i> hodnoty v seznamu rovny <span class="fixed">True</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; and $ map (&gt;4) [5,6,7,8]
True
ghci&gt; and $ map (==4) [4,4,4,3,4]
False
</pre>
<p>
Funkce <span class="label function">or</span> je podobná funkci <span class="fixed">and</span>, jenom vrátí <span class="fixed">True</span> pokud <i>nějaká</i> z booleovských hodnot v seznamu je <span class="fixed">True</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; or $ map (==4) [2,3,4,5,6,1]
True
ghci&gt; or $ map (&gt;4) [1,2,3]
False
</pre>
<p>
Funkce <span class="label function">any</span> a <span class="label function">all</span> vezmou predikát a poté zkontrolují, zdali některý z prvků nebo všechny prvky v seznamu vyhovují predikátu, v tomto pořadí. Obvykle použijeme tyhle dvě funkce místo abychom na seznam namapovali funkce <span class="fixed">and</span> nebo <span class="fixed">or</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; any (==4) [2,3,5,6,1,4]
True
ghci&gt; all (&gt;4) [6,9,10]
True
ghci&gt; all (`elem` ['A'..'Z']) "HEJKLUCIjakje"
False
ghci&gt; any (`elem` ['A'..'Z']) "HEJKLUCIjakje"
True
</pre>
<p>
Funkce <span class="label function">iterate</span> vezme funkci a počáteční hodnotu. Následně aplikuje funkci na počáteční hodnotu, poté aplikuje funkci na výsledek té aplikace funkce, poté opět funkci na výsledek předchozí aplikace atd. Vrátí všechny výsledky ve formě nekonečného seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; take 10 $ iterate (*2) 1
[1,2,4,8,16,32,64,128,256,512]
ghci&gt; take 3 $ iterate (++ "haha") "haha"
["haha","hahahaha","hahahahahaha"]
</pre>
<p>
Funkce <span class="label function">splitAt</span> vezme číslo a seznam. Potom rozdělí seznam na pozici, kterou zadává číslo, a jako výsledek vrátí dva seznamy v n-tici.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; splitAt 3 "hejchlape"
("hej","chlape")
ghci&gt; splitAt 100 "hejchlape"
("hejchlape","")
ghci&gt; splitAt (-3) "hejchlape"
("","hejchlape")
ghci&gt; let (a,b) = splitAt 3 "foobar" in b ++ a
"barfoo"
</pre>
<p>
Funkcička <span class="label function">takeWhile</span> je opravdu užitečná. Postupně tahá prvky ze seznamu, zatímco platí predikát, a poté, když narazí na prvek, jež nevyhovuje predikátu, je tahání přerušeno. To se ukázalo být velmi užitečné.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; takeWhile (&gt;3) [6,5,4,3,2,1,2,3,4,5,4,3,2,1]
[6,5,4]
ghci&gt; takeWhile (/=' ') "Tohle je věta."
"Tohle"
</pre>
<p>
Řekněme, že chceme znát součet všech třetích mocnin (přirozených čísel) menších než 10000. Nemůžeme namapovat výraz <span class="fixed">(^3)</span> na seznam <span class="fixed">[1..]</span>, aplikovat nějaký filtr a poté to celé zkusit sečíst, protože filtrování nekonečného seznamu nikdy neskončí. Možná víte, že tahle řada prvků narůstá, ale Haskell ne. To je důvod, proč můžeme udělat tohle:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; sum $ takeWhile (&lt;10000) $ map (^3) [1..]
53361
</pre>
<p>
Aplikujeme výraz <span class="fixed">(^3)</span> na nekonečný seznam a hned poté co narazíme na prvek, který je větší nebo rovný číslu 10000, seznam je oříznut. Pak tedy můžeme výsledek jednoduše sečíst.
</p>
<p>
Funkce <span class="label function">dropWhile</span> je podobná, jenom zahazuje všechny prvky, dokud je predikát pravdivý. Jakmile se jednou predikát vyhodnotí jako <span class="fixed">False</span>, vrátí se zbytek seznamu. Extrémně užitečná a půvobná funkce!
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; dropWhile (/=' ') "Tohle je věta."
" je věta."
ghci&gt; dropWhile (&lt;3) [1,2,2,2,3,4,5,4,3,2,1]
[3,4,5,4,3,2,1]
</pre>
<p>
Byl nám zadán seznam představující hodnotu akcií k určitému datu. Tento seznam je vytvořený z n-tic jejichž první složka je hodnota akcií, druhá je rok, třetí měsíc a čtvrtá den. Chtěli bychom vědět, kdy hodnota akcií poprvé přesáhla tisíc dolarů!
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let stock = [(994.4,2008,9,1),(995.2,2008,9,2),(999.2,2008,9,3),(1001.4,2008,9,4),(998.3,2008,9,5)]
ghci&gt; head (dropWhile (\(val,y,m,d) -&gt; val &lt; 1000) stock)
(1001.4,2008,9,4)
</pre>
<p>
Funkce <span class="label function">span</span> je podobná funkci <span class="fixed">takeWhile</span>, jenom vrací dvojici seznamů. První seznam obsahuje všechno co by obsahovalo zavolání funkce <span class="fixed">takeWhile</span> na stejný predikát a seznam. Druhý seznam obsahuje část seznamu, která by se zahodila.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let (fw, rest) = span (/=' ') "Tohle je věta." in "První slovo: " ++ fw ++ ", zbytek:" ++ rest
"První slovo: Tohle, zbytek: je věta."
</pre>
<p>
Zatímco funkce <span class="fixed">span</span> rozdělí seznam za místem, kde predikát platí, funkce <span class="label function">break</span> ho roztrhne tam, kde platí poprvé. Když napíšeme <span class="fixed">break p</span>, je to stejné jako bychom napsali <span class="fixed">span (not . p)</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; break (==4) [1,2,3,4,5,6,7]
([1,2,3],[4,5,6,7])
ghci&gt; span (/=4) [1,2,3,4,5,6,7]
([1,2,3],[4,5,6,7])
</pre>
<p>
Po použití funkce <span class="fixed">break</span> bude druhý výsledný seznam začínat prvním prvkem, který vyhovuje predikátu.
</p>
<p>
Funkce <span class="label function">sort</span> jednoduše seřadí seznam. Typ prvků v seznamu musí patřit do typové třídy <span class="fixed">Ord</span>, protože pokud prvky v seznamu nemají stanovené uspořádání, seznam nemůže být seřazen.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; sort [8,5,3,2,1,6,4,2]
[1,2,2,3,4,5,6,8]
ghci&gt; sort "Tohle bude brzy serazeno"
"   Tabbdeeeehlnoorrsuyzz"
</pre>
<p>
Funkce <span class="label function">group</span> vezme seznam a seskupí sousedící prvky, které jsou si rovny, do dalšího seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; group [1,1,1,1,2,2,2,2,3,3,2,2,2,5,6,7]
[[1,1,1,1],[2,2,2,2],[3,3],[2,2,2],[5],[6],[7]]
</pre>
<p>
Jestliže seřadíme seznam před seskupením, můžeme zjistit, kolikrát se v tom seznamu jednotlivé prvky vyskytují.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map (\l@(x:xs) -&gt; (x,length l)) . group . sort $ [1,1,1,1,2,2,2,2,3,3,2,2,2,5,6,7]
[(1,4),(2,7),(3,2),(5,1),(6,1),(7,1)]
</pre>
<p>
Funkce <span class="label function">inits</span> a <span class="label function">tails</span> jsou podobné funkcím <span class="fixed">init</span> a <span class="fixed">tail</span>, jenom s tím rozdílem, že se aplikují rekurzivně na seznam tak dlouho dokud něco ještě zbývá. Sledujte.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; inits "w00t"
["","w","w0","w00","w00t"]
ghci&gt; tails "w00t"
["w00t","00t","0t","t",""]
ghci&gt; let w = "w00t" in zip (inits w) (tails w)
[("","w00t"),("w","00t"),("w0","0t"),("w00","t"),("w00t","")]
</pre>
<p>
Zkusíme použít fold pro implementaci hledání částí seznamu.
</p>
<pre name="code" class="haskell:hs">
search :: (Eq a) =&gt; [a] -&gt; [a] -&gt; Bool
search needle haystack =
    let nlen = length needle
    in  foldl (\acc x -&gt; if take nlen x == needle then True else acc) False (tails haystack)
</pre>
<p>
Nejprve zavoláme funkci <span class="fixed">tails</span> na seznam ve kterém hledáme. Poté projdeme každý zbytek a zjistíme, jestli začiná tím stejným co hledáme.
</p>
<p>
Takhle jsme vlastně vytvořili funkci která se chová stejně jako <span class="label function">isInfixOf</span>. Funkce <span class="fixed">isInfixOf</span> hledá v seznamu jeho část a vrací <span class="fixed">True</span> jestliže se hledaná část vyskytuje někde v cílovém seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; "zloděj" `isInfixOf` "jsem zloděj koček"
True
ghci&gt; "Zloděj" `isInfixOf` "jsem zloděj koček"
False
ghci&gt; "zloději" `isInfixOf` "jsem zloděj koček"
False
</pre>
<p>
Funkce <span class="label function">isPrefixOf</span> a <span class="label function">isSuffixOf</span> hledají část seznamu na jeho začátku a konci, v tomto pořadí.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; "hej" `isPrefixOf` "hej ty tam!"
True
ghci&gt; "hej" `isPrefixOf` "sakra, hej ty tam!"
False
ghci&gt; "tam!" `isSuffixOf` "hej ty tam!"
True
ghci&gt; "tam!" `isSuffixOf` "hej ty tam"
False
</pre>
<p>
Funkce <span class="label function">elem</span> a <span class="label function">notElem</span> zjišťují, jestli prvek je nebo není v daném seznamu.
</p>
<p>
Funkce <span class="label function">partition</span> vezme nějaký seznam a predikát a vrátí dvojici seznamů. První seznam ve výsledku obsahuje všechny prvky vyhovující predikátu, druhý obsahuje všechny nevyhovující.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; partition (`elem` ['A'..'Z']) "BOBsidneyMORGANeddy"
("BOBMORGAN","sidneyeddy")
ghci&gt; partition (&gt;3) [1,3,5,6,3,2,1,0,3,7]
([5,6,7],[1,3,3,2,1,0,3])
</pre>
<p>
Je důležité porozumět tomu, jak se tohle liší oproti funkcím <span class="fixed">span</span> a <span class="fixed">break</span>:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; span (`elem` ['A'..'Z']) "BOBsidneyMORGANeddy"
("BOB","sidneyMORGANeddy")
</pre>
<p>
Zatímco funkce <span class="fixed">span</span> a <span class="fixed">break</span> jsou hotovy jakmile narazí na první prvek který nevyhovuje nebo vyhovuje predikátu, funkce <span class="fixed">partition</span> prochází celý seznam a rozděluje ho podle predikátu.
</p>
<p>
Funkce <span class="label function">find</span> vezme nějaký seznam a predikát a vrací první prvek který vyhovuje tomu predikátu. Avšak vrátí nám ho zabalený v datovém typu <span class="fixed">Maybe</span>. Algebraické datové typy probereme více do hloubky v následující kapitole, ale prozatím nám stačí vědět, že hodnota datového typu <span class="fixed">Maybe</span> může být buď <span class="fixed">Just něco</span> nebo <span class="fixed">Nothing</span>. Podobně jako seznam může být buď prázdný seznam nebo seznam obsahující nějaké prvky, <span class="fixed">Maybe</span> je možná prázdné (neobsahuje nic) nebo možná obsahuje právě jeden prvek. A jako je typ seznamu kupříkladu celých čísel <span class="fixed">[Int]</span>, typ, který možná obsahuje celé číslo, je <span class="fixed">Maybe Int</span>. V každém případě, pojďme si vzít naši funkci <span class="fixed">find</span> na projížďku.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; find (&gt;4) [1,2,3,4,5,6]
Just 5
ghci&gt; find (&gt;9) [1,2,3,4,5,6]
Nothing
ghci&gt; :t find
find :: (a -&gt; Bool) -&gt; [a] -&gt; Maybe a
</pre>
<p>
Všimněte si typu funkce <span class="fixed">find</span>. Její výsledek je <span class="fixed">Maybe a</span>. To je podobné jako když máme typ <span class="fixed">[a]</span>, akorát hodnota typu <span class="fixed">Maybe</span> může obsahovat buď žádné prvky nebo jeden prvek, zatímco seznam může obsahovat žádné prvky, jeden prvek, nebo více prvků.</p>
<p>
Vzpomeňte si jak jsme určovali, kdy poprvé hodnota našich akcií přesáhla tisíc dolarů. Vytvořili jsme výraz <span class="fixed">head (dropWhile (\(val,y,m,d) -&gt; val &lt; 1000) stock)</span>. Vzpomeňte si, že funkce <span class="fixed">head</span> rozhodně není bezpečná. Co by se stalo, kdyby hodnota našich akcií nikdy nepřekonala tisíc dolarů? Naše aplikace funkce <span class="fixed">dropWhile</span> by vrátila prázdný seznam a pokus o získání počátku toho seznamu by skončil běhovou chybou. Nicméně pokud to přepíšeme na <span class="fixed">find (\(val,y,m,d) -&gt; val &gt; 1000) stock</span>, bude to mnohem bezpečnější. Jestliže naše akcie nikdy nepřesáhly tisíc dolarů (tedy jestliže žádný prvek nevyhovoval predikátu), dostali bychom hodnotu <span class="fixed">Nothing</span>. Ale ten seznam obsahoval platnou odpověď, dostali bychom něco jako <span class="fixed">Just (1001.4,2008,9,4)</span>.
<p>
Funkce <span class="label function">elemIndex</span> je něco jako <span class="fixed">elem</span>, jenom nevrací booleovskou hodnotu. Možná vrátí index prvku, který hledáme. Jestliže se v našem seznamu prvek nevyskytuje, vrátí se <span class="fixed">Nothing</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; :t elemIndex
elemIndex :: (Eq a) =&gt; a -&gt; [a] -&gt; Maybe Int
ghci&gt; 4 `elemIndex` [1,2,3,4,5,6]
Just 3
ghci&gt; 10 `elemIndex` [1,2,3,4,5,6]
Nothing
</pre>
<p>
Funkce <span class="label function">elemIndices</span> je podobná funkci <span class="fixed">elemIndex</span>, jenom vrátí seznam indexů určujících vícenásobný výskyt prvku v našem seznamu. Jelikož používáme seznam pro znázornění indexů, nepotřebujeme typ <span class="fixed">Maybe</span>, protože neúspěch se dá vyjádřit prázdným seznamem, který je synonymní s <span class="fixed">Nothing</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; ' ' `elemIndices` "Kde tu jsou mezery?"
[3,6,11]
</pre>
<p>
Funkce <span class="label function">findIndex</span> je podobná funkci <span class="fixed">find</span>, ale jenom možná vrátí index prvního prvku, který vyhovuje predikátu. Podobně tak funkce <span class="label function">findIndices</span> vrátí indexy všech prvků, které vyhovují predikátu, ve formě seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; findIndex (==4) [5,3,2,1,6,4]
Just 5
ghci&gt; findIndex (==7) [5,3,2,1,6,4]
Nothing
ghci&gt; findIndices (`elem` ['A'..'Z']) "Kde Tu Jsou Verzálky?"
[0,4,7,12]
</pre>
<p>
Funkcemi <span class="fixed">zip</span> a <span class="fixed">zipWith</span> jsme se už zabývali. Vysvětlili jsme si, že tyto funkce sepnou dva seznamy, buď jako dvojici, nebo pomocí binární funkce (což je funkce se dvěma parametry). Ale co když chceme dát dohromady tři seznamy? Nebo sepnout tři seznamy pomocí funkce se třemi parametry? Tak k tomu nám slouží funkce <span class="label function">zip3</span>, <span class="label function">zip4</span> atd. a funkce <span class="label function">zipWith3</span>, <span class="label function">zipWith4</span> atd. Takhle to pokračuje až k číslu 7. I když se to může zdát nedostatečné, úplně to stačí, protože se nestává moc často, že byste potřebovali dávat dohromady osm a víc seznamů. Mimo to existuje velmi chytrý způsob, jak sepnout nekonečný počet seznamů, ale zatím jsme nepokročili natolik, abychom si to mohli ukázat.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; zipWith3 (\x y z -&gt; x + y + z) [1,2,3] [4,5,2,2] [2,2,3]
[7,9,8]
ghci&gt; zip4 [2,3,3] [2,2,2] [5,5,3] [2,2,2]
[(2,2,5,2),(3,2,5,2),(3,2,3,2)]
</pre>
<p>
Stejně jako u normálního spínání se seznamy, které jsou delší než nejkratší seznam z těch spínaných, oříznou na jeho velikost.
</p>
<p>
Funkce <span class="label function">lines</span> je užitečná, pokud se snažíme zpracovat soubory nebo vstup odněkud. Vezme řetězec a vrátí každý řádek toho řetězce jako položku seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; lines  "první řádek\ndruhý řádek\ntřetí řádek"
["první řádek","druhý řádek","třetí řádek"]
</pre>
<p>
Znak <span class="fixed">'\n'</span> znázorňuje ukončení řádku v UNIXu. Zpětné lomítko má v haskellových řetězcích a znacích zvláštní význam.
</p>
<p>
Funkce <span class="label function">unlines</span> je opačná funkce k funkci <span class="fixed">lines</span>. Vezme seznam řetězců a spojí je pomocí znaku <span class="fixed">'\n'</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; unlines ["první řádek", "druhý řádek", "třetí řádek"]
"první řádek\ndruhý řádek\ntřetí řádek\n"
</pre>
<p>
Funkce <span class="label function">words</span> a <span class="label function">unwords</span> jsou určené na rozdělování řádku textu na slova a na spojování seznamu slov do textu. Jsou dost užitečné.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; words "hej tohle jsou slova v téhle větě"
["hej","tohle","jsou","slova","v","téhle","větě"]
ghci&gt; words "hej tohle        jsou   slova v téhle\nvětě"
["hej","tohle","jsou","slova","v","téhle","větě"]
ghci&gt; unwords ["hej", "nazdar", "kámo"]
"hej nazdar kámo"
</pre>
<p>
Funkci <span class="label function">nub</span> jsme si již také zmínili. Vezme seznam a vytřídí z něj duplikátní prvky, což nám vrátí seznam, jehož každý prvek je sněhová unikátní vločka! Tahle funkce má celkem divné jméno. Zjistil jsem, že „nub“ znamená v angličtině malou hrudku nebo nezbytnou část něčeho. Podle mého názoru by měli používat opravdová slova pro názvy funkcí místo archaických.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; nub [1,2,3,4,3,2,1,2,3,4,3,2,1]
[1,2,3,4]
ghci&gt; nub "Hrozně moc slov a tak"
"Hrozně mcslvatk"
</pre>
<p>
Funkce <span class="label function">delete</span> vezme nějaký prvek a k němu seznam a odstraní první výskyt toho prvku v tom seznamu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; delete 'h' "hej ty sthará bandho!"
"ej ty sthará bandho!"
ghci&gt; delete 'h' . delete 'h' $ "hej ty sthará bandho!"
"ej ty stará bandho!"
ghci&gt; delete 'h' . delete 'h' . delete 'h' $ "hej ty sthará bandho!"
"ej ty stará bando!"
</pre>
<p>
Operátor <span class="label function">\\</span> je rozdílová funkce pro seznamy. Chová se v zásadě jako rozdíl množin. Za každý prvek v pravém seznamu odstraní výskyt odpovídajícího prvku v levém.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; [1..10] \\ [2,5,9]
[1,3,4,6,7,8,10]
ghci&gt; "Já, velké dítě" \\ "velké"
"Já,  dítě"
</pre>
<p>
Napsání <span class="fixed">[1..10] \\ [2,5,9]</span> je stejné jako <span class="fixed">delete 2 . delete 5 . delete 9 $ [1..10]</span>.
</p>
<p>
Funkce <span class="label function">union</span> se také chová podobně jako množinová funkce. Vrátí sjednocení dvou seznamů. V podstatě projde všechny prvky ve druhém seznamu a připojí je k prvnímu, jestliže tam už nejsou obsaženy. Dávejte si ovšem pozor, že duplikáty z druhého seznamu zmizí!
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; "hej chlape" `union` "chlape jak je"
"hej chlapek"
ghci&gt; [1..7] `union` [5..10]
[1,2,3,4,5,6,7,8,9,10]
</pre>
<p>
Funkce <span class="label function">intersect</span> funguje jako průnik množin. Vrátí pouze ty prvky, které jsou nalezeny v obou seznamech.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; [1..7] `intersect` [5..10]
[5,6,7]
</pre>
<p>
Funkce <span class="label function">insert</span> vezme nějaký prvek seznam prvků, které mohou být porovnány, a vloží ho do toho seznamu. Vloží ho tam tak, aby všechny prvky, jež jsou větší nebo rovné, byly napravo od něj. Jestliže použijeme funkci <span class="fixed">insert</span> pro vložení prvku do seřazeného seznamu, tento seznam zůstane seřazený.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; insert 4 [1,2,3,5,6,7]
[1,2,3,4,5,6,7]
ghci&gt; insert 'g' $ ['a'..'f'] ++ ['h'..'z']
"abcdefghijklmnopqrstuvwxyz"
ghci&gt; insert 3 [1,2,4,3,2,1]
[1,2,3,4,3,2,1]
</pre>
<p>
Funkce <span class="fixed">length</span>, <span class="fixed">take</span>, <span class="fixed">drop</span>, <span class="fixed">splitAt</span>, <span class="fixed">!!</span> a <span class="fixed">replicate</span> mají společnou vlastnost, že vezmou nějakou hodnotu typu <span class="fixed">Int</span> jako jeden z jejich parametrů, přestože by mohlo být mnohem obecnější a použitelnější, kdyby prostě akceptovali jakýkoliv typ, který je součástí typových tříd <span class="fixed">Integral</span> nebo <span class="fixed">Num</span> (záleží na jednotlivých funkcích). Je tomu tak z historických důvodů. Nicméně, po opravě tohohle by přestalo fungovat velké množství existujícího kódu. To je důvod, proč modul <span class="fixed">Data.List</span> obsahuje obecnější protějšky těchhle funkcí, pojmenované <span class="label function">genericLength</span>, <span class="label function">genericTake</span>, <span class="label function">genericDrop</span>, <span class="label function">genericSplitAt</span>, <span class="label function">genericIndex</span> a <span class="label function">genericReplicate</span>. Kupříkladu funkce <span class="fixed">length</span> má typ <span class="fixed">length :: [a] -&gt; Int</span>. Když si zkusíme vypočítat průměr seznamu čísel napsáním <span class="fixed">let xs = [1..6] in sum xs / length xs</span>, dostaneme typovou chybu, protože nemůžeme použít operátor <span class="fixed">/</span> k celočíselnému dělení. Funkce <span class="fixed">genericLength</span> má, na druhou stranu, typ <span class="fixed">genericLength :: (Num a) =&gt; [b] -&gt; a</span>. Protože se <span class="fixed">Num</span> může chovat jako desetinné číslo, průměr se napsáním <span class="fixed">let xs = [1..6] in sum xs / genericLength xs</span> spočítá bez problémů.
</p>
<p>
Každá z funkcí <span class="fixed">nub</span>, <span class="fixed">delete</span>, <span class="fixed">union</span>, <span class="fixed">intersect</span> a <span class="fixed">group</span> má svůj obecnější protějšek nazvaný <span class="label function">nubBy</span>, <span class="label function">deleteBy</span>, <span class="label function">unionBy</span>, <span class="label function">intersectBy</span> a <span class="label function">groupBy</span>. Rozdíl mezi nimi je v tom, že první skupina funkcí používá operátor <span class="fixed">==</span> pro testování rovnosti, zatímco ty s <i>By</i> vezmou porovnávací funkci a poté pomocí ní testují prvky v seznamu. Funkce <span class="fixed">group</span> tedy odpovídá <span class="fixed">groupBy (==)</span>.
</p>
<p>
Kupříkladu si vezměme seznam popisující chování funkce každou sekundu. Chtěli bychom ho rozdělit do podseznamů podle toho, jestli hodnoty byly menší nebo větší než nula. Kdybychom použili obyčejné <span class="fixed">group</span>, museli bychom seskupit pouze sousední hodnoty, které se rovnají. Ale my je chceme seskupit na základě toho, zdali jsou záporné nebo ne. Proto na scénu vstupuje funkce <span class="fixed">groupBy</span>! Porovnávací funkce poskytovaná funkci s <i>By</i> by měla vzít dva prvky stejného typu a vrátit <span class="fixed">True</span>, jestliže je dle svých standardů považuje za rovné.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let values = [-4.3, -2.4, -1.2, 0.4, 2.3, 5.9, 10.5, 29.1, 5.3, -2.4, -14.5, 2.9, 2.3]
ghci&gt; groupBy (\x y -&gt; (x &gt; 0) == (y &gt; 0)) values
[[-4.3,-2.4,-1.2],[0.4,2.3,5.9,10.5,29.1,5.3],[-2.4,-14.5],[2.9,2.3]]
</pre>
<p>
Z příkladu jasně vidíme, které sekce jsou kladné a které záporné. Poskytnutá porovnávací funkce vezme dva prvky a poté vrátí <span class="fixed">True</span> pouze pokud jsou oba záporné nebo kladné. Tato funkce může býta také zapsána jako <span class="fixed">\x y -&gt; (x &gt; 0) &amp;&amp; (y &gt; 0) || (x &lt;= 0) &amp;&amp; (y &lt;= 0)</span>, ačkoliv si myslím, že ten první způsob je mnohem čitelnější. Ještě přehlednější způsob jak zapsat porovnávající funkce pro funkce s <i>By</i> je importování funkce <span class="label function">on</span> z modulu <span class="fixed">Data.Function</span>. Tato funkce je definována jako:
<pre name="code" class="haskell:ghci">
on :: (b -&gt; b -&gt; c) -&gt; (a -&gt; b) -&gt; a -&gt; a -&gt; c
f `on` g = \x y -&gt; f (g x) (g y)
</pre>
<p>
Takže výraz <span class="fixed">(==) `on` (&gt; 0)</span> vrátí porovnávací funkci, která odpovídá funkci <span class="fixed">\x y -&gt; (x &gt; 0) == (y &gt; 0)</span>. Funkce <span class="fixed">on</span> se hodně používá s funkcemi s <i>By</i>, protože pomocí ní můžeme napsat:
<pre name="code" class="haskell:ghci">
ghci&gt; groupBy ((==) `on` (&gt; 0)) values
[[-4.3,-2.4,-1.2],[0.4,2.3,5.9,10.5,29.1,5.3],[-2.4,-14.5],[2.9,2.3]]
</pre>
<p>
To je opravdu hodně čitelné! Dá to i přečíst nahlas: seskup hodnoty pomocí porovnání jestli jsou prvky větší než nula.
</p>
<p>
Podobně také funkce <span class="fixed">sort</span>, <span class="fixed">insert</span>, <span class="fixed">maximum</span> a <span class="fixed">minimum</span> mají své obecnější protějšky. Funkce jako <span class="fixed">groupBy</span> vezmou funkci, která určuje, kdy se dva prvky rovnají. Funkce <span class="label function">sortBy</span>, <span class="label function">insertBy</span>, <span class="label function">maximumBy</span> a <span class="label function">minimumBy</span> vezmou funkci, která určí, kdy je jeden prvek větší, menší nebo rovný druhému. Typ funkce <span class="fixed">sortBy</span> je <span class="fixed">sortBy :: (a -&gt; a -&gt; Ordering) -&gt; [a] -&gt; [a]</span>. Jestli si vzpomínáte z dřívějška, typ <span class="fixed">Ordering</span> může nabývat hodnoty <span class="fixed">LT</span>, <span class="fixed">EQ</span>, nebo <span class="fixed">GT</span>. Funkce <span class="fixed">sort</span> odpovídá funkci <span class="fixed">sortBy compare</span>, protože funkce <span class="fixed">compare</span> prostě vezme dva prvky jejichž typ patří do typové třídy <span class="fixed">Ord</span>, a vrátí jejich uspořádání.
</p>
<p>
Seznamy mohou být porovnávány, a když na to dojde, tak jde o lexikografické porovnání. Co když máme nějaký seznam seznamů a chtěli bychom ho seřadit ne podle obsahu vnitřních seznamů, ale podle jejich délky? No, pravděpodobně jste odhadli, že bychom na to použili funkci <span class="fixed">sortBy</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let xs = [[5,4,5,4,4],[1,2,3],[3,5,4,3],[],[2],[2,2]]
ghci&gt; sortBy (compare `on` length) xs
[[],[2],[2,2],[1,2,3],[3,5,4,3],[5,4,5,4,4]]
</pre>
<p>
Paráda! Část <span class="fixed">compare `on` length</span> je v podstatě normální angličtina! Jestli si nejste jistí, k čemu tady funkce <span class="fixed">on</span> je, tak výraz <span class="fixed">compare `on` length</span> funguje stejně jako <span class="fixed">\x y -&gt; length x `compare` length y</span>. Když se zabýváte funkcemi s <i>By</i> využívajícími porovnávací funkce, obvykle napíšete <span class="fixed">(==) `on` něco</span>, a když se zabýváte funkcemi s <i>By</i> využívajícími porovnávací funkce, obvykle napíšete <span class="fixed">compare `on` něco</span>.
</p>
<a name="data-char"></a><h2><?=$contents['moduly']['subchapters']['data-char']?></h2>
<img src="legochar.png" alt="lego znak" class="right" width="230" height="323">
<p>
Modul <span class="fixed">Data.Char</span> dělá přesně to, co jeho název napovídá. Exportuje funkce zabývajícími se znaky. Také je užitečný při filtrování a mapování řetězců, protože to jsou vlastně jenom seznamy znaků.
</p>
<p>
Mimo jiné modul <span class="fixed">Data.Char</span> exportuje znakové predikáty. To jsou funkce, které vezmou nějaký znak a řeknou nám o něm, jestli určité předpoklady platí nebo ne. Tady je máme:
</p>
<p>
Funkce <span class="label function">isControl</span> zkontroluje, zdali se jedná o řídící znak.
</p>
<p>
Funkce <span class="label function">isSpace</span> zkontroluje, zdali se jedná o prázdné znaky. To zahrnuje mezery, tabulátory, nové řádky apod.
</p>
<p>
Funkce <span class="label function">isLower</span> zkontroluje, zdali se jedná o minusku (malé písmeno).
</p>
<p>
Funkce <span class="label function">isUpper</span> zkontroluje, zdali se jedná o verzálku (velké písmeno).
</p>
<p>
Funkce <span class="label function">isAlpha</span> zkontroluje, zdali se jedná o písmeno.
</p>
<p>
Funkce <span class="label function">isAlphaNum</span> zkontroluje, zdali se jedná o písmeno nebo číslici.
</p>
<p>
Funkce <span class="label function">isPrint</span> zkontroluje, zdali se jedná o tisknutelný znak. Kupříkladu řídící znaky nejsou tisknutelné.
</p>
<p>
Funkce <span class="label function">isDigit</span> zkontroluje, zdali se jedná o číslici.
</p>
<p>
Funkce <span class="label function">isOctDigit</span> zkontroluje, zdali se jedná o oktalovou (osmičkovou) číslici.
</p>
<p>
Funkce <span class="label function">isHexDigit</span> zkontroluje, zdali se jedná o hexadecimální (šestnáctkovou) číslici.
</p>
<p>
Funkce <span class="label function">isLetter</span> zkontroluje, zdali se jedná o písmeno. (Je totožná s funkcí <span class="fixed">isAlpha</span>.)
</p>
<p>
Funkce <span class="label function">isMark</span> zkontroluje, zdali se jedná o unikódové diakritické znaménko. To jsou znaky, které se kombinují s předcházejícími písmeny, aby vytvořily znak s diakritikou. Používejte tohle, pokud jste Francouz.
</p>
<p>
Funkce <span class="label function">isNumber</span> zkontroluje, zdali se jedná o číslici. (Je totožná s funkcí <span class="fixed">isDigit</span>.)
</p>
<p>
Funkce <span class="label function">isPunctuation</span> zkontroluje, zdali se jedná o interpunkci.
</p>
<p>
Funkce <span class="label function">isSymbol</span> zkontroluje, zdali se jedná o elegantní matematický symbol nebo označení měny.
</p>
<p>
Funkce <span class="label function">isSeparator</span> zkontroluje, zdali se jedná o unikódovou mezeru nebo oddělovač.
</p>
<p>
Funkce <span class="label function">isAscii</span> zkontroluje, zdali se znak nachází mezi prvními 128 pozicemi znakové sady.
</p>
<p>
Funkce <span class="label function">isLatin1</span> zkontroluje, zdali se znak nachází mezi prvními 256 pozicemi znakové sady.
</p>
<p>
Funkce <span class="label function">isAsciiUpper</span> zkontroluje, zdali se jedná o ASCII verzálku.
</p>
<p>
Funkce <span class="label function">isAsciiLower</span> zkontroluje, zdali se jedná o ASCII minusku.
</p>
<p>
Všechny tyhle predikáty jsou typu <span class="fixed">Char -&gt; Bool</span>. Většinou je budete používat k filtrování řetězců nebo k podobnému účelu.   Kupříkladu řekněme, že vytváříme program, který požaduje uživatelské jméno a tohle uživatelské jméno se může sestávat pouze z alfanumerických znaků. Můžeme použít funkci <span class="fixed">all</span> z modulu <span class="fixed">Data.List</span> v kombinaci s predikátem z modulu <span class="fixed">Data.Char</span>, abychom stanovili, zdali je uživatelské jméno v pořádku.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; all isAlphaNum "bobby283"
True
ghci&gt; all isAlphaNum "eddy ryba!"
False
</pre>
<p>
Bezva. Pro případ že si nevzpomínáte, funkce <span class="fixed">all</span> vezme predikát a nějaký seznam a vrátí hodnotu <span class="fixed">True</span> pouze tehdy, když predikát vyhovuje každému prvku z daného seznamu.
</p>
<p>
Také můžeme použít funkci <span class="fixed">isSpace</span> pro simulaci funkce <span class="fixed">words</span> z modulu <span class="fixed">Data.List</span>.
<pre name="code" class="haskell:ghci">
ghci&gt; words "hej kluci to jsem já"
["hej","kluci","to","jsem","já"]
ghci&gt; groupBy ((==) `on` isSpace) "hej kluci to jsem já"
["hej"," ","kluci"," ","to"," ","jsem"," ","já"]
ghci&gt;
</pre>
<p>
Hmmm, no, dělá to něco podobného co funkce <span class="fixed">words</span>, ale zůstanou nám prvky obsahující mezery. Hmm, co bychom s tím mohli dělat? Já vím, odfiltrujeme ty zmetky.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; filter (not . any isSpace) . groupBy ((==) `on` isSpace) $ "hej kluci to jsem já"
["hej","kluci","to","jsem","já"]
</pre>
<p>
Ach.
</p>
<p>
Modul <span class="fixed">Data.Char</span> také exportuje datový typ podobající se typu <span class="fixed">Ordering</span>. Typ <span class="fixed">Ordering</span> může nabývat hodnoty <span class="fixed">LT</span>, <span class="fixed">EQ</span> nebo <span class="fixed">GT</span>. Je to jakýsi výčet. Popisuje několik možných výsledků, jež mohou nastat při porovnávání dvou prvků. Typ <span class="fixed">GeneralCategory</span> je rovněž výčtový. Poskytuje nám několik možných kategorií, do kterých znak může spadat. Hlavní funkce pro získání obecné kategorie se nazývá <span class="fixed">generalCategory</span>. Její typ je <span class="fixed">generalCategory :: Char -&gt; GeneralCategory</span>. Existuje nějakých 31 kategorií, takže si je sem všechny vypisovat nebudeme, ale pohrajeme si s touhle funkcí.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; generalCategory ' '
Space
ghci&gt; generalCategory 'A'
UppercaseLetter
ghci&gt; generalCategory 'a'
LowercaseLetter
ghci&gt; generalCategory '.'
OtherPunctuation
ghci&gt; generalCategory '9'
DecimalNumber
ghci&gt; map generalCategory " \t\nA9?|"
[Space,Control,Control,UppercaseLetter,DecimalNumber,OtherPunctuation,MathSymbol]
</pre>
<p>
Vzhledem k tomu, že typ <span class="fixed">GeneralCategory</span> je součástí typové třídy <span class="fixed">Eq</span>, můžeme také testovat věci jako třeba <span class="fixed">generalCategory c == Space</span>.
</p>
<p>
Funkce <span class="label function">toUpper</span> převede znak na verzálku. Mezery, čísla a podobné znaky zůstanou nezměněny.
</p>
<p>
Funkce <span class="label function">toLower</span> převede znak na minusku.
</p>
<p>
Funkce <span class="label function">toTitle</span> převede znak na titulkovou velikost. Pro většinu znaků odpovídá titulková velikost verzálce.
</p>
<p>
Funkce <span class="label function">digitToInt</span> převede znak na typ <span class="fixed">Int</span>. Aby byl převod úspěšný, převáděný znak musí být v rozsazích <span class="fixed">'0'..'9'</span>, <span class="fixed">'a'..'f'</span> nebo <span class="fixed">'A'..'F'</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map digitToInt "34538"
[3,4,5,3,8]
ghci&gt; map digitToInt "FF85AB"
[15,15,8,5,10,11]
</pre>
<p>
Opačná funkce k funkci <span class="fixed">digitToInt</span> je <span class="label function">intToDigit</span>. Vezme hodnotu typu <span class="fixed">Int</span> v rozsahu <span class="fixed">0..15</span> a převede ho na číslici nebo minusku.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; intToDigit 15
'f'
ghci&gt; intToDigit 5
'5'
</pre>
<p>
Funkce <span class="label function">ord</span> a <span class="fixed">chr</span> převedou znak na jeho odpovídající číselnou hodnotu a naopak:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; ord 'a'
97
ghci&gt; chr 97
'a'
ghci&gt; map ord "abcdefgh"
[97,98,99,100,101,102,103,104]
</pre>
<p>
Rozdíl mezi hodnotami <span class="fixed">ord</span> dvou znaků se rovná jejich vzdálenosti v tabulce Unicode.
</p>
<p>
Caesarova šifra je primitivní metoda pro zakódování zpráv, která posouvá každý znak o stanovený počet pozic v abecedě. Můžeme si sami jednoduše vytvořit obměnu Ceasarovy šifry, jenom se nebudeme omezovat na abecedu.
</p>
<pre name="code" class="haskell:hs">
encode :: Int -&gt; String -&gt; String
encode shift msg =
    let ords = map ord msg
        shifted = map (+ shift) ords
    in  map chr shifted
</pre>
<p>
Zde nejprve převedeme řetězec na seznam čísel. Poté přidáme posunutí každého čísla před převedením seznamu čísel zpátky na znaky. Jestliže jste skládací kovbojové, mohli byste zapsat tělo funkce jako <span class="fixed">map (chr . (+ shift) . ord) msg</span>. Zkusme zkusit zakódovat několik zpráv.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; encode 3 "Heeeeej"
"Khhhhhm"
ghci&gt; encode 4 "Heeeeej"
"Liiiiin"
ghci&gt; encode 1 "abcd"
"bcde"
ghci&gt; encode 5 "Veselé Vánoce! Ho, ho, ho!"
"[jxjqî%[æsthj&amp;%Mt1%mt1%mt&amp;"
</pre>
<p>
Zakódovalo se to bez problémů. Rozkódování zprávy je v podstatě pouhé posunutí o stejný počet míst jako se posouvalo při zakódování, jenom na druhou stranu.
</p>
<pre name="code" class="haskell:hs">
decode :: Int -&gt; String -&gt; String
decode shift msg = encode (negate shift) msg
</pre>
<pre name="code" class="haskell:ghci">
ghci&gt; encode 3 "Jsem čajová konvička."
"Mvhp#Đdmryä#nrqylĐnd1"
ghci&gt; decode 3 "Mvhp#Đdmryä#nrqylĐnd1"
"Jsem čajová konvička."
ghci&gt; decode 5 . encode 5 $ "Tohle je věta."
"Tohle je věta."
</pre>
<a name="data-map"></a><h2><?=$contents['moduly']['subchapters']['data-map']?></h2>
<p>
Asociační seznamy (taktéž nazývané slovníky) jsou seznamy používané pro ukládání dvojic klíč-hodnota, u kterých nezáleží na pořadí. Kupříkladu můžeme použít asociační seznam na ukládání telefonních čísel, kde by telefonní čísla byla hodnotami a jména lidí by byla klíči. Nestaráme se o pořadí, v jakém jsou uloženy, stačí nám jenom získat správné telefonní číslo pro určitou osobu.
</p>
<p>
Nejzřejmější způsob znázornění asociačních seznamů v Haskellu by bylo mít seznam dvojic. První složka seznamu by byla klíč, druhá složka hodnota. Tady máme příklad asociačního seznamu s telefonními čísly:
</p>
<pre name="code" class="haskell:hs">
phoneBook =
    [("betty","555-2938")
    ,("bonnie","452-2928")
    ,("patsy","493-2928")
    ,("lucille","205-2928")
    ,("wendy","939-8282")
    ,("penny","853-2492")
    ]
</pre>
<p>
I přes zdánlivě podivné odsazení je tohle seznam dvojic řetězců. Nejběžnější úloha pro práci s asociačními seznamy je vyhledání nějaké hodnoty pomocí klíče. Napišme si funkci, která najde hodnotu podle jejího klíče.
</p>
<pre name="code" class="haskell:hs">
findKey :: (Eq k) =&gt; k -&gt; [(k,v)] -&gt; v
findKey key xs = snd . head . filter (\(k,v) -&gt; key == k) $ xs
</pre>
<p>
Celkem jednoduché. Tahle funkce vezme klíč a seznam, profiltruje seznam, takže zůstanou pouze odpovídající klíče, vybere z nich první dvojici a vrátí danou hodnotu. Ale co se stane když klíč který hledáme není přítomen v asociačním seznamu? Hmm. V tomhle případě skončíme u pokusu získat první prvek z prázdného seznamu, což vyhodí běhovou chybu. Rozhodně bychom se měli vyhnout vytváření tak lehce havarovatelných programů, takže zkusme použít datový typ <span class="fixed">Maybe</span>. Jestliže nenalezneme klíč, vrátíme hodnotu <span class="fixed">Nothing</span>. Pokud ho ale nalezneme, vrátíme <span class="fixed">Just něco</span>, kde něco je hodnota odpovídající klíči.
</p>
<pre name="code" class="haskell:hs">
findKey :: (Eq k) =&gt; k -&gt; [(k,v)] -&gt; Maybe v
findKey key [] = Nothing
findKey key ((k,v):xs) = if key == k
                            then Just v
                            else findKey key xs
</pre>
<p>
Podívejte se na typ funkce. Ta vezme klíč jež se dá porovnat, k němu nějaký asociační seznam a možná vyprodukuje nějakou hodnotu. Zní to celkem dobře.
</p>
<p>
Tohle je učebnicový příklad rekurzivní funkce pracující se seznamem. Okrajový případ, rozdělení seznamu na první prvek a zbytek, rekurzivní volání, je to tam všechno. Tohle je klasické skládání, takže se pojďme podívat, jak by se to dalo přepsat pomocí foldu.
</p>
<pre name="code" class="haskell:hs">
findKey :: (Eq k) =&gt; k -&gt; [(k,v)] -&gt; Maybe v
findKey key = foldr (\(k,v) acc -&gt; if key == k then Just v else acc) Nothing
</pre>
<div class="hintbox"><em>Poznámka:</em> je obvykle lepší použít foldy pro tuhle obyčejnou seznamovou rekurzi namísto explicitního rekurze, protože se jednodušeji čtou a rozeznávají. Každý ví, že se jedná o skládání, když vidí volání funkce <span class="fixed">foldr</span>, ale k rozpoznání explicitní rekurze je potřeba více přemýšlení.</div>
<pre name="code" class="haskell:ghci">
ghci&gt; findKey "penny" phoneBook
Just "853-2492"
ghci&gt; findKey "betty" phoneBook
Just "555-2938"
ghci&gt; findKey "wilma" phoneBook
Nothing
</pre>
<img src="legomap.png" alt="lego mapa" class="left" width="214" height="240">
<p>
Funguje to jedna radost! Jestliže máme v našem seznamu telefonní číslo holky, vybereme právě to číslo, jinak nic nevrátíme.
</p>
<p>
Právě jsme si vlastně napsali funkci <span class="fixed">lookup</span> z modulu <span class="fixed">Data.List</span>. Jestliže chceme najít odpovídající hodnotu ke klíči, musíme projít všechny prvky ze seznamu než na něj narazíme. Modul <span class="fixed">Data.Map</span> nabízí asociační seznamy, jež jsou mnohem rychlejší (protože jsou vnitřně implementovány pomocí stromů), a také poskytuje velké množství užitečných funkcí. Odteď budeme říkat, že pracujeme s mapami místo s asociačními seznamy.
</p>
<p>
Protože modul <span class="fixed">Data.Map</span> exportuje funkce, které by kolidovaly s těmi z modulů <span class="fixed">Prelude</span> a <span class="fixed">Data.List</span>, vyřešíme to kvalifikovaným importem.
<pre name="code" class="haskell:hs">
import qualified Data.Map as Map
</pre>
<p>
Vložte tuhle deklaraci importu do skriptu a poté ho načtěte do GHCi.
</p>
<p>
Pokročíme dál a podíváme se, co pro nás modul <span class="fixed">Data.Map</span> může uložit! Tady je základní přehled jeho funkcí.
</p>
<p>
Funkce <span class="label function">fromList</span> vezme asociační seznam (ve formě seznamu dvojic) a vrátí mapu těchto asociací.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.fromList [("betty","555-2938"),("bonnie","452-2928"),("lucille","205-2928")]
fromList [("betty","555-2938"),("bonnie","452-2928"),("lucille","205-2928")]
ghci&gt; Map.fromList [(1,2),(3,4),(3,2),(5,5)]
fromList [(1,2),(3,2),(5,5)]
</pre>
<p>
Pokud existují duplikátní klíče v originálním asociačním seznamu, jsou zahozeny. Takhle vypadá typ funkce <span class="fixed">fromList</span>:
</p>
<pre name="code" class="haskell:hs">
Map.fromList :: (Ord k) =&gt; [(k, v)] -&gt; Map.Map k v
</pre>
<p>
Říká, že vezme nějaký seznam dvojic typů <span class="fixed">k</span> a <span class="fixed">v</span> a vrátí mapu která zobrazí klíče typu <span class="fixed">k</span> na hodnoty typu <span class="fixed">v</span>. Všimněte si, že když jsme vyráběli asociační seznamy z běžných seznamů, klíče musely být porovnatelné (jejich typ patřil do typové třídy <span class="fixed">Eq</span>), ale teď musí být uspořádatelné. To je podstatné omezení modulu <span class="fixed">Data.Map</span>. Potřebuje, aby klíče byly uspořádatelné, a mohl je tak uspořádat do stromu.
</p>
<p>
Měli byste vždycky použít modul <span class="fixed">Data.Map</span> pro asociační seznamy, kromě případu, kdy byste měli klíče, které nejsou součástí typové třídy <span class="fixed">Ord</span>.
</p>
<p>
Nulární funkce <span class="label function">empty</span> reprezentuje prázdnou mapu. Nepožaduje žádné argumenty, pouze vrátí prázdnou mapu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.empty
fromList []
</pre>
<p>
Funkce <span class="label function">insert</span> vezme klíč, hodnotu a mapu a vrátí novou mapu, která je stejná jako ta stará, jenom má v sobě navíc vložený klíč s hodnotou.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.empty
fromList []
ghci&gt; Map.insert 3 100 Map.empty
fromList [(3,100)]
ghci&gt; Map.insert 5 600 (Map.insert 4 200 ( Map.insert 3 100  Map.empty))
fromList [(3,100),(4,200),(5,600)]
ghci&gt; Map.insert 5 600 . Map.insert 4 200 . Map.insert 3 100 $ Map.empty
fromList [(3,100),(4,200),(5,600)]
</pre>
<p>
Můžeme si napsat svou vlastní funkci <span class="fixed">fromList</span> za použití prázdné mapy, funkce <span class="fixed">insert</span> a foldu. Sledujte:
</p>
<pre name="code" class="haskell:ghci">
fromList' :: (Ord k) =&gt; [(k,v)] -&gt; Map.Map k v
fromList' = foldr (\(k,v) acc -&gt; Map.insert k v acc) Map.empty
</pre>
<p>
Je to celkem nekomplikovaný fold. Začínáme s prázdnou mapou, kterou skládáme zprava a průběžně vkládáme dvojice klíčů a hodnot do akumulátoru.
</p>
<p>
Funkce <span class="label function">null</span> ověří, jestli je mapa prázdná.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.null Map.empty
True
ghci&gt; Map.null $ Map.fromList [(2,3),(5,5)]
False
</pre>
<p>
Funkce <span class="label function">size</span> nahlásí velikost mapy.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.size Map.empty
0
ghci&gt; Map.size $ Map.fromList [(2,4),(3,3),(4,2),(5,4),(6,4)]
5
</pre>
<p>
Funkce <span class="label function">singleton</span> vezme klíč a hodnotu a vytvoří mapu o velikosti jedna.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.singleton 3 9
fromList [(3,9)]
ghci&gt; Map.insert 5 9 $ Map.singleton 3 9
fromList [(3,9),(5,9)]
</pre>
<p>
Funkce <span class="label function">lookup</span> funguje podobně jako ta z modulu <span class="fixed">Data.List</span>, jenom pracuje s mapami. Vrátí  <span class="fixed">Just něco</span>, pokud nalezne pro něco klíč, a <span class="fixed">Nothing</span>, pokud ne.
</p>
<p>
Funkce <span class="label function">member</span> je predikát, který vezme klíč a mapu a informuje, zdali se klíč v mapě vyskytuje nebo ne.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.member 3 $ Map.fromList [(3,6),(4,3),(6,9)]
True
ghci&gt; Map.member 3 $ Map.fromList [(2,5),(4,5)]
False
</pre>
<p>
Funkce <span class="label function">map</span> a <span class="label function">filter</span> fungují skoro stejně jako jejich seznamové protějšky.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.map (*100) $ Map.fromList [(1,1),(2,4),(3,9)]
fromList [(1,100),(2,400),(3,900)]
ghci&gt; Map.filter isUpper $ Map.fromList [(1,'a'),(2,'A'),(3,'b'),(4,'B')]
fromList [(2,'A'),(4,'B')]
</pre>
<p>
Funkce <span class="label function">toList</span> je opakem funkce <span class="fixed">fromList</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.toList . Map.insert 9 2 $ Map.singleton 4 3
[(4,3),(9,2)]
</pre>
<p>
Funkce <span class="label function">keys</span> a <span class="label function">elems</span> vrátí seznam klíčů, respektive hodnot. Funkce <span class="fixed">keys</span> dělá totéž co <span class="fixed">map fst . Map.toList</span> a funkce <span class="fixed">elems</span> totéž co <span class="fixed">map snd . Map.toList</span>.
</p>
<p>
Funkce <span class="label function">fromListWith</span> je senzační funkcička. Chová se stejně jako funkce <span class="fixed">fromList</span>, jenom nezahazuje duplicitní klíče, ale využije zadanou funkci pro rozhodnutí, jak s nimi naložit. Řekněme že holky mohou mít několik telefonních čísel a my máme zadán následující asociační seznam.
</p>
<pre name="code" class="haskell:hs">
phoneBook =
    [("betty","555-2938")
    ,("betty","342-2492")
    ,("bonnie","452-2928")
    ,("patsy","493-2928")
    ,("patsy","943-2929")
    ,("patsy","827-9162")
    ,("lucille","205-2928")
    ,("wendy","939-8282")
    ,("penny","853-2492")
    ,("penny","555-2111")
    ]
</pre>
<p>
Když bychom teď na vytvoření mapy použili funkci <span class="fixed">fromList</span>, ztratili bychom několik čísel! Takže uděláme tohle:
</p>
<pre name="code" class="haskell:hs">
phoneBookToMap :: (Ord k) =&gt; [(k, String)] -&gt; Map.Map k String
phoneBookToMap xs = Map.fromListWith (\number1 number2 -&gt; number1 ++ ", " ++ number2) xs
</pre>
<pre name="code" class="haskell:hs">
ghci&gt; Map.lookup "patsy" $ phoneBookToMap phoneBook
"827-9162, 943-2929, 493-2928"
ghci&gt; Map.lookup "wendy" $ phoneBookToMap phoneBook
"939-8282"
ghci&gt; Map.lookup "betty" $ phoneBookToMap phoneBook
"342-2492, 555-2938"
</pre>
<p>
Jestliže je nalezen duplicitní klíč, naše funkce je použita pro sloučení hodnot těchto klíčů do jiné hodnoty. Můžeme taktéž nejprve všechny hodnoty přetvořit na jednoprvkový seznam a poté použít operátor <span class="fixed">++</span> na přidávání dalších čísel.
</p>
<pre name="code" class="haskell:hs">
phoneBookToMap :: (Ord k) =&gt; [(k, a)] -&gt; Map.Map k [a]
phoneBookToMap xs = Map.fromListWith (++) $ map (\(k,v) -&gt; (k,[v])) xs
</pre>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.lookup "patsy" $ phoneBookToMap phoneBook
["827-9162","943-2929","493-2928"]
</pre>
<p>
Nádhera! Další možné použití je v případě, kdy vytváříme mapu z asociačního seznamu čísel, u kterého si chceme uchovat pouze největší z hodnot, jakmile je nalezen duplicitní klíč.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.fromListWith max [(2,3),(2,5),(2,100),(3,29),(3,22),(3,11),(4,22),(4,15)]
fromList [(2,100),(3,29),(4,22)]
</pre>
<p>
Nebo bychom si hodnoty odpovídajících klíčů mohli přát sečíst.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.fromListWith (+) [(2,3),(2,5),(2,100),(3,29),(3,22),(3,11),(4,22),(4,15)]
fromList [(2,108),(3,62),(4,37)]
</pre>
<p>
Funkce <span class="label function">insertWith</span> je ve stejném vztahu s funkcí <span class="fixed">insert</span>, jako je funkce <span class="fixed">fromListWith</span> s funkcí <span class="fixed">fromList</span>. Vloží dvojici klíč-hodnota do mapy, ale pokud tato mapa již obsahuje daný klíč, zadaná funkce stanoví, co se má provést.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Map.insertWith (+) 3 100 $ Map.fromList [(3,4),(5,103),(6,339)]
fromList [(3,104),(5,103),(6,339)]
</pre>
<p>
Tohle je pouze několik funkcí z modulu <span class="fixed">Data.Map</span>. V <a href="http://www.haskell.org/ghc/docs/latest/html/libraries/containers/Data-Map.html">dokumentaci</a> můžete nalézt jejich kompletní seznam.
</p>
<a name="data-set"></a><h2><?=$contents['moduly']['subchapters']['data-set']?></h2>
<img src="legosets.png" alt="lego set" class="right" width="150" height="236">
<p>
Modul <span class="fixed">Data.Set</span> nám poskytuje množiny podobající se těm matematickým. Množiny jsou něco mezi seznamy a mapami. Všechny prvky v množině jsou unikátní. A protože jsou interně implementovány pomocí stromů (podobně jako mapy z modulu <span class="fixed">Data.Map</span>), jsou taktéž seřazeny. Zjišťování příslušnosti, vkládání, mazání atd. je mnohem rychlejší než provádění stejných akcí se seznamy. Nejobvyklejšími operacemi na množinách jsou vkládání do množiny, zjišťování příslušnosti a převádění množiny na seznam.
</p>
<p>
Protože názvy funkcí z modulu <span class="fixed">Data.Set</span> hodně kolidují s názvy v modulech <span class="fixed">Prelude</span> a <span class="fixed">Data.List</span>, musíme provést kvalifikovaný import.
</p>
<p>
Vložte tento příkaz do skriptu:
</p>
<pre name="code" class="haskell:ghci">
import qualified Data.Set as Set
</pre>
<p>
&hellip; a poté tento skript načtěte přes GHCi.
</p>
<p>
Řekněme že máme dva různé texty. Chceme zjistit, jaké znaky jsou použity v obou dvou.
</p>
<pre name="code" class="haskell:ghci">
text1 = "Měl jsem zrovna anime sen. Anime... Skutečnost... Jak moc se liší?"
text2 = "Stařík převrhl popelnici a teď jsou odpadky rozházené po celém mém trávníku!"
</pre>
<p>
Funkce <span class="label function">fromList</span> funguje zhruba tak jak byste čekali. Vezme libovolný seznam a převede ho na množinu.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let set1 = Set.fromList text1
ghci&gt; let set2 = Set.fromList text2
ghci&gt; set1
fromList " .?AJMSaceijklmnorstuvzíčěš"
ghci&gt; set2
fromList " !Sacdehijklmnoprstuvyzáéíďř"
</pre>
<p>
Jak můžete vidět, položky jsou seřazeny a každý prvek je unikátní. A teď se použijeme funkci <span class="label function">intersection</span>, abychom se podívali, které prvky mají oba texty společné.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.intersection set1 set2
fromList " Saceijklmnorstuvzí"
</pre>
<p>
Můžeme také využít funkci <span class="label function">difference</span> na zjištění, která písmena jsou obsažena v první množině, ale ne ve druhé, a naopak.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.difference set1 set2
fromList ".?AJMčěš"
ghci&gt; Set.difference set2 set1
fromList "!dhpyáéďř"
</pre>
<p>
Nebo si můžeme nechat vypsat všechny unikátní znaky v obou řetězcích pomocí funkce <span class="label function">union</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.union set1 set2
fromList " !.?AJMSacdehijklmnoprstuvyzáéíčďěřš"
</pre>
<p>
Funkce <span class="label function">null</span>, <span class="label function">size</span>, <span class="label function">member</span>, <span class="label function">empty</span>, <span class="label function">singleton</span>, <span class="label function">insert</span> a <span class="label function">delete</span> fungují přesně tak jak byste čekali.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.null Set.empty
True
ghci&gt; Set.null $ Set.fromList [3,4,5,5,4,3]
False
ghci&gt; Set.size $ Set.fromList [3,4,5,3,4,5]
3
ghci&gt; Set.singleton 9
fromList [9]
ghci&gt; Set.insert 4 $ Set.fromList [9,3,8,1]
fromList [1,3,4,8,9]
ghci&gt; Set.insert 8 $ Set.fromList [5..10]
fromList [5,6,7,8,9,10]
ghci&gt; Set.delete 4 $ Set.fromList [3,4,5,4,3,4,5]
fromList [3,5]
</pre>
<p>
Také můžeme zjišťovat, zdali je určitá množina podmnožinou či vlastní podmnožinou dané množiny. Množina A je podmnožinou množiny B, jestliže B obsahuje všechny prvky z množiny A. Množina A je vlastní podmnožina množiny B, jestliže B obsahuje všechny prvky z množiny A, ale v množině B se musí nacházet více prvků než v A.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.fromList [2,3,4] `Set.isSubsetOf` Set.fromList [1,2,3,4,5]
True
ghci&gt; Set.fromList [1,2,3,4,5] `Set.isSubsetOf` Set.fromList [1,2,3,4,5]
True
ghci&gt; Set.fromList [1,2,3,4,5] `Set.isProperSubsetOf` Set.fromList [1,2,3,4,5]
False
ghci&gt; Set.fromList [2,3,4,8] `Set.isSubsetOf` Set.fromList [1,2,3,4,5]
False
</pre>
<p>
Rovněž můžeme v množinách využívat funkce <span class="label function">map</span> a <span class="label function">filter</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; Set.filter odd $ Set.fromList [3,4,5,6,7,2,3,4]
fromList [3,5,7]
ghci&gt; Set.map (+1) $ Set.fromList [3,4,5,6,7,2,3,4]
fromList [3,4,5,6,7,8]
</pre>
<p>
Množiny jsou často používány pro vyčištění seznamu od duplikátů, a to tak, že se seznam převede na množinu pomocí funkce <span class="fixed">fromList</span>, která se následně převede zpět na seznam pomocí funkce <span class="label function">toList</span>. Funkce <span class="fixed">nub</span> z modulu <span class="fixed">Data.List</span> tohle umí také, ale vyřazení duplikátů z většího seznamů je mnohem rychlejši, když ho nacpeme do množiny a poté ho přeměníme zpátky na seznam, než abychom použili funkci <span class="fixed">nub</span>. Jenže funkci <span class="fixed">nub</span> stačí, aby prvky daného seznamu byly součástí typové třídy <span class="fixed">Eq</span>, kdežto pokud chcete nacpat nějaké prvky do množiny, musí patřit do typové třídy <span class="fixed">Ord</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let setNub xs = Set.toList $ Set.fromList xs
ghci&gt; setNub "JAK TI DUPOU KRÁLÍCI?"
" ?ACDIJKLOPRTUÁÍ"
ghci&gt; nub "JAK TI DUPOU KRÁLÍCI?"
"JAK TIDUPORÁLÍC?"
</pre>
<p>
Naše funkce <span class="fixed">setNub</span> je na větších seznamech obecně rychlejší než funkce <span class="fixed">nub</span>, ale jak můžete vidět, tak funkce <span class="fixed">nub</span> zachovává pořadí prvků v seznamu, zatímce funkce <span class="fixed">setNub</span> ne.
</p>
<a name="vytvareni-vlastnich-modulu"></a><h2><?=$contents['moduly']['subchapters']['vytvareni-vlastnich-modulu']?></h2>
<img src="making_modules.png" alt="vytváření modulů" class="right" width="345" height="224">
<p>
Zkoumali jsme zatím pár skvělých modulů, ale jak si vytvoříme vlastní? Téměř každý programovací jazyk nám umožňuje rozdělit náš kód do více souborů a v Haskellu tomu není jinak. Při psaní programů je dobrým zvykem vzít všechny funkce a data, jež slouží k podobnému účelu, a vložit do modulu. Takto můžete jednoduše opětovně použít tyto funkce v jiných programech pouhým importováním daného modulu.
</p>
<p>
Podívejme se na to, jak bychom si mohli napsat naše vlastní moduly vytvořením malého modulu, který poskytuje funkce pro výpočet objemu a povrchu několika geometrických objektů. Začneme vytvořením souboru nazvaného <span class="fixed">Geometry.hs</span>.
</p>
<p>
Řekněme, že modul <i>exportuje</i> funkce. Co to znamená je to, že když importuji modul, mohu používat funkce, které exportuje. Mohou v něm být definovány funkce volající jiné vnitřní funkce, avšak my uvidíme a budeme používat pouze funkce exportované modulem.
</p>
<p>
Na začátku modulu si stanovíme jeho název. Jestliže máme soubor s názvem <span class="fixed">Geometry.hs</span>, měli bychom náš modul pojmenovat <span class="fixed">Geometry</span>. Poté určíme funkce, které budou exportovány, a pak začneme psát funkce. Takže začneme tímhle.
</p>
<pre name="code" class="haskell:ghci">
module Geometry
( sphereVolume
, sphereArea
, cubeVolume
, cubeArea
, cuboidArea
, cuboidVolume
) where
</pre>
<p>
Jak můžete vidět, budeme počítat povrchy a objemy koulí, krychlí a kvádrů. Pustíme se tedy do toho a definujeme si naše funkce:
</p>
<pre name="code" class="haskell:ghci">
module Geometry
( sphereVolume
, sphereArea
, cubeVolume
, cubeArea
, cuboidArea
, cuboidVolume
) where

sphereVolume :: Float -&gt; Float
sphereVolume radius = (4.0 / 3.0) * pi * (radius ^ 3)

sphereArea :: Float -&gt; Float
sphereArea radius = 4 * pi * (radius ^ 2)

cubeVolume :: Float -&gt; Float
cubeVolume side = cuboidVolume side side side

cubeArea :: Float -&gt; Float
cubeArea side = cuboidArea side side side

cuboidVolume :: Float -&gt; Float -&gt; Float -&gt; Float
cuboidVolume a b c = rectangleArea a b * c

cuboidArea :: Float -&gt; Float -&gt; Float -&gt; Float
cuboidArea a b c = rectangleArea a b * 2 + rectangleArea a c * 2 + rectangleArea c b * 2

rectangleArea :: Float -&gt; Float -&gt; Float
rectangleArea a b = a * b
</pre>
<p>
Máme tu celkem standardní geometrii, i když je tu pár věcí k povšimnutí. Protože je krychle speciální případ kvádru, definovali jsme si jeho povrch a objem tím, že jsme s ní nakládali jako s kvádrem jehož strany mají stejnou délku. Taktéž jsme si definovali pomocnou funkci nazvanou <span class="fixed">rectangleArea</span>, která vypočítá obsah obdelníku v závislosti na délce jeho stran. Je to poměrně triviální, protože to je pouhé násobení. Všimněte si, že jsme tuto funkci využili v definicích jiných funkcí (jmenovitě <span class="fixed">cuboidArea</span> a <span class="fixed">cuboidVolume</span>), ale neexportovali jsme ji! Protože chceme, aby náš modul poskytoval pouze funkce pro zpracovávání trojdimenzionálních objektů, použili jsme funkci <span class="fixed">rectangleArea</span>, ale neexportujeme ji.
</p>
<p>
Když vytváříme modul, obvykle exportujeme pouze ty funkce, které se chovají trochu jako rozhraní k našemu modulu, takže je samotná implementace skrytá. Jestliže někdo hodlá použít náš modul <span class="fixed">Geometry</span>, nebude se muset zabývat funkcemi jež neexportujeme. Můžeme se rozhodnout tyto funkce úplně změnit nebo je odstranit v novější verzi (mohli bychom odstranit funkci <span class="fixed">rectangleArea</span> a místo ní použít operátor násobení) a nikomu by to nevadilo, protože je vůbec neposkytujeme.
</p>
<p>
Pro použití našeho modulu stačí napsat:
</p>
<pre name="code" class="haskell:ghci">
import Geometry
</pre>
<p>
Přičemž soubor <span class="fixed">Geometry.hs</span> musí být ve stejném adresáři jako je program, který ho importuje.
</p>
<p>
Moduly také mohou být strukturovány hierarchicky. Každý modul může mít několik podmodulů a ty mohou mít své vlastní podmoduly. Oddělíme si naše funkce, aby <span class="fixed">Geometry</span> byl modul mající tři podmoduly, každý pro jiný typ objektů.
</p>
<p>
Nejprve si vytvoříme adresář nazvaný <span class="fixed">Geometry</span>. Dávejte pozor na velké písmeno G. V tomto adresáři si vytvoříme tři soubory: <span class="fixed">sphere.hs</span>, <span class="fixed">cuboid.hs</span> a <span class="fixed">cube.hs</span>. Tady je výpis toho, co tyto soubory mají obsahovat:
</p>
<p>
<span class="fixed">sphere.hs</span>
</p>
<pre name="code" class="haskell:ghci">
module Geometry.Sphere
( volume
, area
) where

volume :: Float -&gt; Float
volume radius = (4.0 / 3.0) * pi * (radius ^ 3)

area :: Float -&gt; Float
area radius = 4 * pi * (radius ^ 2)
</pre>
<p>
<span class="fixed">cuboid.hs</span>
</p>
<pre name="code" class="haskell:ghci">
module Geometry.Cuboid
( volume
, area
) where

volume :: Float -&gt; Float -&gt; Float -&gt; Float
volume a b c = rectangleArea a b * c

area :: Float -&gt; Float -&gt; Float -&gt; Float
area a b c = rectangleArea a b * 2 + rectangleArea a c * 2 + rectangleArea c b * 2

rectangleArea :: Float -&gt; Float -&gt; Float
rectangleArea a b = a * b
</pre>
<p>
<span class="fixed">cube.hs</span>
</p>
<pre name="code" class="haskell:ghci">
module Geometry.Cube
( volume
, area
) where

import qualified Geometry.Cuboid as Cuboid

volume :: Float -&gt; Float
volume side = Cuboid.volume side side side

area :: Float -&gt; Float
area side = Cuboid.area side side side
</pre>
<p>
Prima! Takže první je modul <span class="fixed">Geometry.Sphere</span>. Všimněte si, že je uložen v adresáři nazvaném <span class="fixed">Geometry</span> a v modulu je pojmenován jako <span class="fixed">Geometry.Sphere</span>. Totéž jsme udělali s kvádrem. Taktéž si všimněte, že jsme ve všech třech podmodulech definovali funkce se stejnými názvy. Můžeme si to dovolit, protože se jedná o oddělené moduly. Dále chceme použít v modulu <span class="fixed">Geometry.Cube</span> funkce z modulu <span class="fixed">Geometry.Cuboid</span>, ale nemůžeme toho docílit obyčejným příkazem <span class="fixed">import Geometry.Cuboid</span>, protože by se exportovaly funkce se stejnými názvy jako jsou v <span class="fixed">Geometry.Cube</span>. To je důvod, proč jsme použili kvalifikovaný import a všechno je v pořádku.
</p>
<p>
Tak když teď založíme soubor nacházející se ve stejné úrovni jako je adresář <span class="fixed">Geometry</span>, můžeme napsat, řekněme:
</p>
<pre name="code" class="haskell:ghci">
import Geometry.Sphere
</pre>
<p>
Poté zavoláme funkce <span class="fixed">area</span> a <span class="fixed">volume</span> a ty nám vypočítají povrch a objem koule. Když bychom si chtěli pohrát se dvěma nebo více těmito moduly, musíme je importovat kvalifikovaně, protože exportují funkce se stejnými názvy. Takže napíšeme něco takového:
</p>
<pre name="code" class="haskell:ghci">
import qualified Geometry.Sphere as Sphere
import qualified Geometry.Cuboid as Cuboid
import qualified Geometry.Cube as Cube
</pre>
<p>
Pak máme možnost zavolat funkce <span class="fixed">Sphere.area</span>, <span class="fixed">Sphere.volume</span>, <span class="fixed">Cuboid.area</span> atd. a každá nám vypočítá povrch nebo objem odpovídajícího objektu.
</p>
<p>
Až budete příště vytvářet soubor, který je opravdu velký a obsahuje hodně funkcí, zkuste se podívat, které funkce slouží společnému účelu a zkuste je oddělit do vlastního modulu. Jakmile bude váš program potřebovat nějakou funkci z tohoto modulu, bude pak pouze stačit ho naimportovat.
</p>
