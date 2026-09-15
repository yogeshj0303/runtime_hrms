<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EvaluateAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:evaluate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evaluates all active alert rules and generates alerts based on business conditions.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\AlertEngineService $engine)
    {
        $this->info('Starting Alert Evaluation Engine...');
        $engine->evaluateAllRules();
        $this->info('Alert Evaluation Complete.');
    }
}
