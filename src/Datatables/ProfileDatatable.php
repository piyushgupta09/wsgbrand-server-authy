<?php

namespace Fpaipl\Authy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Authy\Models\Profile as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class ProfileDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'mobile#asc';
    
    /**
     * It is used to store batch uuid in cache with in this key.
     */
    const IMPORT_BATCH_UUID = 'profile_batch_uuid';

    
    public static function baseQuery($model): Builder
    {
        return $model::query();
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
                'type' => 'buttons.action-link', // action-link - for new page && action-toggle to collapse
                'style' => '',
                'route' => 'profiles.show', // categories.show - for new page
                'function' => '',
                'confirm' => false, // This boolean value control that confirm modal will show or not
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
                ],
                'contacts' => [
                    'name' => 'contacts',
                    'labels' => [
                        'table' => 'Contacts',
                        'export' => 'Contacts'
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
                ],
            ),
            parent::getDefaultPostColumns(),
        );
    }

}