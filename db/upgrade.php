<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_moodle_survey_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads the database manager.

    if ($oldversion < 2024071709) {

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

    if ($oldversion < 2024071714) {

        $table = new xmldb_table('cc_survey_audience_access');
        $field1 = new xmldb_field('student_grade', XMLDB_TYPE_TEXT, null, null, null, null, null);
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        $field2 = new xmldb_field('teacher_grade', XMLDB_TYPE_TEXT, null, null, null, null, null);
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Define table cc_user_grade to be created.
        $table = new xmldb_table('cc_user_grade');

        // Adding fields to table cc_user_grade.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_grade', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('created_at', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('updated_at', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2024071714, 'local', 'moodle_survey');
    }
    if ($oldversion < 2024071715) {
        // Define table cc_user_grade to be created.
        $table = new xmldb_table('cc_user_departments');

        // Adding fields to table cc_user_grade.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_departments', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('created_at', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('updated_at', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2024071715, 'local', 'moodle_survey');
    }

    return true;
}
