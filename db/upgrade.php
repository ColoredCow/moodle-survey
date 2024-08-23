<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_moodle_survey_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads the database manager.

    if ($oldversion < 2024071709) { // Change condition to check the correct version.

        // Define the table where the new fields will be added.
        $table = new xmldb_table('cc_survey_audience_access'); 

        // Define field 'assigned_to' to be added to 'cc_survey_audience_access'.
        $field1 = new xmldb_field('assigned_to', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Conditionally launch add field 'assigned_to'.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        // Define field 'status' to be added to 'cc_survey_audience_access'.
        $field2 = new xmldb_field('status', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, 'not-assigned');

        // Conditionally launch add field 'status'.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Local plugin savepoint reached.
        upgrade_plugin_savepoint(true, 2024071709, 'local', 'moodle_survey');
    }

    /*
    if ($oldversion > 2024071703 && $oldversion < 2024071705) {
        // Define field 'status' to be added to 'moodle_survey'.
        $table = new xmldb_table('moodle_survey');
        $field = new xmldb_field('status', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'active', 'name');

        // Conditionally launch add field 'status'.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2024071704, 'local', 'moodle_survey');
    }
    */

    return true;
}
