<?php

use HeimrichHannot\Email2UsernameBundle\EventListener\CreateNewUserListener;
use HeimrichHannot\Email2UsernameBundle\EventListener\HookListener;

/*
 * Hooks
 */
$GLOBALS['TL_HOOKS']['importUser']['huh_email2username'] = [HookListener::class, 'onImportUser'];