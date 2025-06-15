<?php
// app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule): void
  {
    // $schedule->call(function () {
    //   Log::info('Schedule ran at: ' . now());
    // })->everyMinute();
  }

  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');
    require base_path('routes/console.php');
  }
}
