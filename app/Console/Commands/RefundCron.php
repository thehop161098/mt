<?php

namespace App\Console\Commands;

use Core\Services\RefundService;
use Illuminate\Console\Command;

class RefundCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund daily money lose';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $refundService;

    public function __construct(RefundService $refundService)
    {
        parent::__construct();
        $this->refundService = $refundService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->refundService->refundOrderDaily();
        $this->info('Refund:cron - Run successfully!');
    }
}
