<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<section class=" p-3 p-lg-5 d-flex align-items-center" id="about">
  <div class="w-100">
    <h1 class="mb-0">An    
      <span class="text-primary">Uncaught Exception</span>
      	was encountered
    </h1>
	<hr class="m-0">
	<div class=" mb-3">
		<span class="subheading">Type : </span>
		<span class="text-primary" style="font-size:1.5rem"><?php echo get_class($exception); ?></span>
	</div>
	<hr class="m-0">
	<div class=" mb-3"><span class="subheading">Message : </span>
	<span class="text-primary" style="font-size:1.5rem"><?php echo $message; ?></span></div>
	<hr class="m-0">
	<div class=" mb-3"><span class="subheading">Filename : </span><span class="text-primary" style="font-size:1.5rem"><?php echo $exception->getFile(); ?></span></div>
	<hr class="m-0">
	<div class=" mb-3"><span class="subheading">Line Number : </span><span class="text-primary" style="font-size:1.5rem"><?php echo $exception->getLine(); ?></span></div>
	<hr class="m-0">
	<div class=" mb-3"><span class="subheading">Backtrace :</span></div>
	<?php foreach (debug_backtrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p class="text-primary" style="margin-left:10px">
			File: <?php echo $error['file'] ?><br />
			Line: <?php echo $error['line'] ?><br />
			Function: <?php echo $error['function'] ?>
			</p>

		<?php endif ?>

	<?php endforeach ?>

  </div>
</section>

<?php die(); ?>