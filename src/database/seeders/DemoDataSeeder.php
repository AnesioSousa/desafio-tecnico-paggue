<?php
// src/database/seeders/DemoDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\Sector;
use App\Models\Batch;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        foreach (['admin', 'producer', 'client'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 1) Admin
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'phone' => '11999999999',
            'cpf_cnpj' => '00000000000',
            'password' => Hash::make('secret123'),
            'role' => 'admin',    // legacy column
        ]);
        $admin->assignRole('admin');

        // 2) Three clients
        $clients = [
            ['name' => 'Alice', 'email' => 'alice@example.com', 'cpf' => '11111111111'],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'cpf' => '22222222222'],
            ['name' => 'Carol', 'email' => 'carol@example.com', 'cpf' => '33333333333'],
        ];
        foreach ($clients as $c) {
            $user = User::create([
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => '11900000001',
                'cpf_cnpj' => $c['cpf'],
                'password' => Hash::make('password'),
                'role' => 'client',
            ]);
            $user->assignRole('client');
        }

        // 3) One producer
        $producer = User::create([
            'name' => 'Event Co.',
            'email' => 'producer@example.com',
            'phone' => '11911112222',
            'cpf_cnpj' => '44444444444',
            'password' => Hash::make('producerpass'),
            'role' => 'producer',
        ]);
        $producer->assignRole('producer');

        // 4) One event (owned by this producer)
        $event = Event::create([
            'producer_id' => $producer->id,
            'title' => 'Demo Concert',
            'description' => 'A live demo show',
            'banner_url' => null,
            'date' => '2025-07-01',
            'start_time' => '18:00:00',
            'end_time' => '22:00:00',
            'city' => 'Sao Paulo',
            'venue' => 'Demo Arena',
        ]);

        // 5) Two sectors on this event
        $sectorA = Sector::create([
            'event_id' => $event->id,
            'name' => 'Front Sector',
        ]);
        $sectorB = Sector::create([
            'event_id' => $event->id,
            'name' => 'Back Sector',
        ]);

        // 6) Batches: sector A gets 2, sector B gets 1
        Batch::create([
            'sector_id' => $sectorA->id,
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-10',
            'price' => 100.00,
            'total_quantity' => 50,
        ]);
        Batch::create([
            'sector_id' => $sectorA->id,
            'start_date' => '2025-06-11',
            'end_date' => '2025-06-20',
            'price' => 120.00,
            'total_quantity' => 50,
        ]);
        Batch::create([
            'sector_id' => $sectorB->id,
            'start_date' => '2025-06-05',
            'end_date' => '2025-06-15',
            'price' => 80.00,
            'total_quantity' => 100,
        ]);
    }
}
