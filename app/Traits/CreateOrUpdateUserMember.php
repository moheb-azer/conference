<?php

namespace App\Traits;

use App\Models\User;

trait CreateOrUpdateUserMember
{
    /**
     * Boot the CreatesOrUpdatesUserForMember trait for the model.
     *
     * This method listens to the saving event so it fires on both create and update.
     */
    protected static function bootCreateOrUpdateUserMember(): void
    {
        static::saving(function ($model) {

            if (isset($model->user_email)) {
                // If the model already has an associated user, update it.
                if ($model->user_id) {
//                    dd($model->user_id);
                    $user = User::find($model->user_id);
                    if ($user) {
                        $updateData = [
                            'name'  => $model->name,
                            'email' => $model->user_email,
                        ];
                        // Only update password if provided
                        if (!empty($model->user_password)) {
                            $updateData['password'] = $model->user_password;
                        }
                        $user->update($updateData);
                    }
                } else {
                    // Otherwise, create a new user and assign its id to the member.
                    $user = User::create([
                        'name'     => $model->name,
                        'email'    => $model->user_email,
                        'password' => bcrypt($model->user_password),
                    ]);
                    $model->user_id = $user->id;
                }
                // Optionally remove the temporary attributes so they don't get persisted
                unset($model->user_email, $model->user_password);
            }
        });
    }
}
