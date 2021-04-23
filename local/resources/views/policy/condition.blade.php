@extends((Auth::check()) ? 'layouts.masterDynamic' : 'layouts.static'))
@section('content')

   @include('includes.static.conditions')

@stop()
