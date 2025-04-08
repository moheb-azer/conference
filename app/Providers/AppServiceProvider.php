<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $singletons = [
        \Filament\Http\Responses\Auth\Contracts\LoginResponse::class => \App\Http\Responses\LoginResponse::class,
        \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class => \App\Http\Responses\LogoutResponse::class,
    ];


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ViewAction::configureUsing(function ($action) {
            return $action->color('secondary')
//                ->button()
                ;
        });
        EditAction::configureUsing(function ($action) {
            return $action->color('info')
//                ->button()
                ;
        });
        DeleteAction::configureUsing(function ($action) {
            return $action->color('danger')
//                ->button()
                ;
        });
        TrashedFilter::configureUsing(function ($filter) {
            return $filter->native(false);
        });
        TernaryFilter::configureUsing(function ($filter) {
            return $filter->native(false);
        });
        Select::configureUsing(function ($input) {
            return $input->native(false);
        });
        DatePicker::configureUsing(function ($picker) {
            return $picker
                ->format('Y-m-d')
                ->displayFormat('Y-m-d')
                ->placeholder('YYYY-MM-DD')
                ->native(false);
        });
    }
}
