<?php

namespace App\Filament\User\Resources\MemberResource\Pages;

use App\Filament\User\Resources\MemberResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
//            Actions\DeleteAction::make(),
//            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user']['email'] = User::find($data['user_id'])->email;
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_email'] = $data['user']['email'] ?? null;
        $data['user_password'] = $data['user']['password'] ?? null;
        unset($data['user']);
        return $data;    }

}
