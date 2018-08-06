<?php

$dca = &$GLOBALS['TL_DCA']['tl_member'];

/**
 * Callbacks
 */
$dca['config']['onsubmit_callback']['setMembernameFromEmail'] = ['huh.email2username.listener.callback', 'setMembernameFromEmail'];

/**
 * Fields
 */
System::getContainer()->get('huh.email2username.listener.callback')->modifyDca('tl_member', $dca);