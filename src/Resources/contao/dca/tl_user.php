<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_user'];

/**
 * Callbacks
 */
$arrDca['config']['onsubmit_callback']['setUsernameFromEmail'] = ['huh.email2username.listener.callback', 'setUsernameFromEmail'];

/**
 * Fields
 */
$arrDca['fields']['username']['eval']['disabled']  = true;
$arrDca['fields']['username']['eval']['mandatory'] = false;