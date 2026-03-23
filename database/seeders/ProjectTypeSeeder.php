<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Website', 'sort_order' => 1],
            ['name' => 'Mobile App', 'sort_order' => 2],
            ['name' => 'Web Application', 'sort_order' => 3],
            ['name' => 'E-commerce', 'sort_order' => 4],
        ];

        foreach ($types as $type) {
            ProjectType::firstOrCreate(
                ['name' => $type['name']],
                ['sort_order' => $type['sort_order'], 'is_active' => true]
            );
        }
    }
}
