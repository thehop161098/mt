<?php

namespace App\Console\Commands;

use Core\Services\CommissionService;
use Illuminate\Console\Command;

class BotCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bot Commission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        parent::__construct();
        $this->commissionService = $commissionService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->commissionService->calculatorCommissionBot();
        $this->info('bot:cron - Run successfully!');
    }
}
