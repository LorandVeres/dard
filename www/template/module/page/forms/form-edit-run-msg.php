
<div class="row_6 center_box">
	<form method="post" action="error-messages?a=edit">
		<input type="hidden" name="id" value="<?php echo $msg['id']; ?>" />
		<div class="form_row">
			<label for="er-msg-name">Edit Message</label>
			<textarea id="er-msg-name" name="message" cols="4"><?php echo $msg['message']; ?></textarea>
		</div>
		<div class="form_row">
			<label for="er-msg-id">Message number</label>
			<input id="er-msg-id" type="text" name="number" value="<?php echo $msg['number']; ?>" />
		</div>
		<div class="form_row">
			<label for="er-msg-module">Modue id</label>
			<input id="er-msg-module" type="text" name="module" value="<?php echo $msg['module']; ?>" />
		</div>
		<div class="form_row">
			<label for="er-msg-page">Page id</label>
			<input id="er-msg-page" type="text" name="page" value="<?php echo $msg['page']; ?>" />
		</div>
		<div class="single_form">
			<label for="er-msg-type">Message type</label>
			<select id="er-msg-type" name="type">
				<option value="<?php echo $conf[2]; ?>"><?php echo $conf[0]; ?></option>
				<option value="<?php echo $conf[3]; ?>"><?php echo $conf[1]; ?></option>
			</select>
		</div>
		<div class="center row spacer_5">
			<input type="submit" value="edit" />
		</div>
	</form>
</div>
