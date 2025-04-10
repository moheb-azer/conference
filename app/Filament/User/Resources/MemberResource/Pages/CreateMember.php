<?php

namespace App\Filament\User\Resources\MemberResource\Pages;

use App\Filament\User\Resources\MemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id() ?? null;
        $data['hasLogin'] = auth()->id() ?? null;
        $data['user_email'] = $data['user']['email'] ?? null;
        $data['user_password'] = $data['user']['password'] ?? null;
        unset($data['user']);
//        dd($data);
        return $data;
    }
}
