<?php

namespace App\Http\Livewire\Master;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class CreateOrUpdateUser extends Component
{
    use WithFileUploads, LivewireAlert;

    public $iteration;
    public $role_name;
    public $name;
    public $email;
    public $password;
    public $modelId;
    public $profileImage;

    protected $listeners = [
        'getModelId',
        'forcedCloseModal',
    ];

    public function getModelId($modelId)
    {
        $this->modelId = $modelId;

        $model = User::findOrFail($this->modelId);

        $this->role_name = $model->role_name;
        $this->name = $model->name;
        $this->email = $model->email;
        // $this->password = $model->password;
    }

    protected $rules = [
        'role_name' => 'required',
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|max:20',
    ];

    public function updated($propertyName)
    {

        if(!empty($this->profileImage)){
            $this->validateOnly($propertyName, [
                'role_name' => 'required',
                // 'profileImage' => 'image|max:1024', // 1MB Max
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|max:20',
            ]);
        }elseif(User::where('email', '=', $this->email)->exists()){
            if($this->modelId){
                $modelUpdate = User::findOrFail($this->modelId);

                if($this->email === $modelUpdate->email){
                    $this->validateOnly($propertyName, [
                        'role_name' => 'required',
                        'name' => 'required',
                        'email' => 'required|email',
                        'password' => 'required|min:8|max:20',
                    ]);
                }else{
                    $this->validateOnly($propertyName, [
                        'role_name' => 'required',
                        'name' => 'required',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:8|max:20',
                    ]);
                }
            }else{
                $this->validateOnly($propertyName, [
                    'role_name' => 'required',
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8|max:20',
                ]);
            }

        }else{
            $this->validateOnly($propertyName);
        }
    }

    public function createuser()
    {
        $data = [
            'role_name' => $this->role_name,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            // 'profile_img' => 'default.png'
        ];

        $this->validate();

        // if (!empty($this->profileImage)) {
        //     $imageHashName = $this->profileImage->hashName();

        //     // This is to save the filename of the image in the database
        //     $this->validate([
        //         'profileImage' => 'image|max:1024', // 1MB Max
        //     ]);

        //     $data = array_replace($data, [
        //         'profile_img' => $imageHashName
        //     ]);

        //     // Upload the main image
        //     $this->profileImage->store('public/photos');
        //     Storage::makeDirectory('public/photos_thumb');

        //     // Create a thumbnail of the image using Intervention Image Library
        //     $manager = new ImageManager();
        //     $image = $manager->make('storage/photos/'.$imageHashName)->resize(300, 200);
        //     $image->save('storage/photos_thumb/'.$imageHashName);
        // }

        if ($this->modelId) {
            $modelUpdate = User::findOrFail($this->modelId);

            if($modelUpdate->email === $this->email){
                $this->validate([
                    'email' => 'required|email',
                ]);
            }else{
                $this->validate([
                    'email' => 'required|email|unique:users',
                ]);
            }

            // $profileImageUp = $modelUpdate->profile_img;

            // if($profileImageUp != null){
            //     if (!empty($this->profileImage)) {
            //         $imageHashName = $this->profileImage->hashName();

            //         if($profileImageUp != 'default.png'){
            //             $imagePath = public_path('storage/photos/'.$profileImageUp);
            //             $imagePathThumb = public_path('storage/photos_thumb/'.$profileImageUp);

            //             if(User::exists($imagePath) | User::exists($imagePathThumb)){
            //                 unlink($imagePath);
            //                 unlink($imagePathThumb);
            //             }
            //         }

            //         // This is to save the filename of the image in the database
            //         $data = array_replace($data, [
            //             'profile_img' => $imageHashName
            //         ]);

            //         $this->profileImage->store('public/photos');
            //     }else{
            //         $data = array_replace($data, [
            //             'profile_img' => $profileImageUp
            //         ]);
            //     }
            // }

            $modelUpdate->update($data);
            $this->alert('success', 'User Has Been Edited!', [
                'position' => 'top',
                'timer' => '5000',
                'toast' => true,
                'showConfirmButton' => true,
                'onConfirmed' => '',
                'timerProgressBar' => true,
                'confirmButtonText' => 'Confirm',
            ]);
        } else {
            if(!$this->modelId){
                $this->validate([
                    'email' => 'required|email|unique:users',
                ]);
            }

            User::create($data)->syncRoles($data['role_name']);
            $this->alert('success', 'User Has Been Created!', [
                'position' => 'top',
                'timer' => '5000',
                'toast' => true,
                'showConfirmButton' => true,
                'onConfirmed' => '',
                'timerProgressBar' => true,
                'confirmButtonText' => 'Confirm',
            ]);
        }

        $this->emit('refreshParent');
        $this->dispatchBrowserEvent('close-modal');
        $this->cleanVars();
    }

    public function forcedCloseModal()
    {
        // This is to reset our public variables
        $this->cleanVars();

        // These will reset our error bags
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function cleanVars()
    {
        $this->iteration++;
        $this->modelId = null;
        // $this->profileImage = null;
        $this->role_name = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
    }

    public function render()
    {
        return view('livewire.master.create-or-update-user');
    }
}
