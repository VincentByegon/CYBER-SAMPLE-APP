<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.admin.users.index');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->approved = true;
        $user->save();
        $this->users = User::all();
        session()->flash('success', 'User approved successfully.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->users = User::all();
        session()->flash('success', 'User deleted successfully.');
    }
}
