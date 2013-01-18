<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('cake.generic');
		echo $this->Html->script('jquery.min');
		echo $this->Html->script('photon-beam');
		echo $this->Html->script('photon-beam-player');
		$this->Html->scriptBlock("Photon.base = '" . Router::url("/") ."';", array("inline" => false));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><a href="<?php echo Router::url("/"); ?>">CakePHP MPlayer GUI</a></h1>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<ul class="actions controls">
				<li><a href="<?php echo Router::url("/pause"); ?>">Play/Pause</a></li>
				<li><a href="<?php echo Router::url("/stop"); ?>">Stop</a></li>
				<li><span class="player-time"></span></li>
			</ul>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>