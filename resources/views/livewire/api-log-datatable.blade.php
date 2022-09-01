<div>
    <div class="row mb-4 text-gray-600 dark:text-gray-300">         
        <div class="flex space-x-2" style="display: inline-flex;">
          <div class="col form-inline" style="padding: 6px;">
            Per Page: &nbsp;
            <select wire:model="perPage" class="form-control text-gray-600 border-b">
                <option>2</option>
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>25</option>
             </select>
          </div>

          <!-- <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-green-600 rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green float-left cursor-pointer"
            onclick="confirm('Are you sure you want to Export these records?') || event.stopImmediatePropagation()"
            wire:click="exportSelected()">
            Export
          </a>

          <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-green-600 rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green float-left cursor-pointer"
            onclick="confirm('Are you sure you want to PDF these records?') || event.stopImmediatePropagation()"
            wire:click="pdfexport()">
            PDF
            </a> -->

          <a href="#" type="button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent bg-green-600 rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green float-left cursor-pointer"
            onclick="confirm('Are you sure you want to CSV these records?') || event.stopImmediatePropagation()"
            wire:click="csvexport()">
            CSV
          </a>
        </div>

         <div class="col float-right" style="margin-top: 10px; border: 2px solid #a5a7a7; border-radius:5px">
            <input wire:model.debounce.300ms="search" class="form-control border-b float-right" style="border-radius:5px" type="text" placeholder="Search ID...">
        </div>
    </div>

    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap data-table dark:text-gray-200" style="color:unset; width:100%">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th wire:click="sortBy('id')" class="px-4 py-3 cursor-pointer">ID <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('url')" class="px-4 py-3 cursor-pointer">Url <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('method')" class="px-4 py-3 cursor-pointer">Method <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('payload')" class="px-4 py-3 cursor-pointer">Payload <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                          <th wire:click="sortBy('response')" class="px-4 py-3 cursor-pointer">Response <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="15px" height="15px" style="display: inline;" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg></th>
                        <th class="px-4 py-3">ip</th>
                    </tr>
                </thead>
                <tbody class="bg-transperent divide-y dark:divide-gray-700">
                    @foreach ($logs as $log)
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500  border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <td class="px-4 py-3"> {{ $log->id }} </td>
                            <td class="px-4 py-3"> {{ $log->url }} </td>
                            <td class="px-4 py-3"> {{ $log->method }} </td>
                            <td class="px-4 py-3"> <textarea cols="20" row="4">{{ $log->payload }} </textarea></td>
                            <td class="px-4 py-3"> <textarea cols="20" row="4">{{ $log->response }} </textarea></td></td>
                            <td class="px-4 py-3"> {{ $log->ip }} </td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
            
            <div class="text-gray-600 dark:text-gray-300">
                <p>
                    Showing {{ $logs->firstItem()}} to {{ $logs->lastItem() }} out of {{ $logs->total() }} logs
                </p>
                <p>
                    {{ $logs->links() }}
                </p>
            </div>
        </div>
    </div>
</div>
