
<x-app-layout title="Privacy & Terms">
    <div class="container grid px-6 mx-auto">
        <h6 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Privacy & Terms
        </h6>

        @if(Session::has('privacy_updated'))
        <div class="alert alert-success" role="alert">
        {{Session::get('privacy_updated')}}
        </div>
        @endif

        <div class="container">
            <div class="row">
                <div class="col-md-7 offset-3 mt-4">
                    <div class="card-body">
                        <form method="post" action="{{ route('privacy.update') }}">
                            @csrf
                            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                                <label class="block text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Privacy-Policy</span>
                                    
                                </label>
                                <div class="form-group">
                                    <textarea class="ckeditor form-control" name="privacy">{{ $privacy->privacy }}</textarea>
                                </div>
                                <br>
                                <label class="block text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Terms & Condition</span>
                                    
                                </label>
                                <div class="form-group">
                                    <textarea class="ckeditor form-control" name="term">{{ $privacy->term }}</textarea>
                                </div>
                                <div class="flex flex-wrap py-2 items-center justify-evenly">
                                    <div class="sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 h-12 text-center align-baseline">
                                        <input class="bg-red-500 text-white cursor-pointer  py-2 px-4 rounded" type="submit" value="Update"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Session::has('privacy_updated'))
    toastr.success("{!! Session::get('privacy_updated')!!}");
    @endif
</x-app-layout>

