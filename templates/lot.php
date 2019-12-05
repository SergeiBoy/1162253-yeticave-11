	<nav class="nav">
      <ul class="nav__list container">
	  <?php foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="alllots.php?category_id=<?=$category['id'];?>"><?=$category['category_name'];?></a>
        </li>
	  <?php endforeach; ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?=htmlspecialchars($lot['lot_name']);?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=htmlspecialchars($lot['img_path']);?>" width="730" height="548" alt="<?=htmlspecialchars($lot['lot_name']);?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot['category_name'];?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot['description']);?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer <?php if (get_time_remaining($lot['dt_end'])[0] < 1): ?>timer--finishing<?php endif; ?>">
              <?=implode(':', get_time_remaining($lot['dt_end']));?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=$lot['bid_price'] ?? $lot['initial_price'];?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= $lot['bid_price'] ? ($lot['bid_price'] + $lot['bid_step']) : ($lot['initial_price'] + $lot['bid_step']);?></span>
              </div>
            </div>
			<?php if ($is_bidding_show): ?>
            <form class="lot-item__form" action="lot.php" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" 
					placeholder="<?= $lot['bid_price'] ? ($lot['bid_price'] + $lot['bid_step']) : ($lot['initial_price'] + $lot['bid_step']);?>"
					value="<?= htmlspecialchars($_POST['cost'] ?? '');?>">
                <span class="form__error"><?= $errors['cost'] ?? '';?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
			<?php endif; ?>
          </div>
		  <div class="history">
            <h3>История ставок (<span><?=count($history);?></span>)</h3>
            <table class="history__list">
              <?php foreach ($history as $history_bid): ?>
			  <tr class="history__item">
                <td class="history__name"><?=htmlspecialchars($history_bid['user_name']);?></td>
                <td class="history__price"><?=$history_bid['bid_price'];?> р</td>
                <td class="history__time"><?= get_time_since_adding($history_bid['dt_add']) ?? implode(' в ', explode(' ', $history_bid['dt_add_format']));?></td>
              </tr>
			  <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
    </section>
	