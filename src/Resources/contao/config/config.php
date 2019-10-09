<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['importUser']['huh_email2username'] = [
    \HeimrichHannot\Email2UsernameBundle\EventListener\HookListener::class, 'onImportUser'];