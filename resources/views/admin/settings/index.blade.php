<x-app-layout title="Settings">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Settings
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
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="p-3 dark:bg-gray-800">
                <ul class="list-reset flex border-b dark:bg-gray-800">
                    <li class="p-0 dark:bg-gray-800">
                        <a class="bg-white inline-block py-2 px-4 text-red-800 dark:bg-gray-800 font-semibold active" data-select="one" id="one" href="javascript:void(0)">General</a>
                    </li>
                    <li class="p-0 dark:bg-gray-800">
                        <a class="bg-white inline-block py-2 px-4 text-red-400 dark:bg-gray-800 hover:text-red-800 font-semibold" data-select="two" id="two" href="javascript:void(0)">Email</a>
                    </li>
                    @can('developer')
                    <li class="p-0 dark:bg-gray-800">
                        <a class="bg-white inline-block py-2 px-4 text-red-400 dark:bg-gray-800 hover:text-red-800 font-semibold" data-select="three" id="three" href="javascript:void(0)">Key</a>
                    </li>
                    @endcan
                </ul>
                <div class="content">
               
                <div  id="tabs1">
                    <form method="POST" action="{{ route('app.setting.update',$setting->id) }}" id="edit-user">
                        @csrf
                        @method('PATCH')
                        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">App Name</span>
                                <input id="app_name" name="app_name" value="{{ $setting->name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">App Url</span>
                                <input id="app_url" name="app_url" value="{{ $setting->url }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Environment</span>
                                <select name="app_env" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:focus:shadow-outline-gray">
                                    <option {{ $setting->env == 'local' ? 'selected' : '' }} value="local">Local</option>    
                                    <option {{ $setting->env == 'production' ? 'selected' : '' }} value="production">Production</option>
                                </select>
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Debug</span>
                                <select name="app_debug" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:focus:shadow-outline-gray">
                                    <option {{ $setting->debug == 'true' ? 'selected' : '' }} value="true">True</option>    
                                    <option {{ $setting->debug == 'false' ? 'selected' : '' }} value="false">False</option>
                                </select>
                            </label>
                            @can('developer')
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Api Log</span>
                                <select name="api_log" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:focus:shadow-outline-gray">
                                    <option {{ $setting->api_log == 'true' ? 'selected' : '' }} value="true">True</option>    
                                    <option {{ $setting->api_log == 'false' ? 'selected' : '' }} value="false">False</option>
                                </select>
                            </label>
                            @endcan
                            @can('developer')
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Distance</span>
                                <input id="distance" name="distance" value="{{ $setting->distance }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                            </label>
                            @endcan
                            <div class="flex flex-wrap py-2 items-center justify-evenly">
                                <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                                    <input class="bg-red-500  text-white cursor-pointer py-2 px-4 rounded" type="submit" value="Update" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div  id="tabs2" class="hidden">
                    <form method="POST" action="{{ route('email.setting.update',$email_setting->id) }}" id="edit-user">
                        @csrf
                        @method('PATCH')
                        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Host</span>
                                <input id="host" name="host" value="{{ $email_setting->host }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Port</span>
                                <input id="port" name="port" value="{{ $email_setting->port }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" type="number" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Email</span>
                                <input id="email" name="email" value="{{ $email_setting->email }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" type="email" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Password</span>
                                <input id="password" name="password" value="{{ $email_setting->password }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">From Address</span>
                                <input id="from_address" name="from_address" value="{{ $email_setting->from_address }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">From Name</span>
                                <input id="from_name" name="from_name" value="{{ $email_setting->from_name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Encryption</span>
                                <input id="encryption" name="encryption" value="{{ $email_setting->encryption }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                            </label>
                            <div class="flex flex-wrap py-2 items-center justify-evenly">
                                <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                                    <input class="bg-red-500  text-white cursor-pointer py-2 px-4 rounded" type="submit" value="Update" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="tabs3" class="hidden">
                    <form method="POST" action="{{ route('key.setting.update',$setting->id) }}" id="edit-user">
                        @csrf
                        @method('PATCH')
                        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">FCM Key</span>
                                <input id="fcm" name="fcm" value="{{ $setting->fcm }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                            </label>
                            <div class="flex flex-wrap py-2 items-center justify-evenly">
                                <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                                    <input class="bg-red-500  text-white cursor-pointer py-2 px-4 rounded" type="submit" value="Update" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function closeAlert(event){
            let element = event.target;
            while(element.nodeName !== "BUTTON"){
                element = element.parentNode;
            }
            element.parentNode.parentNode.removeChild(element.parentNode);
        }

        document.getElementById("one").onclick = function() {showTab(this)};
      document.getElementById("two").onclick = function() {showTab(this)};
      document.getElementById("three").onclick = function() {showTab(this)};
      
      function showTab(e) {
        let selectType = $(e).attr("data-select");
      	if (selectType == 'one') {
      	    $("#tabs2,#tabs3").hide();
      	    $("#tabs1").show();
      	    $("#one").addClass('text-red-800 active');
      	    $("#two,#three").removeClass('text-red-800 active');
      
      	} else if (selectType == 'two') {
      
      		$("#tabs1,#tabs3").hide();
      	    $("#tabs2").show();
      		$("#two").addClass('text-red-800 active');
      		$("#one,#three").removeClass('text-red-800 active').addClass('text-red-400');
      
      	} else if (selectType == 'three') {
      
      		$("#tabs2,#tabs1").hide();
      	    $("#tabs3").show();
      	    $("#three").addClass('text-red-800 active');
      		$("#one,#two").removeClass('text-red-800 active').addClass('text-red-400');
      	
      	}      
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