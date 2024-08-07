<?php

namespace Database\Seeders;

use App\Models\Navigation;
use App\Models\RollPermission;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Navigation::truncate();

        $menus = config("navigation");


        RollPermission::create([
            "name" => "Super Admin",
            "parent_id" => "",
            "child_id" => "",
        ]);
    }
}
