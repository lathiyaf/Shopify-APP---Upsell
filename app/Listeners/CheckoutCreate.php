<?php

namespace App\Listeners;

use App\Events\CheckCheckoutCreate;
use App\Models\RcAbandonedCheckout;
use App\Models\RcCustomer;
use App\Models\RcLineItems;
use App\Models\RcShop;
use App\Models\RcWebhook;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CheckoutCreate
{
    use BaseTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckCheckoutCreate  $event
     * @return void
     */
    public function handle(CheckCheckoutCreate $event)
    {
        try{
            logger("========== START:: LISTENER CheckCheckoutCreate =========");
            DB::beginTransaction();
                logger(json_encode($event));
                $ids = $event->ids;
                $user = User::find($ids['user_id']);

                $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                $webhookResonse = RcWebhook::find($ids['webhook_id']);
                if ($webhookResonse) {
                    $data = json_decode($webhookResonse->body);

                    $is_exist_db_chkout = RcAbandonedCheckout::where('sh_checkout_id', $data->id)->where('shop_id',
                        $shop->id)->orderBy('created_at', 'desc')->first();

                    $db_chkout = ($is_exist_db_chkout) ? $is_exist_db_chkout :  new RcAbandonedCheckout;

                    $sh_customer = (@$data->customer) ? $data->customer : [];

                    $db_chkout->user_id = $user->id;
                    $db_chkout->shop_id = $shop->id;
                    $db_chkout->sh_checkout_id = $data->id;
                    $db_chkout->sh_customer_id = (@$sh_customer->id) ? $sh_customer->id : '';
                    $db_chkout->email = $data->email;
                    $db_chkout->phone = $data->phone;
                    $db_chkout->source_name = $data->source_name;
                    $db_chkout->abandoned_checkout_url = $data->abandoned_checkout_url;
                    $db_chkout->checkout_created_at = date('Y-m-d H:i:s', strtotime($data->created_at));
                    $db_chkout->currency = $data->presentment_currency;
                    $db_chkout->currency_symbol = currencyH($data->presentment_currency);
                    $db_chkout->total_line_items_price = $data->total_line_items_price;
                    $db_chkout->total_price = $data->total_price;
                    $db_chkout->is_ordered = 0;
                    $db_chkout->save();

                    $sh_lineItems = (@$data->line_items) ? $data->line_items : [];

//                    remove lineitems if exist
                    $is_exist_db_lineitems = RcLineItems::where('db_checkout_id', $db_chkout->id)->where('shop_id',
                        $shop->id)->get();
                    if (count($is_exist_db_lineitems)) {
                        foreach ($is_exist_db_lineitems as $lkey => $lval) {
                            $lval->delete();
                        }
                    }

//                    create new lineitems

                    foreach ($sh_lineItems as $lkey => $lval) {
                        $sh_product = $this->getShopifyData($user, $lval->product_id, 'product', 'id,handle,image');

                        $db_lineItem = new RcLineItems;
                        $db_lineItem->user_id = $user->id;
                        $db_lineItem->shop_id = $shop->id;
                        $db_lineItem->db_checkout_id = $db_chkout->id;
                        $db_lineItem->sh_product_id = $lval->product_id;
                        $db_lineItem->sh_variant_id = $lval->variant_id;
                        $db_lineItem->product_handle = (@$sh_product['handle']) ? $sh_product['handle'] : '';
                        $db_lineItem->title = $lval->title;
                        $db_lineItem->quantity = $lval->quantity;
                        $db_lineItem->price = $lval->price;
                        $db_lineItem->total_price = $lval->price;
                        $db_lineItem->image = (@$sh_product['image']['src']) ? $sh_product['image']['src'] : '';
                        $db_lineItem->save();
                    }

//                    save customer

                    if(!empty($sh_customer)){
                        $is_exist_customer = RcCustomer::where('user_id', $user->id)->where('sh_customer_id', $sh_customer->id)->first();

                        $db_customer = ($is_exist_customer) ? $is_exist_customer : new RcCustomer;
                        $db_customer->user_id = $user->id;
                        $db_customer->shop_id = $shop->id;
                        $db_customer->sh_customer_id = $sh_customer->id;
                        $db_customer->first_name = $sh_customer->first_name;
                        $db_customer->last_name = $sh_customer->last_name;
                        $db_customer->email = $sh_customer->email;
                        $db_customer->phone = $sh_customer->phone;
                        $db_customer->order_count = $sh_customer->orders_count;
                        $db_customer->total_spend = $sh_customer->total_spent;
                        $db_customer->total_spend_currency = $sh_customer->currency;
                        // $db_customer->date_first_order = $sh_customer->currency;
                        $db_customer->save();

                         if(!$is_exist_customer){
                            $t = ($db_chkout->email != '') ? 'email' : 'sms';
                            $udata = [
                                'subscriber' => 1,
                            ];
                            $this->runAnalytics($user, $t, $udata);
                        }
                       
                    }
                     if(!$is_exist_db_chkout){
                            $t = ($db_chkout->email != '') ? 'email' : 'sms';
                            $udata = [
                                'abandoned_orders' => 1
                            ];
                            $this->runAnalytics($user, $t, $udata);
                        }
                }
        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            logger("========== ERROR:: LISTENER CheckCheckoutCreate =========");
            logger(json_encode($e));
        }
    }
}
