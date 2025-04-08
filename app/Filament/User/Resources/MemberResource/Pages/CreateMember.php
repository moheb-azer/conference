<?php

namespace App\Filament\User\Resources\MemberResource\Pages;

use App\Filament\User\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected static bool $canCreateAnother = false;
}
