<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        $categories = [
            ['name' => 'Information Technology',      'slug' => 'information-technology',       'icon' => 'laptop'],
            ['name' => 'Engineering',                 'slug' => 'engineering',                   'icon' => 'settings'],
            ['name' => 'Finance and Accounting',      'slug' => 'finance-and-accounting',        'icon' => 'bar-chart'],
            ['name' => 'Marketing and Sales',         'slug' => 'marketing-and-sales',           'icon' => 'trending-up'],
            ['name' => 'Human Resources',             'slug' => 'human-resources',               'icon' => 'users'],
            ['name' => 'Design and Creative',         'slug' => 'design-and-creative',           'icon' => 'pen-tool'],
            ['name' => 'Healthcare',                  'slug' => 'healthcare',                    'icon' => 'heart'],
            ['name' => 'Education and Training',      'slug' => 'education-and-training',        'icon' => 'book'],
            ['name' => 'Operations and Logistics',    'slug' => 'operations-and-logistics',      'icon' => 'truck'],
            ['name' => 'Legal',                       'slug' => 'legal',                         'icon' => 'briefcase'],
            ['name' => 'Customer Support',            'slug' => 'customer-support',              'icon' => 'headphones'],
            ['name' => 'Data and Analytics',          'slug' => 'data-and-analytics',            'icon' => 'database'],
            ['name' => 'Product Management',          'slug' => 'product-management',            'icon' => 'package'],
            ['name' => 'Construction and Real Estate','slug' => 'construction-and-real-estate',  'icon' => 'home'],
            ['name' => 'Hospitality and Tourism',     'slug' => 'hospitality-and-tourism',       'icon' => 'globe'],
            ['name' => 'Media and Communications',    'slug' => 'media-and-communications',      'icon' => 'radio'],
            ['name' => 'Research and Development',    'slug' => 'research-and-development',      'icon' => 'search'],
            ['name' => 'Administration',              'slug' => 'administration',                'icon' => 'clipboard'],
        ];

        foreach ($categories as $cat) {
            if (! DB::table('job_categories')->where('slug', $cat['slug'])->exists()) {
                DB::table('job_categories')->insert([
                    'name'       => $cat['name'],
                    'slug'       => $cat['slug'],
                    'icon'       => $cat['icon'],
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('Job categories seeded.');
    }
}