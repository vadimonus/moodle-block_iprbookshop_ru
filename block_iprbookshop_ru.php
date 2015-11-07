<?php

/**
 * Authentication on www.iprbookshop.ru
 *
 * @package    block
 * @subpackage iprbookshop_ru
 * @copyright  2015 Vadim Dvorovenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_iprbookshop_ru extends block_base {
    
    function init() {
        $this->title = get_string('pluginname', 'block_iprbookshop_ru');
    }
    
    function specialization() {
        $title = get_config('block_iprbookshop_ru', 'title');
        if ($title !== false) {
            $this->title = $title;
        } else {
            $this->title = get_string('defaulttitle', 'block_iprbookshop_ru');
        }
    }

    function applicable_formats() {
        return array('all' => true);
    }
    
    function instance_allow_multiple() {
        return true;
    }

    public function instance_can_be_docked() {
        return (!empty($this->title) && parent::instance_can_be_docked());
    }

    function has_config() {
        return true;
    }

    function get_content () {
        global $OUTPUT, $PAGE;
        
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';

        if (has_capability('block/iprbookshop_ru:use', $PAGE->context)) {
            $text = get_config('block_iprbookshop_ru', 'link');
            if (!$text) {
                $text = get_string('defaultlink', 'block_iprbookshop_ru');
            }
            $url = new moodle_url('/blocks/iprbookshop_ru/redirect.php', array('contextid' => $PAGE->context->id));
            $link = new action_link($url, $text);
            $link->attributes = array('target' => '_blank');
            $this->content->text .= html_writer::div($OUTPUT->render($link));
        }
        
        if (has_capability('block/iprbookshop_ru:viewstats', context_system::instance())) {
            $text = get_string('statistics','block_iprbookshop_ru');
            $url = new moodle_url('/blocks/iprbookshop_ru/statistics.php');
            $link = new action_link($url, $text);
            $this->content->text .= html_writer::div($OUTPUT->render($link));
        }
        return $this->content;
    }
}
