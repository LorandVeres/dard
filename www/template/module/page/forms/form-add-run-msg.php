
<div class="row_6 center_box">
	<form method="post" action="error-messages?a=add">
		<div class="form_row">
			<label for="er-msg-name">Add Message</label>
			<textarea id="er-msg-name" name="message" cols="4"></textarea>
		</div>
		<div class="form_row">
			<label for="er-msg-module">Modue id</label>
			<input id="er-msg-module" type="text" name="module" value="<?php echo $this->param_moduleid; ?>" />
		</div>
		<div class="form_row">
			<label for="er-msg-page">Page id</label>
			<input id="er-msg-page" type="text" name="page" value="<?php echo $this->param_pageid; ?>" />
		</div>
		<div class="single_form">
			<label for="er-msg-type">Message type</label>
			<select id="er-msg-type" name="type">
				<option value="0">Error</option>
				<option value="1">Confirm</option>
			</select>
		</div>
		<div class="center row spacer_5">
			<input type="submit" value="add new message" />
		</div>
	</form>
</div>
