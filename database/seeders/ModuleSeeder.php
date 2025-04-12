<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('modules')->truncate();
        // DB::table('modules')->insert($this->getModules());
        $this->getModules();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getModules()
    {
        $modules = [
            [
                'id' => '49',
                'title' => 'My Dashboard',
                'description' => NULL,
                'url' => 'dashboard',
                'icon' => 'my_dashboard_icon',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Personalize Dashboard',
                            'key' => $this->toSnakeCase('Personalize Dashboard'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '73',
                'title' => 'My Assignments',
                'description' => NULL,
                'url' => 'my-assignments',
                'icon' => 'my_Assignments_icon',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '15',
                'title' => 'My Courses',
                'description' => 'User assigned courses only',
                'url' => 'mycourses/list',
                'icon' => 'MyCourses',
                'parent_module_id' => '73',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on My Courses',
                            'key' => $this->toSnakeCase('Access on Projects'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of assigned courses',
                            'key' => $this->toSnakeCase('Limit the number of assigned courses'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '74',
                'title' => 'My Tasks',
                'description' => 'User assigned task only',
                'url' => 'mytask/list',
                'icon' => 'myTasks',
                'parent_module_id' => '73',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Tasks',
                            'key' => $this->toSnakeCase('Access on Tasks'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of assigned Tasks',
                            'key' => $this->toSnakeCase('Limit the number of assigned Tasks'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],

                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '76',
                'title' => 'My Snapshots',
                'description' => 'User Snapshots only',
                'url' => 'mysnapshots',
                'icon' => 'mySnapshots',
                'parent_module_id' => '73',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Snapshots',
                            'key' => $this->toSnakeCase('Access on Snapshots'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '75',
                'title' => 'My Timesheet',
                'description' => 'User Timesheet only',
                'url' => 'mytimesheet',
                'icon' => 'myTimesheet',
                'parent_module_id' => '73',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Tasks',
                            'key' => $this->toSnakeCase('Access on Tasks'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => '# of Days Allowed for a manual time entry',
                            'key' => $this->toSnakeCase('# of Days Allowed for a manual time entry'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '19',
                'title' => 'Trainings',
                'description' => 'courses and articles',
                'url' => '',
                'icon' => 'Training',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '12',
                'title' => 'Courses',
                'description' => 'courses list',
                'url' => 'courses/list',
                'icon' => 'Courses',
                'parent_module_id' => '19',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Courses',
                            'key' => $this->toSnakeCase('Access on Courses'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Access on completion required',
                            'key' => $this->toSnakeCase('Access on completion required'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Access on force order',
                            'key' => $this->toSnakeCase('Access on force order'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of assigned courses',
                            'key' => $this->toSnakeCase('Limit the number of assigned courses'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the number of assigned articles of a courses',
                            'key' => $this->toSnakeCase('Limit the number of assigned articles of a courses'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the number of assigned Quizzes of a courses',
                            'key' => $this->toSnakeCase('Limit the number of assigned Quizzes of a courses'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'limit of storage of gallery in courses',
                            'key' => $this->toSnakeCase('limit of storage of gallery in courses'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '18',
                'title' => 'Quizzes',
                'description' => 'f',
                'url' => 'quiz/list',
                'icon' => 'Quizzes',
                'parent_module_id' => '19',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Quizzes',
                            'key' => $this->toSnakeCase('Access on Quizzes'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Quizzes',
                            'key' => $this->toSnakeCase('Limit the number of Quizzes'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the size of attachments of a quiz',
                            'key' => $this->toSnakeCase('Limit the size of attachments of a quiz'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the total Number of attachments of a quiz',
                            'key' => $this->toSnakeCase('Limit the total Number of attachments of a quiz'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '14',
                'title' => 'Articles',
                'description' => 'Articles list here',
                'url' => 'articles/list',
                'icon' => 'Articles',
                'parent_module_id' => '19',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Articles',
                            'key' => $this->toSnakeCase('Access on Articles'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number pages of Articles',
                            'key' => $this->toSnakeCase('Limit the number pages of Articles'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the size of attachments of a Article',
                            'key' => $this->toSnakeCase('Limit the size of attachments of a Article'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the total Number of attachments of a Article',
                            'key' => $this->toSnakeCase('Limit the total Number of attachments of a Article'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],

                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '13',
                'title' => 'Projects & Tasks',
                'description' => NULL,
                'url' => '',
                'icon' => 'Projects-and-Task',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '1',
                'title' => 'Projects',
                'description' => 'Projects',
                'url' => 'projects/list',
                'icon' => 'projects',
                'parent_module_id' => '13',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Projects',
                            'key' => $this->toSnakeCase('Access on Projects'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Projects',
                            'key' => $this->toSnakeCase('Limit the number of Projects'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the number of Members of a Projects',
                            'key' => $this->toSnakeCase('Limit the number of Members of a Projects'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field'),
                            ],
                        ],
                        [
                            'title' => 'Limit the number of Tasks of a Projects',
                            'key' => $this->toSnakeCase('Limit the number of Tasks of a Projects'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field'),
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '2',
                'title' => 'Tasks',
                'description' => 'Activities',
                'url' => 'activities',
                'icon' => 'Tasks',
                'parent_module_id' => '13',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Tasks',
                            'key' => $this->toSnakeCase('Access on Tasks'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number pages of Tasks',
                            'key' => $this->toSnakeCase('Limit the number pages of Tasks'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'Limit the number of Assignees of a Tasks',
                            'key' => $this->toSnakeCase('Limit the number of Assignees of a Tasks'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '5',
                'title' => 'Clients',
                'description' => 'Clients',
                'url' => 'clients',
                'icon' => 'Clients',
                'parent_module_id' => '13',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Clients',
                            'key' => $this->toSnakeCase('Access on Clients'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Clients',
                            'key' => $this->toSnakeCase('Limit the number of Clients'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '50',
                'title' => 'Monitoring',
                'description' => NULL,
                'url' => 'NULL',
                'icon' => 'new_montiring_icon',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '5',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '48',
                'title' => 'Live Dashboard',
                'description' => NULL,
                'url' => 'live-dashboard',
                'icon' => 'Live_Dashboard',
                'parent_module_id' => '50',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Live Dashboard',
                            'key' => $this->toSnakeCase('Access on Live Dashboard'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '52',
                'title' => 'Team Dashboard',
                'description' => NULL,
                'url' => 'team-dashboard',
                'icon' => 'My_Dashboard',
                'parent_module_id' => '50',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'status' => 0,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Team Dashboard',
                            'key' => $this->toSnakeCase('Access on Team Dashboard'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '47',
                'title' => 'Timesheet',
                'description' => 'NULL',
                'url' => 'split_time',
                'icon' => 'split_time',
                'parent_module_id' => '50',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Timesheet',
                            'key' => $this->toSnakeCase('Access on Timesheet'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '9',
                'title' => 'Snapshots',
                'description' => 'Snapshots',
                'url' => 'snapshots',
                'icon' => 'Snapshots',
                'parent_module_id' => '50',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Timesheet',
                            'key' => $this->toSnakeCase('Access on Timesheet'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '22',
                'title' => 'Resource Management',
                'description' => '',
                'url' => '',
                'icon' => 'Employee_management',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '6',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '25',
                'title' => 'Employees',
                'description' => NULL,
                'url' => 'hiring/list',
                'icon' => 'Hiring',
                'parent_module_id' => '22',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Employee',
                            'key' => $this->toSnakeCase('Access on Employee'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Employees',
                            'key' => $this->toSnakeCase('Limit the number of Employees'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '26',
                'title' => 'Departments',
                'description' => NULL,
                'url' => 'departments',
                'icon' => 'departments',
                'parent_module_id' => '22',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access to Employees',
                            'key' => $this->toSnakeCase('Access to Employees'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Department',
                            'key' => $this->toSnakeCase('Limit the number of Department'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '82',
                'title' => 'Idle Time Approval',
                'description' => NULL,
                'url' => 'idle-time-approval',
                'icon' => 'idle_time_approval',
                'parent_module_id' => '22',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 1,
                'status' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '31',
                'title' => 'Teams',
                'description' => 'Teams',
                'url' => 'groups',
                'icon' => 'groups',
                'parent_module_id' => '22',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'status' => 0,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access to Teams',
                            'key' => $this->toSnakeCase('Access to Teams'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Teams',
                            'key' => $this->toSnakeCase('Limit the number of Teams'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '77',
                'title' => 'Scheduling Management',
                'description' => 'Scheduling Management',
                'url' => '',
                'icon' => 'Scheduling_Management',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '7',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '27',
                'title' => 'Shift Scheduling',
                'description' => NULL,
                'url' => 'shifts',
                'icon' => 'Shift_Schedule',
                'parent_module_id' => '77',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access to Shift Scheduling',
                            'key' => $this->toSnakeCase('Access to Shift Scheduling'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Shift Scheduling',
                            'key' => $this->toSnakeCase('Limit the number of Shift Scheduling'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'allow to Configure multiple breaks',
                            'key' => $this->toSnakeCase('allow to Configure multiple breaks'),
                            'values' => [
                                // $this->toSnakeCase('# input field'),
                                $this->toSnakeCase('1'), // for  allow
                                $this->toSnakeCase('2'), // for not allow

                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '28',
                'title' => 'Break Scheduling',
                'description' => 'Break Scheduling',
                'url' => 'breaks',
                'icon' => 'Break_Schedule',
                'parent_module_id' => '77',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access to Break Scheduling',
                            'key' => $this->toSnakeCase('Access to Break Scheduling'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the number of Break Scheduling',
                            'key' => $this->toSnakeCase('Limit the number of Break Scheduling'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                        [
                            'title' => 'allow to Configure multiple breaks',
                            'key' => $this->toSnakeCase('allow to Configure multiple breaks'),
                            'values' => [
                                // $this->toSnakeCase('# input field'),
                                $this->toSnakeCase('1'), // for  allow
                                $this->toSnakeCase('2'), // for not allow

                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '78',
                'title' => 'Reports',
                'description' => NULL,
                'url' => 'module-reports',
                'icon' => 'Reports',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '8',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '71',
                'title' => 'Course Time Report',
                'description' => 'Training Reports',
                'url' => 'module-reports/course-time-reports',
                'icon' => 'course_time_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Course Time',
                            'key' => $this->toSnakeCase('Access on Course Time'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 19,
            ],
            [
                'id' => '72',
                'title' => 'Course Performance Report',
                'description' => 'Training Reports',
                'url' => 'module-reports/course-performance-reports',
                'icon' => 'course_performance_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Course Performance',
                            'key' => $this->toSnakeCase('Access on Course Performance'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 19,
            ],
            [
                'id' => '62',
                'title' => 'Project Time Report',
                'description' => NULL,
                'url' => 'module-reports/projects-time-report',
                'icon' => 'project_time_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '3',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Project Time',
                            'key' => $this->toSnakeCase('Access on Project Time'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '57',
                'title' => 'User Time Report',
                'description' => NULL,
                'url' => 'module-reports/user-time-report',
                'icon' => 'user_time_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on User Time',
                            'key' => $this->toSnakeCase('Access on User Time'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '58',
                'title' => 'Idle & Manual Time Report',
                'description' => NULL,
                'url' => 'module-reports/idle-manual-time-report',
                'icon' => 'idle_manual_time_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '5',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Idle & Manual Time',
                            'key' => $this->toSnakeCase('Access on Idle & Manual Time'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '59',
                'title' => 'Team Work Report',
                'description' => NULL,
                'url' => 'module-reports/team-work-report',
                'icon' => 'team_work_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '6',
                'module_type' => 1,
                'status' => 0,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Team Work',
                            'key' => $this->toSnakeCase('Access on Team Work'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '51',
                'title' => 'Web & App Tracking',
                'description' => NULL,
                'url' => 'web_app_tracking',
                'icon' => 'Web_App_Tracking',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '6',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Web & App Tracking',
                            'key' => $this->toSnakeCase('Access on Web & App Tracking'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '83',
                'title' => 'User Productivity',
                'description' => NULL,
                'url' => 'productive-report',
                'icon' => 'productive_report',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '7',
                'module_type' => 1,
                'status' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '79',
                'title' => 'Short Time Report',
                'description' => NULL,
                'url' => 'module-reports/short-time-report',
                'icon' => 'short_time_report',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '8',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Team Work',
                            'key' => $this->toSnakeCase('Access on Team Work'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '80',
                'title' => 'Over Time Report',
                'description' => NULL,
                'url' => 'module-reports/over-time-report',
                'icon' => 'over_time_report',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '9',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Team Work',
                            'key' => $this->toSnakeCase('Access on Team Work'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '84',
                'title' => 'Work Day Summary',
                'description' => NULL,
                'url' => 'module-reports/work-day-reports',
                'icon' => 'work_day_report_icon',
                'parent_module_id' => '78',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '10',
                'module_type' => 1,
                'status' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Work Day Summary',
                            'key' => $this->toSnakeCase('Access on Work Day Summary'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 13,
            ],
            [
                'id' => '61',
                'title' => 'Configurations',
                'description' => 'Configurations',
                'url' => NULL,
                'icon' => 'Configurations',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '9',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '44',
                'title' => 'Company Settings',
                'description' => 'Company',
                'url' => 'company/edit',
                'icon' => 'Company',
                'parent_module_id' => '61',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],

            [
                'id' => '85',
                'title' => 'Attendance',
                'description' => 'Attendance',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '44',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 2,
                'rules' => json_encode([

                    'info' => [
                        [
                            'title' => 'Access on attendance',
                            'key' => $this->toSnakeCase('Access on attendance'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Allow to access attendance',
                            'key' => $this->toSnakeCase('Allow to access attendance'),
                            'values' => [
                                $this->toSnakeCase('1'), // 1 for enable
                                $this->toSnakeCase('2'), // 2 for disable

                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '63',
                'title' => 'General Settings',
                'description' => 'General Settings',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '44',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 2,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '65',
                'title' => 'Organization',
                'description' => 'Organization',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '63',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 2,
                'rules' => json_encode([

                    'info' => [
                        [
                            'title' => 'Access on Company',
                            'key' => $this->toSnakeCase('Access on Company'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Allow to configure timezone',
                            'key' => $this->toSnakeCase('allow to configure timezone'),
                            'values' => [
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2'),

                            ],
                        ],
                        [
                            'title' => 'Allow to configure billing currency',
                            'key' => $this->toSnakeCase('allow to configure billing currency'),
                            'values' => [
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2'),
                            ],
                        ],
                        [
                            'title' => 'Allow to configure weekend days',
                            'key' => $this->toSnakeCase('allow to configure weekend days'),
                            'values' => [
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2'),
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '66',
                'title' => 'Display Settings',
                'description' => 'Display Settings',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '63',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 2,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Themes',
                            'key' => $this->toSnakeCase('Access on Themes'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Allow to configure themes',
                            'key' => $this->toSnakeCase('allow to configure themes'),
                            'values' => [
                                $this->toSnakeCase('# input field'),
                                // $this->toSnakeCase('1'),
                                // $this->toSnakeCase('2')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '64',
                'title' => 'Timesheet/Tracking Settings',//'Monitoring',
                'description' => 'Monitoring',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '44',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 2,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '67',
                'title' => 'Time Tracking',
                'description' => 'Monitoring',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '64',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '1',
                'module_type' => 2,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Time Tracking',
                            'key' => $this->toSnakeCase('Access on Time Tracking'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Allow access of Auto tracking',
                            'key' => $this->toSnakeCase('Allow access of Auto tracking'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Allow access of Timesheet',
                            'key' => $this->toSnakeCase('Allow access of Timesheet'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Allow access of Web & App Tracking',
                            'key' => $this->toSnakeCase('Allow access of Web & App Tracking'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Allow access of Capture Screen',
                            'key' => $this->toSnakeCase('Allow access of Capture Screen'),
                            'values' => '',
                        ],
                        [
                            'title' => 'Allow access of Idle Time',
                            'key' => $this->toSnakeCase('Allow access of Idle Time'),
                            'values' => '',
                        ],
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the Duration of Capture Screen ( # Min )',
                            'key' => $this->toSnakeCase('Limit the Duration of Capture Screen'),
                            'values' => [
                                '10',
                                '20',
                                '30',
                            ],
                        ],
                        [
                            'title' => 'Limit the  Frequency of Capture Screen',
                            'key' => $this->toSnakeCase('Limit the  Frequency of Capture Screen'),
                            'values' => [
                                '1',
                                '2',
                                '3',
                                '4',
                                '5',
                                '6',
                                '7',
                                '8',
                                '9',
                                '10',
                                '11',
                                '12',
                            ],
                        ],
                        [
                            'title' => 'Limit the  Duration of Idle Time ( # Min )',
                            'key' => $this->toSnakeCase('Limit the  Duration of Idle Time'),
                            'values' => [
                                '10',
                                '20',
                                '30',
                            ],
                        ],
                        [
                            'title' => 'Limit the  Approval of Idle Time',
                            'key' => $this->toSnakeCase('Limit the  Approval of Idle Time'),
                            'values' => [
                                'accept',
                                'reject',
                                'both',
                                'all',
                            ],
                        ],
                        [
                            'title' => 'Limit the  Idle to inactive of Idle Time',
                            'key' => $this->toSnakeCase('Limit the  Idle to inactive of Idle Time'),
                            'values' => [
                                $this->toSnakeCase('0'),
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2'),
                                $this->toSnakeCase('3'),
                                $this->toSnakeCase('4'),
                                $this->toSnakeCase('5'),
                                $this->toSnakeCase('6'),
                                $this->toSnakeCase('7'),
                                $this->toSnakeCase('8'),
                                $this->toSnakeCase('9'),
                                $this->toSnakeCase('10'),
                                $this->toSnakeCase('11'),
                                $this->toSnakeCase('12'),
                                $this->toSnakeCase('13'),
                                $this->toSnakeCase('14'),
                                $this->toSnakeCase('15'),
                                $this->toSnakeCase('16'),
                                $this->toSnakeCase('17'),
                                $this->toSnakeCase('18'),
                            ],
                        ],
                        [
                            'title' => 'Allow to Limit tracking mode',
                            'key' => $this->toSnakeCase('allow to Limit tracking mode'),
                            'values' => [
                                $this->toSnakeCase('1'), //for auto tracking
                                $this->toSnakeCase('2'), // for timesheet
                                $this->toSnakeCase('3'), // for timesheet
                            ],
                        ],
                        [
                            'title' => 'Allow to Limit web & app tracking',
                            'key' => $this->toSnakeCase('enable_web_app_tracking'),
                            'values' => [
                                $this->toSnakeCase('1'), //for activated
                                $this->toSnakeCase('2'), // for deactivated
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '68',
                'title' => 'Others',
                'description' => 'Monitoring',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '64',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 2,
                'status' => 0,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Online/Offline Storage',
                            'key' => $this->toSnakeCase('Access on Online/Offline Storage'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the storage of local data ( # Hours )',
                            'key' => $this->toSnakeCase('Limit the storage of local data'),
                            'values' => [
                                $this->toSnakeCase('0'),
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2'),
                                $this->toSnakeCase('3'),
                                $this->toSnakeCase('4'),
                                $this->toSnakeCase('5'),
                                $this->toSnakeCase('6'),
                                $this->toSnakeCase('7'),
                                $this->toSnakeCase('8'),
                                $this->toSnakeCase('9'),
                                $this->toSnakeCase('10'),
                                $this->toSnakeCase('11'),
                                $this->toSnakeCase('12'),
                                $this->toSnakeCase('13'),
                                $this->toSnakeCase('14'),
                                $this->toSnakeCase('15'),
                                $this->toSnakeCase('16'),
                                $this->toSnakeCase('17'),
                                $this->toSnakeCase('18'),
                            ],
                        ],
                        [
                            'title' => 'Limit the Modification of Time entry ( # Days )',
                            'key' => $this->toSnakeCase('Limit the Modification of Time entry'),
                            'values' => [
                                $this->toSnakeCase('0'),
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('3'),
                                $this->toSnakeCase('7'),
                                $this->toSnakeCase('15'),
                                $this->toSnakeCase('30'),
                                $this->toSnakeCase('45'),
                                $this->toSnakeCase('60'),
                                $this->toSnakeCase('90'),
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            // array(
            //         'id' => '69',
            //         'title' => 'Idle Time',
            //         'description' => 'Monitoring',
            //         'url' => '-',
            //         'icon' => 'Company',
            //         'parent_module_id' => '64',
            //         'deleted_at' => NULL,
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'updated_at' => date('Y-m-d H:i:s'),
            //         'module_order' => '3',
            //         'module_type' => 2,
            //         'rules' => json_encode([
            //             'info' => [],
            //             'implementation' => [],
            //         ]),
            //          'dependent_module_id' => 0,
            //     ),
            [
                'id' => '70',
                'title' => 'Off Days Calendar',
                'description' => 'Monitoring',
                'url' => '-',
                'icon' => 'Company',
                'parent_module_id' => '63',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '4',
                'module_type' => 2,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Holidays Calendar',
                            'key' => $this->toSnakeCase('Access on Holidays Calendar'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'allow to configure off days calendar',
                            'key' => $this->toSnakeCase('allow to configure off days calendar'),
                            'values' => [
                                $this->toSnakeCase('1'),
                                $this->toSnakeCase('2')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '37',
                'title' => 'Profile and Permissions',
                'description' => 'Profile and Permissions',
                'url' => 'profiles',
                'icon' => 'profile_permissions',
                'parent_module_id' => '61',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '2',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Profile and Permissions',
                            'key' => $this->toSnakeCase('Access on Profile and Permissions'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [
                        [
                            'title' => 'Limit the # of Profiles',
                            'key' => $this->toSnakeCase('Limit the # of Profiles'),
                            'values' => [
                                $this->toSnakeCase('Unlimited'),
                                $this->toSnakeCase('None'),
                                $this->toSnakeCase('# input field')
                            ],
                        ],
                    ],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '11',
                'title' => 'Download client',
                'description' => 'Download Setup',
                'url' => 'setup',
                'icon' => 'Download',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '11',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [
                        [
                            'title' => 'Access on Download Client',
                            'key' => $this->toSnakeCase('Access on Download Client'),
                            'values' => '',
                        ]
                    ],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
            [
                'id' => '81',
                'title' => 'Billing',
                'description' => 'Billing',
                'url' => 'module-billing/billing',
                'icon' => 'module_billing_icon',
                'parent_module_id' => '0',
                'deleted_at' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'module_order' => '10',
                'module_type' => 1,
                'rules' => json_encode([
                    'info' => [],
                    'implementation' => [],
                ]),
                'dependent_module_id' => 0,
            ],
        ];
        if ( is_array($modules) && count($modules) > 0 ) {
            foreach( $modules as $mod ) {
                $this->update_module_data($mod['id'],$mod);
            }
        }
        return $modules;
    }

    private function update_module_data($id=0,$data=[])
    {
        if ( $id > 0 && !empty($this->get_module_id($id)) ) {
            $update_record = [
                                'title' => $data['title'],
                                'description' => $data['description'],
                                'url' => $data['url'],
                                'icon' => $data['icon'],
                                'parent_module_id' => $data['parent_module_id'],
                                'deleted_at' => $data['deleted_at'],
                                'created_at' => $data['created_at'],
                                'updated_at' => $data['updated_at'],
                                'module_order' => $data['module_order'],
                                'module_type' => $data['module_type'],
                                'status' => isset($data['status']) ? $data['status'] : 1,
                                'rules' => $data['rules'],
                                'dependent_module_id' => $data['dependent_module_id'],
                            ];
            DB::table('modules')->where('id', $id)->update($update_record);
        } else {
            DB::table('modules')->insert($data);
        }
    }

    private function get_module_id($id)
    {
        return DB::table('modules')->where('id',$id)->first();
    }

    private function toSnakeCase($inputString)
    {
        // Replace spaces and special characters with underscores
        $snakeCaseString = preg_replace('/[^A-Za-z0-9]+/', '_', $inputString);
        // Convert to lowercase
        $snakeCaseString = strtolower($snakeCaseString);
        // Remove leading and trailing underscores
        $snakeCaseString = trim($snakeCaseString, '_');
        return $snakeCaseString;
    }
}

