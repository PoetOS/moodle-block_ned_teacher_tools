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

function xmldb_block_ned_teacher_tools_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2016082501) {

        $dbman = $DB->get_manager();

        // Define table block_ned_teacher_tools_cach to be created.
        $table = new xmldb_table('block_ned_teacher_tools_cach');

        // Adding fields to table block_ned_teacher_tools_cach.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '18', null, null, null, null);
        $table->add_field('modname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('unmarked', XMLDB_TYPE_INTEGER, '18', null, null, null, '0');
        $table->add_field('marked', XMLDB_TYPE_INTEGER, '18', null, null, null, '0');
        $table->add_field('unsubmitted', XMLDB_TYPE_INTEGER, '18', null, null, null, '0');
        $table->add_field('saved', XMLDB_TYPE_INTEGER, '18', null, null, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '18', null, null, null, '0');

        // Adding keys to table block_ned_teacher_tools_cach.
        $table->add_key('id', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('ix_cor_mod', XMLDB_KEY_UNIQUE, array('courseid', 'modname'));

        // Conditionally launch create table for block_ned_teacher_tools_cach.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    if ($oldversion < 2016111700) {
        $dbman = $DB->get_manager();
        $table = new xmldb_table('block_ned_teacher_tools_cach');

        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '11', null, null, null, '0', 'saved');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('expired', XMLDB_TYPE_INTEGER, '11', null, null, null, '0', 'userid');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $key = new xmldb_key('ix_cor_mod', XMLDB_KEY_UNIQUE, array('courseid', 'modname'));
        $dbman->drop_key($table, $key);

        $key = new xmldb_key('ix_cor_mod_us', XMLDB_KEY_UNIQUE, array('courseid', 'modname', 'userid'));
        $dbman->add_key($table, $key);

        upgrade_block_savepoint(true, 2016111700, 'ned_teacher_tools');
    }

    if ($oldversion < 2017092900) {
        if ($refreshmodefrontpage = get_config('block_ned_teacher_tools', 'refreshmodefrontpage')) {
            if ($refreshmodefrontpage == 'cron') {
                set_config('refreshmodefrontpage', 'manual', 'block_ned_teacher_tools');
            }
        }
        if ($refreshmodecourse = get_config('block_ned_teacher_tools', 'refreshmodecourse')) {
            if ($refreshmodecourse == 'cron') {
                set_config('refreshmodecourse', 'manual', 'block_ned_teacher_tools');
            }
        }
    }
    return true;
}
