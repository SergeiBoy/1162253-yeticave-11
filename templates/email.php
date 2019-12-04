

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?=htmlspecialchars($winner['user_name']);?>!</p>
<?php foreach ($winner['lot'] as $lot): ?>
<p>Ваша ставка для лота <a href="http://1162253-yeticave-11/lot.php?id=<?=$lot['id'];?>"><?=htmlspecialchars($lot['lot_name']);?></a> победила.</p>
<?php endforeach; ?>
<p>Перейдите по ссылке <a href="http://1162253-yeticave-11/mybets.php">мои ставки</a>,
чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>

