<?php

namespace App\Traits;

trait SendAutomationTrait{
    public function index(){
        try{
            dd('111111');
        }catch(\Exception $e){
            logger("============= ERROR :: SendAutomationTrait (index) =============");
            logger($e);
        }
    }
}
