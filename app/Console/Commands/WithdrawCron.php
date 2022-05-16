<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Core\Services\WithdrawService;

class WithdrawCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdraw:cron';

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

    private $withdrawService;

    public function __construct(WithdrawService $withdrawService)
    {
        parent::__construct();
        $this->withdrawService = $withdrawService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->withdrawService->cronCheckStatusHistory();
        $this->info('Cron History Withdraw - Run successfully!');
        return true;
    }
}
