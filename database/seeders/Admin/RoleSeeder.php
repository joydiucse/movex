<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Superadmin','slug' => 'superadmin', 'permissions' => $this->superAdminPermissions()]);
    }

    private function superAdminPermissions()
    {
        return [
            'user_create',
            'user_read',
            'user_update',
            'user_delete',
            'user_account_activity_read',
            'user_payment_logs_read',
            'user_logout_from_devices',

            'role_create',
            'role_read',
            'role_update',
            'role_delete',

            'permission_read',
            'permission_create',
            'permission_update',
            'permission_delete',

            'merchant_create',
            'merchant_read',
            'use_all_merchant',
            'read_all_merchant',
            'merchant_update',
            'merchant_delete',
            'merchant_shop_read',
            'merchant_shop_create',
            'merchant_shop_delete',
            'merchant_shop_update',
            'merchant_payment_account_read',
            'merchant_payment_account_update',
            'merchant_account_activity_read',
            'merchant_cod_charge_read',
            'merchant_charge_read',
            'merchant_payment_logs_read',
            'merchant_api_credentials_read',
            'merchant_api_credentials_update',
            'merchant_staff_read',
            'merchant_staff_create',
            'merchant_staff_update',
            'download_closing_report',

            'deliveryman_create',
            'deliveryman_read',
            'read_all_delivery_man',
            'use_all_delivery_man',
            'deliveryman_update',
            'deliveryman_delete',
            'deliveryman_account_activity_read',
            'deliveryman_payment_logs_read',

            'parcel_create',
            'parcel_read',
            'parcel_update',
            'parcel_delete',

            'read_all_parcel',
            'parcel_pickup_assigned',
            'parcel_reschedule_pickup',
            'parcel_received_by_pickup_man',
            'parcel_received',
            'parcel_transfer_to_hub',
            'parcel_transfer_receive_to_hub',
            'parcel_delivery_assigned',
            'parcel_reschedule_delivery',
            'parcel_returned_to_greenx',
            'parcel_return_assigned_to_merchant',
            'parcel_delivered',
            'parcel_backward',
            'parcel_returned_to_merchant',
            'parcel_cancel',
            'parcel_delete',
            'send_to_paperfly',

            'income_create',
            'income_read',
            'read_all_income',
            'income_update',
            'income_delete',

            'expense_create',
            'expense_read',
            'expense_update',
            'expense_delete',

            'withdraw_read',
            'withdraw_create',
            'withdraw_update',
            'withdraw_process',
            'withdraw_reject',
            'add_to_bulk_withdraw',

            'sms_setting_read',
            'sms_setting_update',
            'sms_campaign_message_send',
            'custom_sms_send',

            'report_read',
            'transaction_history_read',
            'parcels_summary_read',
            'total_summary_read',
            'income_expense_report_read',
            'profit_summary_report_read',
            'merchant_summary_report_read',
            'dashboard_statistics_read',

            'account_read',
            'account_create',
            'account_update',
            'account_statement',

            'fund_transfer_read',
            'fund_transfer_create',
            'fund_transfer_update',
            'fund_transfer_delete',

            'hub_read',
            'hub_create',
            'hub_update',
            'hub_delete',

            'third_party_read',
            'third_party_create',
            'third_party_update',
            'third_party_delete',

            'notice_read',
            'notice_create',
            'notice_update',
            'notice_delete',

            'settings_read',
            'sms_settings_update',
            'charge_setting_update',
            'pickup_and_delivery_time_setting_update',
            'preference_setting_update',

            'bulk_withdraw_read',
            'bulk_withdraw_create',
            'bulk_withdraw_update',
            'bulk_withdraw_process',
            'download_payment_sheet',
        ];
    }
}


