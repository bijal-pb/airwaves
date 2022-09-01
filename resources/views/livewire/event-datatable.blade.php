<div>
    <div class="row mb-4 text-gray-600 dark:text-gray-300">         
        <div class="flex space-x-2" style="display: inline-flex;">
          {{-- <div class="col form-inline" style="padding: 6px;">
            Per Page: &nbsp;
            <select wire:model="perPage" class="form-control text-gray-600 border-b">
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>25</option>
             </select>
          </div> --}}

          <!-- <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-red-500 rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-left cursor-pointer"
            onclick="confirm('Are you sure you want to Export these records?') || event.stopImmediatePropagation()"
            wire:click="exportSelected()">
            Export
          </a>

          <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-red-500 rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-left cursor-pointer"
            onclick="confirm('Are you sure you want to PDF these records?') || event.stopImmediatePropagation()"
            wire:click="pdfexport()">
            PDF
            </a> -->

           <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-red-500 rounded-lg active:bg-red-500 hover:bg-red-500 focus:outline-none focus:shadow-outline-red float-left cursor-pointer"
            onclick="confirm('Are you sure you want to CSV these records?') || event.stopImmediatePropagation()"
            wire:click="csvexport()">
            CSV
          </a> 
        </div>

         <div class="col float-right" style="margin-top: 10px; border: 2px solid #a5a7a7; border-radius:5px">
            <input wire:model.debounce.300ms="search" class="form-control border-b float-right" style="border-radius:5px" type="text" placeholder="Search ID,Name...">
        </div>
    </div>

    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap data-table dark:text-gray-200" style="color:unset; width:100%">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 cursor-pointer">
                        <th wire:click="sortBy('id')" class="px-4 py-3 cursor-pointer">Id <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                        
                          <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer">name <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('description')" class="px-4 py-3 cursor-pointer">Description </th>
                         
                      
                         
                          <th wire:click="sortBy('event_time')" class="px-4 py-3 cursor-pointer">Event time <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('event_date')" class="px-4 py-3 cursor-pointer">Event date <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                         

                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-transperent divide-y dark:divide-gray-700">
                    @foreach ($events as $event)
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <td class="px-4 py-3"> {{ $event->id }} </td>
                            <!-- <td class="px-4 py-3"> {{ $event->group_id }} </td> -->
                            <td class="px-4 py-3"> {{ $event->name }} </td>
                            <td class="px-4 py-3"><textarea disabled width="100" height="150"> {{ $event->description }}</textarea> </td>
                            <!-- <td class="px-4 py-3"> {{ $event->location }} </td> -->
                            <!-- <td class="px-4 py-3"> {{ $event->lat }} </td> -->
                            <!-- <td class="px-4 py-3"> {{ $event->lang }} </td> -->
                            <td class="px-4 py-3"> {{ $event->event_time }} </td>
                            <td class="px-4 py-3"> {{ $event->event_date }} </td>
                            <!-- <td class="px-4 py-3"> @if($event->event_photo != null)<img src="{{asset('/eventimages').'/'.$event->event_photo }}" width="100" height="100" /> @endif</td><td>                                         -->
                                <td>
                                <div class="flex space-x-1">&nbsp;&nbsp;
                                    <a href="{{route('event.detail',$event->id)}}" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                  </svg>
                                              {{-- </svg> --}}
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
                
            <div class=" dark:bg-gray-800  dark:text-gray-200 rounded-lg rounded-t-none max-w-screen rounded-lg border-b border-gray-200 bg-white">
                <div class="dark:bg-gray-800 p-2 sm:flex items-center justify-between">
                  <div class="dark:bg-gray-800 my-2 sm:my-0 flex items-center">
                    <select id="perPage" class="per-page dark:bg-gray-800 mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5" wire:model="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                  </div>
  
                  <div class="dark:bg-gray-800 my-4 sm:my-0">
                    <div  class="dark:bg-gray-800 lg:flex justify-center">
                        <span class="dark:bg-gray-800">{{ $events->links('livewire.datatables.tailwind-pagination') }}</span>
                    </div>
                  </div>
  
                  <div class="flex justify-end text-gray-600 dark:bg-gray-800">
                    Events {{ $events->firstItem() }} - {{ $events->lastItem() }} of
                    {{ $events->total() }}
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var page = localStorage.getItem('event');
        var url = $(location).attr('href');
        
        $(document.body ).click(function() {
              var url = window.location.href;
              var page = window.location.href.split('page=')[1];
              localStorage.setItem('event',page);
        });
        
        $(document).on('change','.per-page',function(event){
            var p = $(this).val();
            localStorage.setItem('perPageEvent',p);
        });
  
        var perPage = localStorage.getItem('perPageEvent');
        if(perPage != 'undefined' && perPage != null){
          $('#perPage').val(perPage);
          @this.set('perPage',perPage);
        }
  
        if(window.location.href.indexOf("page") == -1){
          if(page != null && page != 'undefined'){
              url = url+'?page='+page;
              $(location).attr('href',url);
              window.location.replace(url);
            }
        }
      
    });
  
  </script>
  