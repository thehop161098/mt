<?php

namespace App\Console\Commands;

use Core\Services\UpLevelService;
use Illuminate\Console\Command;

class LevelCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'level:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade level for user';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $upLevelService;

    public function __construct(UpLevelService $upLevelService)
    {
        parent::__construct();
        $this->upLevelService = $upLevelService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->upLevelService->upgradeLevel();
        $this->info('Cron Level - Run successfully!');
        return 0;
    }
}
