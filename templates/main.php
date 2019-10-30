
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
		<? foreach ($categories as $category): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?=htmlspecialchars($category);?></a>
            </li>
		<? endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
		<? foreach ($goods as $good): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$good['img_path'];?>" width="350" height="260" alt="<?=htmlspecialchars($good['name']);?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($good['category']);?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($good['name']);?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?=htmlspecialchars($good['price']);?></span>
                            <span class="lot__cost"><?=set_price(htmlspecialchars($good['price']));?></span>
                        </div>
                        <div class="lot__timer timer <?php if ( get_time_remaining($good['deadline_date'])[0] < 1 ): ?>timer--finishing<? endif; ?>">
                            <?=implode(':', get_time_remaining($good['deadline_date']) );?>
						</div>
                    </div>
                </div>
            </li>
		<? endforeach; ?>
        </ul>
    </section>
