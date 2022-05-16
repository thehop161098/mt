<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Core\Services\DepositService;

class DepositCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $depositService;

    public function __construct(DepositService $depositService)
    {
        parent::__construct();
        $this->depositService = $depositService;
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        $this->depositService->cronjobPlusAmount();
        $this->info('Cron Deposit - Run successfully!');
        return true;
    }
}
