<div class="english-version"><a href="http://learnyouahaskell.com/types-and-typeclasses">English version</a></div>
<h1><?=$contents['typy-a-typove-tridy']['title']?></h1>
<a name="verte-typum"></a><h2>Věřte typům</h2>
<img src="images/cow.png" alt="búú" class="left" width="180" height="127">
<p>
Již jsme zmínili, že Haskell má statický typový systém. Typ každého výrazu je znám už v době překladu, což vede k bezpečnějšímu kódu. Pokud napíšete program, kde se pokusíte dělit booleovský typ číslem, ani se ho nepodaří přeložit. To je dobré, protože je lepší odchytávat chyby tohoto druhu v čase překladu než aby program havaroval. Všechno v Haskellu má svůj typ, takže toho překladač ví o vašem programu celkem hodně, než ho vůbec začne překládat.
</p>
<p>
Na rozdíl od Javy nebo Pascalu má Haskell odvozování typů. Pokud napíšete číslo, nemusíte Haskellu říkat, že to je číslo. Může si ho <i>odvodit</i> sám, takže nemusíte explicitně vypisovat typy svých funkcí a výrazů pro jejich funkčnost. Zabývali jsme se základy Haskellu a na typy jsme se podívali jenom zběžně. Avšak porozumění typovému systému je velmi důležitá součást učení se Haskellu.
</p>
<p>
Typ je něco jako štítek, který je na každém výrazu. Říká nám, do jaké kategorie věcí výraz patří. Výraz <span class="fixed">True</span> je booleovský, <span class="fixed">"ahoj"</span> je řetězec apod.
</p>
<p>
A teď použijeme GHCi na zjištění typu nějakých výrazů. Učiníme tak pomocí příkazu <span class="fixed">:t</span>, za který stačí napsat platný výraz pro zjištění jeho typu. Tak to teda prubneme.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t 'a'
'a' :: Char
ghci&gt; :t True
True :: Bool
ghci&gt; :t "NAZDAR!"
"NAZDAR!" :: [Char]
ghci&gt; :t (True, 'a')
(True, 'a') :: (Bool, Char)
ghci&gt; :t 4 == 5
4 == 5 :: Bool
</pre>
<p>
<img src="images/bomb.png" alt="bomba" class="right" width="171" height="144">
Zde vidíme, že napsáním <span class="fixed">:t</span> a výrazu vypíše zadaný výraz, následovaný <span class="fixed">::</span> a jeho typem. Čtyři tečky <span class="fixed">::</span> se čtou jako „má typ“. Explicitní typy jsou vždy označovány tak, že mají počáteční písmeno velké. Výraz <span class="fixed">'a'</span>, jak můžeme vidět, má typ <span class="fixed">Char</span>. Není těžké usoudit, že se jedná o znak (<i>character</i>). Výraz <span class="fixed">True</span> je typu <span class="fixed">Bool</span>. To dává smysl. Ale co je tohle? Prozkoumání typu výrazu <span class="fixed">"NAZDAR!"</span> vypsalo <span class="fixed">[Char]</span>. Hranaté závorky symbolizují seznam. Takže to čteme jako <i>seznam znaků</i>. Na rozdíl od seznamů, každá n-tice určité délky (arity) má svůj vlastní typ. Takže výraz <span class="fixed">(True, 'a')</span> má typ <span class="fixed">(Bool, Char)</span>, kdežto výraz jako <span class="fixed">('a','b','c')</span> by měl mít typ <span class="fixed">(Char, Char, Char)</span>. Výraz <span class="fixed">4 == 5</span> bude vždycky vracet <span class="fixed">False</span>, tedy je typu <span class="fixed">Bool</span>.
</p>
<p>
Funkce také mají typy. Když si píšeme vlastní funkci, můžeme se rozhodnout jí explicitně deklarovat typ. To je obecně považováno za dobrý zvyk, pokud se ale nejedná o velmi krátkou funkci. Zkusíme deklarovat typ funkcím, jež jsme zatím vytvořili. Pamatujete si na ten generátor seznamu, který filtroval řetězec, aby z něj zůstala pouze velká písmena? Tady je ukázáno, jak vypadá s typovou deklarací.
</p>
<pre name="code" class="haskell: hs">
removeNonUppercase :: [Char] -&gt; [Char]
removeNonUppercase st = [ c | c &lt;- st, c `elem` ['A'..'Z']]
</pre>
<p>
Funkce <span class="fixed">removeNonUppercase</span> má typ <span class="fixed">[Char] -&gt; [Char]</span>, což znamená, že se řetězec zobrazí na řetězec. Je tomu tak, protože vezme řetězec jako parametr a vrátí jiný jako výsledek. Typ <span class="fixed">[Char]</span> má synonymum <span class="fixed">String</span>, takže je srozumitelnější napsat <span class="fixed">removeNonUppercase :: String -&gt; String</span>. Nemusíme psát k této funkci její typ, protože si překladač může sám odvodit, že se jedná o funkci z řetězce do řetězce, ale přesto jsme to udělali. Ale jak zapíšeme typ funkce, která požaduje několik parametrů? Zde je jednoduchá funkce, jež vezme tři celá čísla a sečte je:
</p>
<pre name="code" class="haskell: hs">
addThree :: Int -&gt; Int -&gt; Int -&gt; Int
addThree x y z = x + y + z
</pre>
<p>
Parametry jsou odděleny šipkou <span class="fixed">-&gt;</span> a tím pádem nejsou parametry a návratový typ explicitně odlišeny. Návratový typ je poslední položka v deklaraci a parametry jsou tady ty první tři položky. Později uvidíme, proč jsou všechny pouze odděleny <span class="fixed">-&gt;</span>, místo aby bylo více zřejmé oddělení parametrů od návratových typů, jako třeba <span class="fixed">Int, Int, Int -&gt; Int</span> nebo podobně.
</p>
<p>
Pokud chcete napsat ke své funkci typovou deklaraci, ale nejste si jistí, jaká by měla být, můžete vždycky funkci napsat bez ní a ověřit si to pomocí <span class="fixed">:t</span>. Funkce jsou taktéž výrazy, takže <span class="fixed">:t</span> bude s funkcemi fungovat bez problémů.
</p>
<p>
Tady je přehled několika běžných typů.
</p>
<p>
<span class="label type">Int</span> zastupuje celá čísla. Třeba <span class="fixed">7</span> může být <span class="fixed">Int</span>, ale <span class="fixed">7.2</span> ne. Typ <span class="fixed">Int</span> je ohraničený, což znamená, že má minimální a maximální hodnotu. Na 32bitových počítačích je obvykle maximum hodnoty typu <span class="fixed">Int</span> 2147483647 a minimum -2147483648.
</p>
<p>
<span class="label type">Integer</span> zastupuje, ehm&hellip; také celá čísla. Hlavní rozdíl je v tom, že není ohraničený, takže může být použit pro vyjádření fakt fakt velkých čísel. Tím myslím fakt velkých. Nicméně typ <span class="fixed">Int</span> je efektivnější.
</p>
<pre name="code" class="haskell: hs">
factorial :: Integer -&gt; Integer
factorial n = product [1..n]
</pre>
<pre name="code" class="haskell: ghci">
ghci&gt; factorial 50
30414093201713378043612608166064768844377641568960512000000000000
</pre>
<p>
<span class="label type">Float</span> je reálné číslo s plovoucí desetinnou čárkou.
</p>
<pre name="code" class="haskell: hs">
circumference :: Float -&gt; Float
circumference r = 2 * pi * r
</pre>
<pre name="code" class="haskell: ghci">
ghci&gt; circumference 4.0
25.132742
</pre>
<p>
<span class="label type">Double</span> je reálné číslo s plovoucí desetinnou čárkou a větší přesností!
</p>
<pre name="code" class="haskell: hs">
circumference' :: Double -&gt; Double
circumference' r = 2 * pi * r
</pre>
<pre name="code" class="haskell: ghci">
ghci&gt; circumference' 4.0
25.132741228718345
</pre>
<p>
<span class="label type">Bool</span> je booleovský (logický) typ. Může nabývat pouze dvou hodnot: <span class="fixed">True</span> and <span class="fixed">False</span>.
</p>
<p>
<span class="label type">Char</span> zastupuje znak. Znak se zapisuje mezi dvě jednoduché uvozovky. Seznam znaků je řetězec.
</p>
<p>
N-tice mají také svůj typ, ale ten závisí na jejich velikosti a typu jednotlivých složek, takže může být teoreticky nekonečně typů n-tic, což je víc než můžeme popsat v tomhle tutoriálu. Všimněte si, že prázdná n-tice <span class="label type">()</span> je také typ, který může nabývat pouze jedné hodnoty: <span class="fixed">()</span>.
</p>
<a name="typove-promenne"></a><h2>Typové proměnné</h2>
<p>
Jaký si myslíte že je typ funkce <span class="fixed">head</span>? Funkce <span class="fixed">head</span> vezme seznam věcí libovolného typu a vrátí první prvek, takže jaký by to mohl být typ? Podívejme se na to!
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t head
head :: [a] -&gt; a
</pre>
<p>
<img src="images/box.png" alt="krabice" class="left" width="130" height="93">
Hmmm! Co je to <span class="fixed">a</span>? Je to typ? Vzpomeňte si, že jsme předtím tvrdili, že typy se zapisují velkým počátečním písmenem, takže to není zrovna typ. Protože to není napsáno velkým písmenem, je to ve skutečnosti <em>typová proměnná</em>. Což znamená, že <span class="fixed">a</span> může být jakéhokoliv typu. Je to podobné jako generika v jiných jazycích, jenomže haskellová typová proměnná je mnohem užitečnější, protože nám umožňuje jednoduše psát obecné funkce, pokud není potřeba určitých typových specifik. Funkce, které obsahují typové proměnné, se nazývají <em>polymorfní funkce</em>. Typová deklarace funkce <span class="fixed">head</span> uvádí, že vezme seznam libovolného typu a vrací jeden prvek stejného typu.
</p>
<p>
I když typové proměnné mohou mít názvy delší než jeden znak, obvykle je pojmenováváme a, b, c, d&hellip;
</p>
<p>
Pamatujete si funkci <span class="fixed">fst</span>? Vrátí první složku z dvojice. Prozkoumejme její typ.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t fst
fst :: (a, b) -&gt; a
</pre>
<p>
Vidíme, že <span class="fixed">fst</span> vezme n-tici, jež obsahuje dva typy a vrátí prvek stejného typu, jaký má první složka. To je důvod, proč můžeme použít <span class="fixed">fst</span> na dvojici, která obsahuje jakékoliv dva typy. Všimněte si, že ačkoliv jsou <span class="fixed">a</span> a <span class="fixed">b</span> různé typové proměnné, nemusí mít rozdílný typ. Pouze to uvádí, že je typ první složky a návratové hodnoty stejný.
</p>
<a name="zaklady-typovych-trid"></a><h2>Základy typových tříd</h2>
<img src="images/classes.png" alt="třída" class="right" width="210" height="158">
<p>
Typová třída je druh rozhraní, které definuje nějaké chování. Pokud je typ součástí nějaké typové třídy, znamená to, že podporuje a implementuje chování, jež ta typová třída definuje. Hodně lidí, co někdy programovalo v objektově orientovaných jazycích, je zmatených, protože si myslí, že jsou stejné jako objektové třídy. No, nejsou. Můžete je považovat za taková lepší javová rozhraní.
</p>
<p>
Jaký typ má funkce <span class="fixed">==</span>?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t (==)
(==) :: (Eq a) =&gt; a -&gt; a -&gt; Bool
</pre>
<div class="hintbox"><em>Poznámka</em>: operátor rovnosti <span class="fixed">==</span> je funkce. Stejně jako <span class="fixed">+</span>, <span class="fixed">*</span>, <span class="fixed">-</span>, <span class="fixed">/</span> a skoro všechny operátory. Když se funkce skládá pouze ze zvláštních znaků, je obvykle považována za infixovou. Pokud se chceme podívat na její typ, předat ji jiné funkci nebo ji zavolat prefixově, musíme ji obklopit kulatými závorkami.</div>
<p>
Zajímavé. Vidíme tu novou věc, symbol <span class="fixed">=&gt;</span>. Údaje před symbolem <span class="fixed">=&gt;</span> se nazývají <em>typová omezení</em>. Můžeme přečíst předchozí deklaraci typu jako: funkce rovnosti vezme dvě libovolné hodnoty, které jsou stejného typu, a vrátí <span class="fixed">Bool</span>. Typ těchto dvou hodnot musí být instancí třídy <span class="fixed">Eq</span> (to bylo typové omezení).
</p>
<p>
Typová třída <span class="fixed">Eq</span> poskytuje rozhraní pro testování rovnosti. Každý typ, u něhož dává smysl testovat dvě jeho hodnoty na rovnost, by měl být instancí třídy <span class="fixed">Eq</span>. Všechny standardní haskellové typy s výjimkou IO (typ, který obstarává vstup a výstup) a funkcí jsou součástí typové třídy <span class="fixed">Eq</span>.
</p>
<p>
Funkce <span class="fixed">elem</span> je typu <span class="fixed">(Eq a) =&gt; a -&gt; [a] -&gt; Bool</span>, protože využívá funkci <span class="fixed">==</span> v seznamu, aby ověřila, jestli seznam obsahuje požadovanou hodnotu.
</p>
<p>
Některé základní typové třídy:
</p>
<p>
<span class="label class">Eq</span> je použita pro typy podporující testování rovnosti. Funkce, implementované v této třídě, jsou <span class="fixed">==</span> a <span class="fixed">/=</span>. Takže pokud je u nějaké typové proměnné omezení třídou <span class="fixed">Eq</span>, funkce používá ve své definici operátor <span class="fixed">==</span> nebo <span class="fixed">/=</span>. Všechny typy, jež jsme zmínili předtím, kromě funkcí, jsou součástí <span class="fixed">Eq</span>, takže mohou být testovány na rovnost.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; 5 == 5
True
ghci&gt; 5 /= 5
False
ghci&gt; 'a' == 'a'
True
ghci&gt; "Ho, ho" == "Ho, ho"
True
ghci&gt; 3.432 == 3.432
True
</pre>
<p>
<span class="label class">Ord</span> je typová třída podporující porovnávání. Je určena pro typy, na nichž je definováno uspořádání.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t (&gt;)
(&gt;) :: (Ord a) =&gt; a -&gt; a -&gt; Bool
</pre>
<p>
Všechny zatím probrané typy, kromě typů funkcí, jsou součástí třídy <span class="fixed">Ord</span>. Typová třída <span class="fixed">Ord</span> pokrývá standardní porovnávací funkce jako jsou <span class="fixed">&gt;</span>, <span class="fixed">&lt;</span>, <span class="fixed">&gt;=</span> a <span class="fixed">&lt;=</span>. Funkce <span class="fixed">compare</span> vezme dvě instance třídy <span class="fixed">Ord</span> stejného typu a vrátí jejich uspořádání. Pro uspořádání je určeny typ <span class="label type">Ordering</span>, který může nabývat hodnot <span class="fixed">GT</span>, <span class="fixed">LT</span> nebo <span class="fixed">EQ</span>, které znamenají (v tomto pořadí) <i>větší než</i>, <i>menší než</i> a <i>rovný</i>.
</p>
<p>
Aby mohl být typ instancí <span class="fixed">Ord</span>, musí nejprve patřit do prestižní a exkluzivní třídy <span class="fixed">Eq</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; "Abrakadabra" &lt; "Zebra"
True
ghci&gt; "Abrakadabra" `compare` "Zebra"
LT
ghci&gt; 5 &gt;= 2
True
ghci&gt; 5 `compare` 3
GT
</pre>
<p>
Instance třídy <span class="label class">Show</span> může být převedena do řetězce. Všechny zatím probrané typy, kromě typů funkcí, jsou součástí třídy <span class="fixed">Show</span>. Nejpoužívanější funkce, jež je zahrnutá v typové třídě <span class="fixed">Show</span>, je <span class="fixed">show</span>. Vezme hodnotu, která je instancí typu <span class="fixed">Show</span> a převede ji na řetězec.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; show 3
"3"
ghci&gt; show 5.334
"5.334"
ghci&gt; show True
"True"
</pre>
<p><span class="label class">Read</span> je něco jako opačná typová třída k <span class="fixed">Show</span>. Funkce <span class="fixed">read</span> vezme řetězec a vrátí typ, který je instancí třídy <span class="fixed">Read</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; read "True" || False
True
ghci&gt; read "8.2" + 3.8
12.0
ghci&gt; read "5" - 2
3
ghci&gt; read "[1,2,3,4]" ++ [3]
[1,2,3,4,3]
</pre>
<p>
Zatím v pohodě. Opět jsou všechny typy zahrnuty v této typové třídě. Ale co se stane, jestliže zkusíme napsat <span class="fixed">read "4"</span>?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; read "4"
&lt;interactive&gt;:1:0:
    Ambiguous type variable `a' in the constraint:
      `Read a' arising from a use of `read' at &lt;interactive&gt;:1:0-7
    Probable fix: add a type signature that fixes these type variable(s)
</pre>
<p>
GHCi se nám snaží sdělit, že neví, jakou hodnotu chceme vrátit. Všimněte si, že jsme v předchozích příkladech s <span class="fixed">read</span> později něco dělali s výsledkem. Pomocí toho GHCi mohlo odvodit, jaký druh výsledku chceme dostat z funkce <span class="fixed">read</span>. Pokud by to byl booleovský typ, GHCi by to vědělo a vrátilo by to jako <span class="fixed">Bool</span>. Ale tady pouze ví, že chceme nějaký typ, který je součástí třídy <span class="fixed">Read</span>, jenže neví, jaký. Podívejme se blíže na typ funkce <span class="fixed">read</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t read
read :: (Read a) =&gt; String -&gt; a
</pre>
<p>
Vidíte? Vrátí typ, jenž je součástí <span class="fixed">Read</span>, jenomže pokud ho nepoužijeme později, nebude mít možnost zjistit, jaký typ to je. To je důvod, proč bychom měli použít explicitní <em>typovou anotaci</em>. Typová anotace je způsob konkrétního určení typu nějakého výrazu. Uděláme to přidáním čtyř teček <span class="fixed">::</span> za výraz a poté uvedením typu. Sledujte:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; read "5" :: Int
5
ghci&gt; read "5" :: Float
5.0
ghci&gt; (read "5" :: Float) * 4
20.0
ghci&gt; read "[1,2,3,4]" :: [Int]
[1,2,3,4]
ghci&gt; read "(3, 'a')" :: (Int, Char)
(3, 'a')
</pre>
<p>
U většiny výrazů může překladač typ odvozovat sám. Ale někdy překladač neví, zdali má vrátit kupříkladu typ <span class="fixed">Int</span> nebo <span class="fixed">Float</span> pro výraz jako <span class="fixed">read "5"</span>. Protože je Haskell staticky typovaný jazyk, musí vědět všechny typy před tím, než je kód zkompilován (nebo v případě GHCi interpretován). Takže musíme říct Haskellu: „Hej, tenhle výraz má takovýhle typ, pro případ, že bys to nevěděl!“
</p>
<p>
Instance třídy <span class="label class">Enum</span> jsou sekvenčně seřazené typy &mdash; mohou být vyjmenovány. Hlavní výhoda spočívá v tom, že třída <span class="fixed">Enum</span> může být použita v rozsazích. Má definovány následníky a předchůdce, které můžeme dostat pomocí funkcí <span class="fixed">succ</span> a <span class="fixed">pred</span>. Typy, jenž jsou zahrnuty do této třídy: <span class="fixed">()</span>, <span class="fixed">Bool</span>, <span class="fixed">Char</span>, <span class="fixed">Ordering</span>, <span class="fixed">Int</span>, <span class="fixed">Integer</span>, <span class="fixed">Float</span> a <span class="fixed">Double</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; ['a'..'e']
"abcde"
ghci&gt; [LT .. GT]
[LT,EQ,GT]
ghci&gt; [3 .. 5]
[3,4,5]
ghci&gt; succ 'B'
'C'
</pre>
<p>
Instance třídy <span class="label class">Bounded</span> mají horní a spodní ohraničení.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; minBound :: Int
-2147483648
ghci&gt; maxBound :: Char
'\1114111'
ghci&gt; maxBound :: Bool
True
ghci&gt; minBound :: Bool
False
</pre>
<p>
Funkce <span class="fixed">minBound</span> a <span class="fixed">maxBound</span> jsou zajímavé, protože mají typ <span class="fixed">(Bounded a) =&gt; a</span>. Jsou v jistém smyslu polymorfní konstanty.
</p>
<p>
Všechny n-tice jsou součástí třídy <span class="fixed">Bounded</span>, pokud do ní patři i jednotlivé složky.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; maxBound :: (Bool, Int, Char)
(True,2147483647,'\1114111')
</pre>
<p>
<span class="label class">Num</span> je numerická typová třída. Její instance mají tu vlastnost, že se chovají jako čísla. Podívejme se na typ čísla.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t 20
20 :: (Num t) =&gt; t
</pre>
<p>
Vypadá to, že celá čísla jsou také polymorfní konstanty. Mohou se chovat jako typ, jenž je instancí typové třídy <span class="fixed">Num</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; 20 :: Int
20
ghci&gt; 20 :: Integer
20
ghci&gt; 20 :: Float
20.0
ghci&gt; 20 :: Double
20.0
</pre>
<p>
Toto jsou typy z typové třídy <span class="fixed">Num</span>. Jestliže se podíváme na typ operátoru <span class="fixed">*</span>, uvidíme, že akceptuje veškerá čísla.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; :t (*)
(*) :: (Num a) =&gt; a -&gt; a -&gt; a
</pre>
<p>
Vezme dvě čísla stejného typu a vrátí výsledek, jenž má shodný typ. To je důvod, proč vyhodnocení výrazu <span class="fixed">(5 :: Int) * (6 :: Integer)</span> skončí typovou chybou, kdežto <span class="fixed">5 * (6 :: Integer)</span> bude fungovat a výsledek bude typu <span class="fixed">Integer</span>.
</p>
<p>
Aby se typ mohl přidat do <span class="fixed">Num</span>, musí být už instancí tříd <span class="fixed">Show</span> a <span class="fixed">Eq</span>.
</p>
<p>
<span class="class label">Integral</span> je také numerická typová třída. Narozdíl od <span class="fixed">Num</span>, která zahrnuje všechna čísla včetně reálných a celých, <span class="fixed">Integral</span> obsahuje pouze celá čísla. V této typové třídě jsou typy <span class="fixed">Int</span> a <span class="fixed">Integer</span>.
</p>
<p>
Třída <span class="class label">Floating</span> obsahuje pouze čísla s plovoucí desetinnou čárkou, tedy <span class="fixed">Float</span> a <span class="fixed">Double</span>.
</p>
<p>
Pro zacházení s čísly je velmi užitečná funkce <span class="label function">fromIntegral</span>. Má deklarovaný typ <span class="fixed">fromIntegral :: (Num b, Integral a) =&gt; a -&gt; b</span>. Podle jejího typového omezení můžeme vidět, že vezme celé číslo a udělá z něj obecnější číslo. To je užitečné když chceme, aby spolupracovala celá a desetinná čísla. Například funkce <span class="fixed">length</span> je typu <span class="fixed">length :: [a] -&gt; Int</span>, místo aby byla obecnějšího typu <span class="fixed">(Num b) =&gt; length :: [a] -&gt; b</span>. Řekl bych, že je to z historických důvodů nebo tak něco a zdá se mi to celkem hloupé. Každopádně, jestliže zkusíme zjistit délku seznamu a poté ji přičíst třeba k <span class="fixed">3.2</span>, dostaneme chybu, protože se snažíme spojit dohromady číslo typu <span class="fixed">Int</span> a desetinné číslo. Je potřeba to obejít napsáním <span class="fixed">fromIntegral (length [1,2,3,4]) + 3.2</span>, což to vyřeší.
</p>
<p>
Všimněte si, že funkce <span class="fixed">fromIntegral</span> má několik typových omezení v definici typu. To je úplně v pořádku a jak můžete vidět, typová omezení se v kulatých závorkách oddělují čárkami.
</p>
