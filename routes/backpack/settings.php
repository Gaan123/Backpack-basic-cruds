<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    Route::crud('setting', 'App\Http\Controllers\Admin\Settings\SettingCrudController');
});
