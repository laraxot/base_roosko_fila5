<?php

declare(strict_types=1);

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;

class WaiterSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Mario Rossi',
            'email' => 'waiter@demo.ristorante',
            'password' => bcrypt('password'),
            'role' => 'waiter',
        ]);

        User::query()->create([
            'name' => 'Luca Verdi',
            'email' => 'waiter2@demo.ristorante',
            'password' => bcrypt('password'),
            'role' => 'waiter',
        ]);
    }
}
