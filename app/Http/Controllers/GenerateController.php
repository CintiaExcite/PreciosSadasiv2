<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Impression;
use App\Models\Development;
use Illuminate\Http\Request;

class GenerateController extends Controller
{
    public function priceWithTextG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		Impression::views($product->id, 'web');
		if ($product->comming_soon == 0 && $product->available == 1) {
			$price = $product->price()->first();
			$price_f = number_format($price->price, 2);
			$text_before_price_f = '';
			$text_after_price_f = '';
			if ($price->text_before_price != null && $price->text_before_price != "") {
				$text_before_price_f = $price->text_before_price;
				if (strpos($price->text_before_price, ':') == false) {
					$text_before_price_f = $text_before_price_f . ': ';
				}
			}
			if ($price->text_after_price != null && $price->text_after_price != "") {
				$text_after_price_f = '<p class="precio-texto-despues">' . $price->text_after_price . '</p>'; 
			}
			return '<p class="precio-texto-antes">' . $text_before_price_f . 
					'<span class="precio-modelo">$' . $price_f . '<span><sup style="font-size: 15px;">*</sup></sup>
					</p>' . $text_after_price_f;
		}
		if ($product->available == 0) { return '<p class="precio-modelo">AGOTADO</p>'; }
		if ($product->comming_soon == 1) { return '<p class="precio-modelo">Próximamente</p>'; }
		return "";
	}

	public function priceG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		Impression::views($product->id, 'web');
		if ($product->comming_soon == 0 && $product->available == 1) {
			$price = $product->price()->first();
			return '<p class="precio-modelo">$' . number_format($price->price, 2) . '<sup style="font-size: 15px;">*</sup></p>';
		}
		if ($product->available == 0) { return '<p class="precio-modelo">AGOTADO</p>'; }
		if ($product->comming_soon == 1) { return '<p class="precio-modelo">Próximamente</p>'; }
		return "";
	}

	public function lowestPriceBetweenModelsG(Request $request)
	{
		header('Access-Control-Allow-Origin: *');
		$products_array = explode(",", $request->products_id);
		$products = Product::with('price', 'discount', 'income', 'payment' )->where([['comming_soon', 0], ['available', 1]])->whereIn('id', $products_array)->get();
		$products = $products->toArray();
		usort($products, function($a, $b) { return $a['price']['price'] - $b['price']['price']; });
		$price_f = number_format($products[0]['price']['price'], 2);
		$text_before_price_f = 'Desde: ';
		return '<p class="precio-texto-antes">' . $text_before_price_f . 
				'<span class="precio-modelo">$' . $price_f . '<span><sup style="font-size: 15px;">*</sup></sup>
				</p>';
	}

	public function priceSinceByDevelopmentG(Development $development)
	{
		header('Access-Control-Allow-Origin: *');
		$products = $development->products()->with('price', 'discount', 'income', 'payment')->where([['comming_soon', 0], ['available', 1]])->get();
		$products = $products->toArray();
		usort($products, function($a, $b) { return $a['price']['price'] - $b['price']['price']; });
        $price_since = number_format($products[0]['price']['price'], 2);
        return '<p class="precio-modelo">$' . $price_since . '<sup style="font-size: 15px;">*</sup></p>';
	}

	public function discountIncomePaymentsG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		$discount = $product->discount()->first();
		$income = $product->income()->first();
		$payment = $product->payment()->first();
		$discount_f = $discount->show == 1 ? number_format($discount->discount, 2) : '';
		$income_f = $income->show == 1 ? number_format($income->income_from, 2) : '';
		$payment_f = $payment->show == 1 ? number_format($payment->payments_from, 2) : '';
		$discount_r = '';
		$income_r = '';
		$payment_r = '';
		if ($discount_f != '') {
			$discount_r = '<p class="descuento-modelo">Descuento: <span>$' . $discount_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
		if ($income_f != '') {
			$income_r = '<p class="ingresos-modelo">Ingresos mensuales desde: <span>$' . $income_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
		if ($payment_f != '') {
			$payment_r = '<p class="pagos-modelo">Pagos mensuales desde: <span>$' . $payment_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
	}

	public function discountProductG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		$discount = $product->discount()->first();
		$discount_f = $discount->show == 1 ? number_format($discount->discount, 2) : '';
		$discount_r = '';
		if ($discount_f != '') {
			$discount_r = '<p class="descuento-modelo">Descuento: <span>$' . $discount_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
		return $discount_r;
	}

	public function incomeProductG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		$income = $product->income()->first();
		$income_f = $income->show == 1 ? number_format($income->income_from, 2) : '';
		$income_r = '';
		if ($income_f != '') {
			$income_r = '<p class="ingresos-modelo">Ingresos mensuales desde: <span>$' . $income_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
		return $income_r;
	}

	public function paymentsProductG(Product $product)
	{
		header('Access-Control-Allow-Origin: *');
		$payment = $product->payment()->first();
		$payment_f = $payment->show == 1 ? number_format($payment->payments_from, 2) : '';
		$payment_r = '';
		if ($payment_f != '') {
			$payment_r = '<p class="pagos-modelo">Pagos mensuales desde: <span>$' . $payment_f . '<sup style="font-size: 15px;">*</sup></span></p>';
		}
		return $payment_r;
	}

	public function developmentsWpG()
	{
		header('Access-Control-Allow-Origin: *');
		$developments = Development::select('id as value', 'development as text')->where('status', 1)->get();
		return response()->json($developments, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
	}

	public function productsWpG(Development $development)
	{
		header('Access-Control-Allow-Origin: *');
		$products = $development->products()->select('id as value', 'product as text')->where('status', 1)->get();
		return response()->json($products, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
	}
}