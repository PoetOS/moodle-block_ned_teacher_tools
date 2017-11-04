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
            '<a target="_blank" href="http://ned.ca/marking-manager">http://ned.ca/marking-manager</a>');

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