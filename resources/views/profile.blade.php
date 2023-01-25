<x-app-layout>

    {{-- will be displayed in the {{$header}} app.blade.php --}}
    <x-slot name="header">
        {{-- user image and account information --}}
        <div class="grid grid-cols-5 gap-4">
            {{-- image --}}
            <div class="col-start-2 col-span-1 flex justify-center  w-auto mt-5">
                <img class="w-40 h-40 rounded-full" src="{{ $profile->profile_photo_url }}" alt="{{ $profile->username }}">
            </div>

            <div class="col-start-3 col-span-2 flex justify-start items-center w-auto m-0 ">
                <div class="grid grid-rows-2">
                    {{-- name, edit/add post --}}
                    <div class="flex flex-row items-center">
                        <h1 class="font-light text-3xl mr-14">{{ $profile->username }}</h1>
                        <a href="{{ route('profile.show') }}"
                            class="border border-solid border-gray-300 rounded-md py-0 px-5 mr-16 whitespace-nowrap ">
                            {{ __('Edit Profile') }}</a>
                        <a href="posts/create">
                            <x-jet-button class="ms-8 leading-none whitespace-nowrap">
                                {{ __('Add Post') }}
                            </x-jet-button>
                        </a>
                    </div>

                    {{-- followers/following ,bio,link --}}
                    <div>
                        <ul class="flex flex-row mb-5">
                            <li class="mr-10 cursor-pointer"> <span class="font-semibold">{{ $posts->count() }}</span>
                                {{ __('posts') }}
                            </li>
                            <li class="mr-10"><a href="#"><span class="font-semibold">25 </span> </a>
                                {{ __('followers') }}
                            </li>
                            <li class="mr-10"><a href="#"><span class="font-semibold">25 </span> </a>
                                {{ __('following') }}
                            </li>
                        </ul>
                        <p class="mb-1 font-black">{{ $profile->name }}</p>
                        <p>{{ $profile->bio }}</p>
                        <p class="text-blue-500"> <a href="{{ $profile->url }}"></a>{{ $profile->url }} </p>
                    </div>
                </div>
            </div>
        </div>
        </header>
    </x-slot>

    {{-- the post --}}
    <div class="max-w-4xl my-0 mx-auto">
        <hr class="mb-10">
        <div class="grid grid-cols-3 gap-4 mx-0 mt-0 mb-6">

            @foreach ($posts as $post)
                <div class="post">
                    <a href="posts/{{ $post->id }}" class="w-full h-full">
                        <img src="/storage/{{ $post->image_path }}" class="w-full h-full object-cover">
                        <div class="post-info">
                            <ul>
                                <li class="inline-block font-semibold me-7">
                                    <span class="absolute h-1 w-1 overflow-hidden">{{ __('Likes:') }}</span>
                                    <i class="fas fa-heart" aria-hidden="true"></i>
                                    66
                                </li>
                                <li class="inline-block font-semibold">
                                    <span class="absolute h-1 w-1 overflow-hidden">{{ __('Comments:') }}</span>
                                    <i class="fas fa-comment" aria-hidden="true"></i>
                                    25
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
</x-app-layout>
