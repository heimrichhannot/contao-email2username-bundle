<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_member'];

/**
 * Callbacks
 */
$arrDca['config']['onsubmit_callback']['setUsernameFromEmail'] = ['huh.email2username.listener.callback', 'setMembernameFromEmail'];

/**
 * Fields
 */
$arrDca['fields']['username']['eval']['disabled']  = true;
$arrDca['fields']['username']['eval']['mandatory'] = false;