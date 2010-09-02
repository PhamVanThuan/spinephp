<?php echo $this->view('hello', true); ?>
<?php echo $form->open(null, 'post', true, array('class' => 'form')); ?>
<?php if($errors): ?>
	Oh no! We found some errors when processing the form.
	<ul>
		<?php foreach($errors as $msg): ?>
		<li><?php echo $msg; ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php echo $form->fieldset_open('Login'); ?>
<?php echo $form->label('Username', 'username'); ?>: <?php echo $form->input('username', $post->source('username'), array('id' => 'username')); ?><br />
<?php echo $form->label('Password', 'pasword'); ?>: <?php echo $form->password('password', null, array('id' => 'password')); ?><br />
<?php echo $form->submit('submit', 'Login'); ?>
<?php
	echo $form->fieldset_close();
	echo $form->close();
?>