<?php

$dca = &$GLOBALS['TL_DCA']['tl_user'];

/**
 * Callbacks
 */
$dca['config']['onsubmit_callback']['setUsernameFromEmail'] = ['huh.email2username.listener.callback', 'setUsernameFromEmail'];

/**
 * Fields
 */
System::getContainer()->get('huh.email2username.listener.callback')->modifyDca('tl_user', $dca);