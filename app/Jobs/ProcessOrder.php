<?php

namespace App\Jobs;

use App\Events\OrderEvent;
use Core\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $dataOrder;
    private $orderService;

    public function __construct(OrderService $orderService, Object $dataOrder)
    {
        $this->orderService = $orderService;
        $this->dataOrder = $dataOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->orderService->createOrder($this->dataOrder->user_id, $this->dataOrder->amount,
            $this->dataOrder->coin, $this->dataOrder->walletType, $this->dataOrder->type);
        broadcast(new OrderEvent($response))->toOthers();
    }
}
