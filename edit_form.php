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
 * @package    block_ned_teacher_tools
 * @copyright  Michael Gardener <mgardener@cissq.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/blocks/ned_teacher_tools/lib.php');

/**
 * Simple ned_teacher_tools block config form definition
 *
 * @package    contrib
 * @subpackage block_ned_teacher_tools
 * @copyright  2011 MoodleFN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Simple ned_teacher_tools block config form class
 *
 * @copyright 2011 MoodleFN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_ned_teacher_tools_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        $hideshow = array(0 => get_string('hide'), 1 => get_string('show'));
        $yesno = array(0 => get_string('no'), 1 => get_string('yes'));

        // Configuring a NED Teacher Tools.
        if (!$pluginname =  get_config('block_ned_teacher_tools','blocktitlecourselevel')) {
            $pluginname = get_string('teachertools', 'block_ned_teacher_tools');
        }

        $mform->addElement('header', 'configheader',
            get_string('blocksettings', 'block_ned_teacher_tools', $pluginname)
        );

        $mform->addElement('static', 'blockinfo', get_string('blockinfo', 'block_ned_teacher_tools'),
            '<a target="_blank" href="http://ned.ca/teacher-tools">http://ned.ca/teacher-tools</a>');

        $settingurl = new moodle_url('/admin/settings.php', array('section' => 'blocksettingned_teacher_tools'));
        $mform->addElement('static', 'blocksettings', get_string('blocksitesettings', 'block_ned_teacher_tools'),
            html_writer::link($settingurl, get_string('opensitesettingspage', 'block_ned_teacher_tools'),
                array('target' => '_blank')
            )
        );
        if ($this->block->page->course->id != SITEID) {
            // General Settings.
            $mform->addElement('header', 'generalsettings',
                get_string('generalsettings', 'block_ned_teacher_tools'));

            $mform->addElement('select', 'config_include_orphaned',
                get_string('include_orphaned', 'block_ned_teacher_tools'), $yesno);
            $mform->setDefault('config_keepseparate', 0);

            // Custom links.
            $blocksettings = block_ned_teacher_tools_get_block_config($this->block->page->course->id);

            $mform->addElement('header', 'customlinks',
                get_string('customlinks', 'block_ned_teacher_tools'));

            $numberoflinksoptions = array();
            for ($i = 0; $i <= 10; $i++) {
                $numberoflinksoptions[$i] = $i;
            }
            $mform->addElement('select', 'config_numberoflinks',
                get_string('numberoflinks', 'block_ned_teacher_tools'), $numberoflinksoptions);
            $mform->setDefault('config_numberoflinks', '0');

            $mform->addElement('static', 'iconcoodes', get_string('iconcoodes', 'block_ned_teacher_tools'),
                '<a target="_blank" href="http://fontawesome.io/icons/">http://fontawesome.io/icons/</a>');

            $linkbehaviouroptions = array(
                '_blank' => get_string('newwindow', 'block_ned_teacher_tools'),
                '_self' => get_string('samewindow', 'block_ned_teacher_tools'),
                '_popup' => get_string('popup', 'block_ned_teacher_tools')
            );

            $numberoflinks = (isset($blocksettings->numberoflinks)) ? $blocksettings->numberoflinks : 0;
            if (!empty($numberoflinks)) {
                for ($i = 1; $i <= (int)$numberoflinks; $i++) {
                    $mform->addElement('html', html_writer::div(get_string('link', 'block_ned_teacher_tools').' '.$i,
                        'block_ned_teacher_tools_configsubtitle')
                    );
                    $mform->addElement('text', 'config_iconcode_'.$i, get_string('iconcode', 'block_ned_teacher_tools'));
                    $mform->setType('config_iconcode_'.$i, PARAM_TEXT);
                    $mform->setDefault('config_iconcode_'.$i, 'fa-square-o');

                    $mform->addElement('text', 'config_customlinkstitle_'.$i, get_string('customlinkstitle', 'block_ned_teacher_tools'));
                    $mform->setType('config_customlinkstitle_'.$i, PARAM_TEXT);

                    $mform->addElement('text', 'config_customlinkurl_'.$i, get_string('link', 'block_ned_teacher_tools'));
                    $mform->setType('config_customlinkurl_'.$i, PARAM_URL);

                    $mform->addElement('select', 'config_linkbehaviour_'.$i,
                        get_string('linkbehaviour', 'block_ned_teacher_tools').'-'.$i, $linkbehaviouroptions);
                }
            }
            // Site links.
            $mform->addElement('html', html_writer::div('Site links', 'block_ned_teacher_tools_configsubtitle'));
            if ($numberoflinks = get_config('block_ned_teacher_tools', 'numberoflinks')) {
                for ($i = 1; $i <= $numberoflinks; $i++) {
                    $mform->addElement('select', 'config_sitelink_' . $i, get_config('block_ned_teacher_tools', 'customlinkstitle_' . $i), $yesno);
                    $mform->setDefault('config_sitelink_' . $i, 1);
                }
            }




            // Other setting.
            $mform->addElement('header', 'othersettings',
                get_string('othersettings', 'block_ned_teacher_tools'));

            $mform->addElement('select', 'config_keepseparate',
                get_string('keepseparate', 'block_ned_teacher_tools'), $yesno);
            $mform->setDefault('config_keepseparate', 1);

            $mform->addElement('select', 'config_showtopmessage',
                get_string('showtopmessage', 'block_ned_teacher_tools'), array('0' => 'No', '1' => 'Yes'));
            $mform->setDefault('config_showtopmessage', 0);

            $mform->addElement('editor', 'config_topmessage', get_string('topmessage', 'block_ned_teacher_tools'));
            $mform->setType('config_topmessage', PARAM_RAW);
        }
    }
}