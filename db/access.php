<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/moodle_survey:view-surveys' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-surveys' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-school' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-school' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-principal' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-principal' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-counsellor' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-counsellor' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-teacher' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-teacher' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-student' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-student' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-school-admin' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
        ),
    ),
    'local/moodle_survey:view-school-admin' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
        ),
    ),
    'local/moodle_survey:assign-course-to-school' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
        ),
    ),
    'local/moodle_survey:assign-course-to-user' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-courses' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-courses' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-my-courses' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-moocs' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-moocs' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'principal' => CAP_ALLOW,
            'counsellor' => CAP_ALLOW,
            'schooladmin' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-mooc-category' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-mooc-category' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:create-course-category' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-course-category' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-quick-access-buttons' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-individual-survey-insights' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:can-assign-survey-to-users' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'counsellor' => CAP_ALLOW,
        ),
    ),
    'local/moodle_survey:view-survey-analysis' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'principal' => CAP_ALLOW,
        ),
    ),
);
