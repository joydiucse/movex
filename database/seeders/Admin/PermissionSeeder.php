<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = [
            //for staff
            'users'           => ['read' => 'user_read', 'create' => 'user_create', 'update' => 'user_update', 'delete' => 'user_delete', 'account_activity_read' => 'user_account_activity_read', 'payment_logs_read' => 'user_payment_logs_read','logout_from_devices' => 'user_logout_from_devices'],
            'roles'           => ['read' =>  'role_read', 'create' => 'role_create', 'update' =>  'role_update', 'delete' =>  'role_delete'],
            'permissions'     => ['read' =>  'permission_read', 'create' => 'permission_create', 'update' =>  'permission_update', 'delete' =>  'permission_delete'],
            'merchant'        => ['read' =>  'merchant_read', 'read_all' => 'read_all_merchant', 'use_all' => 'use_all_merchant', 'create' => 'merchant_create', 'update' =>  'merchant_update', 'delete' =>  'merchant_delete', 'shop_read' => 'merchant_shop_read', 'shop_create' => 'merchant_shop_create', 'shop_delete' => 'merchant_shop_delete', 'shop_update' => 'merchant_shop_update', 'staff_read' => 'merchant_staff_read', 'staff_create' => 'merchant_staff_create', 'staff_update' => 'merchant_staff_update', 'payment_account_read' => 'merchant_payment_account_read', 'payment_account_update' => 'merchant_payment_account_update','account_activity_read' => 'merchant_account_activity_read', 'cod_charge_read' => 'merchant_cod_charge_read', 'charge_read' => 'merchant_charge_read', 'payment_logs_read' => 'merchant_payment_logs_read','api_credentials_read' => 'merchant_api_credentials_read','api_credentials_update' => 'merchant_api_credentials_update','download_closing_report' => 'download_closing_report'],
            'delivery_man'    => ['read' =>  'deliveryman_read', 'read_all' => 'read_all_delivery_man', 'use_all' => 'use_all_delivery_man', 'create' => 'deliveryman_create', 'update' =>  'deliveryman_update', 'delete' =>  'deliveryman_delete', 'account_activity_read' => 'deliveryman_account_activity_read', 'payment_logs_read' => 'deliveryman_payment_logs_read'],
            'parcel'          => ['read' =>  'parcel_read', 'read_all' => 'read_all_parcel', 'create' => 'parcel_create', 'update' =>  'parcel_update', 'delete' =>  'parcel_delete', 'pickup_assigned'=>'parcel_pickup_assigned', 'reschedule_pickup'=>'parcel_reschedule_pickup','received_by_pickup_man' => 'parcel_received_by_pickup_man', 'received_to_warehouse'=>'parcel_received', 'transfer_to_hub' => 'parcel_transfer_to_hub','transfer_receive_to_hub' => 'parcel_transfer_receive_to_hub','delivery_assigned'=>'parcel_delivery_assigned', 'reschedule_delivery'=>'parcel_reschedule_delivery', 'returned_to_greenx'=>'parcel_returned_to_greenx', 'return_assigned_to_merchant'=>'parcel_return_assigned_to_merchant', 'delivered'=>'parcel_delivered', 'delivery_backward' => 'parcel_backward', 'returned_to_merchant'=> 'parcel_returned_to_merchant', 'cancel'=>'parcel_cancel','send_to_paperfly' => 'send_to_paperfly'],
            'income'          => ['read' =>  'income_read', 'read_all' => 'read_all_income', 'create' => 'income_create', 'update' =>  'income_update', 'delete' =>  'income_delete'],
            'expense'         => ['read' =>  'expense_read', 'create' => 'expense_create', 'update' =>  'expense_update', 'delete' =>  'expense_delete'],
            'withdraw'        => ['read' =>  'withdraw_read', 'create' =>  'withdraw_create', 'update' =>  'withdraw_update', 'process' => 'withdraw_process', 'reject' =>  'withdraw_reject','add_to_bulk_withdraw' => 'add_to_bulk_withdraw'],
            'bulk_withdraw'   => ['read' =>  'bulk_withdraw_read', 'create' =>  'bulk_withdraw_create', 'update' =>  'bulk_withdraw_update', 'process' => 'bulk_withdraw_process', 'download_payment_sheet' => 'download_payment_sheet'],
            'sms_setting'     => ['read' =>  'sms_setting_read', 'update' =>  'sms_setting_update','send_bulk_sms' => 'sms_campaign_message_send','send_sms'=> 'custom_sms_send'],
            'report'          => ['read' =>  'report_read', 'transaction_history_read' => 'transaction_history_read', 'parcels_summary_read' => 'parcels_summary_read','total_summary_read'=> 'total_summary_read', 'income_expense_report_read' => 'income_expense_report_read', 'profit_summary_report_read' => 'profit_summary_report_read','merchant_summary_report_read' => 'merchant_summary_report_read','dashboard_statistics_read' => 'dashboard_statistics_read'],
            'account'         => ['read' =>  'account_read', 'create' => 'account_create', 'update' => 'account_update','statement' => 'account_statement'],
            'fund_transfer'   => ['read' =>  'fund_transfer_read', 'create' => 'fund_transfer_create', 'update' => 'fund_transfer_update', 'delete' => 'fund_transfer_delete'],
            'hub'             => ['read' =>  'hub_read', 'create' => 'hub_create', 'update' => 'hub_update', 'delete' => 'hub_delete'],
            'third_party'     => ['read' =>  'third_party_read', 'create' => 'third_party_create', 'update' => 'third_party_update', 'delete' => 'third_party_delete'],
            'notice'          => ['read' =>  'notice_read', 'create' => 'notice_create', 'update' => 'notice_update', 'delete' => 'notice_delete'],
            'settings'        => ['read' =>  'settings_read', 'sms_settings_update' => 'sms_settings_update', 'charge_update' => 'charge_setting_update', 'pickup_and_delivery_time_update' => 'pickup_and_delivery_time_setting_update', 'preference_update' => 'preference_setting_update'],
        ];

        foreach($attributes as $key => $attribute){
        	$permission               = new Permission();
        	$permission->attribute    = $key;
            $permission->keywords     = $attribute;
        	$permission->save();
        }
    }
}
