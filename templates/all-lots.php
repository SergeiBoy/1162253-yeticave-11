
	<nav class="nav">
      <ul class="nav__list container">
		<?php foreach ($categories as $category): ?>
        <li class="nav__item <?php if ( isset($category['cur_category']) ): {$category_name = $category['category_name'];} ?>nav__item--current<?php endif; ?>">
          <a href="alllots.php?id=<?=$category['id'];?>"><?=$category['category_name'];?></a>
        </li>
		<?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?=$category_name ?? '';?>»</span></h2>
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
                  <span class="lot__amount"><?= isset($good['bid_price']) ? "{$good['count_bids']} ".
								get_noun_plural_form( intval($good['count_bids']), 'ставка', 'ставки', 'ставок') : 'Стартовая цена'; ?></span>
                  <span class="lot__cost"><?= $good['bid_price'] ?? $good['initial_price']; ?><b class="rub">р</b></span>
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
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
	