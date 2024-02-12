<div class="card border-0 mt-3">

    <div class="card-header rounded-0 bg-dark py-2 d-flex justify-content-between align-items-center w-100">
        <button class="btn border-0 ps-0 text-white flex-fill text-start" type="button" 
            data-bs-toggle="collapse" data-bs-target="#modelNewAddress" 
            aria-expanded="true" aria-controls="modelNewAddress">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <i class="bi bi-chevron-down me-2"></i>
                    <span class="font-quick ls-1 fw-bold">Add Address</span>
                </div>
            </div>
        </button>
        <div class="">
            <button 
                class="btn border-0 text-white font-quick ls-1 fw-bold" 
                type="button" wire:click='reloadData'>
                <i class="bi bi-arrow-clockwise"></i>
            </button>
            <button 
                class="btn border-0 text-white font-quick ls-1 fw-bold" 
                type="button" wire:click="toggleForm">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </div>

    <div x-data="{ show: @entangle('showForm') }" x-show="show" x-transition.delay.100ms class="card-body p-0 text-bg-secondary">

        <div class="row m-0 py-3">

            <p class="font-robot ls-1">
                @if ($formType == 'create')
                    Add New Address
                @else
                    Edit Address
                @endif
            </p>

            {{-- Business Name --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressName" class="form-control" wire:model.lazy="name" required>
                    <label for="inputAddressName" class="font-quick text-dark font-normal">Business Name</label>
                    @error('name')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- GSTIN --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressGstin" class="form-control" wire:model.lazy="gstin" required>
                    <label for="inputAddressGstin" class="font-quick text-dark font-normal">GSTIN</label>
                    @error('gstin')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Line 1 --}}
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressline1" class="form-control" wire:model.lazy="line1" required>
                    <label for="inputAddressline1" class="font-quick text-dark font-normal">Line 1</label>
                    @error('line1')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Line 2 --}}
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressline2" class="form-control" wire:model.lazy="line2" required>
                    <label for="inputAddressline2" class="font-quick text-dark font-normal">Line 2</label>
                    @error('line2')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- State --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressState" class="form-control" wire:model.lazy="state" required>
                    <label for="inputAddressState" class="font-quick text-dark font-normal">State</label>
                    @error('state')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Pincode --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" id="inputAddressPincode" class="form-control" wire:model.lazy="pincode" required>
                    <label for="inputAddressPincode" class="font-quick text-dark font-normal">Pincode</label>
                    @error('pincode')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="btn-group w-50 ms-auto">
                <button type="button" wire:click.prevent="resetForm()" class="btn btn-outline-light">Reset</button>
                @if ($formType == 'create')
                    <button type="button" wire:click.prevent="store()" class="btn btn-info">Save</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-info">Update</button>
                @endif
            </div>
            
        </div>

    </div>

    <div class="collapse show" id="modelNewAddress">
        @if ($addresses->isEmpty())
            <p class="p-3 mb-0">
                No Address is available yet.
            </p>
        @else     
            <ul class="list-group list-group-flushed">
                @foreach($addresses as $key => $address)
                    <li class="list-group-item rounded-0">
        
                        <div class="d-flex">
                            
                            <div class="flex-fill d-flex align-items-center font-quick mb-2">
                                <span style="width: 20px">{{ $loop->iteration }}</span>
                                <p class="mb-0 text-capitalize fw-bold">{{ $address->name }}</p>

                                @if ($billingAddressId == $address->id)
                                    <span class="badge bg-success rounded-pill ms-2">Billing</span>
                                @else    
                                    <button 
                                        class="btn btn-sm btn-outline-dark ms-3" 
                                        wire:click.prevent='setAsBilling({{ $address->id }})'>
                                        For Billing
                                    </button>
                                @endif
                                    
                                @if ($shippingAddressId == $address->id)
                                    <span class="badge bg-success rounded-pill ms-2">Shipping</span>
                                @else
                                    <button 
                                        class="btn btn-sm btn-outline-dark ms-3" 
                                        wire:click.prevent='setAsShipping({{ $address->id }})'>
                                        For Shipping
                                    </button>
                                @endif

                            </div>
        
                            <div class="btn-group">
                                <button 
                                    class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseAddressDetail{{ $key }}"
                                    aria-expanded="false" aria-controls="collapseAddressDetail{{ $key }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button 
                                    class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                                    wire:click.prevent="edit({{ $address->id }})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button 
                                    class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                                    wire:click.prevent="delete({{ $address->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
        
                        </div>
                        
                        <div class="collapse" id="collapseAddressDetail{{ $key }}">
                            <div class="d-flex flex-column">
                                <span>{{ $address->line1 }}</span>
                                <span>{{ $address->line2 }}</span>
                                <span>{{ $address->state }}</span>
                                <span>{{ $address->pincode }}</span>
                                <span>GSTIN: {{ $address->gstin }}</span>
                            </div>
                        </div>
                        
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @include('panel::includes.livewire-alert')

</div>