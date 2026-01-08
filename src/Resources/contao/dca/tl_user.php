<?php

use HeimrichHannot\Email2UsernameBundle\DataContainer\UserContainer;

$dca = &$GLOBALS['TL_DCA']['tl_user'];

/*
 * Callbacks
 */
$dca['config']['onload_callback']['huh_email2username'] = [UserContainer::class, 'onLoad'];
$dca['config']['onsubmit_callback']['huh_email2username'] = [UserContainer::class, 'onSubmit'];
