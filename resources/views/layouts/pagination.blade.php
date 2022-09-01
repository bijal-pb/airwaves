@if ($paginator->hasPages())
    <ul class="inline-flex items-center">
       
        @if ($paginator->onFirstPage())
            <li class="disabled "><button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-red" aria-label="Previous">
                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                  <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </button></li>
        @else
            <li class="dt-page"><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-red" aria-label="Previous">
                <svg aria-hidden="true" class="w-4 h-4 fill-current text-red-600" viewBox="0 0 20 20">
                  <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
              </button></a></li>
        @endif


      
        @foreach ($elements as $element)
           
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif


           
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="px-3 py-1 text-white transition-colors duration-150 bg-red-600 border border-r-0 border-red-600 rounded-md focus:outline-none focus:shadow-outline-red">{{ $page }}</span></li>
                    @else
                        <li class="dt-page"><a href="{{ $url }}" class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-red">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach


        
        @if ($paginator->hasMorePages())
            <li class="dt-page"><a href="{{ $paginator->nextPageUrl() }}" rel="next"><button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-red" aria-label="Next">
                <svg class="w-4 h-4 fill-current text-red-600" aria-hidden="true" viewBox="0 0 20 20">
                  <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
              </button></a></li>
        @else
            <li class="disabled"><button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-red" aria-label="Next">
                <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                  <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
              </button></li>
        @endif
    </ul>
@endif 