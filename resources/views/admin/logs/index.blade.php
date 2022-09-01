
<x-app-layout title="Api-Logs">
    <div class="container grid px-6 mx-auto">
        <h6 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Api Log
        </h6>
        <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-red-600 rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red float-right cursor-pointer" href="{{ route('apilog.delete') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.49dZFkcrKdk7XegyMd3kp4MGQoLFeMWM6Lion2T3q3h6DScBViFrXXuZoxkHq1TB1mGufMoGzfXd7jJ7ocgpJGxdEiGirjG-1-1z" clipRule="evenodd" />
                </svg>
                <span>Delete All</span>
            </a>
        </div>
        <livewire:api-log-datatable />
    </div>
</x-app-layout>