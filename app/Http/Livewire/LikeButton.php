<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class LikeButton extends Component
{
    // to save the received post_id and post to this variables
    private $post;
    public $post_id; // public variables could be accessed by js and frontend
    public $isLiked; // to set the button color in the component


    // the ToggleLike function is only called when clicked on the button so when i refresh the button color will disappear
    // livewire call the function mount when the component like-button is running
    public function mount($post_id)
    {
        $this->post = Post::find($post_id);
        // check if the user is logged in
        if ($this->post != null && auth()->user() != null) {
            $this->post->likedByUser(auth()->user()) ? $this->isLiked = true : $this->isLiked = false;
        }
    }

    public function ToggleLike($post_id) // to set the like-button color and do the database query
    {
        $this->post = Post::find($post_id);

        // check if the user is logged in
        if ($this->post != null && auth()->user() != null) {

            $this->post->likedByUsers()->toggle(auth()->user()); // toggle() is a helper check is the like is found in the data base and delete or add it if not found
            $this->post->likedByUser(auth()->user()) ? $this->isLiked = true : $this->isLiked = false;
        } else {
            redirect(route('login'));
        }
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
