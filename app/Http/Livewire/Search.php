<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Search extends Component
{
    private $profiles = null; // to save the matched users
    public $search; // the text to search
    public $results; // the search result

    public function findProfile($search)
    {
        if ($this->search != null) {
            $this->profiles = User::where('username', 'LIKE', '%'  . $search . '%')
                ->where('id', '<>', auth()->user()->id)
                ->take(5) // take only five results
                ->get();
        } else {
            $this->profiles = null;
            $this->results = null; // to clear the search area after the user delete the text
        }

        if ($this->profiles != null) {
            $fields = [];
            $filtered = [];

            foreach ($this->profiles as $profile) {

                $fields['username'] = $profile->username;
                $fields['profile_photo_url'] = $profile->profile_photo_url;
                $filtered[] = $fields;
            }
            $this->results = $filtered;
        }
    }

    public function render()
    {

        return view('livewire.search');
    }
}
