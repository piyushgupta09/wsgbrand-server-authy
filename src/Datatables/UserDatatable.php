<?php

namespace Fpaipl\Authy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class UserDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'name#asc';
    
    /**
     * It is used to store batch uuid in cache with in this key.
     */
    const IMPORT_BATCH_UUID = 'User_batch_uuid';

    
    public static function baseQuery($model): Builder
    {
        return $model::query()->newUsers();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            default: return collect();
        }
    }

    public function topButtons(): array
    {
        return array_merge(
            parent::topButtonsPart2()  
        );
    }

    public function tableButtons(): array
    {
        return array(
            'view' => [
                'show' => [
                    'active' => $this->features()['row_actions']['show']['view']['active'],
                    'trash' => $this->features()['row_actions']['show']['view']['trash'],
                ],
                'label' => 'View',
                'icon' => 'bi bi-eye',
                'type' => 'buttons.action-link', 
                'style' => '',
                'route' => 'users.show',
                'function' => '',
                'confirm' => false,
            ],
        );
    }

    public function getColumns(): array
    {
        return array_merge(
            parent::getDefaultPreColumns(),
            array(
                'name' => [
                    'name' => 'name',
                    'labels' => [
                        'table' => 'Name',
                        'export' => 'Name'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Name',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
                'type' => [
                    'name' => 'type',
                    'labels' => [
                        'table' => 'Type',
                        'export' => 'Type'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => true,
                    'fillable' => [],
                ],
                'email' => [
                    'name' => 'email',
                    'labels' => [
                        'table' => 'Email',
                        'export' => 'Email'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Email',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
            ),
            parent::getDefaultPostColumns(),
        );
    }

}