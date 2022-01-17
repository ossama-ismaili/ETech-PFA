<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TruncateOldPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old promotions';

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
     */
    public function handle()
    {
        $this->info("Run");

        while (true) {
            if (now()->second === 0) {
                Promotion::where('end_date', '<=', Carbon::now("GMT+1"))->each(function ($item) {
                    $this->info("Delete Promotion of Product : ".$item->product->title);
                    $item->delete();
                });
            }

            sleep(1);
        }
    }
}
