<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_moodle_survey
 * @category    admin
 * @copyright   2024 ColoredCow 
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('root', new admin_category('moodle_survey', get_string('pluginname', 'local_moodle_survey')));
	
	$ADMIN->add('moodle_survey', new admin_externalpage('manage_survey', get_string('managesurvey', 'local_moodle_survey'),
                 new moodle_url('/local/moodle_survey/manage_survey.php')));

    $ADMIN->add('moodle_survey', new admin_externalpage('create_survey', get_string('createsurvey', 'local_moodle_survey'),
                 new moodle_url('/local/moodle_survey/create_survey.php')));

    // $ADMIN->add('root', new admin_category('moodle_survey', get_string('pluginname', 'Saishiko Surveys')));

    // $settings = new admin_settingpage('saishiko_survey_settings', 'Saishiko Surveys Settings');

    // $settings->add(new admin_externalpage('moodle_survey/manage', '', 
    //     html_writer::link(new moodle_url('/local/moodle_survey/manage_survey.php'), get_string('managesurvey', 'Manage Surveys'))));

    // $settings->add(new admin_setting_heading('moodle_survey/create', '', 
    //     html_writer::link(new moodle_url('/local/moodle_survey/create_survey.php'), get_string('createsurvey', 'Create Survey'))));

    // $ADMIN->add('moodle_survey', $settings);
}
