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

// Block Info.
$settings->add( new admin_setting_configempty('block_ned_teacher_tools/blockinfo',
        get_string('blockinfo', 'block_ned_teacher_tools'),
        '<a target="_blank" href="http://ned.ca/marking-manager">http://ned.ca/marking-manager</a>'
    )
);

$showhideoptions = array(
    '1' => get_string('show', 'block_ned_teacher_tools'),
    '0' => get_string('hide', 'block_ned_teacher_tools')
);
$yesnooptions = array(
    '1' => get_string('yes', 'block_ned_teacher_tools'),
    '0' => get_string('no', 'block_ned_teacher_tools')
);

$numberofdays = array();
for ($i = 1; $i <= 100; $i++) {
    $numberofdays[$i] = $i;
}

$numberofpercent = array();
for ($i = 1; $i <= 100; $i++) {
    $numberofpercent[$i] = $i;
}


// General Settings.
$settings->add(
    new admin_setting_heading(
        'generalsettings',
        get_string('generalsettings', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/refreshmodefrontpage',
        get_string('refreshmodefrontpage', 'block_ned_teacher_tools'),
        '',
        'manual',
        array(
            'pageload' => get_string('pageload', 'block_ned_teacher_tools'),
            'manual' => get_string('manual', 'block_ned_teacher_tools')
        )
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/refreshmodecourse',
        get_string('refreshmodecourse', 'block_ned_teacher_tools'),
        '',
        'pageload',
        array(
            'pageload' => get_string('pageload', 'block_ned_teacher_tools'),
            'manual' => get_string('manual', 'block_ned_teacher_tools')
        )
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/adminfrontpage',
        get_string('adminfrontpage', 'block_ned_teacher_tools'),
        '',
        'enrolled',
        array(
            'enrolled' => get_string('enrolledcourses', 'block_ned_teacher_tools'),
            'all' => get_string('allcourses', 'block_ned_teacher_tools')
        )
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/listcourseszeroungraded',
        get_string('listcourseszeroungraded', 'block_ned_teacher_tools'),
        '',
        0,
        $yesnooptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/include_orphaned',
        get_string('include_orphaned', 'block_ned_teacher_tools'),
        '',
        0,
        $yesnooptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/allcourseswithblock',
        get_string('allcourseswithblock', 'block_ned_teacher_tools'),
        '',
        1,
        $yesnooptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/includehiddencourses',
        get_string('includehiddencourses', 'block_ned_teacher_tools'),
        '',
        0,
        $yesnooptions
    )
);
$coursecaturl = new moodle_url('/blocks/ned_teacher_tools/coursecategories.php');
$settings->add( new admin_setting_configempty('block_ned_teacher_tools/courseselection',
    get_string('coursecategoriesincluded', 'block_ned_teacher_tools'),
    '<a class="btn" href="'.$coursecaturl->out().'">'.get_string('selectcategories', 'block_ned_teacher_tools').'</a>')
);

$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/suspendeduserstoshow',
        get_string('suspendeduserstoshow', 'block_ned_teacher_tools'),
        '',
        0,
        array(
            '0' => get_string('none', 'block_ned_teacher_tools'),
            '1' => get_string('suspendedenrollments', 'block_ned_teacher_tools'),
            '2' => get_string('suspendedusers', 'block_ned_teacher_tools'),
            '3' => get_string('both', 'block_ned_teacher_tools')
        )
    )
);


$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/editortoggle',
        get_string('editortoggle', 'block_ned_teacher_tools'),
        get_string('experimental', 'block_ned_teacher_tools'),
        0,
        $showhideoptions
    )
);

// Layout and format.
$settings->add(
    new admin_setting_heading(
        'layoutandformat',
        get_string('layoutandformat', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configtext(
        'block_ned_teacher_tools/blocktitlesitelevel',
        get_string('blocktitlesitelevel', 'block_ned_teacher_tools'),
        '',
        get_string('markingmanager', 'block_ned_teacher_tools'),
        PARAM_TEXT
    )
);
$settings->add(
    new admin_setting_configtext(
        'block_ned_teacher_tools/blocktitlecourselevel',
        get_string('blocktitlecourselevel', 'block_ned_teacher_tools'),
        '',
        get_string('teachertools', 'block_ned_teacher_tools'),
        PARAM_TEXT
    )
);

$themeconfig = theme_config::load($CFG->theme);
$layouts = array();
foreach (array_keys($themeconfig->layouts) as $layout) {
    $layouts[$layout] = $layout;
}

$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/pagelayout',
        get_string('pagelayout', 'block_ned_teacher_tools'),
        '',
        'course',
        $layouts
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showcourselink',
        get_string('showcourselink', 'block_ned_teacher_tools'),
        '',
        0,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showtitles',
        get_string('titlesforlinkclusters', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);

// NED Teacher Tools.
$settings->add(
    new admin_setting_heading(
        'markingmanager',
        get_string('markingmanager', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showunmarked',
        get_string('showunmarked', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showmarked',
        get_string('showmarked', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showunsubmitted',
        get_string('showunsubmitted', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);

// Quick links.
$settings->add(
    new admin_setting_heading(
        'quicklinks',
        get_string('quicklinks', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showgradeslink',
        get_string('showgradeslink', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showgradebook',
        get_string('showgradebook', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showreportslink',
        get_string('showreportslink', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);

// Notices.
$settings->add(
    new admin_setting_heading(
        'notices',
        get_string('notices', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/shownotloggedinuser',
        get_string('config_shownotloggedinuser', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/daysnotlogged',
        get_string('setnumberofdays', 'block_ned_teacher_tools'),
        '',
        7,
        $numberofdays
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showstudentnotsubmittedassignment',
        get_string('config_showstudentnotsubmittedassignment', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/daysnotsubmited',
        get_string('setnumberofdays', 'block_ned_teacher_tools'),
        '',
        7,
        $numberofdays
    )
);

$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showstudentmarkslessthanfiftypercent',
        get_string('config_showstudentmarkslessthanfiftypercent', 'block_ned_teacher_tools'),
        '',
        1,
        $showhideoptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/percent',
        get_string('setpercentmarks', 'block_ned_teacher_tools'),
        '',
        50,
        $numberofpercent
    )
);

// Other setting.
$settings->add(
    new admin_setting_heading(
        'othersettings',
        get_string('othersettings', 'block_ned_teacher_tools'),
        ''
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/keepseparate',
        get_string('keepseparate', 'block_ned_teacher_tools'),
        '',
        1,
        $yesnooptions
    )
);
$settings->add(
    new admin_setting_configselect(
        'block_ned_teacher_tools/showtopmessage',
        get_string('showtopmessage', 'block_ned_teacher_tools'),
        '',
        0,
        $yesnooptions
    )
);
$settings->add(
    new admin_setting_confightmleditor(
        'block_ned_teacher_tools/topmessage',
        get_string('topmessage', 'block_ned_teacher_tools'),
        '',
        ''
    )
);