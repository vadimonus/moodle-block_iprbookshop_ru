<?php

/**
 * Authentication on www.iprbookshop.ru
 *
 * @package    block
 * @subpackage iprbookshop_ru
 * @copyright  2015 Vadim Dvorovenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext(
            'block_iprbookshop_ru/domain', 
            new lang_string('domain', 'block_iprbookshop_ru'), 
            '',
            '', 
            PARAM_RAW));
    
    $settings->add(new admin_setting_configtext(
            'block_iprbookshop_ru/secretkey', 
            new lang_string('secretkey', 'block_iprbookshop_ru'), 
            '',
            '', 
            PARAM_RAW));
   
    $settings->add(new admin_setting_configtext(
            'block_iprbookshop_ru/title', 
            new lang_string('title', 'block_iprbookshop_ru'), 
            '',
            new lang_string('defaulttitle', 'block_iprbookshop_ru'), 
            PARAM_RAW));
   
    $settings->add(new admin_setting_configtext(
            'block_iprbookshop_ru/link', 
            new lang_string('link', 'block_iprbookshop_ru'), 
            '',
            new lang_string('defaultlink', 'block_iprbookshop_ru'), 
            PARAM_RAW));
}


