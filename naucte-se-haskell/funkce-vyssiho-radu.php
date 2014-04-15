<div class="english-version"><a href="http://learnyouahaskell.com/higher-order-functions">English version</a></div>
<h1 style="margin-left:-3px">Funkce vyššího řádu</h1>
<img src="images/sun.png" alt="sluníčko" class="right" width="203" height="183">
<p>
Funkce v Haskellu mohou mít jako parametr jiné funkce a stejně tak mohou být funkce návratovou hodnotou. Funkce, která provádí obě tyhle věci se nazývá funkce vyššího řádu. Funkce vyššího řádu nejsou jenom součástí haskellové praxe, ony jsou v podstatě haskellovou praxí. Ukazuje se, že když chceme zadávat výpočty pomocí definování věcí jaké <i>jsou</i>, místo definování kroků, které změní nějaké stavy a možná pomocí cyklů, funkce vyššího řádu jsou nezbytné. Je to velice silný nástroj na řešení problémů a způsob myšlení o programech.
</p>
<a name="curryfikovane-funkce"></a><h2>Curryfikované funkce</h2>
<p>
Každá funkce v Haskellu bere oficiálně pouze jeden parametr. Tak jak je možné, že jsme si předtím definovali a používali několik funkcí, jež braly více než jeden parametr? No, je to důmyslný trik! Všechny funkce, které předtím akceptovaly <i>několik parametrů</i>, byly <em>curryfikované funkce</em>. Co to znamená? Nejlépe to pochopíte na příkladu. Pojďme vzít naši dobrou známou, funkci <span class="fixed">max</span>. Vypadá to, že vezme dva parametry a vrátí ten, který je větší. Při vyhodnocování <span class="fixed">max 4 5</span> se nejprve vytvoří funkce, která vezme parametr a vrací buď <span class="fixed">4</span> a nebo ten parametr, což závisí na tom, co je větší. Poté je aplikována hodnota <span class="fixed">5</span> na tu funkci a funkce vyprodukuje náš požadovaný výsledek. Zní to jako jazykolam, ale je to ve skutečnosti vážně skvělý koncept. Následující dvě volání jsou ekvivalentní:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; max 4 5
5
ghci&gt; (max 4) 5
5
</pre>
<p class="hintbox">
<em>Poznámka překladatele</em>: pojem <i>curryfikace</i> je pojmenován (stejně jako tento programovací jazyk) podle amerického matematika a logika Haskella Curryho. Protože nebyla ustálena pravopisná podoba slova, lze se setkat i s alternativními verzemi <i>currifikace</i> nebo <i>curryifikace</i>, které označují tu stejnou věc. Curryfikace se zdá být nejsprávnější variantou.
</p>
<img src="images/curry.png" alt="haskell curry" class="left" width="160" height="238">
<p>
Vložená mezera mezi dvěma věcmi je jednoduše <b>aplikace funkce</b>. Mezera je něco jako operátor a má nejvyšší prioritu. Podívejme se na typ funkce <span class="fixed">max</span>. Je <span class="fixed">max :: (Ord a) =&gt; a -&gt; a -&gt; a</span>. To může být také zapsáno jako <span class="fixed">max :: (Ord a) =&gt; a -&gt; (a -&gt; a)</span>. Což bychom mohli číst: <span class="fixed">max</span> vezme <span class="fixed">a</span> a vrátí (to je ta šipka <span class="fixed">-&gt;</span>) funkci, která vezme nějaké <span class="fixed">a</span> a vrátí <span class="fixed">a</span>. To je důvod, proč jsou návratový typ a parametry funkce vždy odděleny pomocí šipek.
</p>
<p>
A jaký to má pro nás přínos? Jednoduše řečeno, jestliže zavoláme funkci s méně parametry, dostaneme zpátky <em>částečně aplikovanou</em> funkci, což znamená funkci, která požaduje tolik parametrů, kolik jich vynecháme. Použití častečné aplikace (zavolání funkce s méně parametry, jestli chcete) je prima cesta, jak vytvořit za běhu funkce, které můžeme poslat jiné funkci nebo je naplnit nějakými daty.
</p>
<p>
Podívejte se na tuhle až urážlivě jednoduchou funkci:
</p>
<pre name="code" class="haskell:ghci">
multThree :: (Num a) =&gt; a -&gt; a -&gt; a -&gt; a
multThree x y z = x * y * z
</pre>
<p>
Co se doopravdy děje, když provedeme <span class="fixed">multThree 3 5 9</span> nebo <span class="fixed">((multThree 3) 5) 9</span>? Nejprve je aplikován parametr <span class="fixed">3</span> na funkci <span class="fixed">multThree</span>, protože jsou odděleny mezerou. To vytvoří funkci, která vezme jeden parametr a vrátí funkci. Takže pak se na to aplikuje <span class="fixed">5</span>, což vytvoří funkci, která vezme parametr a vynásobí ho 15. Poté se na tu funkci aplikuje <span class="fixed">9</span> a výsledek je 135 nebo tak nějak. Zapamatujte si, že typ této funkce by se dal zapsat jako <span class="fixed">multThree :: (Num a) =&gt; a -&gt; (a -&gt; (a -&gt; a))</span>. Ta věc před první šipkou <span class="fixed">-&gt;</span> je parametr, který funkce vezme, a ta věc za ní je to co vrací. Takže naše funkce vezme <span class="fixed">a</span> a vrátí funkci typu <span class="fixed">(Num a) =&gt; a -&gt; (a -&gt; a)</span>. Podobně tato funkce vezme <span class="fixed">a</span> a vrátí funkci typu <span class="fixed">(Num a) =&gt; a -&gt; a</span>. A konečně tato funkce prostě vezme <span class="fixed">a</span> a vrátí nějaké jiné <span class="fixed">a</span>. Podívejte se na tohle:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let multTwoWithNine = multThree 9
ghci&gt; multTwoWithNine 2 3
54
ghci&gt; let multWithEighteen = multTwoWithNine 2
ghci&gt; multWithEighteen 10
180
</pre>
<p>
Zavoláním funkce s méně parametry, abych tak řekl, vytvoříme novou funkci za běhu. Co když chceme vytvořit funkci, která vezme číslo a porovná ho s číslem <span class="fixed">100</span>? Mohli bychom to napsat nějak takhle:
</p>
<pre name="code" class="haskell:hs">
compareWithHundred :: (Num a, Ord a) =&gt; a -&gt; Ordering
compareWithHundred x = compare 100 x
</pre>
<p>
Jestli že funkci zavoláme s parametrem <span class="fixed">99</span>, vrátí <span class="fixed">GT</span>. Jasná věc. Všimněte si, že <span class="fixed">x</span> je na vpravo na obou stranách rovnice. A teď přemýšlejte, co vrací výraz <span class="fixed">compare 100</span>. Vrací funkci, která vezme číslo a porovná ho s číslem <span class="fixed">100</span>. Páni! Není to ta funkce, jakou jsme hledali? Můžeme to přepsat jako:
</p>
<pre name="code" class="haskell:hs">
compareWithHundred :: (Num a, Ord a) =&gt; a -&gt; Ordering
compareWithHundred = compare 100
</pre>
<p>
Deklarace typu zůstává stejná, portože <span class="fixed">compare 100</span> vrací funkci. Porovnání funkce má typ <span class="fixed">(Ord a) =&gt; a -&gt; (a -&gt; Ordering)</span> a zavolání s parametrem <span class="fixed">100</span> vrátí typ <span class="fixed">(Num a, Ord a) =&gt; a -&gt; Ordering</span>. Vkrade se tam další typové omezení, protože číslo <span class="fixed">100</span> je součástí typové třídy <span class="fixed">Num</span>.
</p>
<div class="hintbox"><em>Hej!</em> Ujistěte se, že opravdu chápete, jak curryfikované funkce a částečná aplikace funguje, protože jsou to vážně důležitá témata!</div>
<p>
Infixová funkce může být také částečně aplikovaná za použití řezu (sekce). K rozříznutí infixové funkce ji jednoduše obklopíme kulatými závorkami a dodáme parametr na jednu stranu. To vytvoří funkci, která vezme jeden parametr a ten aplikuje na tu stranu, kde chybí operand. Příklad rozříznuté triviální funkce:
</p>
<pre name="code" class="haskell:hs">
divideByTen :: (Floating a) =&gt; a -&gt; a
divideByTen = (/10)
</pre>
<p>
Zavolání, řekněme, <span class="fixed">divideByTen 200</span> je stejné jako provedení <span class="fixed">200 / 10</span> nebo jako <span class="fixed">(/10) 200</span>. Funkce, která ověřuj, zda je zadaný znak velké písmeno:
</p>
<pre name="code" class="haskell:hs">
isUpperAlphanum :: Char -&gt; Bool
isUpperAlphanum = (`elem` ['A'..'Z'])
</pre>
<p>
Jedinou neobvyklou věcí u řezu je použití mínusu <span class="fixed">-</span>. Z definice řezu by <span class="fixed">(-4)</span> mohl být výsledek z funkce, která vezme číslo a odečte od něj čtyřku. Nicméně, z důvodů pohodlí, <span class="fixed">(-4)</span> znamená mínus čtyři. Takže když budete chtít vytvořit funkci pro odečítání čtyřky od čísla, jež dostane jako parametr, částečně aplikujte funkci <span class="fixed">subtract</span>, jako třeba: <span class="fixed">(subtract 4)</span>.
</p>
<p>
Co se stane, pokud zkusíme zadat do GHCi <span class="fixed">multThree 3 4</span>, bez toho, abychom výraz definovali pomocí konstrukce let nebo ho předali jiné funkci?
</p>
<pre name="code" class="haskell:hs">
ghci&gt; multThree 3 4
&lt;interactive&gt;:1:0:
    No instance for (Show (t -&gt; t))
      arising from a use of `print' at &lt;interactive&gt;:1:0-12
    Possible fix: add an instance declaration for (Show (t -&gt; t))
    In the expression: print it
    In a 'do' expression: print it
</pre>
<p>
GHCi nám říká, že výraz vyprodukovaný funkcí má typ <span class="fixed">a -&gt; a</span>, ale neví, jako to vypsat na obrazovku. Funkce nejsou instancí typové třídy <span class="fixed">Show</span>, takže nemůžeme získat hezkou textovou reprezentaci funkce. Když napíšeme, řekněme, <span class="fixed">1 + 1</span> do příkazové řádky GHCi, nejprve se spočítá výsledek <span class="fixed">2</span> a poté na něj zavolá funkce <span class="fixed">show</span>, aby se získala textové reprezentace toho čísla. A textová reprezentace čísla <span class="fixed">2</span> je řetězec <span class="fixed">"2"</span>, který je pak vypsán na naši obrazovku.
</p>
<a name="vyssi-rad"></a><h2>Trocha vyššího řádu je v pořádku</h2>
<p>
Funkce mohou mít jako parametry funkce a také vracet funkce. Abychom si to objasnili, vytvoříme si funkci, která vezme další funkci a aplikuje ji na něco dvakrát!
</p>
<pre name="code" class="haskell:hs">
applyTwice :: (a -&gt; a) -&gt; a -&gt; a
applyTwice f x = f (f x)
</pre>
<img src="images/bonus.png" alt="rocková chobotnice" class="right" width="166" height="190">
<p>
Jako první si všimněte deklarace typu. Předtím jsme nepotřebovali závorky, protože šipka <span class="fixed">-&gt;</span> je sama o sobě asociativní zprava. Nicméně tady jsou povinné. Ukazují, že první parametr je funkce, která něco vezme a vrátí tu stejnou věc. Druhým parametrem je něco stejného typu a co má návratovou hodnotu stejného typu. Mohli bychom číst tuhle typovou deklaci curryfikovaným způsobem, ale abychom se vyhnuli bolení hlavy, řekneme prostě, že tahle funkce vezme dva parametry a vrátí jednu věc. Prvním parametrem je nějaká funkce (typu <span class="fixed">a -&gt; a</span>) a druhý má jako typ to stejné <span class="fixed">a</span>. První funkce může být třeba <span class="fixed">Int -&gt; Int</span> nebo <span class="fixed">String -&gt; String</span> nebo cokoliv jiného. Ale druhý parametr pak musí být stejného typu.
</p>
<div class="hintbox"><em>Poznámka:</em> od teď si budeme říkat, že funkce vezme několik parametrů, přestože každá taková funkce ve skutečnosti bere pouze jeden parametr a vrácí částečně aplikovanou funkci, dokud nevrátí solidní hodnotu. Takže v zájmu jednoduchosti budeme tvrdit, že  <span class="fixed">a -&gt; a -&gt; a</span> má dva parametry, i když víme, co se doopravdy odehrává pod kapotou.</div>
<p>
Obsah této funkce je celkem srozumitelný. Prostě vezmeme parametr <span class="fixed">f</span> jako funkci, aplikujeme na něj <span class="fixed">x</span> pomocí oddělení mezerou a poté se výsledek aplikuje znovu na funkci <span class="fixed">f</span>. Kromě toho ještě nějaké blbnutí s funkcemi:
</p>
<pre name="code" class="haskell:hs">
ghci&gt; applyTwice (+3) 10
16
ghci&gt; applyTwice (++ " HAHA") "HEJ"
"HEJ HAHA HAHA"
ghci&gt; applyTwice ("HAHA " ++) "HEJ"
"HAHA HAHA HEJ"
ghci&gt; applyTwice (multThree 2 2) 9
144
ghci&gt; applyTwice (3:) [1]
[3,3,1]
</pre>
<p>
Skvělost a užitečnost částečné aplikace je zjevná. Jestliže naše funkce po nás chce, abychom ji předali funkci, která vezme pouze jeden parametr, můžeme částečně aplikovat funkci na to místo, kde bere ten jeden parametr, a poté ji předat.
</p>
<p>
A teď využijeme programování s částečnou aplikací na napsání vážně užitečné funkce, která je ve standardní knihovně. Je nazvaná <span class="fixed">zipWith</span>. Vezme funkci a dva seznamy jako parametr a poté spojí ty dva seznamy aplikací funkce na korespondující elementy. Tady je ukázána naše implementace:
</p>
<pre name="code" class="haskell:hs">
zipWith' :: (a -&gt; b -&gt; c) -&gt; [a] -&gt; [b] -&gt; [c]
zipWith' _ [] _ = []
zipWith' _ _ [] = []
zipWith' f (x:xs) (y:ys) = f x y : zipWith' f xs ys
</pre>
<p>
Podívejte se na typovou deklaraci. První parametr je funkce, která vezme dvě věci a vyprodukuje třetí. Nemusí být stejného typu, ale mohou. Druhý a třetí parametr jsou seznamy. Výsledek je také seznam. První musí být seznam typu <span class="fixed">a</span>, protože spojovací funkce má první argument typu <span class="fixed">a</span>. Druhý musí být seznam typu <span class="fixed">b</span>, protože spojovací funkce má druhý argument také typu <span class="fixed">b</span>. Výsledek je seznam věcí typu <span class="fixed">c</span>. Jestliže typová deklarace funkce říká, že akceptuje funkci typu <span class="fixed">a -&gt; b -&gt; c</span> jako parametr, bude také akceptovat funkci typu <span class="fixed">a -&gt; a -&gt; a</span>, ale opačně to neplatí! Pamatujte si, že když vytváříme funkce, zvláště ty vyššího řádu, a nejsme si jistí jejich typem, můžeme jednoduše vynechat jejich typovou deklaraci a poté se podívat pomocí příkazu <span class="fixed">:t</span> na to, co Haskell sám odvodí.
</p>
<p>
Činnost funkce je celkem podobná normální funkci <span class="fixed">zip</span>. Okrajové podmínky jsou stejné, akorát je tam navíc jeden argument, spojovací funkce, ale na tom argumentu v okrajových podmínkách nezáleží, takže na to prostě použijeme podtržítko <span class="fixed">_</span>. A část s posledním vzorem je rovněž podobná funkci <span class="fixed">zip</span>, jenom nevytváří <span class="fixed">(x,y)</span>, ale <span class="fixed">f x y</span>. Jedna funkce vyššího řádu může být použita na spoustu odlišných úkolů, pokud je napsána dostatečně obecně. Tady je malá názorná ukázka různých věcí, co naše funkce <span class="fixed">zipWith'</span> může provádět:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; zipWith' (+) [4,2,5,6] [2,6,2,3]
[6,8,7,9]
ghci&gt; zipWith' max [6,3,2,1] [7,3,1,5]
[7,3,2,5]
ghci&gt; zipWith' (++) ["foo", "bar", "baz"] [" fighters", "man", "mek"]
["foo fighters","barman","bazmek"]
ghci&gt; zipWith' (*) (replicate 5 2) [1..]
[2,4,6,8,10]
ghci&gt; zipWith' (zipWith' (*)) [[1,2,3],[3,5,6],[2,3,4]] [[3,2,2],[3,4,5],[5,4,3]]
[[3,4,6],[9,20,30],[10,12,12]]
</pre>
<p>
Jak můžete vidět, jediná funkce vyššího řádu může být použita k více účelům. Imperativní programování obvykle používá věci jako jsou for smyčky, while smyčky, přiřazení něčeho do proměnné, kontrolování stavu atd. pro dosažení nějakého chování a poté to obalí nějakým rozhraním, třeba funkcí. Funkcionální programování používá funkce vyššího řádu pro abstrahování běžných schémat, jako procházení dvou seznamů po dvojicích a zacházení s těmito dvojicemi nebo dostávání množin řešení a vylučování těch, které nepotřebujeme.
</p>
<p>
Vytvoříme si další funkci, vyskytující se ve standardní knihovně, nazvanou <span class="fixed">flip</span>. Flip jednoduše vezme nějakou funkci a vrátí funkci, podobnou naší původní funkci, s tím rozdílem, že jsou první dva argumenty prohozeny. Můžeme ji napsat třeba jako:
</p>
<pre name="code" class="haskell:hs">
flip' :: (a -&gt; b -&gt; c) -&gt; (b -&gt; a -&gt; c)
flip' f = g
    where g x y = f y x
</pre>
<p>
Když si přečteme typovou deklaraci, můžeme říct, že vezme funkci, která požaduje nějaké <span class="fixed">a</span> a nějaké <span class="fixed">b</span> a vrátí funkci, která požaduje nějaké <span class="fixed">b</span> a nějaké <span class="fixed">a</span>. Druhý pár závorek je opravdu nezbytný, protože funkce jsou implicitně curryfikované a protože je šipka <span class="fixed">-&gt;</span> asociativní zprava. Typ <span class="fixed">(a -&gt; b -&gt; c) -&gt; (b -&gt; a -&gt; c)</span> je stejný jako <span class="fixed">(a -&gt; b -&gt; c) -&gt; (b -&gt; (a -&gt; c))</span>, což je totéž co <span class="fixed">(a -&gt; b -&gt; c) -&gt; b -&gt; a -&gt; c</span>. Napsali jsme, že <span class="fixed">g x y = f y x</span>. Pokud to je pravda, pak také platí, že <span class="fixed">f y x = g x y</span>, ne? Budeme-li to mít na paměti, můžeme funkci definovat dokonce ještě jednodušeji.
</p>
<pre name="code" class="haskell:hs">
flip' :: (a -&gt; b -&gt; c) -&gt; b -&gt; a -&gt; c
flip' f y x = f x y
</pre>
<p>
Tady jsme využili faktu, že jsou funkce curryfikované. Když zavoláme funkci <span class="fixed">flip' f</span> bez dalších dvou parametrů, vrátí funkci <span class="fixed">f</span>, která vezme tyhle dva parametry, ale zavolá je v opačném pořadí. Přestože funkce s prohozenými parametry jsou obvykle předány další funkci, můžeme rozmýšlet dopředu a využít curryfikace, když vytváříme funkce vyšších řádů, a napsat, jaký by mohl být jejich konečný výsledek, pokud jsou zavolány s plnou aplikací.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; flip' zip [1,2,3,4] "ahoj"
[('a',1),('h',2),('o',3),('j',4)]
ghci&gt; zipWith (flip' div) [2,2..] [10,8,6,4,2]
[5,4,3,2,1]
</pre>
<a name="mapy-a-filtry"></a><h2>Mapy a filtry</h2>
<p>
<span class="function label">map</span> vezme funkci a seznam a aplikuje tu funkci na každý prvek ze seznamu, což vyrobí nový seznam. Podívejme se, jaký má typ a jak je definována.
</p>
<pre name="code" class="haskell:hs">
map :: (a -&gt; b) -&gt; [a] -&gt; [b]
map _ [] = []
map f (x:xs) = f x : map f xs
</pre>
<p>
Z typu zjistíme, že požaduje funkci, která vezme nějaké <span class="fixed">a</span> a vrátí nějaké <span class="fixed">b</span>, dále seznam věcí typu <span class="fixed">a</span> a vrátí seznam věcí typu <span class="fixed">b</span>. Je zajímavé, jak pouhým pohledem na typ funkce můžete občas říct, co funkce dělá. Funkce <span class="fixed">map</span> je jedna z těch opravdu všestranných funkcí vyššího řádu, které mohou být použity na milión způsobů. Tady je v akci:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map (+3) [1,5,3,1,6]
[4,8,6,4,9]
ghci&gt; map (++ "!") ["BUM", "BÁC", "PRÁSK"]
["BUM!","BÁC!","PRÁSK!"]
ghci&gt; map (replicate 3) [3..6]
[[3,3,3],[4,4,4],[5,5,5],[6,6,6]]
ghci&gt; map (map (^2)) [[1,2],[3,4,5,6],[7,8]]
[[1,4],[9,16,25,36],[49,64]]
ghci&gt; map fst [(1,2),(3,5),(6,3),(2,6),(2,5)]
[1,3,6,2,2]
</pre>
<p>
Pravděpodobně jste si všimli, že by se všechny ty ukázky daly přepsat pomocí generátorů seznamu. Když napíšeme <span class="fixed">map (+3) [1,5,3,1,6]</span>, tak to je stejné jako <span class="fixed">[x+3 | x &lt;- [1,5,3,1,6]]</span>. Nicméně použití funkce <span class="fixed">map</span> je mnohem čitelnější pro případy, ve kterých pouze aplikujete nějakou funkci na prvek ze seznamu, zvláště pokud se vypořádáváte s mapováním mapování a pak by celá ta věc s hromadou závorek byla dost komplikovaná.
</p>
<p>
<span class="label function">filter</span> je funkce, která vezme predikát (predikát je funkce, která nám řekne, jestli je něco pravda nebo nepravda, takže v našem případě to je funkce vracející booleovskou hodnotu) a nějaký seznam a potom vrátí seznam prvků, které vyhovují predikátu. Typ a implementace vypadají nějak takhle:
</p>
<pre name="code" class="haskell:hs">
filter :: (a -&gt; Bool) -&gt; [a] -&gt; [a]
filter _ [] = []
filter p (x:xs)
    | p x       = x : filter p xs
    | otherwise = filter p xs
</pre>
<p>
Celkem jednoduchá věc. Jestliže se <span class="fixed">p x</span> vyhodnotí jako <span class="fixed">True</span>, prvek se zařadí do nového seznamu. Pokud ne, zahodí ho. Nějaké ukázky použití:
</p>
<pre name="code" class="haskell:hs">
ghci&gt; filter (&gt;3) [1,5,3,2,1,6,4,3,2,1]
[5,6,4]
ghci&gt; filter (==3) [1,2,3,4,5]
[3]
ghci&gt; filter even [1..10]
[2,4,6,8,10]
ghci&gt; let notNull x = not (null x) in filter notNull [[1,2,3],[],[3,4,5],[2,2],[],[],[]]
[[1,2,3],[3,4,5],[2,2]]
ghci&gt; filter (`elem` ['a'..'z']) "SmĚjeŠ sE mI, pRoToŽe jSeM JinÝ."
"mjesmpooejein"
ghci&gt; filter (`elem` ['A'..'Z']) "SmějU se váM, prOTožE jSte sTejní."
"SUMOTEST"
</pre>
<p>
Tohle všechno by také dalo docílit pomocí generátorů seznamu s predikáty. Neexistuje žádná sada pravidel, kdy použít <span class="fixed">map</span> a <span class="fixed">filter</span> versus generátor seznamu, musíte se prostě v závislosti na kódu a kontextu rozhodnout, co je více čitelné. K aplikaci několika predikátů ve funkci <span class="fixed">filter</span> je u generátoru seznamu ekvivalentní buď několikanásobné filtrování nebo spojení predikátů pomocí logické funkce <span class="fixed">&amp;&amp;</span>.
</p>
<p>
Vzpomínáte si na naši funkci quicksort z <a href="rekurze#rychle-rad">předchozí kapitoly</a>? Použili jsme generátor seznamu na odfiltrování prvků seznamu, které byly menší (nebo rovné) a větší než pivot. Můžeme docílit stejné funkčnosti a ještě lepší čitelnosti použitím funkce <span class="fixed">filter</span>:
</p>
<pre name="code" class="haskell:ghci">
quicksort :: (Ord a) =&gt; [a] -&gt; [a]
quicksort [] = []
quicksort (x:xs) =
    let smallerSorted = quicksort (filter (&lt;=x) xs)
        biggerSorted = quicksort (filter (&gt;x) xs)
    in  smallerSorted ++ [x] ++ biggerSorted
</pre>
<img src="images/map.png" alt="mapa" class="left" width="210" height="115">
<p>
Mapování a filtrování jsou živobytím nářadí funkcionálního programátora. Hm. Nezáleží na tom, jestli budete využívat spíše funkce <span class="fixed">map</span> a <span class="fixed">filter</span> oproti generátoru seznamu. Vzpomeňte si, jak jsme řešili problém hledání pravoúhlých trojúhelníků s určitým obvodem. V imperativním programování bychom to mohli vyřešit zanořením tří smyček a následným testováním, jestli aktuální kombinace dá dohromady pravoúhlý trojúhelník a jestli má správný obvod. Pokud by to byl ten případ, mohli bychom to vytisknout na obrazovku nebo něco. Ve funkcionálním programování se tohoto schématu dá docílit mapováním a filtrováním. Vytvoříte si funkci, která vezme nějakou hodnotu a vyprodukuje nějaký výsledek. Namapujeme tuhle funkci na seznam hodnot a poté z něj odfiltrujeme vyhovující výsledky. I když něco namapujeme na seznam vícekrát a poté to několikrát odfiltrujeme projde to díky lenosti Haskellu tím seznamem pouze jednou.
</p>
<p>
Pojďme <em>najít největší číslo pod 100000, které je dělitelné číslem 3829</em>. Abychom toho docílili, jednoduše odfiltrujeme množinu možností, o kterých víme, že se mezi nimi nalézá řešení.
</p>
<pre name="code" class="haskell:hs">
largestDivisible :: (Integral a) =&gt; a
largestDivisible = head (filter p [100000,99999..])
    where p x = x `mod` 3829 == 0
</pre>
<p>
Nejprve si vytvoříme seznam všech čísel menší než 100000, sestupně. Poté ho odfiltrujeme naším predikátem a protože jsou čísla seřazena sestupně, největší číslo, které vyhovuje našemu predikátu, bude prvním prvkem našeho seznamu. Jako výchozí množinu jsme dokonce nemuseli použít konečný seznam. Opět je tu vidět lenost v akci. Protože jsme nakonec využili pouze první prvek z filtrovaného seznamu, nezáleží na tom, jestli je ten seznam konečný nebo ne. Vyhodnocování se zastaví, když se nalezne první přijatelné řešení.
</p>
<p>
Dále zkusíme <em>najít součet všech lichých čtverců, které jsou menší než 10000</em>. Nejprve si ale představíme funkci <span class="label function">takeWhile</span>, protože ji využijeme v našem řešení. Vezme nějaký predikát a seznam a poté ten seznam prochází od začátku a vrací jeho prvky, dokud vyhovují predikátu. Jakmile se narazí na prvek, pro který predikát neplatí, procházení se zastaví. Pokud bychom chtěli získat první slovo z řetězce <span class="fixed">"sloni si umí užívat"</span>, mohli bychom udělat něco jako <span class="fixed">takeWhile (/= ' ') "sloni si umí užívat"</span> a to by nám vrátilo řetězec <span class="fixed">"sloni"</span>. Dobře. Součet všech lichých čtverců, které jsou menší než 10000. Začneme namapováním funkce <span class="fixed">(^2)</span> na nekonečný seznam <span class="fixed">[1..]</span>. Poté to odfiltrujeme, abychom dostali jen ty liché. A pak, vybereme prvky, které jsou menší než 10000. A nakonec to celé sečteme. Nemusíme si na to ani definovat funkci, můžeme to vyřešit jedním řádkem v GHCi:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; sum (takeWhile (&lt;10000) (filter odd (map (^2) [1..])))
166650
</pre>
<p>
Skvělé! Začali jsme s nějakými počátečními daty (nekonečný seznam všech přirozených čísel) a poté jsme na něj namapovali funkci, odfiltrovali ho a zredukovali na část, která vyhovovala naším potřebám, a tu jsme prostě sečetli. Mohli jsme to také zapsat pomocí generátoru seznamu:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; sum (takeWhile (&lt;10000) [m | m &lt;- [n^2 | n &lt;- [1..]], odd m])
166650
</pre>
<p>
Je to otázka vkusu, který zápis se vám bude líbit více. Znovu, tohle celé umožňuje lenost v Haskellu. Můžeme namapovat a odfiltrovat nekonečný seznam, protože se to nebude ve skutečnosti mapovat a filtrovat hned, tyhle akce se odloží. Jenom když donutíme Haskell ukázat nám součet, funkce <span class="fixed">sum</span> řekne funkci <span class="fixed">takeWhile</span>, že potřebuje čísla. Funkce <span class="fixed">takeWhile</span> přinutí filtry a mapy, aby se prováděly, ale jenom dokud nepotká číslo větší nebo rovné 10000.
</p>
<p>
V našem dalším problému se budeme vypořádávat s Collatzovou řadou. Vezmeme nějaké přirozené číslo. Jestliže je to číslo sudé, vydělíme ho dvěma. Jestliže je liché, vynásobíme ho třemi a přičteme k tomu jedničku. Na výsledné číslo aplikujeme stejný postup, což vyprodukuje nové číslo a tak dále. V podstatě dostaneme posloupnost čísel. Má se za to, že pro libovolné počáteční číslo skončí posloupnost jedničkou. Takže pokud vezmeme jako počáteční číslo 13, dostaneme tuhle řadu: <i>13, 40, 20, 10, 5, 16, 8, 4, 2, 1</i>. Výraz 13 * 3 + 1 se rovná 40. Číslo 40 vydělené 2 je 20, atd. Vidíme, že tato posloupnost má deset členů.
</p>
<p>
A teď chceme znát odpověď na tohle: <em>pro všechna čísla mezi 1 a 100, kolik posloupností je delších než 15?</em> Nejprve si napíšeme funkci, která vytvoří posloupnost:
</p>
<pre name="code" class="haskell:hs">
chain :: (Integral a) =&gt; a -&gt; [a]
chain 1 = [1]
chain n
    | even n =  n:chain (n `div` 2)
    | odd n  =  n:chain (n*3 + 1)
</pre>
<p>
Protože posloupnost končí jedničkou, je to okrajový případ. To je celkem standardní rekurzivní funkce.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; chain 10
[10,5,16,8,4,2,1]
ghci&gt; chain 1
[1]
ghci&gt; chain 30
[30,15,46,23,70,35,106,53,160,80,40,20,10,5,16,8,4,2,1]
</pre>
<p>
Jej! Vypadá to, že funguje korektně. A nyní přichází funkce, jež nám poskytne odpověď na naši otázku:
</p>
<pre name="code" class="haskell:hs">
numLongChains :: Int
numLongChains = length (filter isLong (map chain [1..100]))
    where isLong xs = length xs &gt; 15
</pre>
<p>
Namapujeme funkci <span class="fixed">chain</span> na seznam <span class="fixed">[1..100]</span>, abychom dostali seznam posloupností, které jsou znázorněny seznamem. Poté je odfiltrujeme pomocí predikátu, který se jednoduše podívá, jestli má seznam více než 15 prvků. Jakmile jsme hotoví s filtrováním, podíváme se, kolik posloupností zbylo ve výsledném seznamu.
</p>
<div class="hintbox"><em>Poznámka:</em> tahle funkce je typu <span class="fixed">numLongChains :: Int</span>, protože <span class="fixed">length</span> vrací číslo typu <span class="fixed">Int</span> místo <span class="fixed">Num a</span> z historických důvodů. Kdybychom chtěli vrátit více obecné <span class="fixed">Num a</span>, mohli bychom použít funkci <span class="fixed">fromIntegral</span> na výsledný počet.</div>
<p>
Použitím funkce <span class="fixed">map</span> můžeme také vytvořit věci jako <span class="fixed">map (*) [0..]</span>, minimálně z toho důvodu, abychom si ukázali, jak funguje curryfikace a že jsou (částečně aplikované) funkce opravdové hodnoty, které můžete posílat dalším funkcím nebo vkládat do seznamů (jenom je nemůžete změnit na řetězce). Zatím jsme pouze mapovali funkce, které použily jeden parametr na celý seznam, jako třeba <span class="fixed">map (*2) [0..]</span>, abychom obdrželi seznam typu <span class="fixed">(Num a) =&gt; [a]</span>, ale můžeme také bez problémů používat <span class="fixed">map (*) [0..]</span>. Stane se zde to, že je číslo v seznamu aplikováno na funkci <span class="fixed">*</span>, která má typ <span class="fixed">(Num a) =&gt; a -&gt; a -&gt; a</span>. Aplikace pouze jednoho parametru na funkci, která vezme dva parametry, vrátí funkci, která požaduje jeden parametr. Jestliže ji namapujeme <span class="fixed">*</span> na seznam <span class="fixed">[0..]</span>, vrátí se nám seznam funkcí, které požadují pouze jeden parametr, takže je to typ <span class="fixed">(Num a) =&gt; [a -&gt; a]</span>. Když napíšeme <span class="fixed">map (*) [0..]</span>, vytvoří to stejný seznam jako kdybychom napsali <span class="fixed">[(*0),(*1),(*2),(*3),(*4),(*5)&hellip;]</span>.
</p>
<pre name="code" class="haskell:hs">
ghci&gt; let listOfFuns = map (*) [0..]
ghci&gt; (listOfFuns !! 4) 5
20
</pre>
<p>
Získání prvku s indexem <span class="fixed">4</span> z našeho seznamu vrátí funkci, která odpovídá <span class="fixed">(*4)</span>. A poté jednoduše aplikujeme číslo <span class="fixed">5</span> na tu funkci. Takže je to stejné jako napsání <span class="fixed">(* 4) 5</span> nebo prostě <span class="fixed">5 * 4</span>.
</p>
<a name="lambdy"></a><h2>Lambdy</h2>
<img src="images/lambda.png" alt="lambda" class="right" width="203" height="230">
<p>
Lambdy jsou v zásadě anonymní funkce, které jsou používané, protože často potřebujeme nějakou funkci jenom jednou. Obvykle si vytváříme lambdu, abychom ji předali funkci vyššího řádu. Pro vytvoření lambdy napíšeme znak <span class="fixed">\</span> (protože vypadá jako řecké písmeno lambda, když na něj pořádně zamžouráte) a poté napíšeme parametry, oddělené mezerami. Za tím následuje šipka <span class="fixed">-&gt;</span> a tělo funkce. Obvykle to celé obklopíme kulatými závorkami, protože jinak to má sahá dál napravo.
</p>
<p>
Jestliže se podíváte o pětadvacet centimetrů nahoru, uvidíte, že jsme v naší funkci <span class="fixed">numLongChains</span> použili konstrukci <i>where</i>, abychom si vytvořili funkci <span class="fixed">isLong</span>, kterou jsme předali funkci <span class="fixed">filter</span>. Takže místo toho můžeme použít lambdu:
</p>
<pre name="code" class="haskell:hs">
numLongChains :: Int
numLongChains = length (filter (\xs -&gt; length xs &gt; 15) (map chain [1..100]))
</pre>
<p>
Lambdy jsou výrazy, to je důvod, proč je můžeme jen tak předat. Výraz <span class="fixed">(\xs -&gt; length xs &gt; 15)</span> vrátí funkci, která nám poví, jaké seznamy jsou delší než 15.
</p>
<img src="images/lamb.png" alt="jehně" class="left" width="200" height="134">
<p>
Lidé, jež nejsou důkladně obeznámeni s tím, jak curryfikace a částečná aplikace funguje, často používají lambdy tam, kde nemusí být. Kupříkladu výrazy <span class="fixed">map (+3) [1,6,3,2]</span> a <span class="fixed">map (\x -&gt; x + 3) [1,6,3,2]</span> jsou ekvivalentní, protože <span class="fixed">(+3)</span> a <span class="fixed">(\x -&gt; x + 3)</span> jsou obě funkce, co vezmou nějaké číslo a přičtou k němu trojku. Netřeba dodávat, že v tomhle případě je používat lambdu hloupé, jelikož je částečná aplikace mnohem čitelnější.
</p>
<p>
Lambdy mohou mít, stejně jako běžné funkce, libovolný počet parametrů:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; zipWith (\a b -&gt; (a * 30 + 3) / b) [5,4,3,2,1] [1,2,3,4,5]
[153.0,61.5,31.0,15.75,6.6]
</pre>
<p>
A můžete také, stejně jako v běžných funkcích, používat vzory. Jediný rozdíl je v tom, že nemůžete definovat více vzorů pro jeden parametr, jako napsání <span class="fixed">[]</span> a <span class="fixed">(x:xs)</span> na místo stejného parametru a poté ověřovat aktuální hodnotu. Jestliže vzory selžou u lambdy, dojde k běhové chybě, takže opatrně se vzory v lambda funkcích!
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map (\(a,b) -&gt; a + b) [(1,2),(3,5),(6,3),(2,6),(2,5)]
[3,8,9,8,7]
</pre>
<p>
Lambdy se normálně obklopují kulatými závorkami, pokud nechceme, aby sahaly dál napravo. Je to zajímavé: díky směru, v jakém jsou funkce automaticky curryfikované, jsou tyhle dva zápisy ekvivalentní:
</p>
<pre name="code" class="haskell:ghci">
addThree :: (Num a) =&gt; a -&gt; a -&gt; a -&gt; a
addThree x y z = x + y + z
</pre>
<pre name="code" class="haskell:ghci">
addThree :: (Num a) =&gt; a -&gt; a -&gt; a -&gt; a
addThree = \x -&gt; \y -&gt; \z -&gt; x + y + z
</pre>
<p>
Jestliže definujeme takovou funkci, je zřejmé, proč má takovou typovou deklaraci, jako má. U typové deklarace i rovnice jsou tři šipky <span class="fixed">-&gt;</span>. Samozřejmě, první způsob zápisu funkcí je mnohem čitelnější, druhý je spíše fígl na ukázání curryfikace.
</p>
<p>
Nicméně existují případy, ve který je použití této notace skvělé. Myslím si, že funkce <span class="fixed">flip</span> je nejvíce přehledná, když je zapsaná nějak takhle:
</p>
<pre name="code" class="haskell:ghci">
flip' :: (a -&gt; b -&gt; c) -&gt; b -&gt; a -&gt; c
flip' f = \x y -&gt; f y x
</pre>
<p>
Ačkoliv to je stejné, jako když napíšeme <span class="fixed">flip' f x y = f y x</span>, ozřejmíme tím, že to bude většinou použito pro vytvoření nové funkce. Nejobvyklejší použití funkce <span class="fixed">flip</span> je zavolat ji pouze s jedním parametrem a předat výslednou funkci pomocí mapy nebo filtru. Takže používejte lambda funkce tímhle způsobem, když chcete zdůraznit, že je vaše funkce určená pro částečnou aplikaci a pro předání jiné funkci jako parametr.
</p>
<a name="akumulacni-funkce-fold"></a><h2>Akumulační funkce fold</h2>
<img src="images/origami.png" alt="origami" class="right" width="220" height="221">
<p>
Když jsme se předtím zabývali rekurzí, všímali jsme si schématu u mnoha rekurzivních funkcí, které zacházejí se seznamy. Obvykle jsme měli okrajový případ pro prázdný seznam. Představili jsme si vzor <span class="fixed">x:xs</span> a poté jsme prováděli určité akce týkající se  prvku a zbytku seznamu. Ukázalo se, že to je velmi častý vzor, takže bylo zavedeno pár užitečných funkcí, které ho zapouzdřují. Těmto funkcím se říká foldy (skládače). Jsou podobné funkci <span class="fixed">map</span>, akorát omezují seznam na nějakou jednu hodnotu.
</p>
<p>
Fold vezme binární funkci, nějakou počáteční hodnotu (rád ji říkám akumulátor) a seznam na poskládání. Binární funkce sama o sobě požaduje dva parametry. Je zavolána s akumulátorem a prvním (nebo posledním) prvkem a vytvoří nový akumulátor. Poté je tato binární funkce zavolána s novým akumulátorem a s novým prvním (nebo posledním) prvkem a tak dále. Jakmile projdeme celý seznam, zbyde nám pouze akumulátor, na který jsme seznam zredukovali.
</p>
<p>
Nejprve se podívejme na funkci <span class="label function">foldl</span>, také nazvaná levý fold. Poskládá seznam z levé strany. Binární funkce je aplikována na počáteční hodnotu a první prvek ze seznamu. To vytvoří novou akumulační hodnotu a binární funkce je zavolána s tou hodnotou a dalším prvkem atd.
</p>
<p>
Pojďme si znovu implementovat funkci <span class="fixed">sum</span>, jenom tentokrát použijeme fold místo explicitní rekurze.
</p>
<pre name="code" class="haskell:hs">
sum' :: (Num a) =&gt; [a] -&gt; a
sum' xs = foldl (\acc x -&gt; acc + x) 0 xs
</pre>
<p>
Zkouška, raz, dva, tři:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; sum' [3,5,2,1]
11
</pre>
<img src="images/foldl.png" alt="foldl" class="left" width="172" height="348">
<p>
Podíváme se do hloubky, co se při tomto vyhodnocování děje. Funkce <span class="fixed">\acc x -&gt; acc + x</span> je binární. Počáteční hodnota je tu <span class="fixed">0</span> a <span class="fixed">xs</span> je seznam, který bude poskládán. Na začátku je dosazeno číslo <span class="fixed">0</span> na místo parametru <span class="fixed">acc</span> v binární funkci a číslo <span class="fixed">3</span> je (jako současný prvek) dosazeno na místo parametru <span class="fixed">x</span>. Sečtení <span class="fixed">0 + 3</span> vytvoří číslo <span class="fixed">3</span> a to se stane novou akumulační hodnotou, dá-li se to tak říct. Dále je použité číslo <span class="fixed">3</span> jako akumulační hodnota a číslo <span class="fixed">5</span> jako aktuální prvek a tím pádem se číslo <span class="fixed">8</span> stane novým akumulátorem. Pokračujeme, máme parametry <span class="fixed">8</span> a <span class="fixed">2</span>, nová akumulační hodnota je tedy <span class="fixed">10</span>. A nakonec je použito číslo <span class="fixed">10</span> jako akumulační hodnota a číslo <span class="fixed">1</span> jako aktuální prvek, což vytvoří číslo <span class="fixed">11</span>. Gratuluji, zvládli jsme skládání!
</p>
<p>
Tento odborný nákres nalevo objasňuje, co se ve foldu odehrává, krok za krokem (den za dnem!). Zelenohnědé číslo je akumulační hodnota. Můžete vidět, jak je seznam takříkajíc požírán zleva akumulátorem. Mňamy, mňam, mňam! Jestliže vezmeme v úvahu, že funkce mohou být curryfikované, můžeme zapsat tuhle funkci ještě více stručněji, jako třeba:
</p>
<pre name="code" class="haskell:hs">
sum' :: (Num a) =&gt; [a] -&gt; a
sum' = foldl (+) 0
</pre>
<p>
Lambda funkce <span class="fixed">(\acc x -&gt; acc + x)</span> dělá to stejné co <span class="fixed">(+)</span>. Můžeme vynechat <span class="fixed">xs</span> jako parametr, protože zavolání <span class="fixed">foldl (+) 0</span> vrátí funkci, která vezme seznam. Obecně, jestliže máte funkci jako <span class="fixed">foo a = bar b a</span>, můžete ji díky curryfikaci přepsat jako <span class="fixed">foo = bar b</span>.
</p>
<p>
Rozhodopádně si pojďme napsat vlastní funkci s levým foldem, než pokročíme k pravému. Jsem si jistý, že všichni víte, že funkce <span class="fixed">elem</span> kontroluje, jestli se určitá hodnota vyskytuje v seznamu, takže to nebudu opakovat (jejda, zrovna jsem to udělal!). Pojďme si ji napsat pomocí levého foldu.
</p>
<pre name="code" class="haskell:hs">
elem' :: (Eq a) =&gt; a -&gt; [a] -&gt; Bool
elem' y ys = foldl (\acc x -&gt; if x == y then True else acc) False ys
</pre>
<p>
Fajn, fajn, fajn, co to tady máme? Počáteční hodnota &mdash; a zároveň akumulátor &mdash; je zde booleovská hodnota. Typ akumulační hodnoty a typ výsledku je vždycky stejný, když se zabýváme s foldy. Pamatujte si, že pokud vůbec nevíte, co použít za počáteční hodnotu, dá vám určitou představu. Začneme s <span class="fixed">False</span>. Dává to smysl použít <span class="fixed">False</span> jako počáteční hodnotu. Předpokládáme, že tam ta hodnota není. Také když zavoláme fold na prázdný seznam, výsledkem bude počáteční hodnota. Pak zkontrolujeme, jestli se aktuální prvek rovná tomu, který hledáme. Jestliže je, nastavíme akumulátor na <span class="fixed">True</span>. Jestliže není, jednoduše necháme akumulátor nezměněný. Pokud byl předtím <span class="fixed">False</span>, zůstane stejný, protože se prvky nerovnají. Pokud byl <span class="fixed">True</span>, necháme ho tak.
</p>
<p>
Pravý fold, <span class="function label">foldr</span>, pracuje podobně jako levý, jenom s tím rozdílem, že akumulátor požírá hodnoty zprava. Také binární funkce pro levý fold má akumulátor jako první parametr a aktuální hodnotu jako druhý (tedy <span class="fixed">\acc x -&gt; &hellip;</span>), binární funkce pro pravý fold má aktuální hodnotu jako první parametr a akumulátor jako druhý (tedy <span class="fixed">\x acc -&gt; &hellip;</span>). Dává to celkem smysl, že pravý fold má akumulátor napravo, protože skládá z pravé strany.
</p>
<p>
Hodnota akumulátoru (a tudíž i výsledek) ve foldu může být jakéhokoliv typu. Může to být číslo, booleovská proměnná nebo i seznam. Implementujeme si funkci map pomocí pravého foldu. Akumulátor bude seznam, budeme akumulovat mapovaný seznam prvek po prvku. Z toho je jasné, že počáteční prvek bude prázdný seznam.
</p>
<pre name="code" class="haskell:hs">
map' :: (a -&gt; b) -&gt; [a] -&gt; [b]
map' f xs = foldr (\x acc -&gt; f x : acc) [] xs
</pre>
<p>
Jestliže namapujeme funkci <span class="fixed">(+3)</span> na seznam <span class="fixed">[1,2,3]</span>, přistoupíme k seznamu z pravé strany. Vezmeme poslední prvek, což je číslo <span class="fixed">3</span>, a aplikujeme na něj funkci, z čehož se stane číslo <span class="fixed">6</span>. Poté ho připojíme k akumulátoru, což nám dává <span class="fixed">[]</span>. Výraz <span class="fixed">6:[]</span> je <span class="fixed">[6]</span> a to je současný akumulátor. Aplikujeme <span class="fixed">(+3)</span> na číslo <span class="fixed">2</span>, což je <span class="fixed">5</span>, které připojíme (<span class="fixed">:</span>) k akumulátoru, takže je akumulátor teď <span class="fixed">[5,6]</span>. Aplikujeme <span class="fixed">(+3)</span> na číslo <span class="fixed">1</span> a to připojíme k akumulátoru, takže je konečná hodnota <span class="fixed">[4,5,6]</span>.
</p>
<p>
Samozřejmě bychom si mohli napsat tuhle funkci pomocí levého foldu. Bylo by to <span class="fixed">map' f xs = foldl (\acc x -&gt; acc ++ [f x]) [] xs</span>, ale problém je v tom, že funkce <span class="fixed">++</span> mnohem náročnější než <span class="fixed">:</span>, takže obvykle používáme pravé foldy na sestavování nových seznamů ze seznamu.
</p>
<img src="images/washmachine.png" alt="pračka" class="right" width="250" height="205">
<p>
Když obrátíte seznam, můžete na něj použít pravý fold místo levého a naopak. Někdy ho ani nemusíte obracet. Funkce <span class="fixed">sum</span> může být implementována prakticky stejně pomocí levého a pravého foldu. Hlavní rozdíl je v tom, že pravý fold může pracovat s nekonečnými seznamy, kdežto levý ne! Pro objasnění, pokud někdy vezmete nekonečný seznam od určitého místa a začnete ho skládat zprava, dostanete se časem na začátek toho seznamu. Nicméně pokud vezmete nekonečný seznam od určitého místa a zkusíte ho skládat zleva, nikdy se nedostanete na konec!
</p>
<p>
<em>Foldy mohou být použity pro zápis funkcí, u kterých procházíme seznam jedenkrát, prvek po prvku, a poté vrátíme něco, co je na tom průchodu založené. Kdykoliv hodláte projít seznam, abyste něco vrátili, je pravděpodobné, že chcete fold.</em> To je důvod, proč jsou foldy, spolu s mapami a filtry, jeden z nejužitečnějších typů funkcí ve funkcionálním programování.
</p>
<p>
Funkce <span class="label function">foldl1</span> a <span class="label function">foldr1</span> fungují podobně jako <span class="fixed">foldl</span> a <span class="fixed">foldr</span>, jenom jim nemusíte explicitně poskytnout počáteční hodnotu. Předpokládá se, že první (nebo poslední) prvek ze seznamu bude počáteční hodnota a ta se poté začne skládat s následujícími hodnotami. Budeme-li to mít na mysli, můžeme funkci <span class="fixed">sum</span> napsat jako <span class="fixed">sum = foldl1 (+)</span>. Protože záleží na tom, aby skládaný seznam obsahoval alespoň jeden prvek, vyhodí se běhová chyba, jestliže tyto funkce zavoláme na prázdný seznam. Na druhou stranu, funkce <span class="fixed">foldl</span> and <span class="fixed">foldr</span> fungují i s prázdnými seznamy. Když pracujete s foldy, popřemýšlejte o tom, jak by se měly chovat na prázdných seznamech. Jestliže nedává smysl pracovat s prázdným seznamem, můžete spíše použít funkci <span class="fixed">foldl1</span> nebo <span class="fixed">foldr1</span>.
</p>
<p>
Abych vám ukázal, jak jsou foldy užitečné, naprogramujeme si pár standardních funkcí za pomocí foldů:
</p>
<pre name="code" class="haskell:hs">
maximum' :: (Ord a) =&gt; [a] -&gt; a
maximum' = foldr1 (\x acc -&gt; if x &gt; acc then x else acc)

reverse' :: [a] -&gt; [a]
reverse' = foldl (\acc x -&gt; x : acc) []

product' :: (Num a) =&gt; [a] -&gt; a
product' = foldr1 (*)

filter' :: (a -&gt; Bool) -&gt; [a] -&gt; [a]
filter' p = foldr (\x acc -&gt; if p x then x : acc else acc) []

head' :: [a] -&gt; a
head' = foldr1 (\x _ -&gt; x)

last' :: [a] -&gt; a
last' = foldl1 (\_ x -&gt; x)
</pre>
<p>
Funkci <span class="fixed">head</span> je lepší napsat pomocí vzorů, ale takhle je ukázaný zapis pomocí foldů. Řekl bych, že naše definice funkce <span class="fixed">reverse'</span> je celkem důmyslná. Vezmeme jako počáteční hodnotu prázdný seznam a poté postupujeme v našem seznamu zleva a připojujeme prvky k akumulátoru. Nakonec si vytvoříme obrácený seznam. Binární funkce <span class="fixed">\acc x -&gt; x : acc</span> vypadá podobně jako funkce <span class="fixed">:</span>, jenom má přehozené parametry. To je důvod, proč bychom mohli zapsat naši funkci na obracení seznamu jako <span class="fixed">foldl (flip (:)) []</span>.
</p>
<p>
Další způsob, jak si představit pravé a levé foldy je takový: řekněme, že máme pravý fold, binární funkce je <span class="fixed">f</span> a počáteční funkce je <span class="fixed">z</span>. Jestliže skládáme zprava seznam <span class="fixed">[3,4,5,6]</span>, děláme v podstatě tohle: <span class="fixed">f 3 (f 4 (f 5 (f 6 z)))</span>. Funkce <span class="fixed">f</span> je zavolána na poslední prvek v seznamu a akumulátor, výsledná hodnota je předána jako akumulátor další hodnotě a tak dále. Jestliže funkce <span class="fixed">f</span> bude <span class="fixed">+</span> a počáteční akumulátor <span class="fixed">0</span>, celý výraz bude <span class="fixed">3 + (4 + (5 + (6 + 0)))</span>, což dává výsledek <span class="fixed">18</span>. Nebo když napíšeme <span class="fixed">+</span> jako prefixovou funkci, bude z toho <span class="fixed">(+) 3 ((+) 4 ((+) 5 ((+) 6 0)))</span>. Podobně, když skládáme zleva ten stejný seznam pomocí binární funkce <span class="fixed">g</span> a akumulátorem <span class="fixed">z</span>,  vznikne nám výraz <span class="fixed">g (g (g (g z 3) 4) 5) 6</span>. Jestliže použijeme jako binární funkci <span class="fixed">flip (:)</span> a jako akumulátor <span class="fixed">[]</span> (takže obracíme seznam), dostaneme z toho <span class="fixed">flip (:) (flip (:) (flip (:) (flip (:) [] 3) 4) 5) 6</span>. A samozřejmě, když vyhodnotíte tenhle výraz, dostanete seznam <span class="fixed">[6,5,4,3]</span>.
</p>
<p>
Funkce <span class="function label">scanl</span> a <span class="function label">scanr</span> jsou stejné jako <span class="fixed">foldl</span> a <span class="fixed">foldr</span>, jenom vypisují všechny dílčí stavy akumulátoru ve formě seznamu. Existují také funkce <span class="fixed">scanl1</span> a <span class="fixed">scanr1</span>, jež jsou analogické k funkcím <span class="fixed">foldl1</span> a <span class="fixed">foldr1</span>.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; scanl (+) 0 [3,5,2,1]
[0,3,8,10,11]
ghci&gt; scanr (+) 0 [3,5,2,1]
[11,8,3,1,0]
ghci&gt; scanl1 (\acc x -&gt; if x &gt; acc then x else acc) [3,4,5,3,7,9,2,1]
[3,4,5,5,7,9,9,9]
ghci&gt; scanl (flip (:)) [] [3,2,1]
[[],[3],[2,3],[1,2,3]]
</pre>
<p>
Když používáte funkci <span class="fixed">scanl</span>, konečný výsledek bude poslední prvek z výsledného seznamu, kdežto <span class="fixed">scanr</span> umístí výsledek na začátek.
</p>
<p>
Scany se používají na monitorování postupu funkce, která může být napsána pomocí foldu. Odpovězme si na otázku: „<em>Kolik prvků je potřeba pro součet druhých odmocnin všech přirozených čísel, aby přesáhl hodnotu 1000?</em>“ Abychom dostali odmocniny všech přirozených čísel, stačí napsat jenom <span class="fixed">map sqrt [1..]</span>. A teď, pro získání součtu bychom mohli použít fold, ale protože nás zajímá, jak součet postupuje, použijeme scan. Jakmile jsme hotoví se scanem, podíváme se, kolik součtů je pod 1000. První součet v seznamu scanů bude normálně jednička. Druhý bude jedna plus druhá odmocnina ze dvou. Třetí bude to předtím plus druhá odmocnina ze tří. Jestliže existuje X součtů menších než 1000, bude potřeba X&nbsp;+&nbsp;1 prvků pro součet, přesahující 1000.
</p>
<pre name="code" class="haskell:hs">
sqrtSums :: Int
sqrtSums = length (takeWhile (&lt;1000) (scanl1 (+) (map sqrt [1..]))) + 1
</pre>
<pre name="code" class="haskell:ghci">
ghci&gt; sqrtSums
131
ghci&gt; sum (map sqrt [1..131])
1005.0942035344083
ghci&gt; sum (map sqrt [1..130])
993.6486803921487
</pre>
<p>
Použijeme zde funkci <span class="fixed">takeWhile</span> místo <span class="fixed">filter</span>, protože funkce <span class="fixed">filter</span> neumí pracovat s nekonečnými seznamy. I když víme, že je seznam vzrůstající, <span class="fixed">filter</span> to neví, takže použijeme <span class="fixed">takeWhile</span>, abychom generování seznamu scanů přerušili při prvním výskytu součtu větším než 1000.
</p>
<a name="aplikace-funkce"></a><h2>Aplikace funkce pomocí $</h2>
<p>
Dále se podíváme na funkci <span class="fixed">$</span>, která se také nazývá <i>aplikace funkce</i>. Nejprve se mrkneme, jak je definována:
</p>
<pre name="code" class="haskell:hs">
($) :: (a -&gt; b) -&gt; a -&gt; b
f $ x = f x
</pre>
<img src="images/dollar.png" alt="dolar" class="left" width="180" height="136">
<p>
Co to sakra je? Co je tohle za neužitečný operátor? Je to jenom aplikovaná funkce! No skoro, ale nejen to! Zatímco obyčejná aplikace funkce (vložení mezery mezi dvě věci) má celkem vysokou prioritu, funkce <span class="fixed">$</span> má prioritu nejnižší. Aplikace funkce pomocí mezery je asociativní zleva (takže <span class="fixed">f a b c</span> je to stejné co <span class="fixed">((f a) b) c)</span>), aplikace funkce pomocí <span class="fixed">$</span> je asociativní zprava.
</p>
<p>
To je sice skvělé, ale jak nám tohle pomůže? Většinou ji využijeme pro konvenci, abychom nemuseli psát tolik závorek. Předpokládejme výraz <span class="fixed">sum (map sqrt [1..130])</span>. Protože má funkce <span class="fixed">$</span> tak nízkou prioritu, můžeme ho přepsat na <span class="fixed">sum $ map sqrt [1..130]</span>, čímž jsme si <span title="Ve skutečnosti neušetřili, ale čert to vem.">ušetřili drahocenné úhozy na klávesnici</span>! Když se narazí na operátor <span class="fixed">$</span>, výraz napravo je aplikován jako parametr funkci nalevo. A co takhle <span class="fixed">sqrt 3 + 4 + 9</span>? Tenhle výraz sečte devítku, čtyřku a druhou odmocninu trojky. Kdybychom chtěli druhou odmocninu z <i>3&nbsp;+&nbsp;4&nbsp;+&nbsp;9</i>, museli bychom napsat <span class="fixed">sqrt (3 + 4 + 9)</span> nebo s použitím operátoru <span class="fixed">$</span> můžeme napsat <span class="fixed">sqrt $ 3 + 4 + 9</span>, protože operátor <span class="fixed">$</span> má nižší prioritu než všechny ostatní. To je důvod, proč si můžete představit funkci <span class="fixed">$</span> jako obdobu toho, když napíšeme otevírací závorku a poté někam hodně daleko na pravou stranu výrazu závorku uzavírací.
</p>
<p>
A co třeba <span class="fixed">sum (filter (&gt; 10) (map (*2) [2..10]))</span>? No protože je operátor <span class="fixed">$</span> asociativní zprava, výraz <span class="fixed">f (g (z x))</span> odpovídá výrazu <span class="fixed">f $ g $ z x</span>. A tak můžeme přepsat <span class="fixed">sum (filter (&gt; 10) (map (*2) [2..10]))</span> na <span class="fixed">sum $ filter (&gt; 10) $ map (*2) [2..10]</span>.
</p>
<p>
Kromě zbavování se závorek nám operátor <span class="fixed">$</span> umožňuje zacházet s aplikací funkce jako s jinými funkcemi. Tímto způsobem můžeme kupříkladu namapovat aplikaci funkce na seznam funkcí.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map ($ 3) [(4+), (10*), (^2), sqrt]
[7.0,30.0,9.0,1.7320508075688772]
</pre>
<a name="skladani-funkci"></a><h2>Skládání funkcí</h2>
<p>
V matematice je skládání funkcí (kompozice) definováno takto: <img src="images/composition.png" alt="(f . g)(x) = f(g(x))">, což znamená, že skládání dvou funkcí vytvoří novou funkci, kterou když zavoláme s parametrem, řekněme <i>x</i>, je ekvivalentní zavolání funkce <i>g</i> s parametrem <i>x</i> a poté zavolání funkce <i>f</i> na výsledek.
</p>
<p>
V Haskellu je skládání funkcí v podstatě stejná věc. Pro skládání funkcí používáme funkci <span class="fixed">.</span>, jež je definována následovně:
</p>
<pre name="code" class="haskell:hs">
(.) :: (b -&gt; c) -&gt; (a -&gt; b) -&gt; a -&gt; c
f . g = \x -&gt; f (g x)
</pre>
<img src="images/notes.png" alt="noty" class="left" width="230" height="198">
<p>
Popřemýšlejte nad typovou deklarací. Funkce <span class="fixed">f</span> musí mít jako parametr hodnotu se stejným typem jako je typ návratové hodnoty <span class="fixed">g</span>. Takže výsledná funkce vezme parametr stejného typu jako požaduje <span class="fixed">g</span> a vrátí hodnotu shodného typu, jako vrací <span class="fixed">f</span>. Výraz <span class="fixed">negate . (* 3)</span> vrací funkci, která vezme nějaké číslo, vynásobí ho trojkou a poté zneguje.
</p>
<p>
Jedno z použití skládání funkcí je vytvoření funkcí za chodu pro předání jiným funkcím. Jasně, můžeme na to použít lambdu, ale častokrát je skládání funkcí čistější a stručnější. Řekněme, že máme seznam čísel a chceme je všechny převést na záporná čísla. Jeden ze způsobů, jak to provést, by mohl být negace absolutní hodnoty, jako tady:
</p>
<pre name="code" class="haskell:hs">
ghci&gt; map (\x -&gt; negate (abs x)) [5,-3,-6,7,-3,2,-19,24]
[-5,-3,-6,-7,-3,-2,-19,-24]
</pre>
<p>
Všimněte si lambdy, že vypadá jako výsledek skládání funkcí. S využitím skládání funkcí to můžeme přepsat na:
</p>
<pre name="code" class="haskell:hs">
ghci&gt; map (negate . abs) [5,-3,-6,7,-3,2,-19,24]
[-5,-3,-6,-7,-3,-2,-19,-24]
</pre>
<p>
Báječné! Skládání funkcí je asociativní zprava, takže můžeme skládat více funkcí najednou. Výraz <span class="fixed">f (g (z x))</span> je stejný jako <span class="fixed">(f . g . z) x</span>. Pokud tohle vezmeme v úvahu, můžeme převést výraz:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map (\xs -&gt; negate (sum (tail xs))) [[1..5],[3..6],[1..7]]
[-14,-15,-27]
</pre>
<p>
na výraz:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; map (negate . sum . tail) [[1..5],[3..6],[1..7]]
[-14,-15,-27]
</pre>
<p>
Ale co funkce s více parametry? Když je chceme použít při skládání funkcí, obvykle je musíme částečně aplikovat natolik, abychom dostali funkci, která bere pouze jeden parametr. Například <span class="fixed"> sum (replicate 5 (max 6.7 8.9))</span> může být zapsáno jako <span class="fixed">(sum . replicate 5 . max 6.7) 8.9</span> nebo jako <span class="fixed">sum . replicate 5 . max 6.7 $ 8.9</span>. O co tady jde je tohle: je vytvořena funkce, která vezme <span class="fixed">max 6.7</span> a aplikuje na to <span class="fixed">replicate 5</span>. Poté se vytvoří funkce, co vezme předchozí výsledek a provede součet. A nakonec je tato funkce zavolána na číslo <span class="fixed">8.9</span>. Ale obvykle to čteme takto: číslo <span class="fixed">8.9</span> se aplikuje na výraz <span class="fixed">max 6.7</span>, poté se na to aplikuje výraz <span class="fixed">replicate 5</span> a nakonec se na to aplikuje funkce <span class="fixed">sum</span>. Jestliže chcete zapsat výraz s hromadou závorek za pomocí skládání funkcí, můžete začít vložením posledního parametru nejvnitřnější funkce za operátor <span class="fixed">$</span> a poté prostě skládat všechny ostatní volání funkcí, zapsat je bez jejich posledního parametru a vkládat mezi ně tečky. Pokud máte výraz <span class="fixed">replicate 100 (product (map (*3) (zipWith max [1,2,3,4,5] [4,5,6,7,8])))</span>, můžete ho zapsat jako <span class="fixed">replicate 100 . product . map (*3) . zipWith max [1,2,3,4,5] $ [4,5,6,7,8]</span>. Jestliže výraz končí třemi kulatými závorkami, je pravděpodobné, že po předělání na skládání funkcí bude obsahovat tři operátory skládání.
</p>
<p>
Další obvyklé použití skládání funkcí je definování funkce v takzvaném pointfree stylu (také se mu říká point<i>less</i> styl). Vezmeme si pro příklad funkci, kterou jsme si napsali dříve:
</p>
<pre name="code" class="haskell:hs">
sum' :: (Num a) =&gt; [a] -&gt; a
sum' xs = foldl (+) 0 xs
</pre>
<p>
Část <span class="fixed">xs</span> je přítomna na obou pravých stranách. Díky curryfikaci můžeme <span class="fixed">xs</span> vynechat, protože zavolání <span class="fixed">foldl (+) 0</span> vytvoří funkci, která požaduje seznam. Zápisu <span class="fixed">sum' = foldl (+) 0</span> takové funkce se říká zápis v pointfree stylu. Jak bychom mohli napsat tohle v pointfree stylu?
</p>
<pre name="code" class="haskell:hs">
fn x = ceiling (negate (tan (cos (max 50 x))))
</pre>
<p>
Nemůžeme se jednoduše zbavit <span class="fixed">x</span> na obou stranách. Parametr <span class="fixed">x</span> je v těle funkce uzávorkovaný. Výraz <span class="fixed">cos (max 50)</span> by nedával smysl. Nemůžete dostat kosinus funkce. Uděláme to, že vyjádříme <span class="fixed">fn</span> jako skládání funkcí.
</p>
<pre name="code" class="haskell:hs">
fn = ceiling . negate . tan . cos . max 50
</pre>
<p>
Skvělé! Pointfree styl bývá často čitelnější a stručnější, protože nás nutí přemýšlet o funkcích a o výsledcích skládání funkcí místo přemýšlení nad daty a jejich přesouvání. Můžete vzít jednoduchou funkci a použít skládání jako lepidlo na vytvarování složitějších funkcí. Nicméně někdy může být zápis funkce v pointfree stylu méně čitelný, pokud je funkce příliš komplikovaná. To je důvod, proč se nedoporučuje dělat dlouhé řetězce poskládaných funkcí, ačkoliv se přiznávám, že se občas ve skládání funkcí vyžívám. Preferovaný styl je používat konstrukci <i>let</i> pro označování mezivýsledků nebo rozdělení problému na podproblémy a poté je dát dohromady, místo vytváření dlouhých řetězců funkcí, aby to dávalo smysl i případnému čtenáři.
</p>
<p>
V sekci o mapách a filtrech jsme řešili problém hledání součtu všech lichých čtverců, které byly menší než 10000. Takto vypadá řešení, když se vloží do funkce.
</p>
<pre name="code" class="haskell:hs">
oddSquareSum :: Integer
oddSquareSum = sum (takeWhile (&lt;10000) (filter odd (map (^2) [1..])))
</pre>
<p>
Jako fanoušek skládání funkcí bych to nejspíš zapsal takhle:
</p>
<pre name="code" class="haskell:hs">
oddSquareSum :: Integer
oddSquareSum = sum . takeWhile (&lt;10000) . filter odd . map (^2) $ [1..]
</pre>
<p>
Nicméně kdyby byla šance, že někdo jiný bude číst můj kód, napsal bych to asi takhle:
</p>
<pre name="code" class="haskell:hs">
oddSquareSum :: Integer
oddSquareSum =
    let oddSquares = filter odd $ map (^2) [1..]
        belowLimit = takeWhile (&lt;10000) oddSquares
    in  sum belowLimit
</pre>
<p>
Nevyhrál bych s tím žádný programátorský turnaj, ale člověk, co by to četl, by to patrně považoval za čitelnější než řetězec poskládaných funkcí.
</p>