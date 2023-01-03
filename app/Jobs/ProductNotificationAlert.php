<?php

namespace App\Jobs;

use App\Models\GodownProduct;
use App\Models\ProductNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductNotificationAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $productIds;
    protected array $godownIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $productIds = [], array $godownIds = [])
    {
        $this->productIds = $productIds;
        $this->godownIds = $godownIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $godownProducts = GodownProduct::whereIn('product_id', $this->productIds)
        ->whereIn('godown_id', $this->godownIds)
        ->whereRaw('stock <= alert_on')
        ->get();

        foreach($godownProducts as $godownProduct){
            $productnotification = ProductNotification::create([
                'product_id'=> $godownProduct->product_id,
                'godown_id'=> $godownProduct->godown_id,
                'noti_type'=>'low_stock',
                'status'=>0,
                'read_at'=>null,
                'read_by'=>null,
            ]);
            $productnotification->save();
        }
    }
}
