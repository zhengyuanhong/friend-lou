<?php

namespace App\Console\Commands;

use App\Model\Lou;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DealLouData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'z:lou-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理时间';

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
     * @return mixed
     */
    public function handle()
    {
        $res = Lou::query()->where('status', 2)->get();

        foreach ($res as $v) {
            $v->repayment_end_at = $v->repayment_at;
            $v->repayment_at = Carbon::parse($v->created_at)->addDays($v->duration);
            $v->save();
        }
    }
}
