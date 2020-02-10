<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function getUsersEmailForCreateDevelopment()
    {
    	$emails = Setting::where('name', 'users_email_for_create_development')->first();
    	return explode(',', $emails->value);
    }

    public static function getUsersEmailForEditDevelopment()
    {
    	$emails = Setting::where('name', 'users_email_for_edit_development')->first();
    	return explode(',', $emails->value);
    }

    public static function getUsersEmailForDeleteDevelopment()
    {
    	$emails = Setting::where('name', 'users_email_for_delete_development')->first();
    	return explode(',', $emails->value);
    }

    public static function getUsersEmailForCreateProduct()
    {
        $emails = Setting::where('name', 'users_email_for_create_product')->first();
        return explode(',', $emails->value);
    }

    public static function getUsersEmailForEditProduct()
    {
        $emails = Setting::where('name', 'users_email_for_edit_product')->first();
        return explode(',', $emails->value);
    }

    public static function getUsersEmailForDeleteProduct()
    {
        $emails = Setting::where('name', 'users_email_for_delete_product')->first();
        return explode(',', $emails->value);
    }
}
