<x-app-layout title="Posts">
    <div class="container grid px-6 mx-auto">
        <h6 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Posts 
        </h6>
       
        <livewire:post-datatable exportable/>
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
    
</x-app-layout>