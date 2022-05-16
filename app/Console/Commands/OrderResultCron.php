<?php

namespace App\Console\Commands;

use Core\Services\OrderResultService;
use Illuminate\Console\Command;

class OrderResultCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculator money for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $orderResultService;

    public function __construct(OrderResultService $orderResultService)
    {
        parent::__construct();
        $this->orderResultService = $orderResultService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->orderResultService->calResult();
        $this->info('orders:cal - Run successfully!');
    }
}
