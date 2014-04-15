<div class="english-version"><a href="http://learnyouahaskell.com/recursion">English version</a></div>
<h1 style="margin-left:-2px">Rekurze</h1>
<a name="ahoj-rekurze"></a><h2>Ahoj, rekurze!</h2>
<img src="images/recursion.png" alt="SOVĚTSKÉ RUSKO" class="left" width="250" height="179">
<p>
Rekurzi jsme stručně zmínili v předchozí kapitole. V této kapitole se na ni podíváme zblízka, proč je v Haskellu tak důležitá a jak můžeme najít velice výstižná a elegantní řešení problémů díky rekurzivnímu myšlení.
</p>
<p>
Pokud stále ještě nevíte, co to rekurze je, přečtěte si tuhle větu. Ha, ha! Dělám si legraci! Rekurze je ve skutečnosti způsob definování funkce, ve kterém je použita tatáž funkce ve své vlastní definici. Definice v matematice jsou často zadány rekurzivně. Například fibonacciho posloupnost je definována rekurzivně. Nejprve si definujeme první dvě fibonacciho čísla nerekurzivně. Řekneme, že <i>F(0) = 0</i> a <i>F(1) = 1</i>, což znamená, že nulté a první fibonacciho číslo je nula a jednička (v tomto pořadí). Poté prohlásíme, že pro ostatní přirozená čísla je fibonacciho číslo součet dvou předchozích fibonacciho čísel. Takže <i>F(n) = F(n-1) + F(n-2)</i>. Tím pádem <i>F(3)</i> je <i>F(2) + F(1)</i>, což je <i>(F(1) + F(0)) + F(1)</i>. Protože jsme se dostali k nerekurzivně definovaným fibonacciho číslům, můžeme bezpečně říct, že <i>F(3)</i> se rovná 2. Nerekurzivně definované části rekurzivních definicí (stejně jako bylo tady <i>F(0)</i> a <i>F(1)</i>) se také říká <em>okrajová podmínka</em> a je dost důležitá, jestliže chceme, aby naše rekurzivní funkce někdy skončila. Kdybychom neměli definované <i>F(0)</i> a <i>F(1)</i> nerekurzivně, nikdy bychom nedostali řešení pro jakékoliv číslo, protože bychom po dosažení nuly šli do záporných čísel. Z ničeho nic bychom tvrdili, že <i>F(-2000)</i> je <i>F(-2001) + F(-2002)</i> a konec by byl stále v nedohlednu!
</p>
<p>
Rekurze je v Haskellu důležitá, protože na rozdíl od imperativních jazyků provádíme výpočty deklarováním něčeho, jaké to <i>je</i>, místo deklarování <i>jak</i> to dostat. To je důvod, proč v Haskellu nejsou žádné while nebo for smyčky a místo toho jsme museli mnohokrát použít rekurzi pro deklaraci něčeho, jaké to je.
</p>
<a name="maximalni-skvelost"></a><h2>Maximální skvělost</h2>
<p>
Funkce <span class="fixed">maximum</span> vezme seznam věcí, které se dají uspořádat (např. instance typové třídy <span class="fixed">Ord</span>) a vrátí největší z nich. Přemýšlejte, jak byste to napsali imperativním stylem. Pravděpodobně byste si vytvořili proměnnou, aby uchovávala aktuální maximální hodnotu a poté byste postupně procházeli seznam a pokud by byl prvek v seznamu větší než současná nejvyšší hodnota, nahradili byste to tím prvkem. Maximální hodnota, která by zbyla na konci, by byla výsledek. Páni! To jsme použili celkem dost slov na popsání tak jednoduchého algoritmu!
</p>
<p title="Ano, tohle je ještě delší popis za použití více slov. Autor tohoto textu doufá, že si toho nikdo nevšimne.">
A teď se podíváme, jak bychom to definovali rekurzivně. Mohli bychom nejprve vytvořit okrajovou podmínku a prohlásit, že maximum z jednoprvkového seznamu se rovná tomu prvku z něj. Pak můžeme říct, že maximum z delšího seznamu je první prvek, pokud je ten prvek větší než maximum ze zbytku. Jestliže je maximum ze zbytku větší, tak je výsledek maximum ze zbytku. Takhle to je! A teď si to napíšeme v Haskellu.
</p>
<pre name="code" class="haskell:hs">
maximum' :: (Ord a) =&gt; [a] -&gt; a
maximum' [] = error "maximum z prázdného seznamu"
maximum' [x] = x
maximum' (x:xs)
    | x &gt; maxTail = x
    | otherwise = maxTail
    where maxTail = maximum' xs
</pre>
<p>
Jak můžete vidět, vzory se dobře snášejí s rekurzí! Většina imperativních jazyků neobsahuje vzory, takže musíte vytvořit hromadu if a else na otestování okrajových podmínek. Zde je jednoduše zahrneme do vzorů. Tedy první okrajová podmínka říká, že jestliže je seznam prázdný, spadni! To dává smysl, protože co je maximum z prázdného seznamu? Nevím. Druhý vzor také zastává okrajovou podmínku. Říká, že jestliže to je jednoprvkový seznam, prostě vrať ten jediný prvek.
</p>
<p>
A třetí vzor je ten, ve kterém se všechno děje. Použili jsme vzor na rozdělení seznamu na první prvek a zbytek. Tohle je běžný postup při provádění rekurze na seznamech, na který je potřeba si zvyknout. Použijeme konstrukci <i>where</i> pro definování maxima zbytku seznamu <span class="fixed">maxTail</span>. Poté zkontrolujeme, jestli je první prvek větší než maximum ze zbytku seznamu. Jestliže je, vrátíme ho. V opačném případě vrátíme maximum ze zbytku seznamu.
</p>
<p>
Zkusíme vzít na ukázku nějaký seznam čísel, třeba <span class="fixed">[2,5,1]</span>, a zjistit, jak by to s ním mohlo fungovat. Jestliže na něj zavoláme funkci <span class="fixed">maximum'</span>, první dva vzory nebudou sedět. Třetí ovšem ano a rozdělí seznam na  <span class="fixed">2</span> a <span class="fixed">[5,1]</span>. Klauzule <i>where</i> chce znát maximum z <span class="fixed">[5,1]</span>, takže následujme tuhle cestu. Znovu se to zastaví u třetího vzoru a <span class="fixed">[5,1]</span> se rozdělí na <span class="fixed">5</span> a <span class="fixed">[1]</span>. Klauzule <i>where</i> chce znovu znát nejvyšší číslo ze seznamu <span class="fixed">[1]</span>. Protože to je okrajová podmínka, vrátí <span class="fixed">1</span>. Konečně! Takže když postoupíme o krok dál porovnáním čísla <span class="fixed">5</span> s  maximem z <span class="fixed">[1]</span> (což je <span class="fixed">1</span>), dostaneme zpátky <span class="fixed">5</span>. Víme tedy, že maximum z <span class="fixed">[5,1]</span> je <span class="fixed">5</span>. Postoupíme zase o krok dál, kde máme <span class="fixed">2</span> a <span class="fixed">[5,1]</span>. Porovnáním čísla <span class="fixed">2</span> s maximem z <span class="fixed">[5,1]</span>, což je <span class="fixed">5</span>, se rozhodneme pro číslo <span class="fixed">5</span>.
</p>
<p>
Čistější způsob, jak napsat tuhle funkci, je použít <span class="fixed">max</span>. Pokud si pamatujete, <span class="fixed">max</span> je funkce, která vezme dvě čísla a vrátí větší z nich. Tady je ukázka, jak bychom mohli přepsat <span class="fixed">maximum'</span> za použití funkce <span class="fixed">max</span>:
</p>
<pre name="code" class="haskell:hs">
maximum' :: (Ord a) =&gt; [a] -&gt; a
maximum' [] = error "maximum z prázdného seznamu"
maximum' [x] = x
maximum' (x:xs) = max x (maximum' xs)
</pre>
<p>
To je ale elegantní! Maximum ze seznamu je v podstatě <span class="fixed">max</span> z prvního prvku a maxima ze zbytku.
</p>
<img src="images/maxs.png" alt="max" class="center" width="651" height="267">
<a name="nekolik-dalsich-rekurzivnich-funkci"></a><h2>Několik dalších rekurzivních funkcí</h2>
<p>
A teď, když víme, jak se zhruba dá myslet rekurzivně, napišme si pár funkcí s použitím rekurze. Nejprve si implementujeme funkci <span class="fixed">replicate</span>. Tato funkce vezme parametr typu <span class="fixed">Int</span> a nějaký prvek a vrátí seznam, který má několik opakování toho stejného prvku. Kupříkladu <span class="fixed">replicate 3 5</span> vrátí <span class="fixed">[5,5,5]</span>. Zamysleme se nad okrajovou podmínkou. Můj tip je, že okrajová podmínka je 0 nebo méně. Jestliže zkusíme opakovat něco nulakrát, měl by se vrátit prázdný seznam. Stejně pro záporná čísla, protože to nedává moc smysl.
</p>
<pre name="code" class="haskell:hs">
replicate' :: (Num i, Ord i) =&gt; i -&gt; a -&gt; [a]
replicate' n x
    | n &lt;= 0    = []
    | otherwise = x:replicate' (n-1) x
</pre>
<p>
Použili jsme stráže místo vzorů, protože testujeme booleovskou podmínku. Jestliže je <span class="fixed">n</span> menší nebo rovno 0, vrátí se prázdný seznam. Jinak vrátí seznam, který má <span class="fixed">x</span> jako první prvek a poté <span class="fixed">x</span> opakované n mínus jedenkrát jako zbytek. Část s <span class="fixed">(n-1)</span> způsobí, že naše funkce nakonec dosáhne okrajové podmínky.
</p>
<div class="hintbox"><em>Poznámka:</em> třída <span class="fixed">Num</span> není podtřídou <span class="fixed">Ord</span>. Což znamená, že mohou existovat čísla, která nedodržují uspořádání. To je důvod, proč jsme museli specifikovat omezení třídami <span class="fixed">Num</span> a <span class="fixed">Ord</span> pro sčítání nebo odčítání a taktéž pro porovnávání.</div>
<p>
Dále si vytvoříme funkci <span class="fixed">take</span>. Ta vezme určitý počet prvků ze seznamu. Například výraz <span class="fixed">take 3 [5,4,3,2,1]</span> vrátí seznam <span class="fixed">[5,4,3]</span>. Jestliže zkusíme vzít 0 nebo méně prvků ze seznamu, dostaneme prázdný seznam. Stejně pokud zkusíme vybrat něco z prázdného seznamu, dostaneme prázdný seznam. Všimněte si, že tohle jsou dvě okrajové podmínky. Takže to zkusíme zapsat:
</p>
<pre name="code" class="haskell:hs">
take' :: (Num i, Ord i) =&gt; i -&gt; [a] -&gt; [a]
take' n _
    | n &lt;= 0   = []
take' _ []     = []
take' n (x:xs) = x : take' (n-1) xs
</pre>
<img src="images/painter.png" alt="malíř" class="right" width="350" height="276">
<p>
První vzor uvádí, že pokud zkusíme vzít 0 nebo záporný počet prvků, dostaneme prázdný seznam. Všimněte si, že jsme použili <span class="fixed">_</span> na ověření seznamu, protože nás v tomhle případě ten seznam nezajímá. Také si všimněte použití stráže bez části <span class="fixed">otherwise</span>. Jestliže se tím pádem ukáže, že <span class="fixed">n</span> je větší než 0, ověření selže a přejde se na další vzor. Druhý vzor naznačuje, že pokud chceme vzít cokoliv z prázdného sezamu, dostaneme prázdný seznam. Třetí vzor rozdělí seznam na první prvek a zbytek. A poté uvedeme, že když vezmeme <span class="fixed">n</span> prvků ze seznamu, je to stejné jako sezam, který obsahuje první prvek  <span class="fixed">x</span> a poté seznam <span class="fixed">n-1</span> prvků ze zbytku. Zkuste si vzít kus papíru, abyste si zapsali, jak by mohlo vypadat vyhodnocování, řekněme, tří prvků ze seznamu <span class="fixed">[4,3,2,1]</span>.
</p>
<p>
Funkce <span class="fixed">reverse</span> jednoduše převrátí seznam. Zamyslete se nad okrajovou podmínkou. Jaká je? No tak&hellip; je to prázdný seznam! Převrácený prázdný seznam je stejný jako prázdný seznam. Tak jo. A co s ostatními případy? No, můžeme říct, že když rozdělíme seznam na jeho první prvek a zbytek, převrácený seznam je stejný jako převrácený zbytek a k němu na konec přidaný první prvek.
</p>
<pre name="code" class="haskell:hs">
reverse' :: [a] -&gt; [a]
reverse' [] = []
reverse' (x:xs) = reverse' xs ++ [x]
</pre>
<p>
A je to!
</p>
<p>
Protože Haskell podporuje nekonečné seznamy, naše rekurze nemusí mít okrajovou podmínku. Pokud by ji ale neobsahovala, tak by skončila v nekonečné smyčce nebo vyprodukovala nekonečnou datovou strukturu, jako třeba nekonečný seznam. Na nekonečných seznamech je dobré, že je můžeme useknout na jakémkoliv místě. Funkce <span class="fixed">repeat</span> vezme prvek a vrátí nekonečný seznam, jež obsahuje jenom ten prvek. Dá se to napsat opravdu jednoduše, sledujte.
</p>
<pre name="code" class="haskell:hs">
repeat' :: a -&gt; [a]
repeat' x = x:repeat' x
</pre>
<p>
Zavolání <span class="fixed">repeat 3</span> nám vytvoří seznam začínající číslem <span class="fixed">3</span>, za kterým následuje nekonečné množství trojek. Takže by se zavolání <span class="fixed">repeat 3</span> mohlo vyhodnotit jako <span class="fixed">3:repeat 3</span>, což je <span class="fixed">3:(3:repeat 3)</span>, poté <span class="fixed">3:(3:(3:repeat 3))</span> a tak dále. Výraz <span class="fixed">repeat 3</span> nikdy neskončí vyhodnocování, zatímco <span class="fixed">take 5 (repeat 3)</span> nám vytvoří seznam pěti trojek. To je v podstatě stejné jako <span class="fixed">replicate 5 3</span>.
</p>
<p>
Funkce <span class="fixed">zip</span> vezme dva seznamy a sepne je dohromady. Vyhodnocení <span class="fixed">zip [1,2,3] [2,3]</span> vrátí <span class="fixed">[(1,2),(2,3)]</span>, protože ořízne delší seznam, aby měl stejnou délku jako ten kratší. Co když sepneme něco s prázdným seznamem? No tak to pak dostaneme prázdný seznam. To je naše okrajová podmínka. Nicméně <span class="fixed">zip</span> požaduje jako parametr dva seznamy, ve skutečnosti jsou tedy dvě okrajové podmínky.
</p>
<pre name="code" class="haskell:hs">
zip' :: [a] -&gt; [b] -&gt; [(a,b)]
zip' _ [] = []
zip' [] _ = []
zip' (x:xs) (y:ys) = (x,y):zip' xs ys
</pre>
<p>
První dva vzory zadávají, že pokud je první nebo druhý seznam prázdný, dostaneme prázdný seznam. Třetí udává, že se dva sepnuté seznamy rovnají spárování jejich prvních prvků a poté připojení jejich sepnutých zbytků. Při sepnutí seznamů <span class="fixed">[1,2,3]</span> a <span class="fixed">['a','b']</span> se pokusí sepnout <span class="fixed">[3]</span> s <span class="fixed">[]</span>. Uplatní se vzor s hraniční podmínkou a takže vznikne výsledek <span class="fixed">(1,'a'):(2,'b'):[]</span>, což je úplně to stejné jako <span class="fixed">[(1,'a'),(2,'b')]</span>.
</p>
<p>
Napíšeme si ještě jednu další standardní funkci &mdash; <span class="fixed">elem</span>. Ta vezme prvek a seznam a podívá se, jestli se ten prvek vyskytuje v seznamu. Okrajová podmínka, jak to tak u seznamů většinou bývá, je prázdný seznam. Víme, že prázdný seznam neobsahuje žádné prvky, takže zcela určitě nebude obsahovat <span title="Ano, droidy. Znáte Hvězdné války?">droidy</span>, které hledáme.
</p>
<pre name="code" class="haskell:hs">
elem' :: (Eq a) =&gt; a -&gt; [a] -&gt; Bool
elem' a [] = False
elem' a (x:xs)
    | a == x    = True
    | otherwise = a `elem'` xs
</pre>
<p>
Poměrně jednoduché a očekávatelné. Jestliže není první prvek stejný jako hledaný, zkontrolujeme zbytek. Když narazíme na prázdný seznam, výsledek je <span class="fixed">False</span>.
</p>
<a name="rychle-rad"></a><h2>Rychle, řaď!</h2>
<p>
Máme seznam věcí, které mohou být seřazeny. Jejich typ je instancí typové třídy <span class="fixed">Ord</span>. A teď bychom je chtěli seřadit! Existuje bezvadný řadící algoritmus, nazvaný quicksort. Je to velice chytrý způsob pro řazení věcí. Zatímco je potřeba více než 10 řádků pro napsání quicksortu v imperativních jazycích, implementace v Haskellu je mnohem kratší a elegantnější. Quicksort se stal haskellovou vábničkou. Tudíž si ho tady napíšeme, přestože je psaní quicksortu v Haskellu považováno za celkem podřadné, protože to všichni používají jako ukázku elegance Haskellu.
</p>
<img src="images/quickman.png" alt="rychlonožka" class="left" width="180" height="235">
<p>
Takže typové omezení této funkce bude <span class="fixed">quicksort :: (Ord a) =&gt; [a] -&gt; [a]</span>. Žádné překvapení. Okrajová podmínka? Prázdný seznam, což jsme čekali. Seřazený prázdný seznam je prázdný seznam. A teď přichází základní algoritmus: <em>seřazený seznam je seznam, který má všechny hodnoty, jež jsou menší (nebo rovny) prvnímu prvku seznamu, na svém začátku (a tyto hodnoty jsou seřazeny), poté obsahuje první prvek a dále následují všechny hodnoty, které jsou větší než první prvek (jsou také seřazeny).</em> Všimněte si, že jsme v této definici dvakrát zmínili slovo <i>seřazeny</i>, takže pravděpodobně budeme muset udělat rekurzivní volání dvakrát! Také si povšiměte, že jsme to definovali použitím slovesa <i>je</i> pro definici algoritmu, místo abychom řekli <i>udělej tohle, udělej tamto, pak udělej to&hellip;</i> V tom spočívá krása funkcionálního programování! Jak zařídíme filtrování seznamu, abychom dostali pouze menší nebo větší prvky než je první prvek z našeho seznamu? Pomocí generátoru seznamu. Takže se do toho pustíme a definujeme si tu funkci.
</p>
<pre name="code" class="haskell:hs">
quicksort :: (Ord a) =&gt; [a] -&gt; [a]
quicksort [] = []
quicksort (x:xs) =
    let smallerSorted = quicksort [a | a &lt;- xs, a &lt;= x]
        biggerSorted = quicksort [a | a &lt;- xs, a &gt; x]
    in  smallerSorted ++ [x] ++ biggerSorted
</pre>
<p>
Zkusíme si malý testovací provoz, abychom viděli, zdali to funguje bez chyb.
</p>
<pre name="code" class="haskell:ghci">
ghci&gt; quicksort [10,2,5,3,1,6,7,4,2,3,4,8,9]
[1,2,2,3,3,4,4,5,6,7,8,9,10]
ghci&gt; quicksort "prilis zlutoucky kun upel dabelske ody"
"     abcddeeeiikkkllllnoopprsstuuuuyyz"
</pre>
<p>
Hurá! Přesně o tomhle jsem mluvil! Takže když máme, řekněme, <span class="fixed">[5,1,9,4,6,7,3]</span> a chceme tento seznam seřadit, algoritmus vezme první prvek, což je <span class="fixed">5</span> a poté ho vloží doprostřed dvou seznamů, které jsou menší a větší než ten prvek. Takže v jednom okamžiku máme <span class="fixed">[1,4,3] ++ [5] ++ [9,6,7]</span>. Víme, že až bude ten seznam celý seřazen, číslo <span class="fixed">5</span> zůstane na čtvrtém místě, protože jsou v seznamu tři čísla menší a tři větší. A teď, jakmile seřadíme <span class="fixed">[1,4,3]</span> a <span class="fixed">[9,6,7]</span>, dostaneme seřazený seznam! Seřadíme ty dva seznamy použitím stejné funkce. Nakonec se to celé rozpadne tak, že dospějeme k prázdným seznamům a prázdné seznamy jsou už svým způsobem seřazené, vzhledem k tomu, že jsou prázdné. Tady je znázornění:
</p>
<img src="images/quicksort.png" alt="quicksort" class="center" width="799" height="332">
<p>
Prvek, který je na svém místě a nebudeme ho posouvat, je znázorněn <span style="color:#FF6600; font-weight:bold;">oranžově</span>. Jestliže je přečtete z leva do prava, uvidíte seřazený seznam. Ačkoliv jsme si vybrali na porovnávání první prvek, mohli jsme si vybrat jakýkoliv jiný. V quicksortu se prvek, který se používá na porovnávání, nazývá pivot. Je vyznačený <span style="color:#009900; font-weight:bold">zeleně</span>. Vybrali jsme si první prvek, protože se jednoduše dá získat pomocí vzoru. Menší prvky než je pivot jsou zvýrazněny <span style="color:#0f0; font-weight:bold">světle zeleně</span> a větší prvky než je pivot jsou <span style="color:#030; font-weight:bold">tmavě zelené</span>. Nažloutlý přechod symbolizuje aplikaci quicksortu.
</p>
<a name="myslime-rekurzivne"></a><h2>Myslíme rekurzivně</h2>
<p>
Udělali jsme toho dosud pomocí rekurze celkem dost a jak jste si zřejmě všimli, je v tom určité schéma. Obvykle si definujeme okrajový případ a poté funkci, která dělá něco s nějakým prvkem a funkcí aplikovanou na zbytek. Nezáleží na tom, jestli to je seznam, strom nebo nějaká jiná datová struktura. Součet je první prvek seznamu plus součet zbytku. Násobek seznamu je první prvek seznamu krát násobek zbytku. Délka seznamu je jednička plus délka zbytku seznamu. A tak dále, a tak dále&hellip;
<p>
<img src="images/brain.png" alt="mozek" class="left" width="250" height="219">
<p>
Samozřejmě, tyhle funkce mají rovněž okrajové případy. Okrajový případ je obvykle nějaká možnost, u které aplikace rekurze nedává smysl. Když se pracujeme se seznamy, okrajovým případem často bývá prázdný seznam. Jestliže pracujeme se stromy, okrajovým případem obvykle je uzel, jež nemá žádné potomky.
</p>
<p>
Je to podobné, když rekurzivně pracujete s čísly. Obvykle to má co dělat s nějakým číslem a funkcí aplikovanou na to číslo s úpravou. Ukázali jsme si předtím funkci pro výpočet faktoriálu a to je násobek čísla a faktoriál toho čísla, zmenšeného o jedničku. Tahle aplikace rekurze nedává smysl pro nulu, protože faktoriál je definován pouze pro kladná celá čísla. Často se ukáže, že okrajový případ je identita. Identita pro násobení je jednička, protože jestliže vynásobíte něco jedničkou, dostanete to nazpět. Stejně tak když sčítáme seznamy, definujeme přičtení prázdného seznamu jako nulu, protože nula je identita ve sčítání. U quicksortu je okrajový případ prázdný seznam a identita je také prázdný seznam, protože jestliže připojíme prázdný seznam k seznamu, získame ten stejný seznam.
</p>
<p>
Takže když se snažíte myslet rekurzivním způsobem při řešení úloh, zkuste přemýšlet nad případy, na které se rekurze nedá aplikovat a podívat se, jestli je můžete použít jako okrajový případ, přemýšlejte o identitách a přemýšlejte o případném rokladu parametrů funkce (například seznamy jsou obvykle rozkládány na první prvek a zbytek pomocí vzorů) a na jakou část použijete rekurzivní volání.
</p>
