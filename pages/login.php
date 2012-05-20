<?php
	if(defined('PAGEINC')) {
		if($logged == false) {
			echo '<h1>Login</h1>';
			if(isset($_POST['user']) && isset($_POST['pass'])) {
				$user = $_POST['user'];
				$pass = sha1($_POST['pass']);
				
				if($user == $mainconfig['username'] && $pass == $mainconfig['password']) {
					$_SESSION['logged'] = true;
					header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'));
				}
				else {
					echo '<p>Invalid login...</p>';
				}
			}
			?>
			<div id="form">
				<form action="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'); ?>" method="POST">
					<p>User: <input type="text" name="user" class="field" /></p>
					<p>Password: <input type="password" name="pass" class="field" /></p>
					<p><input type="submit" value="Login" class="button" /></p>
				</form>
			</div>
			<?php
		}
		else {
			header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'));
		}
	}
?>