<div>
    {{-- contains like ,comment and share buttons  --}}
    {{-- on click go to the method ToggleLike in LikeButton.php --}}
    <div class="flex-col items-start ps-4 pb-1">
        <div class="flex flex-row items-center">
            <button class="text-2xl me-3 focus:outline-none" wire:model="like-button"
                wire:click="ToggleLike({{ $post_id }})">
                <i class="{{ $isLiked ? 'fas text-red-500' : 'far' }} fa-heart"></i></button>

            {{-- url() get the app url and add to it the id of the post --}}
            <button class="text-2xl me-3 focus:outline-none"
                onClick="document.getElementById('comment-{{ $post_id }}').focus()"><i
                    class="far fa-comment"></i></button>


            <button class="text-2xl me-3 focus:outline-none" onClick="copyToClipboard({{ $post_id }})"
                id="{{ $post_id }}" value="{{ url('') }}/posts/{{ $post_id }}"><i
                    class="far fa-share-square"></i></button>

        </div>
        <span>{{ __('Liked by') }} {{ $likeCount }}</span>
    </div>
</div>
