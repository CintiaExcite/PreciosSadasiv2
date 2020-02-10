<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

use App\Mail\Development\Create as CreateDevelopment;
use App\Mail\Development\Edit as EditDevelopment;
use App\Mail\Development\Delete as DeleteDevelopment;
use App\Mail\Product\Create as CreateProduct;
use App\Mail\Product\Edit as EditProduct;
use App\Mail\Product\Delete as DeleteProduct;
use App\Mail\User\RecoveryPassword;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Model
{
    /**
	 * Create Development
     */
	public static function EmailCreateDevelopment($development)
	{
    	$users_email = Setting::getUsersEmailForCreateDevelopment();
    	if (count($users_email)) { return true; }
    	Mail::to($users_email)->send(new CreateDevelopment($development));
    }

    /**
	 * Edit Development
     */
	public static function EmailEditDevelopment($development)
	{
    	$users_email = Setting::getUsersEmailForEditDevelopment();
    	if (count($users_email)) { return true; }
    	Mail::to($users_email)->send(new EditDevelopment($development));
    }

    /**
	 * Delete Development
     */
	public static function EmailDeleteDevelopment($development)
	{
    	$users_email = Setting::getUsersEmailForDeleteDevelopment();
    	if (count($users_email)) { return true; }
    	Mail::to($users_email)->send(new DeleteDevelopment($development));
    }


    /**
     * Create Product
     */
    public static function EmailCreateProduct($product)
    {
        $users_email = Setting::getUsersEmailForCreateProduct();
        if (count($users_email)) { return true; }
        Mail::to($users_email)->send(new CreateProduct($product));
    }

    /**
     * Edit Product
     */
    public static function EmailEditProduct($product)
    {
        $users_email = Setting::getUsersEmailForEditProduct();
        if (count($users_email)) { return true; }
        Mail::to($users_email)->send(new EditProduct($product));
    }

    /**
     * Delete Product
     */
    public static function EmailDeleteProduct($product)
    {
        $users_email = Setting::getUsersEmailForDeleteProduct();
        if (count($users_email)) { return true; }
        Mail::to($users_email)->send(new DeleteProduct($product));
    }

    /**
     * Recovery Password
     */
    public static function EmailRecoveryPassword($user_email, $pass_temp)
    {
        Mail::to($user_email)->send(new RecoveryPassword($pass_temp));
    }
}
