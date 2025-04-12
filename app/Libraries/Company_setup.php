<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
/**
 *  
 */
class Company_setup
{

    public static function perm($company_setup=false)
    {
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
                    '22' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Resource Management
                    '25' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Employees
                    '26' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Departments
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
                    '71' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Course Time Report
                    '72' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Course Performance Report
                    '73' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Assignments
                    '74' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Tasks
                    '75' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Timesheet
                    '76' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // My Snapshots
                    '77' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Scheduling Management
                    '78' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '79' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '80' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '81' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '82' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '83' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ], // Reports
                    '84' => [
                                'add',
                                'edit',
                                'view',
                                'delete',
                            ] // Reports
                ];
        
        if ( $company_setup == true ) {
            return $data;
        }
    }

    public static function manager_perm($company_setup=false)
    {
        $data = [
                    '1' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects
                    '2' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Tasks
                    '5' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Clients
                    '9' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Snapshots
                    '11' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Download client
                    '12' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], //Courses
                    '13' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects & Tasks
                    '14' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Articles
                    '15' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Courses
                    // '16' => [], // Library 
                    '18' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Quizzes
                    '19' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Trainings
                    '22' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Resource Management
                    '25' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Employees
                    '26' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Departments
                    '27' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Shift Scheduling
                    '28' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Break Scheduling
                    '29' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users / Teams
                    '30' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users
                    '31' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Teams
                    '37' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Profile and Permissions
                    '40' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Assignments
                    '42' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Timesheet
                    '43' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time Approval
                    '44' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Company Settings
                    '47' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Timesheet
                    '48' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Live Dashboard
                    '49' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Dashboard
                    '50' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '51' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '52' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Dashboard
                    '54' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '56' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],//Reports
                    '57' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Time Report
                    '58' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle & Manual Time Report
                    '59' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Work Report
                    '60' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '61' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Configurations
                    '62' => [
                                // 'add',
                                // 'edit',
                                // 'view',
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Project Time Report
                    '63' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // General Settings
                    '64' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '65' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Organization
                    '66' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Display Settings
                    '67' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Time Tracking & Snapshots
                    '68' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '69' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time
                    '70' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Off Days Calendar
                    '71' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Time Report
                    '72' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Performance Report
                    '73' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Assignments
                    '74' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Tasks
                    '75' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Timesheet
                    '76' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Snapshots
                    '77' => [
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Scheduling Management
                    '78' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '79' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '80' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '82' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '83' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '84' => [
                                [ 'add' => 1,'status' => 0 ],
                                [ 'add' => 2 ,'status' => 0 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                ];
        
        if ( $company_setup == true ) {
            return $data;
        }
        
    }

    public static function viewer_perm($company_setup=false)
    {
        $data = [
                    '1' => [
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects
                    '2' => [
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Tasks
                    '5' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Clients
                    '9' => [
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Snapshots
                    '11' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Download client
                    '12' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], //Courses
                    '13' => [
                                // 'view',
                                [ 'add' => 1,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects & Tasks
                    '14' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Articles
                    '15' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Courses
                    '16' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Library 
                    '18' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Quizzes
                    '19' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Trainings
                    '22' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Resource Management
                    '25' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Employees
                    '26' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Departments
                    '27' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Shift Scheduling
                    '28' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Break Scheduling
                    '29' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users / Teams
                    '30' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users
                    '31' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Teams
                    '37' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Profile and Permissions
                    '40' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Assignments
                    '42' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Timesheet
                    '43' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time Approval
                    '44' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Company Settings
                    '47' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Timesheet
                    '48' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Live Dashboard
                    '49' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Dashboard
                    '50' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '51' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '52' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Dashboard
                    '54' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '56' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],//Reports
                    '57' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Time Report
                    '58' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle & Manual Time Report
                    '59' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Work Report
                    '60' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '61' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Configurations
                    '62' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Project Time Report
                    '63' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // General Settings
                    '64' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '65' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Organization
                    '66' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Display Settings
                    '67' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Time Tracking & Snapshots
                    '68' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '69' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time
                    '70' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Off Days Calendar
                    '71' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Time Report
                    '72' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Performance Report
                    '73' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Assignments
                    '74' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Tasks
                    '75' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Timesheet
                    '76' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Snapshots
                    '77' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Scheduling Management
                    '78' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '79' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '80' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '82' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '83' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '84' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                ];
        
        if ( $company_setup == true ) {
            return $data;
        }
        
    }

    public static function users_perm($company_setup=false)
    {
        $data = [
                    '1' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects
                    '2' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Tasks
                    '5' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Clients
                    '9' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Snapshots
                    '11' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Download client
                    '12' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], //Courses
                    '13' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Projects & Tasks
                    '14' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Articles
                    '15' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Courses
                    '16' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Library 
                    '18' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Quizzes
                    '19' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Trainings
                    '22' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Resource Management
                    '25' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Employees
                    '26' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Departments
                    '27' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Shift Scheduling
                    '28' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Break Scheduling
                    '29' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users / Teams
                    '30' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Users
                    '31' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Teams
                    '37' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Profile and Permissions
                    '40' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Assignments
                    '42' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Timesheet
                    '43' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time Approval
                    '44' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Company Settings
                    '47' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Timesheet
                    '48' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Live Dashboard
                    '49' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Dashboard
                    '50' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '51' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '52' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Dashboard
                    '54' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '56' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],//Reports
                    '57' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // User Time Report
                    '58' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle & Manual Time Report
                    '59' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Team Work Report
                    '60' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '61' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Configurations
                    '62' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Project Time Report
                    '63' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // General Settings
                    '64' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Monitoring
                    '65' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Organization
                    '66' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Display Settings
                    '67' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Time Tracking & Snapshots
                    '68' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Web & App Tracking
                    '69' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Idle Time
                    '70' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Off Days Calendar
                    '71' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Time Report
                    '72' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Course Performance Report
                    '73' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Assignments
                    '74' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Tasks
                    '75' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Timesheet
                    '76' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // My Snapshots
                    '77' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Scheduling Management
                    '78' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 0 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '79' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                    '80' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '82' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '83' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ],
                    '84' => [
                                [ 'add' => 1 ,'status' => 1 ],
                                [ 'add' => 2 ,'status' => 1 ],
                                [ 'add' => 3 ,'status' => 1 ],
                                [ 'add' => 4 ,'status' => 1 ],
                            ], // Reports
                ];
        
        if ( $company_setup == true ) {
            return $data;
        }
        
    }

    public static function insert_perm($profile_id,$profile_type,$company_setup)
    {
        $result = [];
        switch($profile_type)
        {

            case 2:
                    $perm = self::manager_perm($company_setup);
                    foreach( $perm as $p => $v ) {
                        foreach( $v as $vv => $ss ) {
                            $result[] = [
                                            'module_id' => $p,
                                            'permission_id' => $ss['add'],
                                            'profile_id'  =>  $profile_id,
                                            'status'  => $ss['status'],
                                            'permanent_disabled' => 0,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                        }
                    }
                break;

            case 3:
                    $perm = self::viewer_perm($company_setup);
                    foreach( $perm as $p => $v ) {
                        foreach( $v as $vv => $ss ) {
                            $result[] = [
                                            'module_id' => $p,
                                            'permission_id' => $ss['add'],
                                            'profile_id'  =>  $profile_id,
                                            'status'  => $ss['status'],
                                            'permanent_disabled' => 0,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                        }
                    }
                break;

            case 4:
                    $perm = self::users_perm($company_setup);
                    foreach( $perm as $p => $v ) {
                        foreach( $v as $vv => $ss ) {
                            $result[] = [
                                            'module_id' => $p,
                                            'permission_id' => $ss['add'],
                                            'profile_id'  =>  $profile_id,
                                            'status'  => $ss['status'],
                                            'permanent_disabled' => 0,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                        }
                    }
                break;

            default:
                    $perm = self::perm($company_setup);
                    foreach( $perm as $p => $v ) {
                        foreach( $v as $vv ) {
                            if ( $vv == 'add') { 
                                $permission = 1;
                            } elseif ( $vv == 'edit') { 
                                $permission = 2;
                            } elseif ( $vv == 'view') { 
                                $permission = 3;
                            } elseif ( $vv == 'delete') { 
                                $permission = 4;
                            }
                            $result[] = [
                                            'module_id' => $p,
                                            'permission_id' => $permission,
                                            'profile_id'  =>  $profile_id,
                                            'status'  => 0,
                                            'permanent_disabled' => 0,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]; 
                        }
                    }
                break;
        
        }
        return $result;
    }

}


