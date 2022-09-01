<x-app-layout title="Roles">
    <div class="container grid px-6 mx-auto">
        <h6 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Role Management
        </h6>
        @can('role-list')
          <div class="mb-2">
            <a class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple float-right cursor-pointer" href="{{ route('roles.create') }}">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clipRule="evenodd" />
                </svg>
                <span>Role</span>
            </a>
        </div>
        @endcan
        
        <livewire:role-datatable />
    </div>
   
    
    <script type="text/javascript">
        $(function () {
          var table = $('.data-table').DataTable({
              processing: false,
              serverSide: true,
              responsive: true,
              ajax: "{{ route('roles.index') }}",
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'name', name: 'name'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
              ],
              dom: 'Blfrtip',
                buttons: [
                'csvHtml5','pdf','print'
             ]
          });

            $('body').on('click', '.deleteUser', function (){
                var role_id = $(this).data("id");
                swal({
                   title: "Are you sure?",
                   text: "Delete this role!",
                   type: "error",
                   showCancelButton: true,
                   dangerMode: true,
                   cancelButtonClass: '#DD6B55',
                   confirmButtonColor: '#dc3545',
                   confirmButtonText: 'Delete!',
                },function (result) {
                if(result){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "/admin/roles"+'/'+role_id,
                        success: function (data) {
                            $('.data-table').DataTable().ajax.reload();
                            toastr.success('Role deleted successfully!');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }else{
                    return false;
                }
            });
            });
            function deleteUser(){
                        // var user_id = $(this).data("id");
                        alert();
                        swal({
                        title: "Are you sure?",
                        text: "Delete this user!",
                        type: "error",
                        showCancelButton: true,
                        dangerMode: true,
                        cancelButtonClass: '#DD6B55',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Delete!',
                        },function (result) {
                        if(result){
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "DELETE",
                                url: "/admin/users"+'/'+role_id,
                                success: function (data) {
                                    // $('.data-table').DataTable().ajax.reload();
                                    toastr.success('Role deleted successfully!');
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }else{
                            return false;
                        }
                    });
        });

            function closeAlert(event){
                let element = event.target;
                while(element.nodeName !== "BUTTON"){
                    element = element.parentNode;
                }
            element.parentNode.parentNode.removeChild(element.parentNode);
    }
            
    });
    </script>
    @if(Session::has('message'))
<script>
    $(function(){
            toastr.success("{{ Session::get('message') }}");
        })
</script>
@endif
</x-app-layout>