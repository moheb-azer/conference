<?php

namespace App\Filament\User\Resources\MemberResource\RelationManagers;

use App\Models\FamilyRelation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FamilyMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'familyMembers';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),
                Forms\Components\DatePicker::make('dob')
                    ->required()
                    ->maxDate(now()->subDay())
                    ->columnSpan(6),
                Forms\Components\Select::make('gender')
                    ->required()
                    ->options([
                        'm' => 'Male',
                        'f' => 'Female',
                    ])
                    ->reactive()
                    ->columnSpan(6),
                Forms\Components\Select::make('family_relation_id')
                    
//                    ->relationship('familyRelation', 'name')
                    ->reactive()
                    ->options(function (callable $get) {
                        $gender = $get('gender');
                        if (!$gender) {
                            return null;
                        } else {
                            $query = FamilyRelation::query();
                            $parentGender = $this->getOwnerRecord()->gender;
                            if ($parentGender === 'm') {
                                $query->where('name', '!=', 'Husband');
                            } elseif ($parentGender === 'f') {
                                $query->where('name', '!=', 'Wife');
                            }
                            // Get the current gender from the form
                            if (!$gender) {
                                $query = [];
                            }
                            if ($gender === 'f') {
                                $query->where('name', '!=', 'Husband');
                                $query->where('name', '!=', 'Father');
                                $query->where('name', '!=', 'Brother');
                                $query->where('name', '!=', 'Son');
                            } elseif ($gender === 'm') {
                                $query->where('name', '!=', 'Mother');
                                $query->where('name', '!=', 'Wife');
                                $query->where('name', '!=', 'Sister');
                                $query->where('name', '!=', 'Daughter');
                            }
                            // Return the remaining options as an array of [id => name]
                            return $query->pluck('name', 'id');
                        }

                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan(6),
                Forms\Components\TextInput::make('hasLogin')
                    ->default(null)
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
                    ->label(__('Whatsapp'))
//                    ->hint(__("enter phone number to check"))
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
                    ->label(__('Whatsapp'))
                    ->inline(false)
//                    ->hint(__("enter phone number to check"))
                    ->reactive()
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
            ])->columns(12);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image')
                    ->label(__(''))
                    ->circular(),
                TextColumn::make('name'),
                TextColumn::make('age')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->formatStateUsing(function ($state) {
                        return $state === 'm' ? __('Male') : __('Female');
                    })
                    ->searchable(),
                TextColumn::make('familyRelation.name'),
                TextColumn::make('phone1')
                    ->searchable(),
                TextColumn::make('phone2')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                ,
                IconColumn::make('whatsapp')
                    ->searchable()
                    ->icon('fab-whatsapp')
                    ->url(function ($record) {
                        if ($record->whatsapp) {
                            return 'https://api.whatsapp.com/send?phone=2'.$record->whatsapp.'&text=';
                        }
                        return null;
                    })
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
