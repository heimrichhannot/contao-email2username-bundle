<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['importUser'][] = ['huh.email2username.listener.hooks', 'importUserHook'];