<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use DB;

class WithdrawSmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `withdraw_sms_templates` (`id`, `subject`, `content`, `sms_to_merchant`, `created_at`, `updated_at`) VALUES
(1, 'payment_create_event', 'A payment request to {account_details} of BDT {amount}, Payment ID: {payment_id} has been placed successfully at {current_date_time}. \r\n{our_company_name}', 0, NULL, '2021-04-11 23:19:01'),
(2, 'payment_update_event', 'Payment ID: {payment_id} to {account_details} of BDT {amount} has been updated to {new_account_details} successfully at {current_date_time}.\r\n{our_company_name}', 0, NULL, '2021-04-11 23:20:45'),
(3, 'payment_cancelled_event', 'Payment ID: {payment_id} to {account_details} of BDT {amount} has been cancelled at {current_date_time}.\r\n{our_company_name}', 0, NULL, NULL),
(4, 'payment_processed_event', 'Payment ID: {payment_id} to {account_details} of BDT {amount} has been processed successfully at {current_date_time}. \r\n{our_company_name}', 0, NULL, NULL),
(5, 'payment_rejected_event', 'Payment ID: {payment_id} to {account_details} of BDT {amount} has been rejected due to {reject_reason} at {current_date_time}.\r\n{our_company_name}', 0, NULL, '2021-04-11 23:20:50')");
    }
}
