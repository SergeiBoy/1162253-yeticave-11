	<nav class="nav">
      <ul class="nav__list container">
	  <? foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="all-lots.html"><?=$category['category_name'];?></a>
        </li>
	  <? endforeach; ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?=$lot['lot_name'];?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$lot['img_path'];?>" width="730" height="548" alt="<?=$lot['lot_name'];?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot['category_name'];?></span></p>
          <p class="lot-item__description"><?=$lot['description'];?></p>
        </div>
        <div class="lot-item__right">
          <?php if (isset($_SESSION['user'])): ?>
		  <div class="lot-item__state">
            <div class="lot-item__timer timer <?php if ( get_time_remaining($lot['dt_end'])[0] < 1 ): ?>timer--finishing<? endif; ?>">
              <?=implode(':', get_time_remaining($lot['dt_end']) );?>
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
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?= $lot['bid_price'] ? ($lot['bid_price'] + $lot['bid_step']) : ($lot['initial_price'] + $lot['bid_step']);?>">
                <span class="form__error">Введите наименование лота</span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
		  <?php endif; ?>
          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
              <tr class="history__item">
                <td class="history__name">Иван</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">5 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Константин</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">20 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Евгений</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">Час назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Игорь</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 08:21</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>