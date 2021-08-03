<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(PermissionsSeeder::class);
        $this->call(SettingsTableSeeder::class);
//       $this->call(\Backpack\Settings\database\seeds\SettingsTableSeeder::class);
//        $this->call(UserSeeder::class);
//        $this->call(CategorySeeder::class);
//        $this->call(ArticleSeeder::class);
//        $this->call(DaysSeeder::class);
//        $this->call(ProgramSeederTableSeeder::class);
//        $this->call(TeamCategorySeeder::class);
//        $this->call(TeamSeeder::class);
//        $this->call(PageSeeder::class);
    }
}
