<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\MemberResource\Pages;
use App\Filament\User\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class   MemberResource extends Resource
{

    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $label = 'Profile';
    public static function getPluralLabel(): ?string
    {
        return 'Profile';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make(__('Login Data'))
                    ->schema([
                        Forms\Components\TextInput::make('user.email')
                            ->email()
                            ->default(Auth::user()->email)
                            ->rules(function (callable $get, ?\App\Models\Member $record) {
                                $userId = $record?->user?->id ?? Auth::id();
                                return [
                                    Rule::unique('users', 'email')->ignore($userId),
                                ];
                            })
                            ->required()
                            ->maxLength(200),
                        Forms\Components\TextInput::make('user.password')
                            ->label(fn($livewire) => $livewire instanceof EditRecord
                                ? __('New Password') // Label for edit form
                                : __('Password') // Label for create form
                            )
                            ->password()
                            ->nullable() // Allow null when editing
//                            ->required(fn($livewire) => $livewire instanceof CreateRecord) // Required only on create
                            ->hidden(fn($livewire) => $livewire instanceof ViewRecord) // Required only on create
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // Hash password if filled
                            ->dehydrated(fn($state) => filled($state)) // Only dehydrate when a password is provided
                            ->maxLength(20),
                    ])->columnSpan(2),

                Fieldset::make(__('Login Data'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->default(Auth::user()->name)
                            ->maxLength(255)
                            ->columnSpan(4),
                        Forms\Components\DatePicker::make('dob')
                            ->required()
                            ->maxDate(now()->subDay())
                            ->columnSpan(4),
                        Forms\Components\Select::make('gender')
                            ->required()
                            ->options([
                                'm' => 'Male',
                                'f' => 'Female',
                            ])
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('hasLogin')
                            ->default(auth()->id())
                            ->disabled()
                            ->hidden(),
                        Forms\Components\TextInput::make('phone1')
                            ->tel()
                            ->reactive()
                            ->required()
                            ->length(11)
                            ->startsWith(['010', '011', '012', '015'])
                            ->columnSpan(3),
                        Forms\Components\Checkbox::make('is_phone1_whatsapp')
                            ->label(__('Is Whatsapp'))
                            ->hint(__("enter phone number to check"))
                            ->reactive()
                            ->inline(false)
                            ->disabled(fn($get) => !$get('phone1') || strlen($get('phone1')) !== 11)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $set('is_phone2_whatsapp', false); // Ensure phone2's WhatsApp checkbox is unchecked
                                }
                            })
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('phone2')
                            ->tel()
                            ->reactive()
                            ->length(11)
                            ->startsWith(['010', '011', '012', '015'])
                            ->columnSpan(3),
                        Forms\Components\Checkbox::make('is_phone2_whatsapp')
                            ->label(__('Is Whatsapp'))
                            ->hint(__("enter phone number befor check"))
                            ->reactive()
                            ->inline()
                            ->disabled(fn($get) => !$get('phone2') || strlen($get('phone2')) !== 11)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $set('is_phone1_whatsapp', false); // Ensure phone1's WhatsApp checkbox is unchecked
                                }
                            })
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label(__(''))
                            ->tel()
                            ->reactive()
                            ->hidden()
                            ->length(11)
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                if ($state == "") {
                                    $set('is_phone1_whatsapp', false);
                                    $set('is_phone2_whatsapp', false);
                                } elseif ($state === $get('phone1')) {
                                    $set('is_phone1_whatsapp', true);
                                    $set('is_phone2_whatsapp', false);
                                } elseif ($state === $get('phone2')) {
                                    $set('is_phone2_whatsapp', true);
                                    $set('is_phone1_whatsapp', false);
                                }
                            }),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper(),
//                        Forms\Components\TextInput::make('hasLogin')
//                            ->required()
//                            ->numeric(),
//                        Forms\Components\Select::make('user_id')
//                            ->relationship('user', 'name')
//                            ->required(),
                    ])
                    ->columns(12)
                    ->columnSpan(2),
            ])->columns(2);
    }

//    public static function table(Table $table): Table
//    {
//        return $table
//            ->columns([
//                Tables\Columns\TextColumn::make('name')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('dob')
//                    ->date()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('gender')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('familyRelation.name')
//                    ->numeric()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('phone1')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('phone2')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('whatsapp')
//                    ->searchable(),
//                Tables\Columns\ImageColumn::make('image'),
//                Tables\Columns\TextColumn::make('hasLogin')
//                    ->numeric()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('user.name')
//                    ->numeric()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('created_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('deleted_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//            ])
//            ->filters([
//                Tables\Filters\TrashedFilter::make(),
//            ])
//            ->actions([
//                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
//            ])
//            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\RestoreBulkAction::make(),
//                ]),
//            ]);
//    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
