	<nav class="nav">
      <ul class="nav__list container">
		<? foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="all-lots.html"><?=$category['category_name'];?></a>
        </li>
		<? endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span>Union</span>»</h2>
			<?php if (!$goods): ?>
				<p>Ничего не найдено по вашему запросу</p>
			<?php endif; ?>
        <ul class="lots__list">
          <? foreach ($goods as $good): ?>
		  <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=$good['img_path'];?>" width="350" height="260" alt="<?=htmlspecialchars($good['lot_name']);?>">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=htmlspecialchars($good['category_name']);?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$good['id'];?>"><?=htmlspecialchars($good['lot_name']);?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount"><?=htmlspecialchars($good['bid_price']);?></span>
                  <span class="lot__cost"><?=set_price(htmlspecialchars($good['initial_price']));?></span>
                </div>
                <div class="lot__timer timer <?php if ( get_time_remaining($good['dt_end'])[0] < 1 ): ?>timer--finishing<? endif; ?>">
                  <?=implode(':', get_time_remaining($good['dt_end']) );?>
                </div>
              </div>
            </div>
          </li>
		  <? endforeach; ?>
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
	