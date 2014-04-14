<div class="english-version"><a href="http://learnyouahaskell.com/starting-out">English version</a></div>
<h1 style="margin-left:-3px"><?=$contents['zaciname']['title']?></h1>
<a name="pripravit-pozor-ted"></a><h2>Připravit, pozor, teď!</h2>
<p>
<img src="startingout.png" class="right" alt="vejce" width="214" height="187">
Fajn, tak začneme! Pokud patříte do skupiny těch hrozných osob, které nečtou úvody do čehokoliv, a přeskočili ho, možná byste si stejně měli přečíst poslední část úvodu, protože se tam vysvětluje, co je potřeba na práci s tímto tutoriálem a jak budeme načítat soubory s funkcemi. První věc, kterou se budeme zabývat, je spuštění interaktivního módu GHC a zavolání nějakých funkcí, abyste se spřátelili s Haskellem. Spusťte si terminál a zadejte do něj <span class="fixed">ghci</span>. Vypíše se zhruba takovéto uvítání:
</p>
<pre name="code" class="haskell: ghci">
GHCi, version 6.10.1: http://www.haskell.org/ghc/  :? for help
Loading package ghc-prim ... linking ... done.
Loading package integer ... linking ... done.
Loading package base ... linking ... done.
Prelude&gt;</pre>
<p>
Gratuluji, jste v GHCi! <a href="http://www.abclinuxu.cz/slovnik/prompt">Prompt</a> je tady <span class="fixed">Prelude&gt;</span>, ale protože se může prodloužit po načtení nějakých dalších věcí, budeme dále používat <span class="fixed">ghci&gt;</span>. Pokud chcete mít stejný prompt, stačí napsat příkaz <span class="fixed">:set prompt "ghci&gt; "</span>.
</p>
<p>
Tady je příklad nějaké jednoduché aritmetiky.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; 2 + 15
17
ghci&gt; 49 * 100
4900
ghci&gt; 1892 - 1472
420
ghci&gt; 5 / 2
2.5
ghci&gt;</pre>
<p>
Tohle je celkem samozřejmé. Můžeme také použít více operátorů na jednom řádku a ukázat si obvyklou prioritu operátorů. Můžeme použít závorky z důvodů explicitnosti nebo pro změnu priority.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; (50 * 100) - 4999
1
ghci&gt; 50 * 100 - 4999
1
ghci&gt; 50 * (100 - 4999)
-244950</pre>
<p>
Docela pěkné, co? Jo, vím, že není, ale vydržte to se mnou. Malá záludnost, na kterou je třeba si dát pozor, jsou záporná čísla. Pokud chceme pracovat se záporným číslem, je vždycky lepší jej obklopit závorkami. Při pokusu o výraz <span class="fixed">5 * -3</span> bude na vás GHCi řvát, ale výraz <span class="fixed">5 * (-3)</span> bude fungovat dobře.
</p>
<p>
Booleova algebra je také celkem jasná. Jak pravděpodobně víte, <span class="fixed">&amp;&amp;</span> znamená booleovské <i>a</i>, <span class="fixed">||</span> znamená booleovské <i>nebo</i>. Pomocí <span class="fixed">not</span> se neguje <span class="fixed">True</span> (<i>pravda</i>) nebo <span class="fixed">False</span> (<i>nepravda</i>).
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; True &amp;&amp; False
False
ghci&gt; True &amp;&amp; True
True
ghci&gt; False || True
True
ghci&gt; not False
True
ghci&gt; not (True &amp;&amp; True)
False</pre>
<p>
Testování na rovnost se dělá nějak takhle.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; 5 == 5
True
ghci&gt; 1 == 0
False
ghci&gt; 5 /= 5
False
ghci&gt; 5 /= 4
True
ghci&gt; "ahoj" == "ahoj"
True </pre>
<p>
A co zkusit zadat výraz <span class="fixed">5 + "lama"</span> nebo <span class="fixed">5 == True</span>? No, pokud zkusíme první kus kódu, dostaneme velkou strašidelnou chybovou zprávu!
</p>
<pre name="code" class="haskell: ghci">
No instance for (Num [Char])
arising from a use of `+' at &lt;interactive&gt;:1:0-9
Possible fix: add an instance declaration for (Num [Char])
In the expression: 5 + "lama"
In the definition of `it': it = 5 + "lama"</pre>
<p>
Jejda! GHCi se nám snaží sdělit, že <span class="fixed">"lama"</span> není číslo a tak by se neměla přičítat k číslu 5. I kdyby to nebyla <span class="fixed">"lama"</span>, ale <span class="fixed">"čtyřka"</span> nebo <span class="fixed">"4"</span>, Haskell by to pořád nepovažoval za číslo. Operátor <span class="fixed">+</span> očekává, že na pravé a levé straně budou čísla. Pokud se pokusíme zadat <span class="fixed">True == 5</span>, GHCi nám sdělí, že nám nesouhlasí typy. Zatímco <span class="fixed">+</span> funguje jenom na číslech, <span class="fixed">==</span> funguje na jakýchkoliv dvou věcech, které se dají porovnávat. Háček je v tom, že oba musí mít odpovídající typ. Nemůžete porovnávat jablka s hruškami. Na typy se podíváme blíže později. Poznámka: můžete provést <span class="fixed">5 + 4.0</span>, protože pětka je záludná a může se vydávat za celé nebo reálné číslo. Kdežto <span class="fixed">4.0</span> se nemůže vydávat za celé číslo, takže se <span class="fixed">5</span> musí přizpůsobit.
</p>
<p>
Možná to nevíte, ale teď jsme tu celou dobu používali funkce. Například <span class="fixed">*</span> je funkce, která bere dvě čísla a násobí je. Jak jste viděli, zavoláme ji vecpáním mezi ně. Tomu se říká <i>infixová</i> funkce. Většina funkcí, které nepracují s čísly, jsou <i>prefixové</i> funkce. Podívejme se na ně.
</p>
<p>
<img src="ringring.png" alt="telefon" class="right" width="160" height="161">
Funkce jsou většinou prefixové, takže i když od teď explicitně neuvedeme, že je to funkce v prefixové formě, budeme ji za ni považovat. Ve většině imperativních jazyků jsou funkce zavolány napsáním názvu funkce a poté parametrů v závorkách, často oddělených čárkami. V Haskellu jsou funkce zavolány napsáním názvu funkce, mezery, a poté parametrů, oddělených mezerami. Pro začátek zkusíme zavolat jednu z nejnudnějších funkcí v Haskellu.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; succ 8
9 </pre>
<p>
Funkce <span class="fixed">succ</span> požaduje jako parametr cokoliv, co má definováno následníka a následně ho vrátí. Jak můžete vidět, oddělili jsme název funkce od parametru mezerou. Zavoláním funkce s více parametry je také jednoduché. Funkce <span class="fixed">min</span> a <span class="fixed">max</span> vezmou dvě věci, které se dají porovnat (jako třeba čísla!) a vrátí menší nebo větší z nich.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; min 9 10
9
ghci&gt; min 3.4 3.2
3.2
ghci&gt; max 100 101
101 </pre>
<p>
Aplikace funkcí (zavolání funkce vložením mezery za ní a přidáním parametrů) má největší přednost ze všeho. Což pro nás znamená, že tyto dva výrazy jsou stejné.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; succ 9 + max 5 4 + 1
16
ghci&gt; (succ 9) + (max 5 4) + 1
16
</pre>
<p>
Nicméně pokud chceme získat následníka součinu čísel 9 a 10, neměli bychom psát <span class="fixed">succ 9 * 10</span>, protože to bychom získali následníka devítky, který by byl násoben desítkou. Tedy číslo 100. Museli bychom napsat <span class="fixed">succ (9 * 10)</span>, abychom obdrželi číslo 91.
</p>
<p>
Pokud funkce požaduje dva parametry, můžeme ji zavolat také infixově, když ji obklopíme zpětnými apostrofy. Například funkce <span class="fixed">div</span> vezme dvě celá čísla a provede s nimi celočíselné dělení. Provedením <span class="fixed">div 92 10</span> dostaneme výsledek 9. Pokud to zapíšeme tímto způsobem, může to vést k nejasnostem, které číslo je dělenec a které dělitel. Můžeme tedy funkci zavolat infixově jako <span class="fixed">92 `div` 10</span>, což je hned jasnější.
</p>
<p>
Hodně lidí, kteří přešli k Haskellu z imperativních jazyků, se snaží držet zápisu, ve kterém závorky znázorňují aplikaci funkce. Kupříkladu v jazyce C se používají závorky na zavolání funkcí jako <span class="fixed">foo()</span>, <span class="fixed">bar(1)</span> nebo <span class="fixed">baz(3, "haha")</span>. Jak jsme již uvedli, pro aplikaci funkce je používána v Haskellu mezera. Tyto funkce by v Haskellu byly zapsány jako <span class="fixed">foo</span>, <span class="fixed">bar 1</span> a <span class="fixed">baz 3 "haha"</span>. Takže pokud uvidíte něco jako <span class="fixed">bar (bar 3)</span>, tak to neznamená, že <span class="fixed">bar</span> je zavoláno s parametry <span class="fixed">bar</span> a <span class="fixed">3</span>. Znamená to, že nejprve zavoláme funkci <span class="fixed">bar</span> s parametrem <span class="fixed">3</span>, abychom obdrželi nějaké číslo, a pak na něj znovu zavolali <span class="fixed">bar</span>. V C by to vypadalo zhruba jako <span class="fixed">bar(bar(3))</span>.
</p>
<a name="miminko-ma-svou-prvni-funkci"></a><h2>Miminko má svou první funkci</h2>
<p>
V předchozí sekci jsme si vyzkoušeli volání funkcí. A teď si zkusíme vytvořit vlastní! Spusťte si svůj oblíbený editor a naťukejte tam následující funkci, která vezme číslo a vynásobí ho dvěma.
</p>
<pre name="code" class="haskell: hs">
doubleMe x = x + x</pre>
<p>
Funkce jsou definovány podobným způsobem, jako jsou volány. Název funkce je následován parametry, oddělenými mezerami. Ale při definování funkce následuje <span class="fixed">=</span>, za kterým určíme, co funkce dělá. Uložte si kód jako <span class="fixed">miminko.hs</span> nebo tak nějak. Nyní přejděte do adresáře, ve kterém je uložen, a spusťte v něm <span class="fixed">ghci</span>. Jakmile budete v GHCi, napište <span class="fixed">:l miminko</span>. Jakmile se náš skript načte, můžeme si hrát s funkcí, jež jsme definovali.
</p>
<pre name="code" class="haskell: ghci">
ghci> :l miminko
[1 of 1] Compiling Main             ( miminko.hs, interpreted )
Ok, modules loaded: Main.
ghci&gt; doubleMe 9
18
ghci&gt; doubleMe 8.3
16.6</pre>
<p>
Protože <span class="fixed">+</span> funguje s celými i s desetinnými čísly (s čímkoliv, co se dá považovat za číslo, vážně), naše funkce budou také fungovat s jakýmkoliv číslem. Vytvořme si funkci, která vezme dvě čísla a vynásobí je dvojkou a poté je sečte.
</p>
<pre name="code" class="haskell: hs">
doubleUs x y = x*2 + y*2 </pre>
<p>
Jednoduché. Také bychom to mohli definovat jako <span class="fixed">doubleUs x y = x + x + y + y</span>. Vyzkoušení přinese očekávaný výsledek (nezapomeňte přidat tuto funkci do souboru <span class="fixed">miminko.hs</span>, uložit jej a poté napsat <span class="fixed">:l miminko</span> v GHCi).
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; doubleUs 4 9
26
ghci&gt; doubleUs 2.3 34.2
73.0
ghci&gt; doubleUs 28 88 + doubleMe 123
478
</pre>
<p>
Jak se dalo čekat, je možné volat funkci z jiných funkcí, které jste si vytvořili. Můžeme toho využít a předefinovat funkci <span class="fixed">doubleUs</span> následovně:
</p>
<pre name="code" class="haskell: hs">
doubleUs x y = doubleMe x + doubleMe y </pre>
<p>
Tohle je velmi jednoduchý příklad běžného schéma, jaké uvidíte všude v Haskellu. Vytvoření základní funkce, která je očividně správná, a pak je poskládána do více složitých funkcí. Takto se také vyhneme opakování. Co když nějaký matematik přijde na to, že dvojka je ve skutečnosti trojka a budete muset upravit svůj program? Stačí předefinovat <span class="fixed">doubleMe</span> na <span class="fixed">x + x + x</span> a protože <span class="fixed">doubleUs</span> volá funkci <span class="fixed">doubleMe</span>, mělo by to automaticky fungovat i v tom divném světě, kde je dvojka trojkou.
</p>
<p>
Funkce v Haskellu nemusí mít konkrétní pořadí, takže nezáleží, když definujete nejprve <span class="fixed">doubleMe</span> a teprve poté <span class="fixed">doubleUs</span>, nebo pokud to uděláte obráceně.
</p>
<p>
A teď vytvoříme funkci, která násobí číslo dvojkou, pokud to číslo je menší nebo rovno 100, protože čísla větší než 100 jsou pro nás dost velké!
</p>
<pre name="code" class="haskell: hs">
doubleSmallNumber x = if x &gt; 100
                        then x
                        else x*2 </pre>
<img src="baby.png" alt="to jste vy" class="left" width="140" height="211">
<p>
Tady jsme ukázali haskellový výraz if. Pravděpodobně znáte if z jiných jazyků. Rozdíl mezi tím v Haskellu a tím v imperativních jazycích je v tom, že část s else je v Haskellu povinná. V imperativních jazycích můžete přeskočit několik kroků, pokud není podmínka splněna, ale v Haskellu musí každý výraz a funkce něco vracet. Mohli bychom mít napsaný podmíněný výraz na jednom řádku, ale já pokládám první způsob za více přehledný. Další věc ohledně if v Haskellu: jedná se o <i>výraz</i>. Výraz je v podstatě kus kódu, který vrací hodnotu. Například <span class="fixed">5</span> je výraz, protože vrací 5, <span class="fixed">4 + 8</span> je výraz, <span class="fixed">x + y</span> je také výraz, protože vrací součet <span class="fixed">x</span> a <span class="fixed">y</span>. Jelikož je else povinné, výraz if vždycky něco vrátí a proto je také výraz. Pokud chceme přidat jedničku ke každému číslu, které je vráceno naší předchozí funkcí, mohli bychom ji napsat zhruba takto.
</p>
<pre name="code" class="haskell: hs">
doubleSmallNumber' x = (if x &gt; 100 then x else x*2) + 1
</pre>
<p>
Pokud bychom zanedbali závorky, přidali bychom jedničku pouze pokud by <span class="fixed">x</span> nebylo větší než 100. Všimněte si čáry <span class="fixed">'</span> na konci názvu funkce. Apostrof nemá v Haskellu žádný speciální syntaktický význam. Je to znak, který se dá použít v názvu funkce. Obvykle používáme <span class="fixed">'</span> k označení striktní verzi funkce (která není líná), nebo lehce změněnou verzi funkce nebo proměnné. Protože je <span class="fixed">'</span> povolený znak v názvu funkce, můžeme vytvořit takovou funkci.
</p>
<pre name="code" class="haskell: hs">
conanO'Brien = "To jsem já, Conan O'Brien!"</pre>
<p>
Jsou tu dvě pozoruhodné věci. První je, že v názvu funkce jsme Conanovo jméno nenapsali velkým písmenem. Je to proto, že funkce by jím neměly začínat. Proč tomu tak je, zjistíme později. Druhá věc je, že tahle funkce nepožaduje žádné parametry. Funkci bez parametrů se obvykle říká <i>definice</i> (nebo <i>pojmenování</i>). Protože nemůžeme měnit význam pojmenování (a funkce) po jejich definování, <span class="fixed">conanO'Brien</span> a řetězec <span class="fixed">"To jsem já, Conan O'Brien!"</span> se mohou při použití zaměňovat.
</p>
<a name="uvod-do-seznamu"></a><h2>Úvod do seznamů</h2>
<p>
<img src="list.png" alt="KUP SI PSA" class="left" width="150" height="103">
Stejně jako nákupní seznamy v reálném světě, seznamy v Haskellu jsou velmi užitečné. Je to nejvíce používaná datová struktura a může být použita na mnoho různých způsobů pro modelování a řešení spoustu problémů. Seznamy jsou TAK skvělé. V téhto sekci se podíváme na základy práce se seznamy, řetězce (které jsou také seznamy) a na generátor seznamu.
</p>
<p>
V Haskellu jsou seznamy <em>homogenní</em> datová struktura. Ukládá několik prvků stejného typu. Což znamená, že můžeme mít seznam čísel nebo seznam znaků, ale nemůžeme mít seznam, který obsahuje několik čísel a poté několik znaků. A nyní, seznam!
</p>
<div class="hintbox"><em>Poznámka</em>: můžeme použít klíčové slovo <span class="fixed">let</span>, abychom definovali pojmenování správně v GHCi. Napsat <span class="fixed">let a = 1</span> v GHCi je totéž jako napsat <span class="fixed">a = 1</span> do souboru a poté ho načíst.
</div>
<pre name="code" class="haskell: ghci">
ghci&gt; let lostNumbers = [4,8,15,16,23,42]
ghci&gt; lostNumbers
[4,8,15,16,23,42]
</pre>
<p>
Jak můžete vidět, seznamy se zadávají pomocí hranatých závorek a hodnoty se z nich oddělují čárkami. Pokud vyzkoušíte vytvořit seznam jako <span class="fixed">[1,2,'a',3,'b','c',4]</span>, Haskell si bude stěžovat, že znaky (které se mimochodem zapisují pomocí znaku mezi jednoduchými uvozovkami) nejsou čísla. Když už mluvíme o znacích, tak textové řetezce jsou jenom seznamy znaků. Zápis <span class="fixed">"ahoj"</span> je pouze syntaktický cukr (zkrácený zápis) pro řetězec <span class="fixed">['a','h','o','j']</span>. Protože řetězce jsou seznamy, můžeme na ně používat funkci pro práci se seznamy, což je velmi šikovné.
</p>
<p>
Běžná úloha je spojení dvou seznamů dohromady. To se dělá pomocí operátoru <span class="fixed">++</span>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [1,2,3,4] ++ [9,10,11,12]
[1,2,3,4,9,10,11,12]
ghci&gt; "ahoj" ++ " " ++ "světe"
"ahoj světe"
ghci&gt; ['k','v'] ++ ['á','k']
"kvák"
</pre>
<p class="hintbox">
<em>Poznámka překladatele</em>: je možné, že GHCi vypíše místo diakritiky v řetězci hromadu divných čísel. Kupříkladu řetězec <span class="fixed">"Příliš žluťoučký kůň úpěl ďábelské ódy."</span> se zobrazí jako <span class="fixed">"P\345\237li\353 \382lu\357ou\269k\253 k\367\328 \250p\283l \271\225belsk\233 \243dy."</span>. Je to z důvodů převodu UTF-8 řetězců na ASCII. Řešením je použít <a href="http://hackage.haskell.org/cgi-bin/hackage-scripts/package/utf8-string">knihovnu utf8-string</a>, která se postará o správnou interpretaci, ale pro účely tohoto tutoriálu bude asi lepší to nechat být a ignorovat to, popřípadě psát příklady bez diakritiky.
</p>
<p>
Pozor na opakované používání operátoru <span class="fixed">++</span> na dlouhé seznamy. Pokud spojujete dva seznamy (i když připojujete jednoprvkový seznam k delšímu seznamu, tedy například: <span class="fixed">[1,2,3] ++ [4]</span>), Haskell musí interně projít přes celý seznam na levé straně od <span class="fixed">++</span>. To není problém, pokud pracujeme s krátkými seznamy. Ale přidávat něco na konec seznamu, který má pět miliónů položek, bude chvíli trvat. Každopádně vložení prvku na začátek seznamu pomocí operátoru <span class="fixed">:</span> je okamžité.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; '1':" KOČIČKA"
"1 KOČIČKA"
ghci&gt; 5:[1,2,3,4,5]
[5,1,2,3,4,5]
</pre>
<p>
Všimněte si, že <span class="fixed">:</span> vezme jako argument číslo a seznam čísel nebo znak a seznam znaků, kdežto <span class="fixed">++</span> dva stejné seznamy. I kdybyste chtěli přidat jeden prvek na konec seznamu pomocí <span class="fixed">++</span>, musí být obklopený hranatými závorkami, aby byl seznam.
</p>
<p>
Výraz <span class="fixed">[1,2,3]</span> je vlastně syntaktický cukr pro <span class="fixed">1:2:3:[]</span>. Dvě hranaté závorky <span class="fixed">[]</span> jsou prázdný seznam. Když k nim připojíme <span class="fixed">3</span> stane se z toho <span class="fixed">[3]</span>. Jestliže připojíme k tomu <span class="fixed">2</span> stane se z toho <span class="fixed">[2,3]</span> a tak dále.
</p>
<p class="hintbox">
<em>Poznámka:</em> <span class="fixed">[]</span>, <span class="fixed">[[]]</span> a <span class="fixed">[[],[],[]]</span> jsou tři odlišné věci. To první je prázdný seznam, to druhé je seznam obsahující jeden prázdný seznam a to třetí je seznam, který obsahuje tři prázdné seznamy.
</p>
<p>
Pokud chcete získat ze seznamu prvek na nějaké pozici, použijte <span class="fixed">!!</span>. Číslování indexu začíná od nuly.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; "Steve Buscemi" !! 6
'B'
ghci&gt; [9.4,33.2,96.2,11.2,23.25] !! 1
33.2
</pre>
<p>
Pokud se ale budete snažit získat šestý prvek ze seznamu, který má pouze čtyři prvky, dostanete chybovou hlášku, takže opatrně!
</p>
<p>
Seznamy mohou také obsahovat seznamy. Taktéž mohou obsahovat seznamy obsahující seznamy obsahující seznamy&hellip;
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let b = [[1,2,3,4],[5,3,3,3],[1,2,2,3,4],[1,2,3]]
ghci&gt; b
[[1,2,3,4],[5,3,3,3],[1,2,2,3,4],[1,2,3]]
ghci&gt; b ++ [[1,1,1,1]]
[[1,2,3,4],[5,3,3,3],[1,2,2,3,4],[1,2,3],[1,1,1,1]]
ghci&gt; [6,6,6]:b
[[6,6,6],[1,2,3,4],[5,3,3,3],[1,2,2,3,4],[1,2,3]]
ghci&gt; b !! 2
[1,2,2,3,4]</pre>
<p>
Seznamy uvnitř seznamu mohou být rozdílné délky, ale nesmí být jiného typu. Stejně jako nemůže být seznam obsahující několik znaků a několik čísel, nemůže být seznam, který obsahuje několik seznamů znaků a několik seznamů čísel.
</p>
<p>
Seznamy mohou být porovnávány, pokud je porovnatelný jejich obsah. Při používání <span class="fixed">&lt;</span>, <span class="fixed">&lt;=</span>, <span class="fixed">&gt;</span> a <span class="fixed">&gt;=</span> jsou seznamy porovnávány v lexikografickém pořadí. Nejprve jsou porovnány první prvky seznamů. Jestliže jsou stejné, pak jsou porovnány druhé prvky atd.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [3,2,1] &gt; [2,1,0]
True
ghci&gt; [3,2,1] &gt; [2,10,100]
True
ghci&gt; [3,4,2] &gt; [3,4]
True
ghci&gt; [3,4,2] &gt; [2,4]
True
ghci&gt; [3,4,2] == [3,4,2]
True
</pre>
<p>
Co dalšího můžeme dělat se seznamy? Zde jsou některé základní funkce na práci se seznamy.
</p>
<p>Funkce <span class="label function">head</span> vezme seznam a vrátí jeho první prvek (hlavu seznamu).</p>
<pre name="code" class="haskell: ghci">
ghci&gt; head [5,4,3,2,1]
5</pre>
<p>Funkce <span class="label function">tail</span> vezme seznam a vrátí jeho zbytek, což je vlastně všechno kromě prvního prvku.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; tail [5,4,3,2,1]
[4,3,2,1]</pre>
<p>Funkce <span class="label function">last</span> vezme seznam a vrátí jeho poslední prvek.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; last [5,4,3,2,1]
1</pre>
<p>Funkce <span class="label function">init</span> vezme seznam a vrátí všechno kromě jeho posledního prvku.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; init [5,4,3,2,1]
[5,4,3,2]</pre>
<p>Pokud budeme seznam považovat za příšeru, bude to asi takovéhle.</p>
<img src="listmonster.png" alt="seznamová příšera" class="center" width="580" height="290">
<p>Ale co se stane, když budeme chtít první prvek z prázdného seznamu?</p>
<pre name="code" class="haskell: ghci">
ghci&gt; head []
*** Exception: Prelude.head: empty list</pre>
<p>
Pane jo! Úplně se nám to vymklo kontrole! Pokud není příšera, nemůže mít ani začátek. Při použití funkcí <span class="fixed">head</span>, <span class="fixed">tail</span>, <span class="fixed">last</span> a <span class="fixed">init</span> dávejte pozor, aby nebyly použity na prázdný seznam. Tato chyba nemůže být odchycena v čase překladu, takže je potřeba dávat pozor, aby se náhodou nepřikázalo Haskellu vybrat prvky z prázdného seznamu.
</p>
<p>Funkce <span class="label function">length</span> vezme seznam a vrátí jeho délku.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; length [5,4,3,2,1]
5</pre>
<p>Funkce <span class="label function">null</span> zjistí, jestli je seznam prázdný. Pokud ano, vrací <span class="fixed">True</span>, v opačném případě <span class="fixed">False</span>. Používejte tuto funkci místo <span class="fixed">xs == []</span> (pokud máte seznam pojmenovaný <span class="fixed">xs</span>).</p>
<pre name="code" class="haskell: ghci">
ghci&gt; null [1,2,3]
False
ghci&gt; null []
True</pre>
<p>Funkce <span class="label function">reverse</span> obrátí seznam.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; reverse [5,4,3,2,1]
[1,2,3,4,5]</pre>
<p>Funkce <span class="label function">take</span> požaduje číslo a seznam. Vezme ze začátku seznamu tolik prvků, kolik je zadáno. Sledujte.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; take 3 [5,4,3,2,1]
[5,4,3]
ghci&gt; take 1 [3,9,3]
[3]
ghci&gt; take 5 [1,2]
[1,2]
ghci&gt; take 0 [6,6,6]
[]</pre>
<p>
Všimněte si, že pokud zkusíme vzít ze seznamu více prvků, než v něm je, prostě vrátí celý seznam. Jestliže zkusíme vzít 0 prvků, získáme tím prázdný seznam.
</p>
<p>Funkce <span class="label function">drop</span> funguje podobně, akorát zahodí určitý počet prvků ze začátku seznamu.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; drop 3 [8,4,2,1,5,6]
[1,5,6]
ghci&gt; drop 0 [1,2,3,4]
[1,2,3,4]
ghci&gt; drop 100 [1,2,3,4]
[] </pre>
<p>Funkce <span class="label function">maximum</span> vezme seznam věcí, které se dají porovnat, a vrátí největší prvek.</p>
<p>Funkce <span class="label function">minimum</span> vrátí nejmenší prvek.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; minimum [8,4,2,1,5,6]
1
ghci&gt; maximum [1,9,2,3,4]
9 </pre>
<p>Funkce <span class="label function">sum</span> vezme seznam čísel a vrátí jejich součet.</p>
<p>Funkce <span class="label function">product</span> vezme seznam čísel a vrátí jejich součin.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; sum [5,2,1,6,3,2,5,7]
31
ghci&gt; product [6,2,1,2]
24
ghci&gt; product [1,2,5,6,7,9,2,0]
0 </pre>
<p>Funkce <span class="label function">elem</span> vezme věc a seznam věcí a sdělí nám, jestli je ta věc prvkem seznamu. Je většinou volána jako infixová funkce, protože je jednodušší ji tak číst.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; 4 `elem` [3,4,5,6]
True
ghci&gt; 10 `elem` [3,4,5,6]
False
</pre>
<p>
Tohle bylo pár základních funkcí na práci se seznamy. Na více funkcí se podíváme v následujících sekcích.
</p>
<a name="serifovy-rozsahy"></a><h2>Šerifovy rozsahy</h2>
<p>
<img src="cowboy.png" alt="kovboj" class="right" width="200" height="258">
Co když budeme chtít seznam všech čísel mezi jedničkou a dvacítkou? Určitě bychom je mohli všechny prostě napsat, ale to není zřejmě řešení pro džentlmeny, kteří požadují od svých programovacích jazyků dokonalost. Místo toho použijeme rozsahy. Rozsahy jsou způsob vytváření seznamů, které jsou aritmetické posloupnosti prvků, které se dají vyjmenovat. Čísla mohou být vyjmenována. Jedna, dva, tři, čtyři atd. Znaky mohou být také vyjmenovány. Abeceda je posloupnost znaků od A do Z (česká abeceda je od A do Ž). Jména nemůžou být vyjmenována. Co následuje po jménu „Jan“? Nevím.
</p>
<p>
Pro vytvoření seznamu všech přirozených čísel od jedničky do dvacítky stačí napsat <span class="fixed">[1..20]</span>. To je stejné, jako bychom napsali  <span class="fixed">[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]</span> a není rozdíl mezi tímto zápisem a předchozím, kromě toho, že vypisování dlouhých posloupností ručně je hloupé.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [1..20]
[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
ghci&gt; ['a'..'z']
"abcdefghijklmnopqrstuvwxyz"
ghci&gt; ['K'..'Z']
"KLMNOPQRSTUVWXYZ" </pre>
<p>
Rozsahy jsou skvělé, protože se v nich také dá uvést přírůstek. Co když chceme všechna sudá čísla mezi jedničkou a dvacítkou? Nebo každé třetí číslo mezi jedničkou a dvacítkou?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [2,4..20]
[2,4,6,8,10,12,14,16,18,20]
ghci&gt; [3,6..20]
[3,6,9,12,15,18] </pre>
<p>
Je to jednoduše otázka oddělení prvních dvou prvků čárkou a potě stanovení horní meze. I když to je celkem chytré, rozsahy nedovedou dělat věci, které od nich někteří lidé očekávají. Nemůžete napsat <span class="fixed">[1,2,4,8,16..100]</span> a očekávat, že tím získáte všechny mocniny dvojky. Za prvé protože můžete pouze uvést pouze jeden přírůstek. A za druhé protože některé nearitmetické posloupnosti jsou víceznačné, pokud zadáme pouze několik jejich prvních členů.
</p>
<p>
Pro vytvoření seznamu všech čísel od dvacítky do jedničky nestačí napsat <span class="fixed">[20..1]</span>, musíte uvést <span class="fixed">[20,19..1]</span>.
</p>
<p>
Pozor na vytváření rozsahů desetinných čísel! Protože nejsou (už z definice) naprosto přesné, jejich používání v rozsazích může vést k celkem divokým výsledkům.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [0.1, 0.3 .. 1]
[0.1,0.3,0.5,0.7,0.8999999999999999,1.0999999999999999]
</pre>
<p>
Moje rada je nepoužívat je v rozsazích.
</p>
<p>
Můžete také pomocí rozsahů vytvářet nekonečné seznamy jednoduše tím, že nestanovíte horní mez. Později se budeme zabývat detailněji nekonečnými seznamy. Teď pojďme prozkoumat, jak dostat prvních 24 násobků třináctky. Jasně, mohli bychom napsat <span class="fixed">[13,26..24*13]</span>. Ale existuje lepší způsob: <span class="fixed">take 24 [13,26..]</span>. Protože je Haskell líný, nebude se snažit vyhodnotit nekonečný seznam okamžitě, protože by s vyhodnocováním nikdy neskončil. Raději počká, co se všechno z toho nekonečného seznamu bude chtít. A uvidí, že chcete  pouze prvních 24 prvků, které s radostí vrátí.
</p>
<p>Užitečné funkce, které vytváří nekonečné seznamy:</p>
<p>Funkce <span class="label function">cycle</span> vezme seznam a opakuje (cyklí) ho nekonečně dlouho. Pokud si chcete výsledný seznam zobrazit, bude pořád pokračovat, takže si ho budete muset někde ukrojit.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; take 10 (cycle [1,2,3])
[1,2,3,1,2,3,1,2,3,1]
ghci&gt; take 12 (cycle "LOL ")
"LOL LOL LOL " </pre>
<p>Funkce <span class="label function">repeat</span> vezme prvek a vytvoří z něj nekonečný seznam, obsahující pouze ten prvek.</p>
<pre name="code" class="haskell: ghci">
ghci&gt; take 10 (repeat 5)
[5,5,5,5,5,5,5,5,5,5]
</pre>
<p>
Ačkoliv je jednodušší použít funkci <span class="label function">replicate</span>, pokud chceme určitý počet opakování jednoho prvku v seznamu. Například <span class="fixed">replicate 3 10</span> vrátí <span class="fixed">[10,10,10]</span>.
</p>
<a name="jsem-generator-seznamu"></a><h2>Jsem generátor seznamu</h2>
<p>
<img src="kermit.png" alt="žába" class="left" width="180" height="156">
Pokud jste někdy absolvovali matematický kurz, možná jste už slyšeli o <i>intenzionálním zápisu množin</i>. Ten se běžně používá pro generování určitých množin. Jednoduchý zápis množiny, jež obsahuje prvních deset sudých přirozených čísel, je <img src="setnotation.png" alt="intenzionální zápis">. Část před svislítkem se nazývá výstupní funkce, <span class="fixed">x</span> je proměnná, <span class="fixed">N</span> je vstupní množina a <span class="fixed">x &lt;= 10</span> je predikát. To znamená, že množina obsahuje dvojnásobek všech přirozených čísel, které vyhovují predikátu.
</p>
<p>
Pokud to budeme chtít vyjádřit v Haskellu, můžeme zkusit něco jako <span class="fixed">take 10 [2,4..]</span>. Ale co když nebudeme chtít dvojnásobky prvních deseti přirozených čísel, ale něco mnohem složitějšího? Mohli bychom ten seznam definovat intenzionálně, tedy ho vygenerovat. Generátor seznamu je velmi podobný intenzionálnímu zápisu množin. Budeme se zatím držet výpisu prvních deseti sudých čísel. Intenzionálně to můžeme zapsat jako <span class="fixed">[x*2 | x &lt;- [1..10]]</span>. Hodnota <span class="fixed">x</span> je brána z rozsahu <span class="fixed">[1..10]</span> a pro každý prvek z <span class="fixed">[1..10]</span> (jež je vázaný na <span class="fixed">x</span>) dostaneme naši hodnotu, akorát vynásobenou dvojkou. Tady je generátor seznamu v akci.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [x*2 | x <- [1..10]]
[2,4,6,8,10,12,14,16,18,20]
</pre>
<p>
Jak můžete vidět, dostaneme požadovaný výsledek. Nyní si přidáme podmínku (nebo také predikát) do naší definice generátoru. Predikáty se zapisují až za část s navázáním proměnné a odděluje se čárkou. Řekněme, že bychom chtěli pouze prvky, jejichž dvojnásobek je větší nebo rovný dvanácti.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [x*2 | x <- [1..10], x*2 >= 12]
[12,14,16,18,20]
</pre>
<p>
Skvělé, funguje to. A co když budeme chtít všechna čísla od 50 do 100, jejichž zbytek po dělení číslem 7 je 3? Jednoduché.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [ x | x &lt;- [50..100], x `mod` 7 == 3]
[52,59,66,73,80,87,94] </pre>
<p>
Úspěch! Zapamatujte si, že se třídění seznamů pomocí predikátů také nazývá <em>filtrování</em>. Vezmeme seznam čísel a vyfiltrujeme je pomocí predikátů. A teď další příklad. Řekněme, že chceme vygenerovat přepsání každého lichého čísla většího než 10 řetězcem <span class="fixed">"BANG!"</span> a každého lichého čísla, které je menší než 10, řetězcem <span class="fixed">"BOOM!"</span>. Pokud číslo není liché, zahodíme ho. Z důvodů pohodlnosti vložíme náš generátor do funkce, abychom ho mohli jednoduše použít vícekrát.
</p>
<pre name="code" class="haskell: ghci">
boomBangs xs = [ if x &lt; 10 then "BOOM!" else "BANG!" | x &lt;- xs, odd x]</pre>
<p>
Poslední část definice generátoru je predikát. Funkce <span class="fixed">odd</span> vrací <span class="fixed">True</span>, pokud je číslo liché, a <span class="fixed">False</span>, pokud je sudé. Prvek je přidán do seznamu pouze pokud jsou všechny predikáty vyhodnoceny jako <span class="fixed">True</span>.
</p>
<pre name="code" class="haskell: hs">
ghci&gt; boomBangs [7..13]
["BOOM!","BOOM!","BANG!","BANG!"]</pre>
<p>
Můžeme zapsat několik predikátů. Jestliže chceme všechna čísla od 10 do 20, která nejsou 13, 15 nebo 19, napíšeme:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [ x | x &lt;- [10..20], x /= 13, x /= 15, x /= 19]
[10,11,12,14,16,17,18,20]</pre>
<p>
Nejen že můžeme mít v definicích generátoru více predikátů (každý prvek musí splňovat veškeré predikáty, aby byl obsažen ve výsledném seznamu), můžeme také vybírat prvky z několika seznamů. Když vybíráme prvky z více seznamů, vygenerují se všechny kombinace ze zadaných seznamů a poté je můžeme zkombinovat ve výstupní funkci. Generátor seznamu, který bere prvky ze dvou seznamů délky 4, vrátí seznam délky 16, za předpokladu, že je nebude filtrovat. Pokud máme dva seznamy, <span class="fixed">[2,5,10]</span> a <span class="fixed">[8,10,11]</span> a budeme chtít součin všech možných kombinací čísel z těchto seznamů, uděláme následující.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [ x*y | x &lt;- [2,5,10], y &lt;- [8,10,11]]
[16,20,22,40,50,55,80,100,110] </pre>
<p>
Jak jsme čekali, délka nového seznamu je 9. Co když budeme chtít všechny součiny, které jsou větší než 50?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; [ x*y | x &lt;- [2,5,10], y &lt;- [8,10,11], x*y &gt; 50]
[55,80,100,110] </pre>
<p>
A co třeba generátor seznamu, který zkombinuje seznamy přídavných a podstatných jmen do bujaré epopeje?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let nouns = ["tulák","žabák","papež"]
ghci&gt; let adjectives = ["líný","nabručený","pletichářský"]
ghci&gt; [adjective ++ " " ++ noun | adjective &lt;- adjectives, noun &lt;- nouns]
["líný tulák","líný žabák","líný papež","nabručený tulák","nabručený žabák",
"nabručený papež","pletichářský tulák","pletichářský žabák","pletichářský papež"]</pre>
<p>
Už vím! Napišme si vlastní verzi funkce <span class="fixed">length</span>! Nazveme ji <span class="fixed">length'</span>.
</p>
<pre name="code" class="haskell: hs">
length' xs = sum [1 | _ &lt;- xs] </pre>
<p>
Znak <span class="fixed">_</span> značí, že je nám jedno, co budeme dělat s prvkem ze seznamu, takže místo psaní názvu proměnné, kterou nikdy nepoužijeme, jednoduše napíšeme <span class="fixed">_</span>. Tato funkce nahradí každý prvek v seznamu číslem <span class="fixed">1</span> a poté je všechny sečte. Což znamená, že výsledný součet bude délka našeho seznamu.
</p>
<p>
Jenom přátelská připomínka: protože jsou řetězce seznamy, můžeme použít generátor seznamu na zpracování a vytváření řetězců. Zde je funkce, která vezme řetězce a odstraní z nich všechno kromě velkých písmen.
</p>
<pre name="code" class="haskell: hs">
removeNonUppercase st = [ c | c &lt;- st, c `elem` ['A'..'Z']] </pre>
<p>
Otestujeme ji:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; removeNonUppercase "Hahaha! Ahahaha!"
"HA"
ghci&gt; removeNonUppercase "neMAMRADZABY"
"MAMRADZABY"</pre>
<p>
Veškerou práci zde zastává predikát. Vyjadřuje, že znak bude obsažen v novém seznamu pouze pokud je prvkem seznamu <span class="fixed">['A'..'Z']</span>. Zanořování generátorů je také možné, pokud operujete nad seznamy, jež obsahují další seznamy. Třeba seznam obsahující seznamy čísel. Pojďme odstranit všechna lichá čísla bez přeskupování seznamu.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let xxs = [[1,3,5,2,3,1,2,4,5],[1,2,3,4,5,6,7,8,9],[1,2,4,2,1,6,3,1,3,2,3,6]]
ghci&gt; [ [ x | x &lt;- xs, even x ] | xs &lt;- xxs]
[[2,2,4],[2,4,6,8],[2,4,2,6,2,6]]
</pre>
<p>
Generátor seznamu je možné zapsat přes několik řádků. Takže pokud zrovna nepracujete v GHCi, je lepší rozdělit dlouhé seznamy přes více řádků, zvláště když jsou zanořené.
</p>
<a name="n-tice"></a><h2>N-tice</h2>
<img src="tuple.png" alt="n-tice" class="right" width="160" height="162">
<p>
V některých ohledech jsou n-tice (uspořádané heterogenní seznamy o n prvcích) podobné seznamům &mdash; slouží pro ukládání několika hodnot do jedné. Jenomže mají pár zásadních odlišností. Seznam čísel je seznam čísel. To je jeho typ a nezáleží na tom, jestli obsahuje jedno číslo nebo nekonečně mnoho. N-tice se ovšem používají, pokud přesně víte, kolik hodnot chcete zkombinovat a jejich typ závisí na počtu a typu jednotlivých složek. Jsou uvozeny kulatými závorkami a jejich složky odděleny čárkami.
</p>
<p>
Další důležitou odlišností je, že nemusí být homogenní. Na rozdíl od seznamu může n-tice obsahovat kombinaci různých typů.
</p>
<p>
Zamysleme se nad tím, jak bychom v Haskellu vyjádřili dvourozměrnou souřadnici. Je možnost použít seznam. To by mohlo fungovat. Co když budeme chtít vložit pár vektorů do seznamu, abychom tak vyjádřili body nějakého útvaru na dvourozměrné ploše? Mohli bychom mít něco jako <span class="fixed">[[1,2],[8,11],[4,5]]</span>. Problém s tímto způsobem spočívá v tom, že bychom z toho mohli udělat něco jako <span class="fixed">[[1,2],[8,11,5],[4,5]]</span>, s čímž Haskell nemá problém, jelikož to je stále seznam seznamů čísel, ale nedává to smysl. Ale n-tice o velikosti dva (nazývána jako dvojice) má svůj vlastní typ, což znamená, že seznam nemůže obsahovat několik dvojic a zároveň nějaké trojice (n-tice velikosti tři), takže ji použijeme místo toho. Namísto obklopování souřadnic hranatými závorkami použijeme kulaté: <span class="fixed">[(1,2),(8,11),(4,5)]</span>. Co když se budeme snažit vytvořit útvar jako <span class="fixed">[(1,2),(8,11,5),(4,5)]</span>? No, dostaneme tuhle chybu:
</p>
<pre name="code" class="haskell: ghci">
Couldn't match expected type `(t, t1)'
against inferred type `(t2, t3, t4)'
In the expression: (8, 11, 5)
In the expression: [(1, 2), (8, 11, 5), (4, 5)]
In the definition of `it': it = [(1, 2), (8, 11, 5), (4, 5)]
</pre>
<p>
Říká nám to, že jsme se pokusili použít dvojici a trojici ve stejném seznamu, k čemuž by nemělo dojít. Stejně jako nemůžete vytvořit seznam jako <span class="fixed">[(1,2),("One",2)]</span>, protože první prvek v seznamu je dvojice čísel a druhý je dvojice sestávající se z řetězce a čísla. N-tice mohou být použity k vyjádření rozmanitých druhů dat. Kupříkladu pokud chceme v Haskellu vyjádřit někoho jméno a jeho věk, můžeme použít trojici: <span class="fixed">("Christopher", "Walken", 55)</span>. Na tomto příkladu můžete vidět, že n-tice mohou obsahovat i seznamy.
</p>
<p>
Použijte n-tice, pokud předem víte, kolik složek budete na data potřebovat. N-tice jsou mnohem méně tvárné, protože se od počtu složek odvíjí jejich typ, takže nelze napsat obecnou funkci na přidání prvku do n-tice &mdash; musí se napsat funkce zvlášť pro dvojici, funkce pro trojici, pro čtveřici atd.
</p>
<p>
I když existuje jednoprvkový seznam, neexistuje věc jako n-tice s jednou složkou. Nedává to moc velký smysl, když se nad tím zamyslíte. Jednosložková n-tice by byla pouze hodnota, kterou by obsahovala, což by pro nás nemělo žádný přínos.
</p>
<p>
Stejně jako seznamy, n-tice se dají porovnávat, pokud jsou její složky porovnatelné. Nedají se ovšem porovnávat dvě n-tice rozdílné velikosti, zatím co je možné porovnávat dva rozdílně dlouhé seznamy. Dvě užitečné funkce, které pracují se dvojicemi:
</p>
<p>
Funkce <span class="label function">fst</span> vezme dvojici a vrátí její první složku.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; fst (8,11)
8
ghci&gt; fst ("Wow", False)
"Wow"</pre>
<p>
Funkce <span class="label function">snd</span> vezme dvojici a vrátí její druhou složku. Jaké překvapení!
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; snd (8,11)
11
ghci&gt; snd ("Wow", False)
False</pre>
<div class="hintbox"><em>Poznámka:</em> tyto funkce pracují pouze se dvojicemi. Nebudou fungovat na trojicích, čtveřicích, pěticích atd. Dostaneme se k jiným způsobům získávání dat z n-tic později.</div>
<p>
Skvělá funkce, která vytváří seznam dvojic: <span class="label function">zip</span>. Vezme dva seznamy a poté je sepne dohromady do seznamu spojením odpovídajích prvků do dvojic. Je to opravdu jednoduchá funkce, ale má hromadu použití. Je zvláště užitečná pro kombinaci nebo propojení dvou seznamů. Následuje názorná ukázka.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; zip [1,2,3,4,5] [5,5,5,5,5]
[(1,5),(2,5),(3,5),(4,5),(5,5)]
ghci&gt; zip [1 .. 5] ["jedna", "dva", "tři", "čtyři", "pět"]
[(1,"jedna"),(2,"dva"),(3,"tři"),(4,"čtyři"),(5,"pět")]
</pre>
<p>
Funkce spáruje prvky a vytvoří z nich nový seznam. První prvek s první, druhý s druhým atd. Všimněte si, že jelikož dvojice může obsahovat různorodé typy složek, <span class="fixed">zip</span> taktéž může vzít dva typově různé seznamy a sepnout je dohromady. Co se stane, když délka seznamů nesouhlasí?
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; zip [5,3,2,6,2,7,2,5,4,6,6] ["já","jsem","želva"]
[(5,"já"),(3,"jsem"),(2,"želva")]
</pre>
<p>
Delší seznam se jednoduše ořízl, aby měl stejnou délku jako kratší. Protože je Haskell líný, můžeme párovat konečné seznamy s nekonečnými:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; zip [1..] ["jablko", "pomeranč", "třešeň", "mango"]
[(1,"jablko"),(2,"pomeranč"),(3,"třešeň"),(4,"mango")]
</pre>
<p>
Zde je úloha, která kombinuje n-tice a generátor seznamu: jaký pravoúhlý trojúhelník s celočíselnými stranami má všechny strany rovné nebo menší než 10 a jeho obvod je 24? Nejprve zkusíme vypsat všechny trojúhelníky se stranami rovnými nebo menšími než 10:
</p>
<pre name="code" class="haskell: ghci">ghci&gt; let triangles = [ (a,b,c) | c &lt;- [1..10], b &lt;- [1..10], a &lt;- [1..10] ] </pre>
<p>
Vygenerovali jsem si čísla ze tří seznamů a zkombinovali jsme je v naši výstupní funkci do trojic. Pokud v GHCi zadáte příkaz <span class="fixed">triangles</span>, dostanete seznam všech možných trojúhelníků se stranami menšími nebo rovnými 10. Dále přidáme podmínku, že to musí být pravoúhlý trojúhelník. Taktéž upravíme tuto funkci přihlédnutím k faktu, že strana <i>b</i> není větší než přepona a že strana <i>a</i> není větší než strana <i>b</i>.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let rightTriangles = [ (a,b,c) | c &lt;- [1..10], b &lt;- [1..c], a &lt;- [1..b], a^2 + b^2 == c^2] </pre>
<p>
Už jsme skoro hotovi. Teď jen změníme funkci prohlášením, že chceme jenom trojúhelníky s obvodem 24.
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let rightTriangles' = [ (a,b,c) | c &lt;- [1..10], b &lt;- [1..c], a &lt;- [1..b], a^2 + b^2 == c^2, a+b+c == 24]
ghci&gt; rightTriangles'
[(6,8,10)]
</pre>
<p>
A tady máme naši odpověď! Tohle je častý postup ve funkcionálním programování. Vezmete si počáteční množinu možných řešení a poté ji přetváříte a aplikujete filtry, dokud nezískáte správné řešení.
</p>
