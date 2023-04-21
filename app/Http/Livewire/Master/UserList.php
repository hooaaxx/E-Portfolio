<?php

namespace App\Http\Livewire\Master;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UserList extends Component
{
    use WithPagination, LivewireAlert;
    public $action;
    public $searchTerm;
    public $viewModal;
    public $selectedUser;
    public $currentUserId;
    public $currentName;
    public $show = false;

    protected $listeners = [
        'refreshParent' => '$refresh',
        'getCurrentUser'
    ];

    public function getCurrentUser($currentUserId)
    {
        $this->currentUserId = $currentUserId;

        $currentUser = User::findOrFail($this->currentUserId);

        $this->currentName = $currentUser->name;
    }

    public function createUpdateModal()
    {
        $this->viewModal = 'createUpdate';
        $this->show = true;
    }

    public function deleteModal()
    {
        $this->viewModal = 'delete';
        $this->show = true;
    }

    public function selectUser($userId, $action)
    {
        $this->selectedUser = $userId;

        if ($action == 'update') {
            $this->emit('getModelId', $this->selectedUser);

            $this->createUpdateModal();
        }else{
            $this->emit('getCurrentUser', $this->selectedUser);

            $this->deleteModal();
        }
    }

    public function delete()
    {
        $deletePhoto = User::findOrFail($this->currentUserId);

        $profileImageDelete = $deletePhoto->profile_img;

        // if($profileImageDelete != 'default.png'){
        //     $imagePath = public_path('storage/photos/'.$profileImageDelete);
        //     $imagePathThumb = public_path('storage/photos_thumb/'.$profileImageDelete);

        //     if(User::exists($imagePath) | User::exists($imagePathThumb)){
        //         unlink($imagePath);
        //         unlink($imagePathThumb);
        //     }
        // }

        User::destroy($this->selectedUser);
        $this->alert('success', 'User Has Been Deleted!', [
            'position' => 'top',
            'timer' => '5000',
            'toast' => true,
            'showConfirmButton' => true,
            'onConfirmed' => '',
            'timerProgressBar' => true,
            'confirmButtonText' => 'Confirm',
        ]);
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $users = User::where(function($query){
            if(!empty($this->searchTerm)){
                $query->where('name', 'like', '%'.$this->searchTerm.'%');
                $query->orWhere('email', 'like', '%'.$this->searchTerm.'%');
            }
        })->latest()->paginate(3);
        return view('livewire.master.user-list', ['users' => $users]);
    }
}
