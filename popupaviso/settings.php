<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_popupaviso', get_string('pluginname', 'local_popupaviso'));

    // Campo com editor HTML para a mensagem
    $settings->add(new admin_setting_confightmleditor(
        'local_popupaviso/mensagem',
        get_string('mensagem', 'local_popupaviso'),
        get_string('mensagem_desc', 'local_popupaviso'),
        ''
    ));

    // ✅ Campo para a URL desejada
    $settings->add(new admin_setting_configtext(
        'local_popupaviso/url',
        get_string('url', 'local_popupaviso'),
        get_string('url_desc', 'local_popupaviso'),
        '', // valor padrão
        PARAM_URL
    ));

    // ✅ Campo para link de vídeo do YouTube
    $settings->add(new admin_setting_configtext(
        'local_popupaviso/video',
        get_string('video', 'local_popupaviso'),
        get_string('video_desc', 'local_popupaviso'),
        '', // valor padrão
        PARAM_URL
    ));

    $settings->add(new admin_setting_configtext(
        'local_popupaviso/cor',
        get_string('cor', 'local_popupaviso'),
        get_string('cor_desc', 'local_popupaviso'),
        '#f8d7da',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'local_popupaviso/limite',
        get_string('limite', 'local_popupaviso'),
        get_string('limite_desc', 'local_popupaviso'),
        '1', // valor padrão: mostrar uma vez
        PARAM_INT
    ));

    // Campo de configuração para o limite de exibição
    $settings->add(new admin_setting_configtext(
        'local_popupaviso/limite',
        get_string('limite', 'local_popupaviso'),
        get_string('limite_desc', 'local_popupaviso'),
        '1', // valor padrão: exibir uma vez por sessão
        PARAM_INT
    ));



    $ADMIN->add('localplugins', $settings);
}
