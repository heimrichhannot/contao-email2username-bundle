<?php

$dca = &$GLOBALS['TL_DCA']['tl_settings'];

/**
 * Palettes
 */
$dca['palettes']['default'] .= ';{email2username_legend},e2uUsers,e2uMembers;';

/**
 * Fields
 */
$fields = [
    'e2uUsers'   => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['e2uUsers'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
    ],
    'e2uMembers' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['e2uMembers'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
    ]
];

$dca['fields'] += $fields;

if (!isset($GLOBALS['TL_CONFIG']['e2uUsers']))
{
    \Contao\Config::persist('e2uUsers', true);
}

if (!isset($GLOBALS['TL_CONFIG']['e2uMembers']))
{
    \Contao\Config::persist('e2uMembers', true);
}