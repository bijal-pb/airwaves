<x-app-layout title="Group Detail">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Groups Details
        </h2>
        <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-right cursor-pointer" href="{{ route('group.index') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                </svg>
                <span>Back</span>
            </a>
        </div><br>
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs bg-white rounded-lg shadow-md dark:bg-gray-800">
        <table>
            <tr>
                <th class="px-20 py-5 text-right">Name : </th>
                <td class="text-left">{{ $groups->name }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Description: </th>
                <td class="text-left">{{ $groups->description }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Location : </th>
                <td class="text-left">{{ $groups->location }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Latitude : </th>
                <td class="text-left">{{ $groups->lat }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Langutitude : </th>
                <td class="text-left">{{ $groups->lang }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Required join : </th>
                <td class="text-left">{{ $groups->required_join }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Created By : </th>
                <td class="text-left">{{ $groups->created_user->name }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Photo : </th>
                <td class="text-left">@if($groups->photo)
                    <img width="150" height="150" src="{{ $groups->photo }}" alt="Image" />
                    @endif</td>
            </tr>
           
          {{--  <tr>  
                 <th> User : </th> 
                 <table class="min-w-full leading-normal">
                 <tr>
                <td>@foreach ($groups->GroupUser as $GroupUser)</td>
                </tr>
                </table>
                <tbody>
                <td> {{ $groups->GroupUser }}</td> 
                @endforeach 
                </tbody>
           </tr> --}}
             </table>
            </div>
           @if($groups->genres != null)
           <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs bg-white rounded-lg shadow-md dark:bg-gray-800">
            <table style="margin-right: auto; text-align: -webkit-match-parent; white-space: pre;">
                    <tr>
                       <th  class="px-20 py-5 text-right font-semibold  text-1xl">Genres Details :</th>  
                    </tr>
                    <tr>
                        <th class="px-20 py-5 text-center">ID :</th>
                        <td class="text-left">{{ $groups->genres->id }}</td>
                    </tr>
                    <tr>
                        <th class="px-20 py-5 text-center">Name :</th>
                        <td class="text-left">{{ $groups->genres->name }}</td>
                    </tr>
                </table>
            </div>
            @endif

       </br>
           @if($groups->GroupUser != null)
         <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs bg-white rounded-lg shadow-md dark:bg-gray-800"> 
            <div>
                <h2 class="px-20 py-5 text-1xl font-semibold leading-tight">Users Details: </h2>
                <table style="margin-right: auto; text-align: -webkit-match-parent; white-space: pre;">
                <thead>
                    <tr>
                        <th class="px-20 py-5 text-center">ID</th>
                        <th class="px-20 py-5 text-center">Name</th>
                        <th class="px-20 py-5 text-center">First name</th>
                        <th class="px-20 py-5 text-center">Last name</th>
                        <th class="px-20 py-5 text-center">Email</th>
                       
                    <tr>
                </thead>
                <tbody>
                    @foreach ($groups->GroupUser as $GroupUser)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $GroupUser->user->id }}</td>
                            <td class="text-center">{{ $GroupUser->user->name }}</td>
                            <td class="text-center">{{ $GroupUser->user->first_name }}</td>
                            <td class="text-center">{{ $GroupUser->user->last_name }}</td>
                            <td class="text-center">{{ $GroupUser->user->email}}</td>
                           
                        </tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
       
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