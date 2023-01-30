<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'sales-read',
            'services-read',
            'pharmacy-read',
            'laborat-read',
            'radiology-read',
            'medrec-read',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
       }

    }
}
