<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckExpiredUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'users:check-expired';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check and deactivate expired user accounts';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Checking for expired users...');

    $count = User::checkAndDeactivateExpiredUsers();

    if ($count > 0) {
      $this->info("Deactivated {$count} expired user(s).");
    } else {
      $this->info('No expired users found.');
    }

    return 0;
  }
}
