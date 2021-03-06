<?php

/*
 * FileSender www.filesender.org
 * 
 * Copyright (c) 2009-2012, AARNet, Belnet, HEAnet, SURFnet, UNINETT
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * *	Redistributions of source code must retain the above copyright
 * 	notice, this list of conditions and the following disclaimer.
 * *	Redistributions in binary form must reproduce the above copyright
 * 	notice, this list of conditions and the following disclaimer in the
 * 	documentation and/or other materials provided with the distribution.
 * *	Neither the name of AARNet, Belnet, HEAnet, SURFnet and UNINETT nor the
 * 	names of its contributors may be used to endorse or promote products
 * 	derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/*
 * Propagates part of the config to javascript
 */

require_once('../includes/init.php');

header('Content-Type: text/javascript; charset=UTF-8');

$banned = Config::get('ban_extension');

$amc = Config::get('autocomplete_min_characters');

?>
if (typeof window === 'undefined') {
	window = {};
}
if (!('filesender' in window)) window.filesender = {};

window.filesender.config = {
    log: true,
    
    site_name: '<?php echo Config::get('site_name') ?>',
    
    upload_chunk_size: <?php echo Config::get('upload_chunk_size') ?>,
    
    upload_display_bits_per_sec: <?php echo Config::get('upload_display_bits_per_sec') ? 'true' : 'false' ?>,
    
    max_transfer_size: <?php echo Config::get('max_transfer_size') ?>,
    max_transfer_files: <?php echo Config::get('max_transfer_files') ?>,
    
    ban_extension: <?php echo is_string($banned) ? "'".$banned."'" : 'null' ?>,
    
    max_transfer_recipients: <?php echo Config::get('max_transfer_recipients') ?>,
    max_guest_recipients: <?php echo Config::get('max_guest_recipients') ?>,
    
    max_transfer_days_valid: <?php echo Config::get('max_transfer_days_valid') ?>,
    default_transfer_days_valid: <?php echo Config::get('default_transfer_days_valid') ?>,
    max_guest_days_valid: <?php echo Config::get('max_guest_days_valid') ?>,
    default_guest_days_valid: <?php echo Config::get('default_guest_days_valid') ?>,
    
    chunk_upload_security: '<?php echo Config::get('chunk_upload_security') ?>',
    
    encryption_enabled: '<?php echo Config::get('encryption_enabled') ?>',
    upload_crypted_chunk_size: '<?php echo Config::get('upload_crypted_chunk_size') ?>',
    crypto_iv_len: '<?php echo Config::get('crypto_iv_len') ?>',
    crypto_crypt_name: '<?php echo Config::get('crypto_crypt_name') ?>',
    crypto_hash_name: '<?php echo Config::get('crypto_hash_name') ?>',
    
    terasender_enabled: <?php echo Config::get('terasender_enabled') ? 'true' : 'false' ?>,
    terasender_advanced: <?php echo Config::get('terasender_advanced') ? 'true' : 'false' ?>,
    terasender_worker_count: <?php echo Config::get('terasender_worker_count') != null ? Config::get('terasender_worker_count') : 1 ?>,
    terasender_start_mode: '<?php echo Config::get('terasender_start_mode') ?>',
    terasender_worker_file: 'lib/terasender/terasender_worker.js?v=<?php echo Utilities::runningInstanceUID() ?>',
    terasender_upload_endpoint: '<?php echo Config::get('site_url') ?>rest.php/file/{file_id}/chunk/{offset}',
    
    stalling_detection: <?php $cfg = Config::get('stalling_detection'); echo json_encode(is_null($cfg) ? true : $cfg) ?>,
    
    max_legacy_file_size: <?php echo Config::get('max_legacy_file_size') ?>,
    legacy_upload_endpoint: '<?php echo Config::get('site_url') ?>rest.php/file/{file_id}/whole',
    legacy_upload_progress_refresh_period: <?php echo Config::get('legacy_upload_progress_refresh_period') ?>,
    
    base_path: '<?php echo GUI::path() ?>',
    support_email: '<?php echo Config::get('support_email') ?>',
    autocomplete: {
        enabled: <?php echo Config::get('autocomplete') ? 'true' : 'false' ?>,
        min_characters: <?php echo (is_int($amc) && $amc) ? $amc : 3 ?>
    },
    
    auditlog_lifetime: <?php $lt = Config::get('auditlog_lifetime'); echo is_null($lt) ? 'null' : $lt ?>,
    
    logon_url: '<?php echo AuthSP::logonURL() ?>',

	language: {
		downloading : "<?php echo Lang::tr('downloading')->out(); ?>",
		decrypting : "<?php echo Lang::tr('decrypting')->out(); ?>",
		file_encryption_wrong_password : "<?php echo Lang::tr('file_encryption_wrong_password')->out(); ?>",
		file_encryption_enter_password : "<?php echo Lang::tr('file_encryption_enter_password')->out(); ?>",
		file_encryption_need_password : "<?php echo Lang::tr('file_encryption_need_password')->out(); ?>"
	}
};

<?php if(Config::get('force_legacy_mode')) { ?>

$(function() {
    filesender.supports = {
        localStorage: false,
        workers: false,
        digest: false
    };
    
    $('#dialog-help li[data-feature="html5"]').toggle(filesender.supports.reader);
    $('#dialog-help li[data-feature="nohtml5"]').toggle(!filesender.supports.reader);
});

<?php } ?>
