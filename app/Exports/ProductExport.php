<?php

namespace App\Exports;

use App\Models\Product;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

//class ProductExport implements FromCollection
class ProductExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
    	return Product::with('development', 'price', 'discount', 'income', 'payment')->where('status', 1)->get();
    }*/
    public function view(): View
    {
        return view('exports.excel.products', [
            'products' => Product::with([
            	'development' => function($query) {
            		$query->with('state');
            	}, 'price', 'discount', 'income', 'payment'])->orderBy('development_id', 'asc')->get()
        ]);
    }
}
