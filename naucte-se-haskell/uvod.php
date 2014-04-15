<div class="english-version"><a href="http://learnyouahaskell.com/introduction">English version</a></div>
<h1>Úvod</h1>
<a name="o-tomto-tutorialu"></a>
<h2>O tomto tutoriálu</h2>
<p>
Vítejte v příručce <em>Naučte se Haskell</em>! Jestliže čtete tento úvod, je naděje, že se chcete učit Haskell. Pokud ano, jste na správném místě, ale pojďme se trochu pobavit o tomhle textu.
</p>
<p>
Rozhodl jsem se ho napsat, protože jsem chtěl utužit mé znalosti Haskellu a protože jsem si myslel, že bych mohl pomoct nováčkům naučit se Haskell z mé perspektivy. Po internetu koluje celkem málo tutoriálů na Haskell. Když jsem s Haskellem začínal, neučil jsem se pouze z jednoho zdroje. Naučil jsem se ho pročítáním několika návodů a článků, protože každý z nich vysvětloval věci jiným způsobem než ostatní. Pročtením těchto zdrojů jsem byl schopný si propojit a utříbit znalosti. Tohle je tedy pokus o přidání dalšího použitelného zdroje na naučení Haskellu a vy máte větší šanci takový zdroj najít.
</p>
<img src="images/bird.png" alt="pták" class="left" width="230" height="192">
<p>
Návod je zaměřen na programátory, kteří mají zkušenosti s imperativními jazyky (C, C++, Java, Python&hellip;), ale nikdy neprogramovali ve funkcionálních jazycích (Haskell, ML, OCaml&hellip;). Ačkoliv bych se i vsadil, že pokud nemáte významné programátorské zkušenosti, chytrý maník jako vy bude moct sledovat příručku a naučit se Haskell.
</p>
<p>
IRC kanál #haskell (nebo #haskell.cz v češtině) na síti freenode je skvělé místo, kde se můžete ptát, pokud nebudete vědět jak dál. Lidé jsou tam nesmírně milí, trpěliví a mají pochopení pro začátečníky.
</p>
<p>
Haskell se mi nepodařilo naučit asi dvakrát, než jsem tomu konečně porozuměl, protože se mi to celé zdálo příliš divné a nechápal jsem to. Ale jednou mi to „docvaklo“ a jakmile jsem se dostal přes úvodní překážky, byla to celkem hračka. Snažím se říct, že: Haskell je skvělý a pokud se zajímáte o programování, měli byste se ho opravdu naučit, i když na začátku vypadá divně. Učit se Haskell je podobné jako se poprvé učit programovat &mdash; je to zábava! Donutí vás to myslet odlišně, což nás přivádí k další sekci&hellip;
</p>

<a name="takze-co-je-to-haskell"></a><h2>Takže co je to Haskell?</h2>
<p>
<img src="images/fx.png" alt="f(x)" class="right" width="150" height="146">
Haskell je <em>čistě funkcionální programovací jazyk</em>.
V imperativních jazycích se provádí věci zadáváním sekvence úloh počítači, které se pak provádí. Při provádění mohou měnit stavy. Například pokud přiřadíte proměnné <span class="fixed">a</span> číslo 5, děláte něco dalšího, a pak změníte hodnotu proměnné na jinou. Máte k dispozici struktury pro kontrolu toku na několikanásobné provádění určitých akcí. V čistě funkcionálním programování neříkáte počítači, co má dělat, spíše mu říkáte, jaká ta věc <i>je</i>. Faktoriál čísla je součin všech čísel od jedničky po zadané číslo, součet seznamu čísel je první číslo plus součet všech zbylých čísel a tak dále. Tohle se vyjadřuje ve formě funkcí. Není možné také přiřadit proměnné nějakou hodnotu a poté ji změnit na něco jiného později. Pokud řeknete, že <span class="fixed">a</span> je 5, nemůžete později říct, že je něco jiného, protože jste právě prohlásili, že je to pětka. Co jste zač, nějaký lhář? Takže v čistě funkcionálních jazycích nemá funkce vedlejší efekty. Jediná věc, co funkce může dělat, je vypočítat něco a vrátit to jako výsledek. Na první pohled to vypadá jako dost omezující, ale ve skutečnosti to má dost pěkné důsledky: pokud je funkce zavolána dvakrát se stejnými parametry, je zaručeno, že vrátí stejný výsledek. Tomu se říká referenční transparentnost a kromě zjišťování překladače o zvyklostech programátora to také umožňuje jednodušeji vyvozovat (a dokonce dokázat) správnost funkce a poté budovat složitější funkce slepováním jednoduchých funkcí k sobě.
</p>
<p>
<img src="images/lazy.png" class="right" alt="lenost" width="240" height="209">
Haskell je <em>líný</em>. To znamená, že pokud mu nenařídíte opak, Haskell nevyhodnotí funkci a nepočítá věci, než po něm nezačnete chtít výsledek. To funguje dobře s referenční transparentností a umožňuje to považovat programy jako řadu <em>trasformací na datech</em>. Také to dovoluje práci s bezva věcmi jako jsou nekonečné datové struktury. Řekněme, že máme neměnitelný seznam čísel <span class="fixed">xs = [1,2,3,4,5,6,7,8]</span> a funkci <span class="fixed">doubleMe</span>, která vynásobí každý prvek seznamu dvojkou a poté vrátí nový seznam. Pokud chceme vynásobit náš seznam osmičkou v imperativním jazyce a udělat <span class="fixed">doubleMe(doubleMe(doubleMe(xs)))</span>, pravděpodobně by se předal seznam jedenkrát a vytvořila se kopie, která by se vrátila. Poté by se předal seznam funkci ještě dvakrát a vrátil by se výsledek. V líném jazyce se zavoláním funkce <span class="fixed">doubleMe</span> na seznamu bez požadování výsledku skončí program zhruba tím, že si řekne: „Jo, jo, udělám to později!“ Ale pokud chcete vidět výsledek, první <span class="fixed">doubleMe</span> řekne tomu druhému, že chce vidět výsledek, hned! Druhé řekne třetímu a třetí neochotně vrátí dvojnásobek 1, což je 2. Druhý obdrží hodnotu a vrací 4 prvnímu. První to uvidí, a zahlásí, že první prvek je 8. Takže udělá pouze jeden průchod seznamem a jenom tehdy když je to opravdu potřeba. Jedna z možností, jak chtít něco od líného jazyka, je vzít nějaká počáteční data a efektivně je transformovat a vylepšovat tak, aby se podobala našemu chtěnému výsledku.
</p>
<p>
<img src="images/boat.png" class="right" alt="loďka" width="160" height="153">
Haskell je <em>staticky typovaný</em>. Jakmile překládáte svůj program, překladač ví, jaký kousek kódu je číslo, jaký je řetězec a tak dále. To znamená, že hodně potenciálních chyb je odchytáno v čase překladu. Pokud se budete snažit sečíst dohromady číslo a řetězec, překladač si vám bude stěžovat. Haskell má velmi dobrý typový systém, který používá <em>typové odvozování</em>. To znamená, že nemusíte explicitně otypovávat každý kus kódu, protože typový systém může inteligentně přijít na hodně věcí. Pokud prohlásíte <span class="fixed">a = 5 + 4</span>, nemusíte Haskellu říkat, že <span class="fixed">a</span> je číslo, může na to přijít sám. Odvozování typů také dovoluje mít kód více obecný. Pokud funkce požaduje dva parametry a sečte je dohromady, není potřeba explicitně uvádět jejich typ, funkce s nimi bude pracovat jako se dvěma parametry, které se chovají jako čísla.
</p>
<p>
Haskell je <em>elegantní a výstižný</em>. Protože používá hodně vysokoúrovňových konceptů, programy napsané v Haskellu jsou obvykle kratší než jejich imperativní ekvivalenty. A kratší programy se jednodušeji spravují než dlouhé a obsahují méně chyb.
</p>
<p>
Haskell vytvořilo několik <em>opravdu chytrých chlápků</em> (s doktorskými tituly). Práce na Haskellu začala v roce 1987, kdy se vytvořil výbor výzkumníků, aby navrhli převratný jazyk. V roce 2003 byl publikován Haskell Report, který definuje ustálenou verzi jazyka.
</p>
<a name="co-bude-potreba"></a><h2>Co bude potřeba</h2>
<p>
Textový editor a překladač Haskellu. Pravděpodobně už máte svůj oblíbený textový editor nainstalován, takže tím nebudeme ztrácet čas. Dva hlavní překladače Haskellu jsou v současnosti GHC (Glasgow Haskell Compiler) a Hugs. Pro účely tohoto tutoriálu budeme používat GHC. Nebudu se zabývat detaily instalace. Na Windows je to otázka stáhnutí instalátoru, několika kliknutí na tlačítko „Další“ a poté restartu počítače. Na linuxových distribucích postavených na Debianu stačí pouze udělat <span class="fixed">apt-get install ghc6 libghc6-mtl-dev</span> a jste vysmátí. Nevlastním Mac, ale zaslechl jsem, že pokud máte <a href="http://www.macports.org/">MacPorty</a>, můžete získat GHC provedením příkazu <span class="fixed">sudo port install ghc</span>. Také si myslím, že se dá v Haskellu dělat vývoj pomocí té potrhlé jednotlačítkové myši, ačkoliv si nejsem jistý.
</p>
<p>
GHC umí vzít skript napsaný v Haskellu (běžně mívají příponu .hs) a přeložit jej, ale má také interaktivní mód, který umožňuje interaktivně interagovat se skripty. Interaktivně. Můžete zavolat funkci z načteného skriptu a výsledky jsou zobrazeny ihned. Pro učení je to mnohem jednodušší a rychlejší než překládat program pokaždé, když se v něm provede změna, a spouštět ho z příkazového řádku. Interaktivní mód je vyvolán napsáním <span class="fixed">ghci</span> do příkazového řádku. Pokud máte definovány nějaké funkce v souboru, nazvaného řekněme <span class="fixed">mojefunkce.hs</span>, načtete ho napsáním <span class="fixed">:l mojefunkce</span> a poté si s nimi můžete hrát, pokud je soubor <span class="fixed">mojefunkce.hs</span> ve stejném adresáři, jako bylo <span class="fixed">ghci</span> spuštěno. Pokud ve skriptu něco změníte, stačí znovu napsat <span class="fixed">:l mojefunkce</span> nebo příkaz <span class="fixed">:r</span>, který je stejný, protože znovu načte současný .hs skript. Mé obvyklé pracovní prostředí, když si pohrávám s programy, je .hs soubor, ve kterém mám definovány některé funkce, jež načtu, vrtám se v něm, načtu ho znovu a tak dále. To je také, čím se zde budeme zabývat.
</p>
