<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class AcceptFollow extends Component
{
    private $profile; // the user profile
    public $profile_id; // the user id
    public $status;


    public function mount($profile_id)
    {
        $this->profile = User::find($profile_id);
        if ($this->profile != null && auth()->user() != null) {
            auth()->user()->accepted($this->profile) ? $this->status = 'Accepted' : $this->status = 'Accept';
        }
    }

    public function toggleAccept($profile_id)
    {
        $this->profile = User::find($profile_id);

        if ($this->profile != null && auth()->user() != null) {

            // if the follow request is accepted
            if (auth()->user()->accepted($this->profile)) {

                auth()->user()->toggleAccepted($this->profile, false); // delete accept
                auth()->user()->accepted($this->profile) ? $this->status = 'Accepted' : $this->status = 'Accept';
            } else {
                auth()->user()->toggleAccepted($this->profile, true); // accept
                auth()->user()->accepted($this->profile) ? $this->status = 'Accepted' : $this->status = 'Accept';
            }
        }
    }

    public function render()
    {
        return view('livewire.accept-follow');
    }
}
