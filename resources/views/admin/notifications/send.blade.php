<x-app-layout title="Notifications">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Send Notifications
        </h2>
        @if (count($errors) > 0)
            <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-500">
                <span class="text-xl inline-block mr-5 align-middle">
                <i class="fas fa-bell"></i>
                </span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="closeAlert(event)">
                <span>×</span>
                </button>
            </div>
        @endif
        @if ($message = Session::get('success'))
        <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-green-500">
            <span class="text-xl inline-block mr-5 align-middle">
              <i class="fas fa-bell"></i>
            </span>
            <span class="inline-block align-middle mr-8">
                {{ $message }}
            </span>
            <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="closeAlert(event)">
              <span>×</span>
          
            </button>
          </div>
        @endif
        <form method="POST" action="{{ route('notifications.send') }}" id="notification">
            @csrf
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Device</span>
                    <select name="device" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:focus:shadow-outline-gray">
                            <option value='all' selected>All</option>
                            <option value='android'>Android</option>
                            <option value='ios'>IOS</option>
                    </select>
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Title</span>
                    <input id="title" name="title" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Enter Title" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Message</span>
                    <textarea id="message" name="message" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Enter Message"></textarea>
                </label>
                
                <div class="flex flex-wrap py-2 items-center justify-evenly">
                    <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                        <input class="bg-red-500 hover:bg-red hover:text-white text-white  py-2 px-4 cursor-pointer rounded" type="submit" value="Send" />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        function closeAlert(event){
            let element = event.target;
            while(element.nodeName !== "BUTTON"){
                element = element.parentNode;
            }
            element.parentNode.parentNode.removeChild(element.parentNode);
        }
    </script>
    @if(Session::has('message'))
    <script>
        $(function(){
                toastr.success("{{ Session::get('message') }}");
            })
    </script>
    @endif
</x-app-layout>