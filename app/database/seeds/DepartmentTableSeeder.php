<?php

class DepartmentTableSeeder extends Seeder 
{
    public function run()
    {
        Department::truncate();
        $departments = ([
            'General Management', 
            'business strategies',
            'Marketing Department',
            'Operations Department',
            'Finance Department',
            'Sales Department',
            'Human Resource Department',
            'Purchase Department'
        ]);

        foreach ($departments as $key => $value) {
            Department::create([
                'name' => $value
            ]);
        }
    }
}