<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Measurement;

class DefaultDataSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'default:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add default data to MySql Tables';

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
        $measurements = [
            'el'=>'eetlepel',
            'tl'=>'theelepel',
            'ml'=>'milliliter',
            'dl'=>'deciliter',
            'kg'=>'kilogram',
            'l'=>'liter',
            'gr'=>'gram',
            'st'=>'stuks',
            'fl'=>'fles',
            'kr'=>'krat',
            'pk'=>'pak',
        ];

        foreach($measurements as $key=>$arrayItem){
            Measurement::updateOrInsert(
                ['abbreviation' => $key, 'full_name'=>$arrayItem]
            );
        }
    }
}
