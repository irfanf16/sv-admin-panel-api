<?php

namespace App\Libraries;
/**
 *  
 */
class Perm
{

    public static function perm($module_name,$perm)
    {
        $mm_name = str_replace("/", "-",trim($module_name));
        $mm_name = str_replace('&','-',$mm_name);
        $data = [
                    '1' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Projects
                    '2' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Tasks
                    '5' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Clients
                    '9' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Snapshots
                    '11' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Download client
                    '12' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], //Courses
                    '13' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Projects & Tasks
                    '14' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Articles
                    '15' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Courses
                    '16' => [], // Library 
                    '18' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Quizzes
                    '19' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Trainings
                    '22' => [], // Resource Management
                    '25' => [], // Employees
                    '26' => [], // Departments
                    '27' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Shift Scheduling
                    '28' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Break Scheduling
                    '29' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Users / Teams
                    '30' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Users
                    '31' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Teams
                    '37' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Profile and Permissions
                    '40' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Course Assignments
                    '42' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Timesheet
                    '43' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Idle Time Approval
                    '44' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Company Settings
                    '47' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // User Timesheet
                    '48' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Live Dashboard
                    '49' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Dashboard
                    '50' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Monitoring
                    '51' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Web & App Tracking
                    '52' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Team Dashboard
                    '54' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '56' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ],//Reports
                    '57' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // User Time Report
                    '58' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Idle & Manual Time Report
                    '59' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Team Work Report
                    '60' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '61' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Configurations
                    '62' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Project Time Report
                    '63' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // General Settings
                    '64' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Monitoring
                    '65' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Organization
                    '66' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Display Settings
                    '67' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Time Tracking & Snapshots
                    '68' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Web & App Tracking
                    '69' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Idle Time
                    '70' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Off Days Calendar
                    '71' => ['view'], // Course Time Report
                    '72' => ['view'], // Course Time Report
                ];
        
        
        if ( in_array( $perm, $data[$mm_name] ) ) {
            return true;
        }
    }

    public static function manager_perm($module_name,$perm)
    {
        $mm_name = str_replace("/", "-",trim($module_name));
        $mm_name = str_replace('&','-',$mm_name);
        $data = [
                    '1' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Projects
                    '2' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Tasks
                    '5' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Clients
                    '9' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Snapshots
                    '11' => [
                                'add',
                                'edit',
                                'view',
                            ], // Download client
                    '12' => [], //Courses
                    '13' => [
                                'add',
                                'edit',
                                'view',
                            ], // Projects & Tasks
                    '14' => [], // Articles
                    '15' => [], // My Courses
                    '16' => [], // Library 
                    '18' => [], // Quizzes
                    '19' => [], // Trainings
                    '22' => [], // Resource Management
                    '25' => [], // Employees
                    '26' => [], // Departments
                    '27' => [], // Shift Scheduling
                    '28' => [], // Break Scheduling
                    '29' => [
                                'add',
                                'edit',
                                'view',
                            ], // Users / Teams
                    '30' => [], // Users
                    '31' => [
                                'add',
                                'edit',
                                'view',
                            ], // Teams
                    '37' => [], // Profile and Permissions
                    '40' => [], // Course Assignments
                    '42' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Timesheet
                    '43' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Idle Time Approval
                    '44' => [], // Company Settings
                    '47' => [
                                'add',
                                'edit',
                                'view',
                            ], // User Timesheet
                    '48' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Live Dashboard
                    '49' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // My Dashboard
                    '50' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Monitoring
                    '51' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Web & App Tracking
                    '52' => [
                                    'add',
                                    'edit',
                                    'view',
                                ], // Team Dashboard
                    '54' => [], // Reports
                    '56' => [],//Reports
                    '57' => [], // User Time Report
                    '58' => [], // Idle & Manual Time Report
                    '59' => [], // Team Work Report
                    '60' => [], // Reports
                    '61' => [], // Configurations
                    '62' => [], // Project Time Report
                    '63' => [], // General Settings
                    '64' => [], // Monitoring
                    '65' => [], // Organization
                    '66' => [], // Display Settings
                    '67' => [], // Time Tracking & Snapshots
                    '68' => [], // Web & App Tracking
                    '69' => [], // Idle Time
                    '70' => [], // Off Days Calendar
                    '71' => ['view'], // Course Time Report
                    '72' => ['view'], // Course Time Report
                ];
        
        if ( in_array( $perm, $data[$mm_name] ) ) {
            return true;
        }
    }

    public static function viewer_perm($module_name,$perm)
    {
        $mm_name = str_replace("/", "-",trim($module_name));
        $mm_name = str_replace('&','-',$mm_name);
        $data = [
                    '1' => [
                                'view',
                            ], // Projects
                    '2' => [
                                'view',
                            ], // Tasks
                    '5' => [], // Clients
                    '9' => [
                                'view',
                            ], // Snapshots
                    '11' => [], // Download client
                    '12' => [], //Courses
                    '13' => [
                                'view',
                            ], // Projects & Tasks
                    '14' => [], // Articles
                    '15' => [], // My Courses
                    '16' => [], // Library 
                    '18' => [], // Quizzes
                    '19' => [], // Trainings
                    '22' => [], // Resource Management
                    '25' => [], // Employees
                    '26' => [], // Departments
                    '27' => [], // Shift Scheduling
                    '28' => [], // Break Scheduling
                    '29' => [], // Users / Teams
                    '30' => [], // Users
                    '31' => [], // Teams
                    '37' => [], // Profile and Permissions
                    '40' => [], // Course Assignments
                    '42' => [], // Timesheet
                    '43' => [], // Idle Time Approval
                    '44' => [], // Company Settings
                    '47' => [], // User Timesheet
                    '48' => [], // Live Dashboard
                    '49' => [], // My Dashboard
                    '50' => [], // Monitoring
                    '51' => [], // Web & App Tracking
                    '52' => [], // Team Dashboard
                    '54' => [], // Reports
                    '56' => [],//Reports
                    '57' => [], // User Time Report
                    '58' => [], // Idle & Manual Time Report
                    '59' => [], // Team Work Report
                    '60' => [], // Reports
                    '61' => [], // Configurations
                    '62' => [], // Project Time Report
                    '63' => [], // General Settings
                    '64' => [], // Monitoring
                    '65' => [], // Organization
                    '66' => [], // Display Settings
                    '67' => [], // Time Tracking & Snapshots
                    '68' => [], // Web & App Tracking
                    '69' => [], // Idle Time
                    '70' => [], // Off Days Calendar
                    '71' => ['view'], // Course Time Report
                    '72' => ['view'], // Course Time Report
                ];
        
        if ( in_array( $perm, $data[$mm_name] ) ) {
            return true;
        }
    }

    public static function users_perm($module_name,$perm)
    {
        $mm_name = str_replace("/", "-",trim($module_name));
        $mm_name = str_replace('&','-',$mm_name);
        $data = [
                    '1' => [
                                    'view',
                                ], // Projects
                    '2' => [
                                    'view',
                                ], // Tasks
                    '5' => [], // Clients
                    '9' => [], // Snapshots
                    '11' => [
                                'view',
                            ], // Download client
                    '12' => [], //Courses
                    '13' => ['view'], // Projects & Tasks
                    '14' => [], // Articles
                    '15' => [
                                'view',
                            ], // My Courses
                    '16' => [], // Library 
                    '18' => [], // Quizzes
                    '19' => [
                                'view',
                            ], // Trainings
                    '22' => [], // Resource Management
                    '25' => [], // Employees
                    '26' => [], // Departments
                    '27' => [], // Shift Scheduling
                    '28' => [], // Break Scheduling
                    '29' => [], // Users / Teams
                    '30' => [], // Users
                    '31' => [], // Teams
                    '37' => [], // Profile and Permissions
                    '40' => [], // Course Assignments
                    '42' => [], // Timesheet
                    '43' => [], // Idle Time Approval
                    '44' => [], // Company Settings
                    '47' => [], // User Timesheet
                    '48' => [], // Live Dashboard
                    '49' => [
                                'view',
                            ], // My Dashboard
                    '50' => [], // Monitoring
                    '51' => [], // Web & App Tracking
                    '52' => [], // Team Dashboard
                    '54' => [], // Reports
                    '56' => [],//Reports
                    '57' => [], // User Time Report
                    '58' => [], // Idle & Manual Time Report
                    '59' => [], // Team Work Report
                    '60' => [], // Reports
                    '61' => [], // Configurations
                    '62' => [], // Project Time Report
                    '63' => [], // General Settings
                    '64' => [], // Monitoring
                    '65' => [], // Organization
                    '66' => [], // Display Settings
                    '67' => [], // Time Tracking & Snapshots
                    '68' => [], // Web & App Tracking
                    '69' => [], // Idle Time
                    '70' => [], // Off Days Calendar
                    '71' => ['view'], // Course Time Report
                    '72' => ['view'], // Course Time Report
                ];
        
        if ( in_array( $perm, $data[$mm_name] ) ) {
            return true;
        }
    }


}


