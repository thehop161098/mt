<?php

namespace App\Console\Commands;

use Core\Services\HandleOrderErrorService;
use Illuminate\Console\Command;

class OrderErrorCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:repay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repay money of error order';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $handleOrderErrorService;

    public function __construct(HandleOrderErrorService $handleOrderErrorService)
    {
        parent::__construct();
        $this->handleOrderErrorService = $handleOrderErrorService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->handleOrderErrorService->handleOrderError();
        $this->info('orders:repay - Run successfully!');
    }
}
