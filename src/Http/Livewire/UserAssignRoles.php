<?php

namespace Fpaipl\Authy\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserAssignRoles extends Component
{
    // working
    public $showForm;
    public $formType;

    public $role;
    public $roles = [];

    public $user;
    public $modelRoles = [];
    public $modelId;
    public $model;
    
    // Alert
    public $showSuccess;
    public $showError;
    public $message;

    /**
     * Initialize the component with the given model ID.
     * This method sets up the user model based on the provided ID, fetches the authenticated user,
     * and prepares a list of assignable roles based on the user type and permissions.
     *
     * @param  mixed  $modelId  The identifier of the user model to be used.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException if the user model is not found.
     * @return void
     */
    public function mount($modelId)
    {
        $this->modelId = $modelId;
        $this->model = User::find($this->modelId);

        if (!$this->model) {
            return redirect()->route('users.index')->with('toast', [
                'class' => 'danger',
                'text' => 'User not found.'
            ]);
        }
        // dd($this->model);
        $this->user = auth()->user();
        $assigableRoles = config('panel.assignable-roles');
        if (!array_key_exists($this->model->type, $assigableRoles)) {
            return redirect()->route('users.index')->with('toast', [
                'class' => 'danger',
                'text' => 'User type not found.'
            ]);
        }
        $this->roles = $assigableRoles[$this->model->type];

        // Assuming $this->model->roles returns an array of role ids for the model
        $this->modelRoles = $this->model->roles->pluck('id')->toArray();

        $this->roles = array_filter($this->roles, function ($role) {
            return $this->isRoleAssignable($role);
        });

        return redirect()->route('users.show', $this->model->uuid)->with('toast', [
            'class' => 'success',
            'text' => 'Role Assigned'
        ]);
    }

    /**
     * Determines if a role is assignable to the user model.
     * This method checks the role's grade and compares it with the roles of the model and the permissions of the authenticated user.
     * 
     * Each role has a grade, first get the auth user role grade, then filter the roles, that b grade can assign to c grade, 
     * a grade can assign to b and c grade and $user->isSuperAdmin() can assign to all grades.
     * 
     * @param  array  $role  The role to check for assignability.
     * @return bool   Returns true if the role is assignable, false otherwise.
     */
    protected function isRoleAssignable($role)
    {
        // Check if the user is a super admin
        if ($this->user->isSuperAdmin()) {
            return true;
        }
    
        // Check if the user has the same role that is being assigned
        if ($this->user->hasRole($role['id'])) {
            return true;
        }
    
        // Retrieve the user's roles with grades
        $userRolesWithGrades = $this->getUserRolesWithGrades();
    
        // Check if the user can assign the given role based on grade hierarchy
        if ($role['grade'] === 'C' && in_array('B', $userRolesWithGrades)) {
            return true;
        }
        if ($role['grade'] === 'B' && in_array('A', $userRolesWithGrades)) {
            return true;
        }
    
        // User cannot assign this role
        return false;
    }
    
    /**
     * Get the grades of the roles that the current user has.
     * 
     * @return array The array of role grades that the user has.
     */
    protected function getUserRolesWithGrades()
    {
        $roles = $this->user->getRoleNames(); // Assuming this returns role names
        $grades = [];
    
        foreach ($roles as $roleName) {
            // Assuming a method to get the grade of a role by its name
            $roleGrade = $this->getRoleGradeByName($roleName);
            if ($roleGrade) {
                $grades[] = $roleGrade;
            }
        }
    
        return $grades;
    }
    
    /**
     * Get the grade of a role by its name.
     * 
     * @param  string $roleName The name of the role.
     * @return string|null The grade of the role, or null if not found.
     */
    protected function getRoleGradeByName($roleName)
    {
        foreach (config('panel.assignable-roles') as $type => $roles) {
            foreach ($roles as $role) {
                if ($role['name'] === $roleName) {
                    return $role['grade'];
                }
            }
        }
    
        return null;
    }
    
    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function reloadData()
    {   
        $this->modelRoles = $this->model->roles()->pluck('name')->toArray();
    }

    public function assignRole()
    {
        if (!in_array($this->role, array_column($this->roles, 'id'))) {
            session()->flash('message', 'Role does not exist.');
            return;
        }
        
        // $this->model->assignRole($this->model->type);
        $this->model->assignRole($this->role);
        $this->reloadData();

        return redirect()->route('users.show', $this->model->uuid)->with('toast', [
            'class' => 'success',
            'text' => 'Role assigned successfully.'
        ]);
    }

    public function removeRole($roleName)
    {
        $this->model->removeRole($roleName);
        $this->reloadData();
        session()->flash('message', 'Role removed successfully.');
    }

    public function removeAllRoles()
    {
        $this->model->roles()->detach();
        $this->reloadData();
        session()->flash('message', 'All roles removed successfully.');
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
        return view('authy::livewire.user-assign-roles');
    }
}
