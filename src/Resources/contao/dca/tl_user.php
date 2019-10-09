<?php

$dca = &$GLOBALS['TL_DCA']['tl_user'];

/**
 * Callbacks
 */
$dca['config']['onload_callback']['huh_email2username'] = [
    \HeimrichHannot\Email2UsernameBundle\DataContainer\UserContainer::class, 'onLoad'];
$dca['config']['onsubmit_callback']['huh_email2username'] = [
    \HeimrichHannot\Email2UsernameBundle\DataContainer\UserContainer::class, 'onSubmit'];