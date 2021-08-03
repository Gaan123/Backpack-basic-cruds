<?php

namespace App\Http\Controllers\traits;



trait SuperDefaultControllerTrait
{
    protected function superSetup()
    {
        $authUser = backpack_user();
        if ($this->constant_exists($this->crud->model, 'PERMISSIONSLUG')) {
            $permissionSlug = $this->crud->model::PERMISSIONSLUG;
            if (!$authUser->can('view-' . $permissionSlug)) {
                $this->crud->denyAccess('list');
            }
            if (!$authUser->can('create-' . $permissionSlug)) {
                $this->crud->denyAccess('create');
            }
            if (!$authUser->can('update-' . $permissionSlug)) {
                $this->crud->denyAccess('update');
            }
            if (!$authUser->can('delete-' . $permissionSlug)) {
                $this->crud->denyAccess('delete');
            }
        }
        $this->crud->operation('list', function () {
            $this->crud->removeButtonFromStack('show', 'line');
//            $this->crud->enablePersistentTable();
            $this->crud->enableExportButtons();
//            $this->crud->addColumn([
//                'name' => 'row_number',
//                'type' => 'row_number',
//                'label' => '#'
//            ])->makeFirstColumn();
        });
    }

    function constant_exists($class, $name)
    {
        if (is_string($class)) {
            return defined("$class::$name");
        } else if (is_object($class)) {
            return defined(get_class($class) . "::$name");
        }
        return false;
    }

}
