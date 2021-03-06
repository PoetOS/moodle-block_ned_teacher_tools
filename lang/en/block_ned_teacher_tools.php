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

$string['showsaved'] = 'Show draft activities';
$string['keepseparate'] = 'Keep draft assignments separate';
$string['setnumberofdays'] = 'Set number of days';
$string['setpercentmarks'] = 'Set percent';
$string['shownotloggedinuser'] = 'Show not logged in user';
$string['setblocktitle'] = 'Set block title';
$string['showstudentnotsubmittedassignment'] = 'Show no of student not submitted assignment';
$string['showstudentmarkslessthanfiftypercent'] = 'Show no of student marks less than 50 percent';
$string['blocksettings'] = 'Configuring a {$a}';
$string['pluginname'] = 'NED Teacher Tools';
$string['plugintitle'] = 'NED Teacher Tools';
$string['headertitle'] = 'NED Teacher Tools';
$string['blocktitle'] = 'NED Teacher Tools';
$string['cfgdisplaytitle'] = 'Display title';
$string['displaytitle'] = 'Activities Submitted';
$string['gradeslink'] = 'Grades';
$string['show'] = 'Show';
$string['hide'] = 'Hide';
$string['sort'] = 'Sort';
$string['view'] = 'View';
$string['marked'] = ' Marked Activities';
$string['reportslink'] = 'Reports';
$string['showcourselink'] = 'Course home link';
$string['showgradeslink'] = 'Progress Report';
$string['showgradeslink_desc'] = 'This setting allows whether to show or hide the grade link in block.';
$string['showgradebook'] = 'Gradebook';
$string['gradebook'] = 'Gradebook';
$string['showmarked'] = 'Marked Activities';
$string['showreportslink'] = 'Student List';
$string['showunmarked'] = 'Requires Grading';
$string['showunsubmitted'] = 'Unsubmitted Activities';
$string['ttmarking'] = 'Marking Interface';
$string['unmarked'] = 'Requires Grading';
$string['marked'] = 'Graded';
$string['saved'] = 'Draft';
$string['unsubmitted'] = ' Not Submitted';
$string['notloggedin'] = ' - no login for';
$string['title:failingwithgradelessthanxpercent'] = 'The following students have an overall grade less than ';
$string['title:notlogin'] = 'The Following Students Have Not Logged in For ';
$string['title:notsubmittedanyactivity'] = 'The Following Students Have Not Submitted Any Activities For ';
$string['title:markslessthanxpercent'] = 'The Following Students Have Not Submitted Any Activities For ';
$string['title:saved'] = 'The Following Students Have Draft Activities';
$string['notsubmittedany'] = ' - no submission for ';
$string['overallfailinggrade'] = ' - grade less than ';
$string['gradingstudentprogress'] = 'Showing {$a->index} of {$a->count}';
$string['grade'] = '<b>Grade: </b>';
$string['config_title'] = 'Instance title';
$string['config_title_help'] = '<p>This setting allows the block title to be changed.</p>
<p>If the block header is hidden, the title will not appear.</p>';
$string['config_showunmarked'] = 'Show unmarked activities';
$string['config_showunmarked_help'] = '<p>This setting allows whether to show .</p>
<p> or hide the unmarked activities in block.</p>';
$string['config_showmarked'] = 'Show marked activities';
$string['config_showmarked_help'] = '<p>This setting allows whether to show .</p>
<p> or hide the marked activities in block.</p>';
$string['config_showsaved'] = 'Show draft activities';
$string['config_showsaved_help'] = '<p>This setting allows whether to show .</p>
<p> or hide the student draft activities in block.</p>';
$string['config_unsubmitted'] = 'Show unsubmitted activities';
$string['config_unsubmitted_help'] = '<p>This setting allows whether to show </p>
<p> or hide the not submitted activities in block.</p>';
$string['config_showgradeslink'] = 'Show grade link';
$string['config_showreportlink'] = 'Show report link';
$string['config_showreportlink_help'] = '<p>This setting allows whether to show </p>
<p> or hide the report link in block.</p>';
$string['config_shownotloggedinuser'] = 'Students not logged in for X days';
$string['config_shownotloggedinuser_desc'] = 'This setting allows whether to show or hide the number of student not
loggedin in previous week.';
$string['config_showstudentnotsubmittedassignment'] = 'Students not submitted for X days';
$string['config_showstudentnotsubmittedassignment_desc'] = 'This setting allows whether to show or hide the number
of student not submitted assignment last week .';
$string['config_showstudentmarkslessthanfiftypercent'] = 'Students with overall grade less than X percent';
$string['config_showstudentmarkslessthanfiftypercent_desc'] = 'This setting allows whether to show or hide the number
of student marks less that 50%.';
$string['config_days'] = 'Set the number of student not logged in x days';
$string['config_days_help'] = '<p>This setting allows to set  </p>
<p>the number of days that student have not logged in course.</p>';
$string['config_percent'] = 'Set the percent of marks';
$string['config_percent_help'] = '<p>This setting allows to set  </p>
<p>the percent of marks and after setting the percent you will see the number of student marks below x percent.</p>';
$string['ned_teacher_tools:addinstance'] = 'Add instance';
$string['ned_teacher_tools:viewblock'] = 'View block';
$string['ned_teacher_tools:viewreadonly'] = 'View readonly';
$string['ned_teacher_tools:myaddinstance'] = 'Add a new NED Teacher Tools block to Dashboard';
$string['simplegradebook'] = 'Progress Report';
$string['studentlist'] = 'Student List';
$string['moodlegradebook'] = 'Open Moodle Gradebook';
$string['descconfig'] = '<p>Activate this option to hide all blocks when viewing the NED Teacher Tools interface
and provide a less cluttered look. Note that before activating this option, you will need to add this code
to <b><em>yourmoodlesite/theme/base/config.php</em>.</b></p>
<p></p>
<pre><code style="font-size:12px; color:#FF7600;">
// Hide left and right block columns when viewing the NED Teacher Tools
\'markingmanager\' => array(
      \'file\' => \'general.php\',
      \'regions\' => array(),
      \'options\' => array(\'noblocks\'=>true),
),
</code></pre>
After you add the above code, your file should look like the image <a href="http://moodlefn.com/docs/marking_manager_no_blocks.png">shown here</a>.  ';
$string['labelnoblocks'] = 'Hide all blocks';
$string['showtopmessage'] = 'Show message above interface';
$string['topmessage'] = 'Message to show';
$string['include_orphaned'] = 'Include orphaned (stealth) activities';
$string['forum'] = 'Forum';
$string['quiz'] = 'Quiz';
$string['assign'] = 'Assignment';
$string['type'] = 'Type';
$string['scale'] = 'Scale';
$string['whocanrate'] = 'Who can rate';
$string['aggregatetype'] = 'Aggregate type';
$string['student_have_posted'] = 'The following students have posted to this forum:';
$string['student'] = 'Student';
$string['posts'] = 'Posts';
$string['replies'] = 'Replies';
$string['rating'] = 'Rating';
$string['morethan10'] = 'There are more than 10 courses with ungraded work.';
$string['student'] = 'Student';
$string['close'] = 'Close';
$string['sectiontitles'] = 'Section titles';
$string['sectiontitles_desc'] = 'blank=course default';
$string['listcourseszeroungraded'] = 'List courses with zero ungraded activities';
$string['version'] = 'Version';
$string['visitpluginhome'] = 'Vist plugin home page';
$string['pluginnametext'] = 'Plug-in name';
$string['pagelayout'] = 'Page layout';
$string['coursecategories'] = 'Course categories';
$string['coursecategoriesincluded'] = 'Course categories included';
$string['selectcategories'] = 'Select categories';
$string['successful'] = 'Successful';
$string['allcategories'] = 'All Categories';
$string['markinmanagerscoursecats'] = 'NED Teacher Tools - Course Categories';
$string['markinmanagerscoursecatsdesc'] = 'Selected the course categories that will be processed by the
NED Teacher Tools block on the Moodle frontpage and dashboard.';
$string['progressreport'] = 'Progress Report';
$string['refreshmodefrontpage'] = 'Block refresh mode - Frontpage';
$string['refreshmodecourse'] = 'Block refresh mode - Course';
$string['pageload'] = 'Page load';
$string['cron'] = 'Cron job';
$string['updatecache'] = 'Update Cache';
$string['updatecachewarning'] = 'Would you like to refresh the NED Teacher Tools block?';
$string['lastrefreshtime'] = 'Last refresh: {$a} ago ';
$string['lastrefreshupdating'] = 'Last refresh: Updating ';
$string['lastrefreshrequired'] = 'Last refresh: Update required ';
$string['name'] = 'Name';
$string['blockinfo'] = 'Block info';
$string['atmaxresubmission'] = 'At max resubmission';
$string['moodledefaultview'] = 'Moodle default view';
$string['manualgrading'] = 'Manual Grading';
$string['nograde'] = 'No grade';
$string['includecourses'] = 'Courses to include';
$string['allcourseswithblock'] = 'Only include courses that have the NED Teacher Tools block';
$string['selectedcourses'] = 'Selected courses (below)';
$string['showonlineeditor'] = 'Show online editor';
$string['hideonlineeditor'] = 'Hide online editor';
$string['studentssubmission'] = 'Student\'s Submission';
$string['save'] = 'Save';
$string['teachersfeedback'] = 'Teacher\'s Feedback';
$string['acceptoverride'] = 'Accept override';
$string['removeoverride'] = 'Remove override';
$string['opengradereport'] = 'Open grade report';
$string['help'] = 'Help';
$string['gradeoverridedetected'] = 'Grade override detected';
$string['checkagain'] = 'Check again';
$string['editortoggle'] = 'Online editor toggle';
$string['experimental'] = 'Experimental';
$string['allactivitytypes'] = 'All activity types';
$string['allparticipants'] = 'All participants';
$string['allgroups'] = 'All groups';
$string['manual'] = 'Manual';
$string['refreshnow'] = 'Refresh now';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['includehiddencourses'] = 'Include hidden courses';
$string['adminfrontpage'] = 'Admin view-frontpage';
$string['enrolledcourses'] = 'Enrolled courses';
$string['allcourses'] = 'All courses';
$string['blocksitesettings'] = 'Block site settings';
$string['opensitesettingspage'] = 'Open site settings page';
$string['titlesforlinkclusters'] = 'Titles for link clusters inside block';
$string['markingmanager'] = 'Marking Manager';
$string['quicklinks'] = 'Quick links';
$string['notices'] = 'Notices';
$string['othersettings'] = 'Other settings';
$string['blocktitlesitelevel'] = 'Block title - site level';
$string['blocktitlecourselevel'] = 'Block title - course level';
$string['teachertools'] = 'Teacher Tools';
$string['generalsettings'] = 'General settings';
$string['layoutandformat'] = 'Layout and format';
$string['suspendeduserstoshow'] = 'Suspended users to show';
$string['none'] = 'None';
$string['suspendedenrollments'] = 'Suspended enrollments';
$string['suspendedusers'] = 'Suspended users';
$string['both'] = 'Both';
$string['includeunsubmittedactivities'] = 'Include unsubmitted activities in final grade';
$string['showsuspendedusers'] = 'Show suspended users';
$string['customlinks'] = 'Custom links';
$string['courseresources'] = 'Course resources';
$string['customlinkstitle'] = 'Custom links title';
$string['numberoflinks'] = 'Number of links';
$string['iconcoodes'] = 'Icon codes';
$string['newwindow'] = 'New window';
$string['samewindow'] = 'Same window';
$string['popup'] = 'Pop-up';
$string['iconcode'] = 'Icon code';
$string['title'] = 'Title';
$string['link'] = 'Link';
$string['linkbehaviour'] = 'Link behaviour';
$string['showinstudentmenu'] = 'Also show in Student menu';
$string['minsbeforerefreshrequired'] = 'Minutes before refresh required';
$string['calcnumofactivities'] = 'Calculation the number of activities that require grading...';
$string['courses'] = 'Course(s)';
$string['coursetomonitor'] = 'Course to Monitor';
$string['listexpiredrefreshrequired'] = 'List has expired. Refresh required.';
$string['truncateactivitynames'] = 'Truncate text in activity names';