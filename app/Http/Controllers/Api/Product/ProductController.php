<?php

namespace App\Http\Controllers\Api\Product;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\Price;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Discount;
use App\Models\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if (User::searchPermitOnArray("M")) {
            $products = Product::with('development', 'price', 'discount', 'income', 'payment')->where('status', 1)->get();
        } else {
            $permits_e = User::permitsEArray();
            $products = Product::with('development', 'price')->where('status', 1)->whereIn('development_id', $permits_e)->get();
        }
        return $this->showAll($products);
    }

    /**
     * Display a listing of the resource for datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDT(Request $request)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $columns = ['id', 'development_id', 'code', 'product', 'comming_soon', 'available', 'status'];
        $page = $request->input('page');
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if (User::searchPermitOnArray("M")) {
            $query = Product::select('id', 'development_id', 'code', 'product', 'comming_soon', 'available', 'status')->with('price', 'discount', 'income', 'payment')->orderBy($columns[$column], $dir);
        } else {
            $permits_e = User::permitsEArray();
            $query = Product::select('id', 'development_id', 'code', 'product', 'comming_soon', 'available', 'status')->with('price', 'discount', 'income', 'payment')->whereIn('development_id', $permits_e)->orderBy($columns[$column], $dir);
        }
        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('product', 'like', '%' . $searchValue . '%');
            });
        }
        $products = $query->paginate($length, ['*'], 'page', $page);
        $custom = collect(['draw' => $request->input('draw')]);
        $data = $custom->merge($products);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!User::searchPermitOnArray("MC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'development_id' => 'required|integer',
            'code' => 'required',
            'product' => 'required',
            'comming_soon' => 'required|integer',
            'available' => 'required|integer',
            'status' => 'required|integer',
        ];
        $messages = [
            'development_id.required' => 'Desarrollo es requerido',
            'development_id.integer' => 'Desarrollo debe ser numérico',
            'code.required' => 'Código del modelo es requerido',
            'product.required' => 'Modelo es requerido',
            'comming_soon.required' => 'Próximamente es requerido',
            'comming_soon.integer' => 'Próximamente debe ser numérico',
            'available.required' => 'Disponible es requerido',
            'available.integer' => 'Disponible debe ser numérico',
            'status.required' => 'Estatus es requerido',
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        $product = new Product;
        $product->development_id = $request->development_id;
        $product->code = $request->code;
        $product->product = $request->product;
        $product->comming_soon = $request->comming_soon;
        $product->release_date = $request->release_date;
        $product->available = $request->available;
        $product->status = $request->status;
        if ($product->save()) {
            $price = new Price;
            $price->product_id = $product->id;
            $price->price = 0.00;
            $price->text_before_price = '';
            $price->text_after_price = '';
            $price->save();
            $discount = new Discount;
            $discount->product_id = $product->id;
            $discount->discount = 0.00;
            $discount->save();
            $income = new Income;
            $income->product_id = $product->id;
            $income->income_from = 0.00;
            $income->save();
            $payment = new Payment;
            $payment->product_id = $product->id;
            $payment->payments_from = 0.00;
            $payment->show = 0;
            $payment->save();
            $user = JWTAuth::parseToken()->authenticate();
            Log::logProduct($user->id, 1, $product->id, $product->product);
            SendEmail::EmailCreateProduct($product);
            return $this->showOne($product); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (!User::searchPermitOnArray("ME")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'development_id' => 'integer',
            'comming_soon' => 'integer',
            'available' => 'integer',
            'status' => 'integer',
        ];
        $messages = [
            'development_id.integer' => 'Desarrollo debe ser numérico',
            'comming_soon.integer' => 'Próximamente debe ser numérico',
            'available.integer' => 'Disponible debe ser numérico',
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        $comming_soon = 0;
        if ($request->has('development_id')) { $product->development_id = $request->development_id; }
        if ($request->has('code')) { $product->code = $request->code; }
        if ($request->has('product')) { $product->product = $request->product; }
        if ($request->has('comming_soon')) { $product->comming_soon = $request->comming_soon; $comming_soon = 1; }
        if ($request->has('release_date')) { $product->release_date = $request->release_date; $comming_soon = 1;}
        if ($request->has('available')) { $product->available = $request->available; }
        if ($request->has('status')) { $product->status = $request->status; }
        if ($product->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        if ($product->save()) { 
            $user = JWTAuth::parseToken()->authenticate();
            Log::logProduct($user->id, 2, $product->id, $product->product);
            SendEmail::EmailEditProduct($product);
            if ($comming_soon == 1) { Log::logProduct($user->id, 4, $product->id, $product->product); }
            return $this->showOne($product);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!User::searchPermitOnArray("MD")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if ($product->delete()) { SendEmail::EmailDeleteProduct($product); return $this->showOne($product); }
        return $this->errorResponse("No se ha podido borrar el registro.", 422);
    }

    public function updateProductImage(Request $request, Product $product)
    {
        //dd($request);
        if (!User::searchPermitOnArray("ME")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'image_sys' => 'file',
        ];
        $messages = [
            'image_sys.file' => 'Imagen debe ser un archivo',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('image_sys')) {
            $product_path = str_replace("http://sadasi.test/precios/public/files/", "", $product->image_sys);
            Storage::disk('files')->delete($product_path);
            $product->image_sys = $request->image_sys->store('products/models');
            //Storage::delete($product->image_sys);
            //$product->image_sys = $request->image_sys->store('');
        }
        if ($product->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            Log::logProduct($user->id, 6, $product->id, $product->product);
            return $this->showOne($product);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    public function downloadExcel()
    {
        return Excel::download(new ProductExport, 'modelos.xlsx');
    }
}