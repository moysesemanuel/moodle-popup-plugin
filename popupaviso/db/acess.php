<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/popupaviso:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => ['manager' => CAP_ALLOW]
    ],
];
