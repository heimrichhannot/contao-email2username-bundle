<?php

$dca = &$GLOBALS['TL_DCA']['tl_member'];

/**
 * Callbacks
 */
$dca['config']['onload_callback']['huh_email2username'] = [\HeimrichHannot\Email2UsernameBundle\DataContainer\MemberContainer::class, 'onLoad'];
$dca['config']['onsubmit_callback']['huh_email2username'] = [\HeimrichHannot\Email2UsernameBundle\DataContainer\MemberContainer::class, 'onSubmit'];
