<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandleWhatsappPhone
{
    /**
     * Boot the HandlesWhatsappPhone trait.
     */
    protected static function bootHandleWhatsappPhone(): void
    {

        static::saving(function ($model) {
            // Always update whatsapp based on the flags
            static::handleWhatsappPhone($model);
        });
//        static::creating(function ($model) {
//
//            static::handleWhatsappPhone($model);
////            dd($model);
//        });
//        static::updating(function ($model) {
//            static::handleWhatsappPhone($model);
//        });
    }

    /**
     * Handle the assignment of the WhatsApp phone number.
     *
     * @param  Model  $model
     * @return void
     */
    public static function handleWhatsappPhone(Model $model): void
    {
        if (isset($model->is_phone1_whatsapp) && $model->is_phone1_whatsapp) {
            $model->whatsapp = $model->phone1;
        } elseif (isset($model->is_phone2_whatsapp) && $model->is_phone2_whatsapp) {
            $model->whatsapp = $model->phone2;
        } else {
            $model->whatsapp = null;
        }
        unset($model->is_phone1_whatsapp, $model->is_phone2_whatsapp);


//        dd($model);
    }
}
