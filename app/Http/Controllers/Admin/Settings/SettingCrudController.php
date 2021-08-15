<?php

namespace App\Http\Controllers\Admin\Settings;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Plank\Mediable\Mediable;

class SettingCrudController extends \Backpack\Settings\app\Http\Controllers\SettingCrudController
{
    use Mediable;

    public function setup()
    {
        CRUD::setModel(Setting::class);
        CRUD::setEntityNameStrings(trans('backpack::settings.setting_singular'), trans('backpack::settings.setting_plural'));
        CRUD::setRoute(backpack_url('setting'));
    }


}
