<?php

namespace Fpaipl\Authy\Http\Livewire;

use Livewire\Component;
use Fpaipl\Authy\Models\Address;

class AddAddress extends Component
{
    // working
    public $showForm;
    public $formType;

    // prefill
    public $model;
    public $addresses;
    public $addressId;
    public $billingAddressId;
    public $shippingAddressId;

    // Form Fields
    public $name;
    public $gstin;
    public $line1;
    public $line2;
    public $state;
    public $pincode;
    
    // Alert
    public $showSuccess;
    public $showError;
    public $message;

    public function mount($modelId, $modelClass)
    {
        $this->model = $modelClass::find($modelId);
        $this->resetForm();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function resetForm()
    {
        $this->name = null;
        $this->gstin = null;
        $this->line1 = null;
        $this->line2 = null;
        $this->state = null;
        $this->pincode = null;
        $this->showForm = false;
        $this->formType = 'create';
        $this->reloadData();
    }

    public function reloadData()
    {
        $this->addresses = $this->model?->addresses ?? collect();
        $this->billingAddressId = $this->model->billing_address_id;
        $this->shippingAddressId = $this->model->shipping_address_id;
    }

    public function store()
    {
        // Validate form inputs
        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'gstin' => ['nullable', 'string', 'min:15', 'max:15'],
            'line1' => ['required', 'string', 'min:3', 'max:100'],
            'line2' => ['nullable', 'string', 'min:3', 'max:100'],
            'state' => ['required', 'string', 'min:3', 'max:100'],
            'pincode' => ['required', 'string', 'min:6', 'max:6'],
        ]);

        // Create address and attach it to the model
        $address = Address::create([
            'name' => $this->name,
            'gstin' => $this->gstin,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'addressable_type' => get_class($this->model),
            'addressable_id' => $this->model->id,
        ]);

        $this->model->billing_address_id = $address->id;
        $this->model->shipping_address_id = $address->id;
        $this->model->saveQuietly();

        if (get_class($this->model) == 'Fpaipl\Brandy\Models\Party') {
            return redirect()->route('parties.show', $this->model->sid)->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        } else {
            return redirect()->route('panel.dashboard')->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        }
    }

    public function edit($addressId)
    {
        // Find the address
        $address = Address::findOrFail($addressId);
        $this->addressId = $addressId;

        // Prefill the form fields
        $this->name = $address->name;
        $this->gstin = $address->gstin;
        $this->line1 = $address->line1;
        $this->line2 = $address->line2;
        $this->state = $address->state;
        $this->pincode = $address->pincode;

        // Change the form type to edit
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function update()
    {
        // Validate form inputs
        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'gstin' => ['required', 'string', 'min:15', 'max:15'],
            'line1' => ['required', 'string', 'min:3', 'max:100'],
            'line2' => ['required', 'string', 'min:3', 'max:100'],
            'state' => ['required', 'string', 'min:3', 'max:100'],
            'pincode' => ['required', 'string', 'min:6', 'max:6'],
        ]);

        // Find the address
        $address = Address::findOrFail($this->addressId);
        $address->name = $this->name;
        $address->gstin = $this->gstin;
        $address->line1 = $this->line1;
        $address->line2 = $this->line2;
        $address->state = $this->state;
        $address->pincode = $this->pincode;

        // Update the address
        $address->update();

        if (get_class($this->model) == 'Fpaipl\Brandy\Models\Party') {
            return redirect()->route('parties.show', $this->model->sid)->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        } else {
            return redirect()->route('panel.dashboard')->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        }

    }

    public function delete($addressId)
    {
        Address::find($addressId)->delete();
        $this->reloadData();
        
        if (get_class($this->model) == 'Fpaipl\Brandy\Models\Party') {
            return redirect()->route('parties.show', $this->model->sid)->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        } else {
            return redirect()->route('panel.dashboard')->with('toast', [
                'class' => 'success',
                'text' => 'Address created successfully'
            ]);
        }
    }

    public function setAsBilling($addressId)
    {
        $address = Address::find($addressId);
        $this->model->billing_address_id = $address->id;
        $this->model->update();
        $this->reloadData();
        $this->showAlert('success', 'Billing address updated successfully.');
    }

    public function setAsShipping($addressId)
    {
        $address = Address::find($addressId);
        $this->model->shipping_address_id = $address->id;
        $this->model->update();
        $this->reloadData();
        $this->showAlert('success', 'Shipping address updated successfully.');
    }

    public function showAlert($type, $message)
    {
        $this->message = $message;
        if ($type === 'error') {
            $this->showError = true;
            $this->showSuccess = false;
        } else {
            $this->showError = false;
            $this->showSuccess = true;
        }
    }

    public function closeAlerts()
    {
        $this->showError = false;
        $this->showSuccess = false;
        $this->reloadData();
    }

    public function render()
    {
        return view('authy::livewire.add-address');
    }
}
