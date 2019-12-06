	<nav class="nav">
      <ul class="nav__list container">
		<?php foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="alllots.php?category_id=<?=$category['id'];?>"><?=$category['category_name'];?></a>
        </li>
		<?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search ?? '');?></span>»</h2>
			<?php if (!$goods): ?>
				<p>Ничего не найдено по вашему запросу</p>
			<?php endif; ?>
        <ul class="lots__list">
          <?php foreach ($goods as $good): ?>
		  <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=htmlspecialchars($good['img_path']);?>" width="350" height="260" alt="<?=htmlspecialchars($good['lot_name']);?>">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=$good['category_name'];?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$good['id'];?>"><?=htmlspecialchars($good['lot_name']);?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount"><?=$good['bid_price'];?></span>
                  <span class="lot__cost"><?=format_price($good['initial_price']);?></span>
                </div>
                <div class="lot__timer timer <?php if (get_time_remaining($good['dt_end'])[0] < 1): ?>timer--finishing<?php endif; ?>">
                  <?=implode(':', get_time_remaining($good['dt_end']));?>
                </div>
              </div>
            </div>
          </li>
		  <?php endforeach; ?>
        </ul>
      </section>
	  <?php if ($pages_quantity > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
			<a <?php if ($current_page_number > 1): ?>href="search.php?search=<?= htmlspecialchars($search);?>&page=<?=($current_page_number - 1);?>"<?php endif; ?>>Назад</a>
		</li>
		<?php for ($i = 1; $i <= $pages_quantity; $i++): ?>
        <li class="pagination-item <?php if ($current_page_number === $i): ?>pagination-item-active<?php endif; ?>">
			<a <?php if ($current_page_number !== $i): ?>href="search.php?search=<?= htmlspecialchars($search);?>&page=<?=$i;?>"<?php endif; ?>><?=$i;?></a>
		</li>
		<?php endfor; ?>
        <li class="pagination-item pagination-item-next">
			<a <?php if ($current_page_number < $pages_quantity): ?>href="search.php?search=<?= htmlspecialchars($search);?>&page=<?=($current_page_number + 1);?>"<?php endif; ?>>Вперед</a>
		</li>
      </ul>
	  <?php endif; ?>
    </div>
	