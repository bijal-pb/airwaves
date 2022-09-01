<x-app-layout title="Event Detail">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Event Detail
        </h2>
        <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-right cursor-pointer" href="{{ route('event.index') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                </svg>
                <span>Back</span>
            </a>
        </div><br>
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs bg-white rounded-lg shadow-md dark:bg-gray-800">
        <table style="margin-right: auto; text-align: -webkit-match-parent; white-space: pre;">
       
            <tr>
                <th class="px-20 py-5 text-right">Name : </th>
                <td class="text-left">{{ $events->name }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Description : </th>
                <td><textarea>{{ $events->description }}</textarea> </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Location : </th>
                <td class="text-left">{{ $events->location }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Latitude : </th>
                <td class="text-left">{{ $events->lat }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Longitude : </th>
                <td class="text-left">{{ $events->lang }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Event time : </th>
                <td class="text-left">{{ $events->event_time }} </td>
            </tr>
            <tr>
                <th class="px-20 py-5 text-right">Event date : </th>
                <td class="text-left">{{ $events->event_date }} </td>
            </tr>

            <tr>
                <th class="px-20 py-5 text-right">Event photo :</th>
                <td class="text-left">
                    <!-- {{ $events->event_photo }}  -->
                <img src="{{ $events->event_photo }}" width="100" height="100" />      
             </td>
            </tr>
        </table>
        </div> 
    </div>
    @if($events->group != null)
            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs bg-white rounded-lg shadow-md dark:bg-gray-800">
            <table style="margin-right: auto; text-align: -webkit-match-parent; white-space: pre;">
                    <tr>
                       <th  class="px-20 py-5 text-right font-semibold">Groups Details :</th>  
                    </tr>
                    <tr>                 
                        <th  class="px-20 py-5 text-right">Name:</th>
                         <td class="text-left">{{ $events->group->name }}</td>
                    </tr>
            </table>
        </div>
      @endif
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