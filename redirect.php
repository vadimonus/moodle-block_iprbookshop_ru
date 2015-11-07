<?php

/**
 * Authentication on www.iprbookshop.ru
 *
 * @package    block
 * @subpackage iprbookshop_ru
 * @copyright  2015 Vadim Dvorovenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_login();

$contextid = required_param('contextid', PARAM_INT);
$bookid = optional_param('bookid', null, PARAM_INT);

require_capability('block/iprbookshop_ru:use', context::instance_by_id($contextid));

$params = array(
    'contextid' => $contextid
);
$event = \block_iprbookshop_ru\event\link_used::create($params);
$event->trigger();

$visit = new stdClass();
$visit->time = time();
$visit->userid = $USER->id;
$visit->contextid = $contextid;
$visit->bookid = $bookid;
$DB->insert_record('block_iprbookshop_ru_visits', $visit);

$secretkey = get_config('block_iprbookshop_ru', 'secretkey');
$domain = get_config('block_iprbookshop_ru', 'domain');

$timestamp = date('YmdHis');
$signature = md5($USER->id . $secretkey . $timestamp);
$params = array(
    'domain' => $domain,
    'id' => $USER->id,
    'time' => $timestamp, 
    'sign' => $signature,
    'name' => $USER->firstname, 
    'lname' => $USER->lastname,
    'ut' => 4, 
    'email' => $USER->email);
if ($bookid) {
    $params['bid'] = $bookid;
}
$url = new moodle_url('http://www.iprbookshop.ru/autologin', $params);
redirect($url);