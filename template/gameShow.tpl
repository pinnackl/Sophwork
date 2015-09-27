<?php
	$this->getLayout('header');
?>
<h1>Game page</h1>
<p>This is a view game info page for the game : <b><?= $game ?></b> </p>
<a href="<?= $editUrl ?>">Edit</a>
<?= $this->e('plop') ?>
</body>
</html>