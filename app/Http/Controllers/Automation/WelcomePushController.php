<?php

namespace App\Http\Controllers\Automation;

use App\Http\Controllers\Controller;
use App\Models\RcWelcomePush;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\AutomationRequest;

class WelcomePushController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        try{
            $user = Auth::user();

            $push = RcWelcomePush::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            if($push){
                $push->logo = ($push->logo == '10.png' || $push->logo == 'welcome-push-img.jpg') ? url('images/'.$push->logo) : \Storage::disk('public')->url('images/uploads/'.$push->logo);
            }

            $data['automations'] = $push;
            $data['place_img'] = url('images/welcome-push-img.jpg');
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AutomationRequest $request, $id){
        try{
            $data = json_decode($request->data);
            $welcome_push = RcWelcomePush::find($id);
            $welcome_push->discount_type = $data->discount_type;
            $welcome_push->discount_value = $data->discount_value;
            $welcome_push->discount_code = $data->discount_code;
            $welcome_push->headline = $data->headline;
            $welcome_push->body_text = $data->body_text;
            $welcome_push->active = $data->active;
            $welcome_push->url = $data->url;

            if(gettype($data->logo) != 'string' && $data->logo != ''){
                $old_img = $welcome_push->logo;
                $welcome_push->logo = $this->uploadImage($request->file);
                $image_path = storage_path("app/public/images/uploads/" . $old_img );

                if(File::exists($image_path)) {
                    unlink($image_path);
                }
            }elseif ($data->logo == ''){
                $welcome_push->logo = 'welcome-push-img.jpg';
            }
            $welcome_push->save();
            return response()->json(['data' => 'Saved!'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * for dropzone image
     */
    public function storeImages(){
        // never delete
    }

    public function uploadImage($value)
    {
        try {
            return ImageTrait::makeImage($value, 'images/uploads/');
        } catch (\Exception $e) {
            \Log::info('-----------------------ERROR: uploadImage-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }
}
