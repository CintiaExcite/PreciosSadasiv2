<?php

namespace App\Http\Controllers\Utils;

use App\User;
use App\Models\UserOld;
use App\Models\Log;
use App\Models\logOld;
use App\Models\ProductOld;
use App\Models\Product;
use App\Models\Price;
use App\Models\Discount;
use App\Models\Income;
use App\Models\Payment;
use App\Models\HistoryPrice;
use App\Models\HistoryPriceOld;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MigrateController extends Controller
{
    public function migrateProducts()
    {
        $products_old = ProductOld::all();
        $count = 0;
        foreach ($products_old as $key => $value) {
            $product_m = new Product;
            $product_m->development_id = $value->development_id;
            $product_m->code = $value->product;
            $product_m->product = $value->product;
            $product_m->image_sys = "products/models/" . $value->image_sys;
            $product_m->comming_soon = $value->comming_son;
            $product_m->available = 1;
            $product_m->status = $value->status;

            if ($value->status == 0) {
                $product_m->deleted_at = $value->updated_at;
            }

            $product_m->created_at = $value->created_at;
            $product_m->updated_at = $value->updated_at;
            $product_m->save();

            $price_m = new Price;
            $price_m->product_id = $product_m->id;
            $price_f1 = str_replace("$", "", $value->price);
            $price_f2 = str_replace(",", "", $price_f1);
            $price_m->price = $price_f2;
            $price_m->text_before_price = $value->text_before_price;
            $price_m->text_after_price = $value->text_after_price;
            $price_m->created_at = $value->created_at;
            $price_m->updated_at = $value->updated_at;
            $price_m->save();

            $discount_m = new Discount;
            $discount_m->product_id = $product_m->id;
            $discount_f1 = str_replace("$", "", $value->discount);
            $discount_f2 = str_replace(",", "", $discount_f1);
            $discount_m->discount = $discount_f2;
            $discount_m->show = 0;
            $discount_m->created_at = $value->created_at;
            $discount_m->updated_at = $value->updated_at;
            $discount_m->save();

            $income_m = new Income;
            $income_m->product_id = $product_m->id;
            $income_from_f1 = str_replace("$", "", $value->income_from);
            $income_from_f2 = str_replace(",", "", $income_from_f1);
            $income_m->income_from = $income_from_f2;
            $income_m->show = 0;
            $income_m->created_at = $value->created_at;
            $income_m->updated_at = $value->updated_at;
            $income_m->save();

            $payment_m = new Payment;
            $payment_m->product_id = $product_m->id;
            $payments_from_f1 = str_replace("$", "", $value->monthly_payments_from);
            $payments_from_f2 = str_replace(",", "", $payments_from_f1);
            $payment_m->payments_from = $payments_from_f2;
            $payment_m->show = 0;
            $payment_m->created_at = $value->created_at;
            $payment_m->updated_at = $value->updated_at;
            $payment_m->save();

            $count++;
            echo "Conteo: ". $count ."<br>";
        }
    }

    public function migrateUsers()
    {
        $users_old = UserOld::all();
        foreach ($users_old as $key => $value) {
            //if ($value->id > 1) {
                $user_m = new User;
                $user_m->name = $value->name . " " . $value->last_name . " " . $value->mothers_last_name;
                $user_m->email = $value->email;
                $user_m->company = $value->company;
                $user_m->cellphone = $value->cellphone;
                $user_m->password = bcrypt("12345678");
                $user_m->status = $value->status;
                $user_m->permits_e = $value->permits_e;

                if ($value->company == "Sadasi") {
                    $user_m->permits = $value->permits . "SCO,";
                } else {
                    $user_m->permits = $value->permits;
                }

                $user_m->change_password = 0;
                $user_m->created_at = $value->created_at;
                $user_m->updated_at = $value->updated_at;
                $user_m->save();
            //}
        }
        echo "Finish Users";
    }

    public function migrateLogs()
    {
        $logs_old = logOld::all();
        foreach ($logs_old as $key => $value) {
            $log_m = new Log;
            $log_m->user_id = $value->user_id;
            $log_m->event = $value->event;
            $log_m->action = $value->action;
            $log_m->state_id = $value->state_id;
            $log_m->development_id = $value->development_id;

            $product_id = 0;
            if($value->product_id <= 276) {
                $product_id = $value->product_id;
            }
            if($value->product_id > 276) {
                $product_id = intval($value->product_id) - 8;
            }
            $log_m->product_id = $product_id;

            $log_m->userc_id = $value->userc_id;
            $log_m->description = $value->description;
            $log_m->created_at = $value->created_at;
            $log_m->updated_at = $value->updated_at;
            $log_m->save();
        }
        echo "Finish Logs";
    }

    public function migrateHistoryPrices()
    {
        $history_price_old = HistoryPriceOld::all();
        foreach ($history_price_old as $key => $value) {
            $history_price_m = new HistoryPrice;
            $product_id = 0;
            if($value->product_id <= 276) { $product_id = $value->product_id; }
            if($value->product_id > 276) { $product_id = intval($value->product_id) - 8; }
            $history_price_m->price_id = $product_id;
            $history_price_m->price = $value->price;
            $history_price_m->created_at = $value->created_at;
            $history_price_m->updated_at = $value->updated_at;            
            $history_price_m->save();
        }
        echo "Finish History Prices";
    }
}
