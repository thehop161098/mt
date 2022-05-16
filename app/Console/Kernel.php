<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\OrderResultCron::class,
        Commands\CommissionCron::class,
        Commands\RefundCron::class,
        Commands\DepositCron::class,
        Commands\WithdrawCron::class,
        Commands\LevelCron::class,
        Commands\OrderErrorCron::class,
        Commands\BotCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:cal')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('commission:cron')
            ->dailyAt('00:00')->withoutOverlapping();
        $schedule->command('refund:cron')
            ->dailyAt('00:00')->withoutOverlapping();
        $schedule->command('deposit:cron')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('withdraw:cron')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('level:cron')
            ->weeklyOn(1, '2:00')->withoutOverlapping();
        $schedule->command('orders:repay')
            ->dailyAt('00:00')->withoutOverlapping();
        $schedule->command('bot:cron')
            ->dailyAt('00:06')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
