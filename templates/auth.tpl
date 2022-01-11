<h1 class="text-center mt-4">Вход для администраторов</h1>
<form method="post" class="container col-sm-3">
	<div class="form-group">
		<label for="login">Логин:</label>
		<input id="login" name="login" type="text" class="form-control" value="<?php echo $_POST['login'] ?? '' ?>" required>
	</div>
	<div class="form-group">
		<label for="passwd">Пароль:</label>
		<input id="passwd" name="passwd" type="password" class="form-control" value="<?php echo $_POST['passwd'] ?? '' ?>" required>
	</div>
	<input type="submit" name="submit" value="Вход" class="btn btn-outline-success">
</form>









