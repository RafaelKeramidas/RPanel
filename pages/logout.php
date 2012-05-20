<?php
	session_destroy();
	header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'));
?>