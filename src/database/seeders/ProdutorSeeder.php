<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produtor;

class ProdutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produtor::factory()
            ->count(25)
            ->create();

        Produtor::factory()
            ->count(25)
            ->create();

        Produtor::factory()
            ->count(25)
            ->create();

        Produtor::factory()
            ->count(25)
            ->create();
    }
}
