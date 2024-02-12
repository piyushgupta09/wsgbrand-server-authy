<?php

namespace Fpaipl\Authy\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Authy\DataTables\UserDatatable as Datatable;

class UserController extends PanelController
{

    public function __construct()
    {
        parent::__construct(new Datatable(), 'App\Models\User' , 'user', 'users.index');
    }

    public function update(Request $request, User $user) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50'
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user->uuid)->with([
            'class' => 'success',
            'text' => 'User updated succefully'
        ]);
    }
   
    // Database Affecting Function

    public function destroy(User $User)
    {
     
        $response = User::safeDeleteModels(
            array($User->id), 
            'App\Models\User'
        );

        switch ($response) {
            case 'dependent':
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['has_dependency']
                ]);
                break;
            case 'success':
                session()->flash('toast', [
                    'class' => 'success',
                    'text' => $this->messages['delete_success']
                ]);
                break;    
            default: // failure
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['delete_error']
                ]);
                break;
        }

        return redirect()->route('users.index');
    }
}
