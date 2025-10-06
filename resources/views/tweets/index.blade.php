<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tweet一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          @foreach ($tweets as $tweet)
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            @if ($tweet->image_path)
              <div class="my-2">
                <img src="{{ asset('storage/' . $tweet->image_path) }}" alt="ツイート画像" class="max-w-xs rounded">
              </div>
            @endif
            <!-- 🔽 投稿者名部分にリンクを追加 -->
            <a href="{{ route('profile.show', $tweet->user) }}">
              <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
            </a>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>

            {{-- コメント表示 --}}
            @foreach ($tweet->comments as $comment)
              <div class="mt-2 ml-4 p-2 bg-gray-200 dark:bg-gray-600 rounded">
                <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                @if ($comment->image_path)
                  <div class="my-2">
                    <img src="{{ asset('storage/' . $comment->image_path) }}" alt="コメント画像" class="max-w-xs rounded">
                  </div>
                @endif
                <p class="text-gray-500 text-xs">投稿者: {{ $comment->user->name }}</p>
              </div>
            @endforeach

            <div class="flex">
              @if ($tweet->liked->contains(auth()->id()))
              <form action="{{ route('tweets.dislike', $tweet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">dislike {{$tweet->liked->count()}}</button>
              </form>
              @else
              <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">like {{$tweet->liked->count()}}</button>
              </form>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</x-app-layout>