<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="grid grid-cols-12 mt-7 gap-4">
        <div class="col-start-5 col-span-4">

            <h3 class="mt-4 mb-4 text-gray-500 font-semibold text-center text-3xl">{{ __('Following:') }}</h3>
        </div>
    </div>

    @if ($following != null && sizeof($following) > 0)
        @foreach ($following as $follower)
            <div class="flex flex-col mb-3">
                <div class="flex flex-row justify-around">
                    <div class="flex flex-row ">
                        <a href="/{{ $follower->username }}"><img src="{{ $follower->profile_photo_url }}" alt="avatar"
                                class="rounded-full h-10 w-10 me-3"></a>
                        <div class="flex flex-col self-center">
                            <a href="/{{ $follower->username }}"
                                class="text-base hover:underline whitespace-nowrap">{{ $follower->username }}</a>
                            <h3 class="text-sm text-gray-500 truncate whitespace-nowrap" style="max-width: 25ch">
                                {{ $follower->bio }}</h3>
                        </div>
                    </div>
                    @livewire('follow-button', ['profile_id' => $follower->id], key($follower->id))
                </div>
            </div>
        @endforeach
    @endif
    </div>
</x-app-layout>
