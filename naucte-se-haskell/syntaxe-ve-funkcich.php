<div class="english-version"><a href="http://learnyouahaskell.com/syntax-in-functions">English version</a></div>
<h1 style="margin-left:-3px">Syntaxe ve funkcích</h1>
<a name="vzory"></a><h2>Vzory</h2>
<img src="images/pattern.png" alt="čtyři!" class="right" width="162" height="250">
<p>
Tato kapitola se bude týkat některých užitečných syntaktických konstruktů a nejprve se pustíme do vzorů (pattern matching). Vzory se sestávají z určitých schémat, kterým mohou data odpovídat a poté se ověřuje, jestli ano, a podle těchto schémat se data dekonstruují.
</p>
<p>
Při definování funkce je možnost napsat samostatně tělo funkce pro jiný vzor. Což vede k velice čistému kódu, který je jednoduchý a čitelný. Vzory se dají použít u jakéhokoliv datového typy &mdash; čísla, znaky, seznamy, n-tice atd. Vytvořme si opravdu triviální funkci, která ověřuje, jestli je zadané číslo sedmička nebo ne.
</p>
<pre name="code" class="haskell: hs">
lucky :: (Integral a) =&gt; a -&gt; String
lucky 7 = "ŠŤASTNÉ ČÍSLO SEDM!"
lucky x = "Je mi líto, máš pech, kámo!"</pre>
<p>
Když zavoláte funkci <span class="fixed">lucky</span>, schéma se bude ověřovat shora dolů a pokud bude sedět, použije se odpovídající tělo funkce. Jediné číslo, jež může odpovídat prvnímu vzoru, je číslo 7. Pokud není zadáno, přejde se na druhý vzor, který zachytí cokoliv a spojí to s <span class="fixed">x</span>. Tahle funkce může být implementována použitím výrazu if. Ale co když chceme funkci, která vypíše číslo od jedničky po pětku a napíše <span class="fixed">Není mezi 1 a 5.</span> pro ostatní čísla? Bez vzorů bychom museli vytvořit celkem spletitý strom z if, then a else. Avšak se vzory:
</p>
<pre name="code" class="haskell: hs">
sayMe :: (Integral a) =&gt; a -&gt; String
sayMe 1 = "Jedna!"
sayMe 2 = "Dva!"
sayMe 3 = "Tři!"
sayMe 4 = "Čtyři!"
sayMe 5 = "Pět!"
sayMe x = "Není mezi 1 a 5."
</pre>
<p>
Všimněte si, že kdybychom přesunuli poslední vzor (obecný, který zachytí všechno) úplně nahoru, tak by funkce vždycky vypsala <span class="fixed">Není mezi 1 a 5.</span>, protože by zachytil všechna čísla a nebyla by šance, že by se pokračovalo dál a přešlo na další vzory.
</p>
<p>
Pamatujete si na funkci <span class="fixed">factorial</span>, jež jsme implementovali předtím? Definovali jsme faktoriál čísla <span class="fixed">n</span> jako <span class="fixed">product [1..n]</span>. Můžeme také definovat faktoriál <i>rekurzivně</i>, způsobem, jakým se obvykle definuje v matematice. Začneme tvrzením, že faktoriál nuly je jednička. Pak uvedeme, že faktoriál každého přirozeného čísla je to číslo vynásobené faktoriálem jeho předchůdce. Takhle to vypadá přeložené do jazyka Haskellu.
</p>
<pre name="code" class="haskell: hs">
factorial :: (Integral a) =&gt; a -&gt; a
factorial 0 = 1
factorial n = n * factorial (n - 1)
</pre>
<p>
Poprvé jsme tu definovali funkci rekurzivně. Rekurze je v Haskellu důležitá a my se na ni podíváme později podrobněji. Ale zatím si v rychlosti ukážeme, co se děje při výpočtu faktoriálu řekněme trojky. Pokusí se vyhodnotit <span class="fixed">3 * factorial 2</span>. Faktoriál dvojky je <span class="fixed">2 * factorial 1</span>, takže zatím máme <span class="fixed">3 * (2 * factorial 1)</span>. Rozepsaný <span class="fixed">factorial 1</span> je <span class="fixed">1 * factorial 0</span>, takže dostáváme <span class="fixed">3 * (2 * (1 * factorial 0))</span>. A teď přichází trik &mdash; definovali jsme faktoriál nuly, že je jedna, a protože se narazí na vzor před tím obecným, jednoduše vrátí jedničku. Takže se finální forma podobá <span class="fixed">3 * (2 * (1 * 1))</span>. Kdybychom napsali druhý vzor před první, tak by zachytil všechna čísla včetně nuly a náš výpočet by nikdy neskončil. To je důvod, proč je pořadí vzorů důležité a je vždycky lepší uvádět dříve vzory pro konkrétní hodnoty a obecné až na konec.
</p>
<p>
Ověřování vzorů může také selhat. Jestliže si definujeme takovouto funkci:
</p>
<pre name="code" class="haskell: hs">
charName :: Char -&gt; String
charName 'a' = "Albert"
charName 'b' = "Bedřich"
charName 'c' = "Cecil"
</pre>
<p>
a poté bude zavolána se vstupem, který jsme neočekávali, stane se tohle:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; charName 'a'
"Albert"
ghci&gt; charName 'b'
"Bedřich"
ghci&gt; charName 'h'
"*** Exception: tut.hs:(53,0)-(55,21): Non-exhaustive patterns in function charName
</pre>
<p>
GHCi si oprávněně stěžuje, že nemáme definovány vzory kompletně. Při vytváření vzorů bychom nikdy neměli zapomenout přidat obecný vzor, aby náš program nepadal po zadání neočekávaném vstupu.
</p>
<p>
Vzory mohou být použity také s n-ticemi. Co když chceme vytvořit funkci, jež vezme dva vektory ve dvoudimenzionálním prostoru (tedy ve formě dvojic) a sečte je dohromady. Při sčítání vektorů sečteme odděleně jejich xové složky a pak jejich ypsilonové složky. Zde je ukázáno, jak bychom toho mohli dosáhnout, kdybychom nevěděli o vzorech:
</p>
<pre name="code" class="haskell: hs">
addVectors :: (Num a) =&gt; (a, a) -&gt; (a, a) -&gt; (a, a)
addVectors a b = (fst a + fst b, snd a + snd b)
</pre>
<p>
Prima, funguje to, ale je lepší způsob, jak to udělat. Upravíme tu funkci, aby používala vzory.
</p>
<pre name="code" class="haskell: hs">
addVectors :: (Num a) =&gt; (a, a) -&gt; (a, a) -&gt; (a, a)
addVectors (x1, y1) (x2, y2) = (x1 + x2, y1 + y2)
</pre>
<p>
A je to! Mnohem lepší. Všimněte si, že tohle je už obecný vzor. Typ funkce <span class="fixed">addVectors</span> (v obou příkladech) je <span class="fixed">addVectors :: (Num a) =&gt; (a, a) -&gt; (a, a) - &gt; (a, a)</span>, takže můžeme zaručit, že dostaneme dvě dvojice jako parametr.
</p>
<p>
Funkce <span class="fixed">fst</span> a <span class="fixed">snd</span> získají složky dvojic. Ale co trojice? No, není na to standardní funkce, ale můžeme si napsat vlastní.
</p>
<pre name="code" class="haskell: hs">
first :: (a, b, c) -&gt; a
first (x, _, _) = x

second :: (a, b, c) -&gt; b
second (_, y, _) = y

third :: (a, b, c) -&gt; c
third (_, _, z) = z
</pre>
<p>
Znak <span class="fixed">_</span> představuje to stejné co představoval u generátorů seznamu. Což znamená, že se vůbec nestaráme o hodnotu v té části, takže prostě napíšeme <span class="fixed">_</span>.
</p>
<p>
Což mi připomíná, že se vzory dají použít i v generátorech seznamu. Sledujte:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; let xs = [(1,3), (4,3), (2,4), (5,3), (5,6), (3,1)]
ghci&gt; [a+b | (a,b) <- xs]
[4,7,6,8,11,4] </pre>
<p>
Při nesplnění vzoru se prostě přejde na další prvek.
</p>
<p>
Samotné seznamy mohou být také použity jako vzory. Můžete ověřit prázdný seznam <span class="fixed">[]</span> nebo jakýkoliv vzor, který obsahuje dvojtečku <span class="fixed">:</span> a prázdný seznam. Protože je <span class="fixed">[1,2,3]</span> jenom syntaktický cukr pro <span class="fixed">1:2:3:[]</span>, dá se použít prvně uvedený vzor. Vzor jako <span class="fixed">x:xs</span> spojí první prvek ze seznamu s <span class="fixed">x</span> a zbytek s <span class="fixed">xs</span>, i když by byl seznam jednoprvkový, to by se z <span class="fixed">xs</span> stal prázdný seznam.
</p>
<div class="hintbox"><em>Poznámka</em>: vzor <span class="fixed">x:xs</span> se používá často, hlavně v rekurzivních funkcích. Ale vzory, obsahující <span class="fixed">:</span>, se dají použít jenom v seznamech délky 1 nebo více.</div>
<p>
Pokud chcete spojit s proměnnými, řekněme, první tři prvky a zbytek seznamu s jinou proměnnou, můžete použít něco jako <span class="fixed">x:y:z:zs</span>. Tohle bude fungovat jenom se seznamem, jež má tři prvky a více.
</p>
<p>
A když teď víme, jak používat vzory se seznamy, napišme si vlastní implementaci funkce <span class="fixed">head</span>.
</p>
<pre name="code" class="haskell: hs">
head' :: [a] -&gt; a
head' [] = error "Nemůžeš zjistit první prvek prázdného seznamu, hňupe!"
head' (x:_) = x
</pre>
<p>
Vyzkoušíme, jestli to funguje:
</p>
<pre name="code" class="haskell: ghci">
ghci&gt; head' [4,5,6]
4
ghci&gt; head' "Nazdar"
'N'
</pre>
<p>
Nádhera! Všimněte si, že pokud chceme spojit několik proměnných (i když je nějaká z nich jenom <span class="fixed">_</span> a ve skutečnosti se vůbec nespojí), musíme vzor obklopit kulatými závorkami. Také si všimněte funkce <span class="fixed">error</span>, jenž jsme použili. Vezme řetězec a vygeneruje běhovou chybu a použije ten řetězec jako informaci o tom, jaká chyba nastala. To způsobí pád programu, takže není moc dobrý nápad tuto funkci používat příliš často. Jenže zavolání funkce <span class="fixed">head</span> na prázdný seznam nedává smysl.
</p>
<p>
Vytvořme si triviální funkci, která nám vypíše první prvky ze seznamu v (ne)vhodné formě v češtině.
</p>
<pre name="code" class="haskell: hs">
tell :: (Show a) =&gt; [a] -&gt; String
tell [] = "Seznam je prázdný."
tell (x:[]) = "Seznam obsahuje jeden prvek: " ++ show x
tell (x:y:[]) = "Seznam obsahuje dva prvky: " ++ show x ++ " a " ++ show y
tell (x:y:_) = "Seznam je dlouhý. První dva prvky jsou: " ++ show x ++ " a " ++ show y
</pre>
<p>
Tato funkce je bezpečná, protože se vypořádá s prázdným seznamem, jedno- a dvouprvkovým seznamem a se seznamem s více než dvěma prvky. Všimněte si, že vzory <span class="fixed">(x:[])</span> a <span class="fixed">(x:y:[])</span> mohou být zapsány jako <span class="fixed">[x]</span> a <span class="fixed">[x,y]</span> (syntaktický cukr, u něhož nepotřebujeme kulaté závorky). Nemůžeme přepsat vzor <span class="fixed">(x:y:_)</span> na tvar s hranatými závorkami, protože potřebujeme, aby to ověřovalo seznamy délky dva a více.
</p>
<p>
Již jsme implementovali svou vlastní funkci <span class="fixed">length</span> pomocí generátoru seznamu. Teď na to použijeme vzory a trochu rekurze:
</p>
<pre name="code" class="haskell: hs">
length' :: (Num b) =&gt; [a] -&gt; b
length' [] = 0
length' (_:xs) = 1 + length' xs</pre>
<p>
Podobá se to funkci na počítání faktoriálu, jež jsme napsali dříve. Nejprve jsme si definovali výsledek známého vstupu &mdash; prázdného seznamu. To je též známo jako <i>okrajová podmínka</i>. Poté jsme ve druhém vzoru vzali kus seznamu pomocí rozdělení na první prvek a zbytek. Napsali jsme, že délka seznamu se rovná jedna plus délka zbytku. Je zde použito podtržítko <span class="fixed">_</span> na ověření prvního prvku, protože se nezajímáme o konkrétní hodnotu. Také si všimněte, že jsme se vypořádali s každým možným typem seznamu. První vzor ověřuje prázdný seznam a druhý cokoliv, co není prázdný seznam.
</p>
<p>
Podívejme se, co se stane, jestliže zavoláme funkci <span class="fixed">length'</span> na řetězec <span class="fixed">"pes"</span>. Nejprve se funkce přesvědčí, zda není vstupem prázdný seznam. Protože není, přejde se na druhý vzor. Druhý vzor odpovídá a výraz se přepíše na <span class="fixed">1 + length' "es"</span>, protože se rozdělí na první prvek a zbytek, který se dále zpracuje. Dobrá. Délka <span class="fixed">length'</span> řetězce <span class="fixed">"es"</span> je, podobně, výraz <span class="fixed">1 + length' "s"</span>. Takže teď máme výraz <span class="fixed">1 + (1 + length' "s")</span>. Hodnota výrazu <span class="fixed">length' "s"</span> je <span class="fixed">1 + length' ""</span> (což může být zapsáno jako <span class="fixed">1 + length' []</span>). A my jsme si definovali, že <span class="fixed">length' []</span> bude <span class="fixed">0</span>. Takže nám nakonec zbude výraz <span class="fixed">1 + (1 + (1 + 0))</span>.
</p>
<p>
Vytvoříme si funkci <span class="fixed">sum</span>. Víme, že součet prázdného seznamu je 0. Napíšeme to jako vzor. A víme také, že součet seznamu je první prvek plus součet zbytku seznamu. Takže když tohle celé zapíšeme, dostaneme:
</p>
<pre name="code" class="haskell:nogutter:nocontrols:hs">
sum' :: (Num a) =&gt; [a] -&gt; a
sum' [] = 0
sum' (x:xs) = x + sum' xs
</pre>
<p>
Také existuje věc, nazvaná <i>zástupný vzor</i>. Je to užitečný způsob, jak rozdělit něco podle vzoru a navázat to na názvy, zatímce stále uchováváme referenci na tu celou věc. Provede se to vložením názvu a zavináče <span class="fixed">@</span> před vzor. Kupříkladu vzor <span class="fixed">xs@(x:y:ys)</span>. Tento vzor bude ověřovat přesně stejnou věc jako <span class="fixed">x:y:ys</span>, jenom můžete přistupovat jednoduše k celému seznamu přes <span class="fixed">xs</span>, aniž byste se museli opakovat a psát znovu <span class="fixed">x:y:ys</span> do těla funkce. Tady je rychlý a hrubý příklad:
</p>
<pre name="code" class="haskell:nogutter:nocontrols:hs">
capital :: String -&gt; String
capital "" = "Prázdný řetězec, jejda!"
capital all@(x:xs) = "První písmeno řetězce " ++ all ++ " je " ++ [x]
</pre>
<pre name="code" class="haskell:ghci">
ghci&gt; capital "Drákula"
"První písmeno řetězce Drákula je D"
</pre>
<p>
Normálně používáme zástupné vzory, abychom se vyhnuli opakování při ověřováňí složitějších vzorů, když musíme využít celý výraz znovu v těle funkce.
</p>
<p>
Ještě jedna věc &mdash; nemůžete ve vzorech použít operátor <span class="fixed">++</span>. Pokud se budete snažit ověřit výraz pomocí vzoru <span class="fixed">(xs ++ ys)</span>, jaký bude první a jaký druhý seznam? Nedává to příliš smysl. Mohlo by dávat smysl, kdybychom chtěli udělat něco jako <span class="fixed">(xs ++ [x,y,z])</span> nebo jenom <span class="fixed">(xs ++ [x])</span>, ale není to možné, už ze samotné podstaty seznamů.
</p>
<a name="straze-straze"></a><h2>Stráže, stráže!</h2>
<img src="images/guards.png" alt="stráž" class="left" width="83" height="180">
<p>
Zatímco vzory jsou určeny k ujištění, že hodnota vyhovuje určité formě, a k její dekonstrukci, stráže jsou pro testování, jestli je nějaká vlastnost hodnoty (či více hodnot) pravdivá nebo nepravdivá. To zní skoro jako výraz if a opravdu je to velice podobné. Věc se má tak, že stráže jsou mnohem čitelnější, když je těch podmínek více, a chovají se spíše jako vzory.
</p>
<p>
Místo vysvětlování jejich syntaxe se do toho rovnou pustíme a vytvoříme si funkci s využitím stráží. Napíšeme si jednoduchou funkce, která na vás bude nadávat v závislosti na vašem <a href="http://cs.wikipedia.org/wiki/Index_t%C4%9Blesn%C3%A9_hmotnosti">indexu tělesné hmotnosti</a> (BMI). Hmotnostní index se rovná váze člověka vydělené druhou mocninou jeho výšky. Pokud je index menší než 18,5, je považován za podvyživeného. Jestliže je mezi 18,5 a 25, je považován za normálního. Hodnota mezi 25 a 30 je nadváha a lidé s BMI nad 30 jsou obézní. Takže tady je ta funkce (nebudeme ji teď počítat, tahle funkce pouze vezme BMI a vynadá vám).
</p>
<pre name="code" class="haskell:hs">
bmiTell :: (RealFloat a) =&gt; a -&gt; String
bmiTell bmi
    | bmi &lt;= 18.5 = "Jsi podvyživený, ty emo, ty!"
    | bmi &lt;= 25.0 = "Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
    | bmi &lt;= 30.0 = "Jsi tlustý! Zhubni, špekoune!"
    | otherwise   = "Jsi velryba, gratuluji!"
</pre>
<p>
Stráž se zadává svislítkem, který následuje za názvem funkce a jejími parametry. Obvykle jsou svislítka o kousek odsazena doprava a zarovnána. Stráže jsou vlastně booleovské výrazy. Jestliže se vyhodnotí jako <span class="fixed">True</span>, je použito odpovídající tělo funkce. Jestliže se vyhodnotí jako <span class="fixed">False</span>, ověřování pokračuje na další stráž a tak dále. Pokud zavoláme tuto funkci s parametrem <span class="fixed">24.3</span>, nejprve se ověří, jestli je menší nebo rovný <span class="fixed">18.5</span>. Protože není, propadne se k další stráži. Ověření se provede u druhé stráže a protože je 24.3 menší než 25.0, je vrácen druhý řetězec.
</p>
<p>
Tohle celé očividně připomíná velký strom z if a else v imperativních jazycích, jenomže stráže jsou lepší a čitelnější. Zatímco z velkých if a else stromů se nebudete tvářit nadšeně, zvláště když je problém zadaný diskrétním způsobem, ze kterého se těžko přepracovává. Stráže jsou velice příjemnou alternativou.
</p>
<p>
Poslední stráž bývá častokrát <span class="fixed">otherwise</span>. Výraz <span class="fixed">otherwise</span> je definován jednoduše jako <span class="fixed">otherwise = True</span> a odchytí všechno. Tohle je velice podobné vzorům, s tím rozdílem, že se u nich ověřuje, jestli vstup odpovídá nějakému schématu a u stráží se kontroluje, zdali vstup vyhovuje booleovským podmínkám. Jestliže se všechny stráže ve funkci vyhodnotí jako <span class="fixed">False</span> (a neposkytli jsme stráž <span class="fixed">otherwise</span>, která odchytí všechno), vyhodnocení přejde na následující <em>vzor</em>. Takhle spolu vzory a stráže nádherně spolupracují. Pokud není nalezena vyhovující stráž nebo vzor, vyhodnocování skončí chybou.
</p>
<p>
Samozřejmě můžeme použít stráže ve funkci, která bere tolik parametrů, kolik chceme. Místo abychom nutili uživatele počítat svůj hmotnostní index před zavoláním funkce, upravíme tuto funkci, tak, že bude požadovat váhu a výšku a vypočítá to pro nás.
</p>
<pre name="code" class="haskell:hs">
bmiTell :: (RealFloat a) =&gt; a -&gt; a -&gt; String
bmiTell weight height
    | weight / height ^ 2 &lt;= 18.5 = "Jsi podvyživený, ty emo, ty!"
    | weight / height ^ 2 &lt;= 25.0 = "Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
    | weight / height ^ 2 &lt;= 30.0 = "Jsi tlustý! Zhubni, špekoune!"
    | otherwise                   = "Jsi velryba, gratuluji!"
</pre>
<p>
Podívejme se, jestli jsem tlustý&hellip;
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; bmiTell 85 1.90
"Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
</pre>
<p>
Jéje! Nejsem tlustý! Ale Haskell mě nazval šeredným. Co už!
</p>
<p>
Všimněte si, že se nepoužívá rovnítko <span class="fixed">=</span> za názvem funkce a jejími parametry, před první stráží. Hodně začátečníkům to vypíše chybu v syntaxi, protože ho tam občas vloží.
</p>
<p>
Další velmi jednoduchý příklad: napišme si vlastní funkci <span class="fixed">max</span>. Určitě si pamatujete, že vezme dvě porovnatelné věci a vrátí větší z nich.
</p>
<pre name="code" class="haskell:hs">
max' :: (Ord a) =&gt; a -&gt; a -&gt; a
max' a b
    | a &gt; b     = a
    | otherwise = b
</pre>
<p>
Stráže se dají také zapsat jednořádkově, i když to nedoporučuji, protože je to méně čitelné, i u krátkých funkcí. Ale pro demonstraci můžeme napsat <span class="fixed">max'</span> následovně:
</p>
<pre name="code" class="haskell:hs">
max' :: (Ord a) =&gt; a -&gt; a -&gt; a
max' a b | a &gt; b = a | otherwise = b
</pre>
<p>
Fuj! To není moc čitelné! Budeme pokračovat: napíšeme si svou vlastní funkci <span class="fixed">compare</span> pomocí stráží.
</p>
<pre name="code" class="haskell:hs">
myCompare :: (Ord a) =&gt; a -&gt; a -&gt; Ordering
a `myCompare` b
    | a &gt; b     = GT
    | a == b    = EQ
    | otherwise = LT
</pre>
<pre name="code" class="haskell:hs">
ghci&gt; 3 `myCompare` 2
GT
</pre>
<div class="hintbox"><em>Poznámka:</em> funkci můžeme kromě infixového zavolání pomocí zpětných apostrofů také infixově definovat. Někdy je tenhle způsob čitelnější.</div>
<a name="lokalni-definice-where"></a><h2>Lokální definice pomocí where</h2>
<p>
V předchozí sekci jsme si definovali funkci na počítání hmotnosti a nadávání. Bylo to nějak takhle:
</p>
<pre name="code" class="haskell:hs">
bmiTell :: (RealFloat a) =&gt; a -&gt; a -&gt; String
bmiTell weight height
    | weight / height ^ 2 &lt;= 18.5 = "Jsi podvyživený, ty emo, ty!"
    | weight / height ^ 2 &lt;= 25.0 = "Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
    | weight / height ^ 2 &lt;= 30.0 = "Jsi tlustý! Zhubni, špekoune!"
    | otherwise                   = "Jsi velryba, gratuluji!"
</pre>
<p>
Všimněte si, že tu třikrát opakujeme kód. Třikrát opakujeme kód. Opakování kódu (třikrát) při programování je asi tak žádoucí jako kopačka do hlavy. Jelikož jsme opakovali stejný výraz třikrát, bylo by ideální, kdybychom ho mohli spočítat, navázat na proměnnou a poté ji používat namísto výrazu. Můžeme tedy upravit naši funkci následovně:
</p>
<pre name="code" class="haskell:hs">
bmiTell :: (RealFloat a) =&gt; a -&gt; a -&gt; String
bmiTell weight height
    | bmi &lt;= 18.5 = "Jsi podvyživený, ty emo, ty!"
    | bmi &lt;= 25.0 = "Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
    | bmi &lt;= 30.0 = "Jsi tlustý! Zhubni, špekoune!"
    | otherwise   = "Jsi velryba, gratuluji!"
    where bmi = weight / height ^ 2
</pre>
<p>
Vložili jsme klíčové slovo <span class="fixed">where</span> za stráže (obvykle je nejlepší ho odsadit stejně jako svislítka) a poté definovat několik názvů nebo funkcí. Tyto definice jsou viditelné všem strážím a mají tu výhodu, že nemusíme opakovat kód. Jestliže jsme se rozhodli, že budeme počítat BMI jinak, stačí nám změnit kód na jednom místě. Také to zlepšuje čitelnost, když pojmenováváme věci a můžeme tím naše programy zrychlit, protože věci jako je tady proměnná <span class="fixed">bmi</span> stačí vypočítat pouze jednou. Mohli bychom jít o kousek dál a přepsat naši funkci takhle:
</p>
<pre name="code" class="haskell:hs">
bmiTell :: (RealFloat a) =&gt; a -&gt; a -&gt; String
bmiTell weight height
    | bmi &lt;= skinny = "Jsi podvyživený, ty emo, ty!"
    | bmi &lt;= normal = "Jsi údajně normální. Pche, vsadím se, že jsi šereda!"
    | bmi &lt;= fat    = "Jsi tlustý! Zhubni, špekoune!"
    | otherwise     = "Jsi velryba, gratuluji!"
    where bmi = weight / height ^ 2
          skinny = 18.5
          normal = 25.0
          fat    = 30.0
</pre>
<p>
Názvy, jež definujeme u funkce v části s where, jsou dostupné pouze v té funkci, takže se nemusíme obávat o zaneřádění jmenných prostorů jiných funkcí. Všimněte si, ze jsou všechny názvy zarovnány do jednoho sloupce. Pokud je pořádně nezarovnáme, Haskell bude zmatený, protože nebude vědět, co je součástí stejného bloku.
</p>
<p>
Konstrukce where není sdílená v tělu funkce mezi různými vzory. Pokud chcete přistupovat u více vzorů v jedné funkci k nějakým definicím, musíte je definovat globálně.
</p>
<p>
Je také možné ve where definicích použít <em>vzory</em>! Můžeme přepsat sekci s where v naší předchozí funkci na:
</p>
<pre name="code" class="haskell:hs">
    ...
    where bmi = weight / height ^ 2
          (skinny, normal, fat) = (18.5, 25.0, 30.0)
</pre>
<p>
Vytvořme si další poctivě triviální funkci, ve které dostaneme někoho jméno a příjmení a vypíšeme jeho iniciály.
</p>
<pre name="code" class="haskell:hs">
initials :: String -&gt; String -&gt; String
initials firstname lastname = [f] ++ ". " ++ [l] ++ "."
    where (f:_) = firstname
          (l:_) = lastname
</pre>
<p>
Mohli bychom to ověřovat přímo v parametrech funkce (bylo by to ve skutečnosti kratší a zřejmější), ale tohle mělo jenom ukázat, že je možné to udělat taky pomocí where definic.
</p>
<p>
Stejně jako jsem si definovali konstanty ve where blocích, můžete také definovat funkce. Abychom zůstali u našeho programovacího zdravotního tématu, vytvoříme se funkci, která vezme seznam dvojic vah a výšek a vrátí jejich index hmotnosti.
</p>
<pre name="code" class="haskell:hs">
calcBmis :: (RealFloat a) =&gt; [(a, a)] -&gt; [a]
calcBmis xs = [bmi w h | (w, h) &lt;- xs]
    where bmi weight height = weight / height ^ 2
</pre>
<p>
A to je všecho, co je potřeba! Důvod, proč jsme v tomto příkladu zavedli <span class="fixed">bmi</span> jako funkci, je protože nemůžeme vypočítat jedno BMI z parametrů funkce. Musíme projít celý seznam předaný funkci a každá dvojice ze seznamu má rozdílné BMI.
</p>
<p>
Konstrukce where se mohou také větvit. Je to běžný postup, vytvořit funkci a definovat k ní nějaké pomocné funkce se svými where klauzulemi, a pak těm funkcím vytvořit další pomocné funkce, každou s vlastními where klauzulemi.
</p>
<a name="lokalni-definice-let"></a><h2>&hellip; a pomocí let</h2>
<p>
Velmi podobné konstrukci where je konstrukce let. Where je syntaktický konstrukt, který umožní navázání proměnných na konec funkce a celá funkce k nim může přistupovat, včetně všech stráží. Let vám umožní navázat proměnné kamkoliv a je sama o sobě výrazem, ale je lokálnější, takže se nedostane přes stráže. Stejně jako každý konstrukt v Haskellu, jež se používá k navázání hodnoty na název, konstrukce let mohou být použity pro ověřování vzorů. Podívejme se na ně v činnosti! Takhle bychom mohli definovat funkci, která nám vrátí vypočítaný povrch válce na základě jeho výšky a poloměru:
</p>
<pre name="code" class="haskell:hs">
cylinder :: (RealFloat a) =&gt; a -&gt; a -&gt; a
cylinder r h =
    let sideArea = 2 * pi * r * h
        topArea  = pi * r^2
    in  sideArea + 2 * topArea
</pre>
<img src="images/letitbe.png" alt="let it be" class="right" width="215" height="240">
<p>
Podoba zápisu je <span class="fixed">let &lt;definice&gt; in &lt;výraz&gt;</span>. Názvy, které definujete v části s let jsou přístupné výrazu v části za in. Jak můžete vidět, dalo by se to také zapsat pomocí konstrukce where. Všimněte si, že názvy jsou také zarovnány do jednoho sloupce. Takže jaký je rozdíl mezi těmito dvěma zápisy? Zatím to vypadá, že se u let píše definice jako první a používaný výraz až později, zatímco u where to je naopak.
</p>
<p>
Rozdíl je v tom, že konstrukce let je sama o sobě výraz. Kdežto where je pouhý syntaktický konstrukt. Pamatujete si, když jsme se zabývali výrazem if a vysvětlovali jsme si, že if a else můžete nacpat téměř kamkoliv?
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; [if 5 &gt; 3 then "Bla" else "Ble", if 'a' &gt; 'b' then "Něco" else "Nic"]
["Bla", "Nic"]
ghci&gt; 4 * (if 10 &gt; 5 then 10 else 0) + 2
42
</pre>
<p>
Stejnou věc můžete udělat s konstrukcí let.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; 4 * (let a = 9 in a + 1) + 2
42
</pre>
<p>
Mohou být také použity na zavedení funkcí s lokální působností:
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; [let square x = x * x in (square 5, square 3, square 2)]
[(25,9,4)]
</pre>
<p>
Jestliže chceme definovat několik proměnných na jednom řádku, evidentně je nemůžeme zarovnat do sloupců. Proto je můžeme oddělit pomocí středníků.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; (let a = 100; b = 200; c = 300 in a*b*c, let foo="Hej "; bar = "ty!" in foo ++ bar)
(6000000,"Hej ty!")
</pre>
<p>
Nemusíte vkládat středník za poslední definici, ale můžete, pokud chcete. Jak jsme již řekli, v konstrukcích let je možnost použít vzory. Je to velice užitečné pro rychlé rozebrání n-tic na složky a navázání na názvy a tak.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; (let (a,b,c) = (1,2,3) in a+b+c) * 100
600
</pre>
<p>
Můžete také vložit konstrukci let dovnitř generátoru seznamu. Přepišme si náš předchozí příklad na počítání seznamů dvojic vah a výšek a použijme v něm let v generátoru seznamu místo abychom definovali pomocnou funkci přes where.
</p>
<pre name="code" class="haskell:hs">
calcBmis :: (RealFloat a) =&gt; [(a, a)] -&gt; [a]
calcBmis xs = [bmi | (w, h) &lt;- xs, let bmi = w / h ^ 2]
</pre>
<p>
Zařadili jsme let do generátoru seznamu, jako by byl predikát, jenom nefiltruje seznam, ale definuje názvy. Názvy, definované pomocí let v generátoru seznamu jsou viditelné výstupní funkci (část před svislítkem <span class="fixed">|</span>) a všem predikátům a případným částem, které následují po definicích. Takže bychom mohli funkci předělat, aby vracela pouze BMI tlustých lidí:
</p>
<pre name="code" class="haskell:hs">
calcBmis :: (RealFloat a) =&gt; [(a, a)] -&gt; [a]
calcBmis xs = [bmi | (w, h) &lt;- xs, let bmi = w / h ^ 2, bmi &gt;= 25.0]
</pre>
<p>
Nemůžeme použít název <span class="fixed">bmi</span> v části s <span class="fixed">(w, h) &lt;- xs</span>, protože je definovaná před konstrukcí let.
</p>
<p>
Vynechali jsme část s in konstrukce let, když jsme pracovali s generátorem seznamu, protože tam je viditelnost názvů předdefinována. Nicméně jsme mohli použít konstrukci let-in v predikátu a názvy mohli definovat tak, aby byly viditelné pouze predikátu. Část s in může být také vynechána, když definujeme funkce a konstanty přímo v GHCi. Pokud to uděláme, názvy pak budou viditelné po celý čas interaktivní relace.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; let zoot x y z = x * y + z
ghci&gt; zoot 3 9 2
29
ghci&gt; let boot x y z = x * y + z in boot 3 4 2
14
ghci&gt; boot
&lt;interactive&gt;:1:0: Not in scope: `boot'
</pre>
<p>
Když je konstrukce let tak skvělá, proč bychom ji nemohli použít všude namísto where, ptáte se? No, protože je konstrukce let výraz a je poctivě lokální ve své působnosti, nemůže být použita mezi strážemi. Někteří lidé mají raději konstrukci where, protože definice následují za funkcí, ve které se používají. V tomto zápisu je tělo funkce blíže názvu funkce a typové deklaraci, takže to je pro některé čitelnější.
</p>
<a name="podmineny-vyraz-case"></a><h2>Podmíněný výraz case</h2>
<img src="images/case.png" alt="kufr" class="right" width="185" height="164">
<p>
Mnoho imperativních jazyků (C, C++, Java apod.) mají case syntaxi a pokud jste v nějakém z nich programovali, pravděpodobně víte, co to je. Funguje to tak, že se vezme proměnná a potom se provedou bloky kódu pro určenou hodnotu té proměnné a je možnost na konec přidat blok, který zachytí cokoliv, pro případ, že by proměnná nabyla hodnoty, se kterou jsme nepočítali.
</p>
<p>
Haskell bere tento koncept a rozšiřuje ho. Jak název napovídá, výrazy case jsou, no, výrazy, podobně jako výraz if a konstrukce let. Nejenom že umí vyhodnocovat výrazy podle možných případů, ve kterých proměnná nabývá určitých hodnot, můžeme také vyhodnocovat na základě vzorů. Hmmm, vzít proměnnou, ověřit podle vzoru, vyhodnotit kus kódu na podle jeho hodnoty, kde jsme to už slyšeli? No jasně, ověřování vzorů podle parametrů v definici funkce! Takže to je ve skutečnosti pouhý syntaktický cukr pro case výraz. Tyhle dva kusy kódy dělají tu stejnou věc a jsou zaměnitelné:
</p>
<pre name="code" class="haskell:hs">
head' :: [a] -&gt; a
head' [] = error "Prázdný list nemá první prvek!"
head' (x:_) = x
</pre>
<pre name="code" class="haskell:hs">
head' :: [a] -&gt; a
head' xs = case xs of []    -&gt; error "Prázdný list nemá první prvek!"
                      (x:_) -&gt; x
</pre>
<p>
Jak můžete vidět, syntaxe výrazu case je pěkně jednoduchá:
</p>
<pre name="code" class="haskell:hs">
case výraz of vzor -&gt; výsledek
              vzor -&gt; výsledek
              vzor -&gt; výsledek
              ...
</pre>
<p>
Obsah části <span class="fixed">výraz</span> je ověřován vzory. Postup je stejný, jaký bychom čekali: je použit první vzor, který sedí. Jestliže pokračuje přes celé case a není nalezen vhodný vzor, nastane běhová chyba.
</p>
<p>
Zatímco vzory u parametrů funkcí mohou být použity pouze při definování těchto funkcí, case výrazy mohou být použity víceméně všude. Kupříkladu:
</p>
<pre name="code" class="haskell:hs">
describeList :: [a] -&gt; String
describeList xs = "Seznam je " ++ case xs of []  -&gt; "prázdný."
                                             [x] -&gt; "jednoprvkový."
                                             xs  -&gt; "víceprvkový."
</pre>
<p>
To je užitečné pro ověřování něčeho uprostřed zápisu výrazu. Protože je ověřování v definici funkce syntaktický cukr pro výraz case, mohli bychom to rovněž definovat takto:
</p>
<pre name="code" class="haskell:hs">
describeList :: [a] -&gt; String
describeList xs = "Seznam je " ++ what xs
    where what []  = "prázdný."
          what [x] = "jednoprvkový."
          what xs  = "víceprvkový."
</pre>