<?php

namespace App\Console\Commands;

use App\Models\UserAttendence;
use Illuminate\Console\Command;

class MarkoutMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:markout-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark markout missing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attendences = UserAttendence::with('user')
            ->where('is_completed', 0)
            ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('-12 hours')))
            ->take(10)
            ->get();

        foreach ($attendences as $key => $attendence) {
            $attendence->status = 5;
            $attendence->is_completed = 1;
            $attendence->save();
        }
    }
}
