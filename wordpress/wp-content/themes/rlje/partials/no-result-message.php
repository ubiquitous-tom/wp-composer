<?php
$message = (!empty($wp_query->query_vars['no_result_message'])) ? $wp_query->query_vars['no_result_message'] : 'No results...';
$inline = (!empty($wp_query->query_vars['no_result_inline'])) ? true : false;
if(!$inline) :
?>
<div class="container browse">
<?php endif; ?>
    <div class="alert alert-info text-center"><?= $message; ?></div>
<?php if(!$inline) :?>
</div>
<?php endif; ?>
