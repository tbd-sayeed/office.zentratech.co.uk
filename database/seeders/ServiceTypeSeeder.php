<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Web Design', 'form_section' => 'project_based', 'sort_order' => 1],
            ['name' => 'Web Development', 'form_section' => 'project_based', 'sort_order' => 2],
            ['name' => 'Mobile App Development', 'form_section' => 'project_based', 'sort_order' => 3],
            ['name' => 'E-Commerce Solutions', 'form_section' => 'project_based', 'sort_order' => 4],
            ['name' => 'Graphic Design', 'form_section' => 'project_based', 'sort_order' => 5],
            ['name' => 'UI/UX Design', 'form_section' => 'project_based', 'sort_order' => 6],
            ['name' => 'Search Engine Optimisation (SEO)', 'form_section' => 'project_based', 'sort_order' => 7],
            ['name' => 'IT Consultancy, Support & Maintenance', 'form_section' => 'project_based', 'sort_order' => 8],
            ['name' => 'Domain & Hosting', 'form_section' => 'domain_hosting', 'sort_order' => 9],
            ['name' => 'Other / Custom', 'form_section' => 'custom', 'sort_order' => 10],
        ];

        foreach ($types as $type) {
            ServiceType::firstOrCreate(
                ['name' => $type['name']],
                ['form_section' => $type['form_section'], 'sort_order' => $type['sort_order'], 'is_active' => true]
            );
        }
    }
}
