<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_moodle_survey_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads the database manager.
    
    return true;
}
