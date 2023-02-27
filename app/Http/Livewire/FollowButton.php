<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{

    private $profile; // user id to be followed profile
    public $profile_id; // user id to be followed
    public $following = "follow"; // to change the button from follow to un follow


    public function mount($profile_id)
    {
        $this->profile = User::find($profile_id);

        if ($this->profile != null && auth()->user() != null) {
            auth()->user()->following($this->profile) ? $this->following = "Unfollow" : $this->following = "Follow";
        }
    }

    public function ToggleFollowing($profile_id)
    {
        $this->profile = User::find($profile_id);

        if ($this->profile != null && auth()->user() != null) {
            auth()->user()->follows()->toggle($this->profile); // to follow a user
            auth()->user()->following($this->profile) ? $this->following = "Unfollow" : $this->following = "Follow";
            auth()->user()->setAccepted($this->profile); // to accept the follow req automaticly when click follow if acc is public
        } else {
            return redirect(route('login'));
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
