<?php

namespace Fpaipl\Authy\Datatables;

use Fpaipl\Authy\Models\Address as Model;
use Illuminate\Database\Eloquent\Builder;
use Fpaipl\Panel\Datatables\ModelDatatable;
use Illuminate\Database\Eloquent\Collection;

class AddressDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'created_at#desc';
    
    public static function baseQuery($model): Builder
    {
        return $model::query();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            case 'models': 
                $collection = collect([
                    [
                        'id' => 'Fpaipl\Prody\Models\Supplier',
                        'name' => 'Supplier'
                    ],
                    [
                        'id' => 'Fpaipl\Brandy\Models\Party',
                        'name' => 'Party'
                    ],
                ]);
                return new Collection($collection);
            
            case 'supplier_id': 
                if (!class_exists('\Fpaipl\Prody\Models\Supplier')) {
                    return new Collection(collect());
                }
                $collection = collect(\Fpaipl\Prody\Models\Supplier::all()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name
                    ];
                }));
                return new Collection($collection);

            case 'party_id':
                if (!class_exists('\Fpaipl\Brandy\Models\Party')) {
                    return new Collection(collect());
                }
                $collection = collect(\Fpaipl\Brandy\Models\Party::all()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name
                    ];
                }));
                return new Collection($collection);

            default: return new Collection(collect());
        }
    }

    public function topButtons(): array
    {
        return array_merge(
            array(
                'add_new' => [
                    'show' => [
                        'active' => true,
                        'trash' => false,
                    ],
                    'icon' => 'bi bi-plus-lg',
                    'label' => 'Create',
                    'type' => 'buttons.action-link',
                    'style' => '',
                    'route' => 'addresses.create',
                    'function' => ''
                ],
            ),
            parent::topButtonsPart1(),
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
                'route' => 'addresses.show',
                'function' => '',
                'confirm' => false,
            ],
            'edit' => [
                'show' => [
                    'active' => $this->features()['row_actions']['show']['edit'],
                    'trash' => false, // Will always be false because we can't edit on trash page.
                ],
                'label' => 'Edit',
                'icon' => 'bi bi-pencil-square',
                'type' => 'buttons.action-link',
                'style' => '',
                'route' => 'addresses.edit',
                'function' => '',
                'confirm' => false,
            ],
            'delete' => [
                'show' => [
                    'active' => $this->features()['row_actions']['show']['delete'],
                    'trash' => false, //Will always be false because we can't delete on trash page.
                ],
                'label' => 'Delete',
                'icon' => 'bi bi-trash',
                'type' => 'buttons.action-delete',
                'style' => '',
                'route' => 'addresses.destroy',
                'function' => '',
                'confirm' => false, // To open confirm mode, we have to set  type' => 'buttons.action-btn' and 'confirm' => true
            ],
        );
    }

    public function getColumns(): array
    {
        return array_merge(
            parent::getDefaultPreColumns(),
            array(
                'model_name' => [
                    'name' => 'model_name',
                    'labels' => [
                        'table' => 'Model Name',
                        'export' => 'Model Name'
                    ],
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => 'getAddressableType',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => '',
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => '',
                        'style' => '',
                        'note' => 'Select Model Type First, and then select correct model name.',
                        'placeholder' => 'Choose Model Type',
                        'component' => 'forms.select-option',
                        'options' =>  [
                            'data' => self::selectOptions('models'),
                            'withRelation' => true,
                            'relation' => '',
                        ],
                        'attributes' => ['required','autofocus'],
                        'rows' => ''
                    ],
                ],
                'supplier_id' => [
                    'name' => 'supplier_id',
                    'labels' => [
                        'table' => 'Supplier Name',
                        'export' => 'Supplier Name'
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
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => '',
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => '',
                        'style' => '',
                        'p_style' => 'col-6',
                        'placeholder' => 'Choose Supplier Name',
                        'component' => 'forms.select-option',
                        'options' =>  [
                            'data' => self::selectOptions('supplier_id'),
                            'withRelation' => false,
                            'relation' => '',
                        ],
                        'attributes' => [],
                        'rows' => ''
                    ],
                ],
                'party_id' =>[
                    'name' => 'party_id',
                    'labels' => [
                        'table' => 'Party Name',
                        'export' => 'Party Name'
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
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => '',
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => '',
                        'style' => '',
                        'p_style' => 'col-6',
                        'placeholder' => 'Choose Party Name',
                        'component' => 'forms.select-option',
                        'options' =>  [
                            'data' => self::selectOptions('party_id'),
                            'withRelation' => false,
                            'relation' => '',
                        ],
                        'attributes' => [],
                        'rows' => ''
                    ],
                ],
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
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-12',
                        'placeholder' => 'Name',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'line1' => [
                    'name' => 'line1',
                    'labels' => [
                        'table' => 'Line1',
                        'export' => 'Line1'
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
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Line1',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'line2' => [
                    'name' => 'line2',
                    'labels' => [
                        'table' => 'Line2',
                        'export' => 'Line2'
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
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Line2',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'state' => [
                    'name' => 'state',
                    'labels' => [
                        'table' => 'State',
                        'export' => 'State'
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
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-md-6',
                        'placeholder' => 'State',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'pincode' => [
                    'name' => 'pincode',
                    'labels' => [
                        'table' => 'Pincode',
                        'export' => 'Pincode'
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
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-md-6',
                        'placeholder' => 'Pincode',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
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
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => ''
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-md-6',
                        'placeholder' => 'Contacts',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
            ),
            parent::getDefaultPostColumns()
        );
    }

}