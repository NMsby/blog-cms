{{-- resources/views/author/media/selector.blade.php --}}
@extends('layouts.app')
@section('title', 'Select Media')

@section('content')
    <div class="grid grid-cols-4 gap-4">
        @foreach($media as $file)
            @if(str_starts_with($file->mime_type, 'image/'))
                <div class="cursor-pointer" onclick="selectMedia('{{ asset('storage/' . $file->path) }}')">
                    <img src="{{ asset('storage/' . $file->path) }}"
                         alt="{{ $file->original_filename }}"
                         class="w-full h-32 object-cover rounded">
                </div>
            @endif
        @endforeach
    </div>

    <script>
        function selectMedia(url) {
            window.opener.postMessage({ mceAction: 'insertContent', content: `<img src="${url}"  alt=""/>` }, '*');
            window.close();
        }
    </script>
@endsection
