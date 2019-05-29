@extends('layouts.app')

@section('content')
    <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <example-component :current-user='{!! Auth::user() !!}'></example-component>
            </div>
        </div>
    
@endsection
