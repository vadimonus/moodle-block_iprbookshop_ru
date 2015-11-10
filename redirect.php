<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Authentication on www.iprbookshop.ru
 *
 * @package    block_iprbookshop_ru
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

date_default_timezone_set('Europe/Moscow');
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