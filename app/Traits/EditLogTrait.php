<?php

namespace App\Traits;
use SoapClient;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use DB;
use App\Models\MerchantEditLog;
use App\Models\DeliveryManEditLog;
trait EditLogTrait {

    public function merchant_edit_log($old_data, $new_data, $merchant_id)
    {

       $edit_log  = new MerchantEditLog;
       $edit_log->previous_data = json_encode($old_data);
       $edit_log->edited_data = json_encode($new_data);
       $edit_log->user_id  = \Sentinel::getUser()->id;
       $edit_log->merchant_id  = $merchant_id;
       $edit_log->save();

    }

    public function deliveryMan_edit_log($old_data, $new_data, $deliveryMan_id)
    {

        $edit_log  = new DeliveryManEditLog;
        $edit_log->previous_data = json_encode($old_data);
        $edit_log->edited_data = json_encode($new_data);
        $edit_log->user_id  = \Sentinel::getUser()->id;
        $edit_log->delivery_man_id   = $deliveryMan_id;

        $user_old = $old_data['user']->toArray();
        unset($user_old['permissions']);
        $user_new = $new_data['user']->toArray();
        unset($user_new['permissions']);

        $user_diif = array_diff($user_old, $user_new);
        $delivery_diif = array_diff($old_data['deliveryMan']->toArray(), $new_data['deliveryMan']->toArray());
        $company_account_diif = array_diff($old_data['company_account']->toArray(), $new_data['company_account']->toArray());
        $delivery_account_diif = array_diff($old_data['delivery_account']->toArray(), $new_data['delivery_account']->toArray());

        if(count($user_diif) > 0 || count($delivery_diif) > 0 ||  count($company_account_diif) > 0 || count($delivery_account_diif) > 0 )
        {
          $edit_log->save();
          return true;
        }

    }

     
}
