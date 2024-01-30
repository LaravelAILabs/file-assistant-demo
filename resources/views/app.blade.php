<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AI Labs - File Assistant</title>
    @vite('resources/css/app.css')

    <style>
        .scrollbar-w-2::-webkit-scrollbar {
            width: 0.25rem;
            height: 0.25rem;
        }

        .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
            --bg-opacity: 1;
            background-color: #edf2f7;
            background-color: rgba(237, 242, 247, var(--bg-opacity));
        }

        .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
            border-radius: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen max-w-2xl">
        {{-- Heading --}}
        <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
            <div class="relative flex items-center justify-between w-full space-x-4">
                <div class="relative text-2xl">
                    Chat ({{ $conversation_id ?? 'N/A' }})
                </div>

                <form method="POST" action="{{ route('destroy') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                        <span class="font-bold">New session</span>
                    </button>
                </form>
            </div>
        </div>

        {{--  Messages --}}
        <div id="messages" class="h-full flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
            @foreach($messages as $message)
                @if ($message->role === \LaravelAILabs\FileAssistant\Enums\RoleType::USER->value)
                    <div class="chat-message">
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-lg mx-2 order-1 items-end">
                                <div>
                                    <span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">
                                        {{ $message->content }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="chat-message">
                        <div class="flex items-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-lg mx-2 order-2 items-start">
                                <div>
                                    <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                        {{ $message->content }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Input --}}
        <form method="POST" action="{{ route('store') }}" class="border-t-2 border-gray-200 pt-4 mb-2 sm:mb-0" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-row">
                Uploaded files: {{ count($files) }}
            </div>

            @if (isset($conversation_id))
                <input type="hidden" name="conversation_id" value="{{ $conversation_id }}"/>
            @endif

            <input
                    class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                    type="file"
                    name="file" />

            <div class="relative flex top-2">
                <input name="message" type="text" placeholder="Write your message!"
                       class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-4 bg-gray-200 rounded-md py-3">

                <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                        <span class="font-bold">Send</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 ml-2 transform rotate-90">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    const el = document.getElementById('messages')
    el.scrollTop = el.scrollHeight
</script>

</html>