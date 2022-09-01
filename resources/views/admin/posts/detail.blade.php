<x-app-layout title="Post Detail">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Post Detail
        </h2>
        <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-right cursor-pointer" href="{{ route('post.index') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                </svg>
                <span>Back</span>
            </a>
        </div><br>
      {{-- {{ $posts }} --}}
        <table style="margin-right: auto; text-align: -webkit-match-parent; white-space: pre;">
            <tr>
                <th>Name : </th>
                <td>{{ $posts->getUser->name }} </td>
            </tr>
            <tr>
                <th>Caption : </th>
                <td>{{ $posts->message }} </td>
            </tr>
            <tr>
                <th>Likes : </th>
                <td>{{ $posts->total_likes }} </td>
            </tr>
            <tr>
                <th>Dislikes : </th>
                <td>{{ $posts->total_unlikes }} </td>
            </tr>
            <tr>
                <th>Comments : </th>
                <td>{{ $posts->total_comments }} </td>
            </tr>
            <tr>
                <th>Media :</th>
                <td></td>
            </tr>
            @foreach ($posts->media as $media)
            <tr>
                <td></td>
                <td>@if($media->type == 'image')
                <img width="200" height="200" src="{{ asset('/images/'.$media->media)}}" alt="Image" /></a>
                    


                    @endif
                    @if($media->type == 'video')
                    <a href="{{ asset('/videos/'.$media->media)}}" target="_blank"><video width="320" height="240" controls>
                   </a>
                    @endif
                    @if($media->type == 'track')
                    <div width="200" height="200"> {{ $media->media }}</div> 
                    @endif
                </td>
            </tr>
            @endforeach
            {{-- <tr>
                <th> Comment : </th>
                @foreach ($posts->postComments as $comment)
                <td> {{ $comment->comment }}</td>
                @endforeach
                
            </tr> --}}
        </table>
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