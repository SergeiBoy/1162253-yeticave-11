<nav class="nav">
      <ul class="nav__list container">
	  <?php foreach ($categories as $category): ?>
		<li class="nav__item">
		  <a href="alllots.php?id=<?=$category['id'];?>"><?=$category['category_name'];?></a>
		</li>
	  <?php endforeach; ?>
	  </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
		<?php foreach ($goods as $good): ?>
		<tr class="rates__item  <?php if ( $good['is_win'] ): ?>
								rates__item--win
								<?php elseif ( $good['is_ended'] ) : ?>
								rates__item--end
								<?php else: ?>
								''
								<?php endif; ?>     ">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=htmlspecialchars($good['img_path']);?>" width="54" height="40" alt="<?=htmlspecialchars($good['lot_name']);?>">
            </div>
			<div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$good['id']; ?>"><?=htmlspecialchars($good['lot_name']);?></a></h3>
			<?php if ( $good['is_win'] ): ?>
			<p><?=$good['contact_info'].' '.$good['email']; ?></p>
			<?php endif; ?>
			</div>
          </td>
          <td class="rates__category">
            <?=($good['category_name']);?>
          </td>
          <td class="rates__timer">
            <div class="timer 	<?php if ( $good['is_win'] ): ?>
								timer--win
								<?php elseif ( $good['is_ended'] ) : ?>
								timer--end
								<?php elseif ( get_time_remaining($good['dt_end'])[0] < 1 ) : ?>
								timer--finishing
								<?php else: ?>
								''
								<?php endif; ?>     ">
			<?php if ( $good['is_win'] ): ?>
			Ставка выиграла
			<?php elseif ( $good['is_ended'] ) : ?>
			Торги окончены
			<?php else: ?>
			<?=implode(':', get_time_remaining($good['dt_end']) );?>
			<?php endif; ?>
			</div>
          </td>
          <td class="rates__price">
            <?=htmlspecialchars($good['bid_price']);?> р
          </td>
          <td class="rates__time">
            <?= get_time_since_adding($good['dt_add']) ?? implode(' в ', explode(' ', $good['dt_add_format']));?>
          </td>
        </tr>
		<?php endforeach; ?>
      </table>
    </section>
	