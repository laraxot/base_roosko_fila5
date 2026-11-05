<?php

declare(strict_types=1);

namespace Modules\Mobile\Database\Seeders;

use Modules\Mobile\Models\WaiterSession;
use Modules\Mobile\Models\OrderQueue;
use Modules\Mobile\Database\Factories\OrderQueueFactory;
use Modules\Mobile\Database\Factories\WaiterSessionFactory;
use Illuminate\Database\Seeder;

class MobileSeeder extends Seeder
{
    public function run(): void
    {
        (new WaiterSessionFactory())->count(5)->create();

        (new OrderQueueFactory())->count(10)->create();
        (new OrderQueueFactory())->count(5)->pending()->create();
        (new OrderQueueFactory())->count(3)->synced()->create();
        (new OrderQueueFactory())->count(2)->failed()->create();
    }
}
