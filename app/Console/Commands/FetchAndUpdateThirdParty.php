<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminated\Console\Loggable;
use App\Managers\ThirdPartyServiceManager;

class FetchAndUpdateThirdParty extends Command
{
    use Loggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:FetchAndUpdateThirdParty {days=1 : Day interval to fetch from third party (including Today), default is today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch from third party And update database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(ThirdPartyServiceManager $manager)
    {
        $daySince = $this->argument('days');
        for ($i = 0; $i < $daySince ; $i++) {
            $date = Carbon::today()->sub($i, 'day')->toDateString();
            $summary = $manager->fetchDailySummaryFromThirdParty($date);
            if ($summary == null) continue;

            $updateInfo = $manager->updateDailySummary($summary, $date);
            $this->logInfo("====================$date====================");
            $this->logInfo($updateInfo);
            $this->logInfo("==================================================");
        }
    }
}
