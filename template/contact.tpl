<?php
	$this->getLayout('header');
?>
		<form method="post" action="<?= $baseUrl ?>/form">
			<input name="message" type="text">
			<button>Send</button>
		</form>
	</body>
</html>