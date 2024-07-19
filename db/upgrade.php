<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_moodle_survey_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads the database manager.

    if ($oldversion < 2024071703) {

        // Define table saishiko_surveys to be created.
        $table = new xmldb_table('moodle_survey');

        // Adding fields to table saishiko_surveys.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, 'Primary key');
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '', 'Name of the survey');
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'Description of the survey');
        $table->add_field('status', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'active', 'Status of the survey');

        // Adding keys to table saishiko_surveys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id'], 'Primary key');

        // Conditionally launch create table for saishiko_surveys.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Saishiko Surveys savepoint reached.
        upgrade_plugin_savepoint(true, 2024071703, 'local', 'moodle_survey');
    }

    // if ($oldversion > 2024071703 && $oldversion < 2024071705) {
    //     // Define new column 'status'
    //     $table = new xmldb_table('moodle_survey');
    //     $field = new xmldb_field('status', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'active', 'name');

    //     // Conditionally define the field
    //     if (!$dbman->field_exists($table, $field)) {
    //         $dbman->add_field($table, $field);
    //     }

    //     // Moodle 3.1 and later will keep track of this upgrade automatically
    //     upgrade_plugin_savepoint(true, 2024071704, 'local', 'moodle_survey');
    // }

    return true;
}
