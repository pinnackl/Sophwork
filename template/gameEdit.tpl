<?php
	$this->getLayout('header');
?>
	<h1>Game page</h1>
	<p>This is a view game edit info page for the game : <b><?= $game ?></b> </p>
	<form action="" method="post">
		<label for="gameId">Game ID :</label>
		<input id="gameID" type="text" name="gameId" value="<?= $game ?>">
		<button>Save</button>
	</form>
	<a href="<?= $cancel ?>">Cancel</a>
</body>
</html>