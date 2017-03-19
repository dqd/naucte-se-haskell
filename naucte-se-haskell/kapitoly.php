<h1>Naučte se Haskell!</h1>
    <ol class="chapters">
    <?php foreach($contents as $link => $info): ?>
        <li>
            <a href="<?=$link?>"><?=$info['title']?></a>
            <ul>
                <?php foreach($info['subchapters'] as $anchor => $title): ?>
                    <li><a href="<?=$link?>#<?=$anchor?>"><?=$title?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
    </ol>
    <p>
    Toto dílo je chráněno licencí <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cz/">Uveďte autora-Neužívejte dílo komerčně-Zachovejte licenci 3.0 Česká republika</a>, protože autor nemohl nalézt licenci s ještě delším názvem.
    </p>
