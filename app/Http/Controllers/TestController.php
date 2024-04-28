<?php

namespace App\Http\Controllers;

use App\Models\PathaoCity;
use App\Models\PathaoZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;
class TestController extends Controller
{
    public function index()
    {
        return PathaoCourier::store()->list();
        return PathaoCourier::store()->list();
        return PathaoCourier::order()
            ->create([
                "store_id"            => "55795", // Find in store list,
                "merchant_order_id"   => "11111", // Unique order id
                "recipient_name"      => "Joy", // Customer name
                "recipient_phone"     => "01785893609", // Customer phone
                "recipient_address"   => "Savar, Dhaka, Bangladesh", // Customer address
                "recipient_city"      => "1", // Find in city method
                "recipient_zone"      => "1016", // Find in zone method
                "recipient_area"      => "12236", // Find in Area method
                "delivery_type"       => "48", // 48 for normal delivery or 12 for on demand delivery
                "item_type"           => "2",
                      "special_instruction" => "Keep Calm",
                            "item_quantity"       => "1", // item quantity
                            "item_weight"         => "1", // parcel weight
                            "amount_to_collect"   => "100", // amount to collect
                            "item_description"    => "Electronics" // product details
                        ]);

    }

    public function getAccessToken()
    {
        /*$response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->post('https://api-hermes.pathao.com/aladdin/api/v1/issue-token', [
                'client_id' => 'MYer0x2bOB',
                'client_secret' => 'OuzBo2ZL3gbMmlOL3Uv0vmjZMXBkSbfMHasEMLnD',
                'username' => 'movexcourier3@gmail.com',
                'password' => 'shan9997',
                'grant_type' => 'password',
            ]);

        return $response->json();*/


        $base_url = "https://courier-api-sandbox.pathao.com";
        $client_id = "267";
        $client_secret = "wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39";
        $client_email = "test@pathao.com";
        $client_password = "lovePathao";
        $grant_type = "password";

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->post($base_url . '/aladdin/api/v1/issue-token', [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'username' => $client_email,
                'password' => $client_password,
                'grant_type' => $grant_type,
            ]);

        return $response->json();

    }

    // Issue a refresh token
    public function refreshAccessToken(Request $request)
    {
        $response = Http::post('https://courier-api-sandbox.pathao.com/aladdin/api/v1/issue-token', [
            'client_id' => '267',
            'client_secret' => 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39',
            'refresh_token' => $request->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return $response->json();
    }

    // Create a new order
    /*public function createOrder(Request $request)
    {
        // First, get the access token
        $accessTokenResponse = $this->getAccessToken($request);
        $accessToken = $accessTokenResponse['access_token'];

        // Then, use the access token to create a new order
        $response = Http::withToken($accessToken)->post('https://courier-api-sandbox.pathao.com/aladdin/api/v1/create-order', [
            // Include order data here
        ]);

        return $response->json();
    }*/

    public function createOrder()
    {
        $base_url = "https://courier-api-sandbox.pathao.com";
        $requestUrl = $base_url . '/aladdin/api/v1/orders/bulk';

        $headers = [
            'Authorization' => 'Bearer ' . "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjMzNTk2M2E2OWQ2ZDMwNTgwOTUyZDI1ZjNiNzcyYzM3NTZiZDEzZGVhZTRhYWYwOTg0YjExYTZkMmE2YmJhMTk0ZTllOGNjNGRkZGUyZmIxIn0.eyJhdWQiOiIyNjciLCJqdGkiOiIzMzU5NjNhNjlkNmQzMDU4MDk1MmQyNWYzYjc3MmMzNzU2YmQxM2RlYWU0YWFmMDk4NGIxMWE2ZDJhNmJiYTE5NGU5ZThjYzRkZGRlMmZiMSIsImlhdCI6MTcxMzEwNDU3NywibmJmIjoxNzEzMTA0NTc3LCJleHAiOjE3MTM1MzY1NzcsInN1YiI6IjM1MiIsInNjb3BlcyI6W119.tjZzjf8UNI1jqocNTidbNwSbpFKJfItl_DAgLnQrGKjtSFobpdUkFug9lQaIL8tVCg0_PTF8z7rTf7VyS51zTmcrB13CxtLv8nI93HTqQP8j2GzHjzvrIMFGSgAOa19cQmhOaecKe7bIFpcP4o71ds4WBd7qNsgUTPF8cVW_T86RYEtqkoed-9Km2LQoRxeV49KSBhKkFu0_A9r_TtIPPecNrtO3hswYUpg556x8YjOF0SSQts41Pfg-XUoVDs0Q_Sbf7jPCIajzhd65wI5pIajECjDdh-FIkmKkwRu3MmMvJwCuQxKQNvu5WB48WvLUAWp6thu4Dpo_wkVReqyeY1B7oIjWaSXbpcNK4cATzQgkaZigOGuSzNxffcBLcOTbzNfVnTimDzR6pRoomF1H9-TYkImA--NAFqHZz48L6wg19iBFwndSi3XylJlRsE2ENim4MUFrLR4x6klPhvvOTxwVwqaC-2l0ro5RnD0WRJufglmYvuik8vtcePN0BhIBJpZ3C76qIP2hzXAtVHLJg-LGSx65gi7TQC072dpcQh0MeLRRSr7Wvj7fH6monjUItVNnhx4J-4ap3W2ClA5irMecGjlS8hFXv4t1LF9VToDc7-lcD-3Fw_7FelMyVouq720fMVoyxVwPs_WujB3fyNfRu3eNLfD40dopC2MV_IE",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $body = [
            'orders' => [
                [
                    'store_id' => '', // Dummy store ID
                    'merchant_order_id' => 'ORD123456', // Dummy merchant order ID
                    'sender_name' => 'John Doe',
                    'sender_phone' => '01783583458',
                    'recipient_name' => 'Jane Smith',
                    'recipient_phone' => '01783583456',
                    'recipient_address' => '123 Main St',
                    'recipient_city' => '1',
                    'recipient_zone' => '2',
                    'recipient_area' => 'Dhaka, Dhaka, Savar',
                    'delivery_type' => '48', // or Standard, etc.
                    'item_type' => '2', // or Document, etc.
                    'special_instruction' => 'Handle with care',
                    'item_quantity' => 1,
                    'item_weight' => 2.5, // in kilograms
                    'amount_to_collect' => 50.00, // Amount to collect from recipient
                    'item_description' => 'Electronics', // Description of the item
                ],
                [
                    'store_id' => '', // Dummy store ID
                    'merchant_order_id' => 'ORD123456', // Dummy merchant order ID
                    'sender_name' => 'John Doe',
                    'sender_phone' => '01783583458',
                    'recipient_name' => 'Jane Smith',
                    'recipient_phone' => '01783583456',
                    'recipient_address' => '123 Main St',
                    'recipient_city' => '1',
                    'recipient_zone' => '2',
                    'recipient_area' => 'Dhaka, Dhaka, Savar',
                    'delivery_type' => '48', // or Standard, etc.
                    'item_type' => '2', // or Document, etc.
                    'special_instruction' => 'Handle with care',
                    'item_quantity' => 1,
                    'item_weight' => 2.5, // in kilograms
                    'amount_to_collect' => 50.00, // Amount to collect from recipient
                    'item_description' => 'Electronics', // Description of the item
                ],

            ]

        ];

        $response = Http::withHeaders($headers)->post($requestUrl, $body);

        return $response->json();
    }

}
