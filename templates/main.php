
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?=$category['symbol_code'];?>">
                <a class="promo__link" href="pages/all-lots.html"><?=$category['category_name'];?></a>
            </li>
		<?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
        <?php foreach ($goods as $good): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($good['img_path']);?>" width="350" height="260" alt="<?=htmlspecialchars($good['lot_name']);?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=($good['category_name']);?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$good['id'];?>"><?=htmlspecialchars($good['lot_name']);?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?=htmlspecialchars($good['bid_price']);?></span>
                            <span class="lot__cost"><?=set_price(htmlspecialchars($good['initial_price']));?></span>
                        </div>
                        <div class="lot__timer timer <?php if ( get_time_remaining($good['dt_end'])[0] < 1 ): ?>timer--finishing<?php endif; ?>">
                            <?=implode(':', get_time_remaining($good['dt_end']) );?>
						</div>
                    </div>
                </div>
            </li>
		<?php endforeach; ?>
        </ul>
    </section>

