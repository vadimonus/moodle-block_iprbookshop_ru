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

require_once('../../config.php');

require_login();
$context = context_system::instance();
require_capability('block/iprbookshop_ru:viewstats', $context);

$PAGE->set_url(new moodle_url('/blocks/iprbookshop_ru/statistics.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('statistics', 'block_iprbookshop_ru'));
$PAGE->set_heading(get_string('statistics', 'block_iprbookshop_ru'));
$PAGE->set_pagelayout('report');

echo $OUTPUT->header();

$sql = "SELECT MIN(time) AS time
        FROM {block_iprbookshop_ru_visits}";
$timefirst = $DB->get_field_sql($sql);
if ($timefirst) {

    $table = new html_table();
    $table->width = 'auto';
    $table->head = array(get_string('month', 'block_iprbookshop_ru'), get_string('visits', 'block_iprbookshop_ru'));
    $table->align = array('center', 'center');

    $date = usergetdate(time());
    $year = $date['year'];
    $month = $date['mon'];

    do {
        $timestart = make_timestamp($year, $month, 1);
        $timeend = make_timestamp($year, $month + 1,  1);
        if ($timeend <= $timefirst) {
            break;
        }

        $monthname = userdate($timestart, '%B %Y');
        $sql = "SELECT COUNT(id) AS count_id
            FROM {block_iprbookshop_ru_visits}
            WHERE time >= :timestart AND time < :timeend";
        $params = array(
            'timestart' => $timestart,
            'timeend' => $timeend);
        $count = $DB->get_field_sql($sql, $params);
        $table->data[] = array($monthname, $count);

        $month--;
        if ($month == 0) {
            $month = 12;
            $year--;
        }
    } while (true);

    echo html_writer::table($table);
}

echo $OUTPUT->footer();