<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class AutomationRequest extends FormRequest
{
    public static $rules = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Self::$rules;
        $data = $this::all();
        $data = $data['data'];

        switch (Route::currentRouteName()) {
            case 'save-automations':
            {
                $rules['data.discount_value'] = 'required';

                if($data['discount_type'] == 1){
                    $rules['data.discount_code'] = 'required';
                }

                if($data['reminder_type'] == 'sms' && $data['automation_type'] == 'automation'){

                   $rules['data.body_text.body_text'] = 'required';
                   $rules['data.body_text.unsubscribe_text'] = 'required';
                }
                if($data['reminder_type'] == 'email'){

                    $rules['data.body_text.subject'] = 'required';
                    $rules['data.body_text.email_preview'] = 'required';
                    $rules['data.body_text.header'] = 'required';
                    $rules['data.body_text.before_cart_body'] = 'required';
                    $rules['data.body_text.discount_banner_text'] = 'required';
                    $rules['data.body_text.footer'] = 'required';

                    if($data['automation_type'] == 'automation'){
                        $rules['data.body_text.cart_title'] = 'required';
                        $rules['data.body_text.product_description'] = 'required';
                        $rules['data.body_text.product_price'] = 'required';
                        $rules['data.body_text.product_qty'] = 'required';
                        $rules['data.body_text.product_total'] = 'required';
                        $rules['data.body_text.cart_total'] = 'required';
                        $rules['data.body_text.button_text'] = 'required';
                    }
                    $rules['data.sender_provider'] = 'required|regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/';
                 }
                 if($data['reminder_type'] == 'push' && $data['automation_type'] == 'automation'){

                    $rules['data.body_text.body_text'] = 'required';
                    $rules['data.body_text.headline'] = 'required';
                 }

                 if($data['automation_type'] == 'automation'){
                    $rules['data.sending_time'] = 'required';
                 }


                break;
            }
            case 'save-welcome-push':{
                $rules['data.discount_code'] = 'required';
                $rules['data.discount_value'] = 'required';
                $rules['data.headline'] = 'required';
                $rules['data.body_text'] = 'required';
                $rules['data.url'] = 'required';

                break;
            }
            default:
                    break;
            }
            return $rules;
    }
}
