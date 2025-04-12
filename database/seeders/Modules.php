<?php

namespace Database\Seeders;

use App\Models\Company\Module;
use Illuminate\Database\Seeder;

class Modules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array('id' => '1','title' => 'Projects','description' => 'Projects','url' => 'projects/list','icon' => 'projects','parent_module_id' => '13','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '2','title' => 'Tasks','description' => 'Activities','url' => 'activities','icon' => 'Tasks','parent_module_id' => '13','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '5','title' => 'Clients','description' => 'Clients','url' => 'clients','icon' => 'Clients','parent_module_id' => '13','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '9','title' => 'Snapshots','description' => 'Snapshots','url' => 'snapshots','icon' => 'Snapshots','parent_module_id' => '50','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '11','title' => 'Download client','description' => 'Download Setup','url' => 'setup','icon' => 'Download','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '11'),
            array('id' => '12','title' => 'Courses','description' => 'courses list','url' => 'courses/list','icon' => 'Courses','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '13','title' => 'Projects & Tasks','description' => NULL,'url' => '','icon' => 'Projects-and-Task','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '14','title' => 'Articles','description' => 'Articles list here','url' => 'articles/list','icon' => 'Articles','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '4'),
            array('id' => '15','title' => 'My Courses','description' => 'User assigned courses only','url' => 'mycourses/list','icon' => 'MyCourses','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '16','title' => 'Library ','description' => 'User library courses only','url' => 'library/list','icon' => 'Library','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '6'),
            array('id' => '18','title' => 'Quizzes','description' => 'f','url' => 'quiz/list','icon' => 'Quizzes','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '5'),
            array('id' => '19','title' => 'Trainings','description' => 'courses and articles','url' => '','icon' => 'Training','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '22','title' => 'Resource Management','description' => '','url' => '','icon' => 'Employee_management','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '5'),
            array('id' => '24','title' => 'Recruitment','description' => NULL,'url' => NULL,'icon' => 'Recruitment','parent_module_id' => '22','module_type' => '1','deleted_at' => '2022-07-20 07:21:38','created_at' => NULL,'updated_at' => NULL,'module_order' => NULL),
            array('id' => '25','title' => 'Employees','description' => NULL,'url' => 'hiring/list','icon' => 'Hiring','parent_module_id' => '22','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '26','title' => 'Departments','description' => NULL,'url' => 'departments','icon' => 'departments','parent_module_id' => '22','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '27','title' => 'Shift Scheduling','description' => NULL,'url' => 'shifts','icon' => 'Shift_Schedule','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '5'),
            array('id' => '28','title' => 'Break Scheduling','description' => 'Break Scheduling','url' => 'breaks','icon' => 'Break_Schedule','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '6'),
            array('id' => '29','title' => 'Users / Teams ','description' => 'explained','url' => NULL,'icon' => 'multiple-users','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '6'),
            array('id' => '30','title' => 'Users','description' => NULL,'url' => 'users','icon' => 'users','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '31','title' => 'Teams','description' => 'Teams','url' => 'groups','icon' => 'groups','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '37','title' => 'Profile and Permissions','description' => 'Profile and Permissions','url' => 'profiles','icon' => 'profile_permissions','parent_module_id' => '61','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '40','title' => 'Course Assignments','description' => 'explained','url' => 'courses/assignCourse/listing','icon' => 'Course_Assignments','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '42','title' => 'Timesheet','description' => 'TimeSheet','url' => 'reporting/users?tab=today','icon' => 'time_sheet','parent_module_id' => '13','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '8'),
            array('id' => '43','title' => 'Idle Time Approval','description' => 'Idle Time Approval','url' => 'reporting/idleTimeUsers','icon' => 'idle','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '44','title' => 'Company Settings','description' => 'Company','url' => 'company/edit','icon' => 'Company','parent_module_id' => '61','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '45','title' => 'Productivity','description' => 'NULL','url' => 'productivity','icon' => 'productivity','parent_module_id' => '13','module_type' => '3','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '10'),
            array('id' => '47','title' => 'User Timesheet','description' => 'NULL','url' => 'split_time','icon' => 'split_time','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '4'),
            array('id' => '48','title' => 'Live Dashboard','description' => NULL,'url' => 'live-dashboard','icon' => 'Live_Dashboard','parent_module_id' => '50','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '49','title' => 'My Dashboard','description' => NULL,'url' => 'dashboard','icon' => 'my_dashboard_icon','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '50','title' => 'Monitoring','description' => NULL,'url' => 'NULL','icon' => 'new_montiring_icon','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '4'),
            array('id' => '51','title' => 'Web & App Tracking','description' => NULL,'url' => 'web_app_tracking','icon' => 'Web_App_Tracking','parent_module_id' => '50','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '4'),
            array('id' => '52','title' => 'Team Dashboard','description' => NULL,'url' => 'team-dashboard','icon' => 'My_Dashboard','parent_module_id' => '50','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '54','title' => 'Reports','description' => 'Training Reports','url' => 'training-reports','icon' => 'report_icon','parent_module_id' => '19','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '7'),
            array('id' => '56','title' => 'Reports','description' => NULL,'url' => 'projects-reports','icon' => 'report_icon','parent_module_id' => '13','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '12'),
            array('id' => '57','title' => 'User Time Report','description' => NULL,'url' => 'employee-reports/user-time-report','icon' => 'report_icon','parent_module_id' => '60','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '58','title' => 'Idle & Manual Time Report','description' => NULL,'url' => 'employee-reports/idle-manual-time-report','icon' => 'report_icon','parent_module_id' => '60','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '59','title' => 'Team Work Report','description' => NULL,'url' => 'employee-reports/team-work-report','icon' => 'report_icon','parent_module_id' => '60','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '60','title' => 'Reports','description' => NULL,'url' => 'employee-reports','icon' => 'report_icon','parent_module_id' => '29','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '7'),
            array('id' => '61','title' => 'Configurations','description' => 'Configurations','url' => NULL,'icon' => 'Configurations','parent_module_id' => '0','module_type' => '1','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '7'),
            array('id' => '62','title' => 'Project Time Report','description' => NULL,'url' => 'projects-reports','icon' => 'report_icon','parent_module_id' => '56','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '63','title' => 'General Settings','description' => 'General Settings','url' => '-','icon' => 'Company','parent_module_id' => '44','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '64','title' => 'Monitoring','description' => 'Monitoring','url' => '-','icon' => 'Company','parent_module_id' => '44','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '65','title' => 'Organization','description' => 'Organization','url' => '-','icon' => 'Company','parent_module_id' => '63','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '66','title' => 'Display Settings','description' => 'Display Settings','url' => '-','icon' => 'Company','parent_module_id' => '63','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '67','title' => 'Time Tracking & Snapshots','description' => 'Monitoring','url' => '-','icon' => 'Company','parent_module_id' => '64','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '1'),
            array('id' => '68','title' => 'Web & App Tracking','description' => 'Monitoring','url' => '-','icon' => 'Company','parent_module_id' => '64','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '2'),
            array('id' => '69','title' => 'Idle Time','description' => 'Monitoring','url' => '-','icon' => 'Company','parent_module_id' => '64','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '3'),
            array('id' => '70','title' => 'Off Days Calendar','description' => 'Monitoring','url' => '-','icon' => 'Company','parent_module_id' => '64','module_type' => '2','deleted_at' => NULL,'created_at' => NULL,'updated_at' => NULL,'module_order' => '4')
          );

        Module::insert($modules);
    }
}
