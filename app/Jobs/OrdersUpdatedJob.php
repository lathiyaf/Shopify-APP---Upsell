<?php namespace App\Jobs;

use App\Events\CheckCheckoutCreate;
use App\Events\CheckOrderUpdate;
use App\Models\RcAutomationOrder;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Response;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class OrdersUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use BaseTrait;
    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Convert domain
        $user = User::where('name', $this->shopDomain)->first();

        $db_order = RcAutomationOrder::where('sh_order_id', $this->data->id)->orderBy('created_at', 'desc')->first();
        $action = ($db_order) ? 'orders/updated' : 'orders/create';
        $webhookId = $this->webhook($action, $user->id, json_encode($this->data));

        event(new CheckOrderUpdate($webhookId, $user->id));
        return Response::make('', 200);

        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
    }
}
