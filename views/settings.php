<?php echo "<h1>".__('Settings') . "</h1>"; ?>
<form action="<?php echo get_url('plugin/djg_poll/save'); ?>" method="post">
	<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Custom'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="settings[defaultMultiple]"><?php echo __('Default multiple') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[defaultMultiple]">
                        <option value="1" <?php if ($settings['defaultMultiple'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['defaultMultiple'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
			<tr>
				<td class="label"><label for="settings[defaultActive]"><?php echo __('Default active') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[defaultActive]">
                        <option value="1" <?php if ($settings['defaultActive'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['defaultActive'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
			<tr>
				<td class="label"><label for="settings[resultsPerPage]"><?php echo __('Results per page') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[resultsPerPage]">
						<option value="1" <?php if ($settings['resultsPerPage'] == "1") echo 'selected = "";' ?>>1</option>
                        <option value="5" <?php if ($settings['resultsPerPage'] == "5") echo 'selected = "";' ?>>5</option>
                        <option value="10" <?php if ($settings['resultsPerPage'] == "10") echo 'selected = "";' ?>>10</option>
                        <option value="20" <?php if ($settings['resultsPerPage'] == "20") echo 'selected = "";' ?>>20</option>
                        <option value="50" <?php if ($settings['resultsPerPage'] == "50") echo 'selected = "";' ?>>50</option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
			<tr>
				<td class="label"><label for="settings[showTab]"><?php echo __('Show tab in admin menu') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[showTab]">
                        <option value="1" <?php if ($settings['showTab'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['showTab'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
		</table>
	</fieldset>
<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Frontend'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="settings[specifyYourVote]"><?php echo __('Specify Your Vote') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[specifyYourVote]">
                        <option value="1" <?php if ($settings['specifyYourVote'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['specifyYourVote'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>	
			<tr>
				<td class="label"><label for="settings[sortResults]"><?php echo __('Sort Results') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[sortResults]">
                        <option value="1" <?php if ($settings['sortResults'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['sortResults'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
			<tr>
				<td class="label"><label for="settings[allowSelectAll]"><?php echo __('Allow To Select All Answres') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[allowSelectAll]">
                        <option value="1" <?php if ($settings['allowSelectAll'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['allowSelectAll'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>	      
		</table>
	</fieldset>
<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Protect'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="settings[checkCookie]"><?php echo __('Check Cookie') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[checkCookie]">
                        <option value="1" <?php if ($settings['checkCookie'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['checkCookie'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
			<tr>
				<td class="label"><label for="settings[checkIP]"><?php echo __('Check IP') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[checkIP]">
                        <option value="1" <?php if ($settings['checkIP'] == "1") echo 'selected = "";' ?>><?php echo __('Yes'); ?></option>
                        <option value="0" <?php if ($settings['checkIP'] == "0") echo 'selected = "";' ?>><?php echo __('No'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>	
		</table>
	</fieldset>
<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Charts Styling'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="settings[chartsSize]"><?php echo __('Chart Size') ?></label></td>
				<td class="field">
                    <select class="select" name="settings[chartsSize]">
                        <option value="800x320" <?php if ($settings['chartsSize'] == "800x320") echo 'selected = "";' ?>><?php echo __('800x320'); ?></option>
                        <option value="500x220" <?php if ($settings['chartsSize'] == "500x220") echo 'selected = "";' ?>><?php echo __('500x220'); ?></option>
                        <option value="360x160" <?php if ($settings['chartsSize'] == "360x160") echo 'selected = "";' ?>><?php echo __('360x160'); ?></option>
                    </select>
				</td>
				<td class="help"></td>
			</tr>
		</table>
	</fieldset>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    </p>
</form>

<script type="text/javascript">
// <![CDATA[
    function setConfirmUnload(on, msg) {
        window.onbeforeunload = (on) ? unloadMessage : null;
        return true;
    }

    function unloadMessage() {
        return '<?php echo __('You have modified this page.  If you navigate away from this page without first saving your data, the changes will be lost.'); ?>';
    }

    $(document).ready(function() {
        // Prevent accidentally navigating away
        $(':input').bind('change', function() { setConfirmUnload(true); });
        $('form').submit(function() { setConfirmUnload(false); return true; });
    });
// ]]>
</script>