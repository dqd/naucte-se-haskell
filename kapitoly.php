<h1>Naučte se Haskell!</h1>
    <ol class="chapters">
    <? foreach($contents as $link => $info): ?>
        <li>
            <a href="<?=$link?>"><?=$info['title']?></a>
            <ul>
                <? foreach($info['subchapters'] as $anchor => $title): ?>
                    <li><a href="<?=$link?>#<?=$anchor?>"><?=$title?></a></li>  
                <? endforeach; ?>
            </ul>
        </li>
    <? endforeach; ?>
    </ol>
    <h2>Připravuje se</h2>
    <ul class="chapters" style="list-style-type:none">
        <li><a class="upcoming">Zbytek Řešení úloh funkcionálně</a></li>
        <li><a class="upcoming">Zbytek Funktorů, aplikativní funktorů a monoidů</a></li>
        <li><a class="upcoming">Monády</a></li>
        <li><a class="upcoming">Transformátory monád</a></li>
        <li><a class="upcoming">Zipy</a></li>
        <li><a class="upcoming">Šipky</a></li>
    </ul>
    <p>
    Toto dílo je chráněno licencí <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cz/">Uveďte autora-Neužívejte dílo komerčně-Zachovejte licenci 3.0 Česká republika</a>, protože autor nemohl nalézt licenci s ještě delším názvem.
    </p>
