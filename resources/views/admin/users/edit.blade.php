<x-app-layout title="Users">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            User Edit
        </h2>
        <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-500  focus:outline-none focus:shadow-outline-red float-right cursor-pointer" href="{{ route('users.index') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                </svg>
                <span>Back</span>
            </a>
        </div>

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
        <form method="POST" action="{{ route('users.update',$user->id) }}" id="edit-user">
            @csrf
            @method('PATCH')
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Name</span>
                    <input id="name" disabled name="name" value="{{ $user->name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Email</span>
                    <input id="email" disabled name="email" value="{{ $user->email }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" type="email" placeholder="xxx@gmail.com" />
                </label>
              
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Status</span>
                    <select id="status" name="status"  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
        
                    <option value="1" {{ $user->status == "1" ? 'selected="selected"' : '' }}>Active</option>
                    <option value="2" {{ $user->status == "2" ? 'selected="selected"' : '' }}>In Active</option>
                                  
                    </select>
                </label>
              
                <div class="flex flex-wrap py-2 items-center justify-evenly">
                    <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                    <input class="bg-red-500 text-white cursor-pointer  py-2 px-4 rounded" type="submit" value="Update"/>
                    <!-- </div>
                    <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline"> -->
                        <a class="inline-block align-baseline bg-red-500 text-white py-2 px-4 rounded" href="{{ route('users.index') }}">
                            Cancel
                        </a>
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