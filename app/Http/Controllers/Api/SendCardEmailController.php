<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use App\Mail\TestEmailAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendCardEmailController extends Controller
{
    public function SendEmail(Request $req)
    {
        // dd('kareem');

        // $info = [
        //     'name' => 'kareem',
        //     'email' => 'user1@user.com',
        //     'address' => 'lorem lorem lorem lorem lorem lorem',
        //     'phone' => '01000260660'
        // ];
        // $info2 = [
        //     [
        //         'name' => 'product1',
        //         'qty' => '2',
        //         'price' => '15'

        //     ],
        //     [
        //         'name' => 'product2',
        //         'qty' => '2',
        //         'price' => '15'

        //     ],
        //     [
        //         'name' => 'product3',
        //         'qty' => '2',
        //         'price' => '15'
        //     ],
        // ];
        $info = $req->info;
        $info2 = $req->cart;
        $sumPrice = collect($info2)
            ->reduce(function ($carry, $item) {
                $totalSum = $item['price'] * $item['qty'];
                $sumtotalPrice = $carry + $totalSum;
                return $sumtotalPrice;
            }, 0);
        // $sumQty = collect($info2)
        //     ->reduce(function ($carry, $item) {
        //         return $carry + $item["qty"];
        //     }, 0);

        // $arra = [
        //         'sumPrice' => "$sumPrice",
        //         'sumQty' => "$sumQty"
        // ];

        // dd($info2);
        $inf_arr = [];
        foreach ($info as $x => $x_value) {
            if ($x == 'email') {
                $X_user = User::select('email')->where('email', $x_value)->count();
                if ($X_user == 1) {
                    array_push($inf_arr, $x_value);
                } else {
                    return response()->json([
                        'status' => true,
                        'Massage' => 'Your Email Is Not Valid'
                    ], 404);
                }
            }
        }
        Mail::to($inf_arr[0])->send(new TestEmail($info, $info2, $sumPrice));
        Mail::to('kareemdiap508@gmail.com')->send(new TestEmailAdmin($info, $info2, $sumPrice));

        return response()->json([
            'status' => true,
            'massage' => 'Successfully '
        ], 200);
    }
}