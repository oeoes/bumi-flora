@extends('layouts.master')

@section('page-title', 'Notifications')
@section('page-description', 'Stock Alert Notification.')

@section('btn-custom')
<a href="{{ route('notifications.delete_all') }}" class="btn btn-danger rounded-pill pr-4 pl-4">Solved All <i data-feather="trash" class="ml-2"></i></a> 
@endsection

@section('custom-css')
<style>
    @media only screen and (max-width: 600px) {
        .my-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>
@endsection


@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            @if(count($notifications))
            @foreach($notifications as $key => $notification)
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <div class="h4">{{ $notification->title }}</div>
                        <small class="badge bagde-sm badge-light" style="font-style: italic">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="card-body">
                        {!! $notification->body !!}
                    </div>
                    <div class="card-footer text-right">
                        <form method="post" action="{{ route('notifications.destroy', ['notification' => $notification->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-info rounded-pill pr-4 pl-4">Solved</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-md-6 offset-md-3">
                <div class="card text-center p-3">
                    <div class="h3 text-muted">Tidak ada pemberitahuan.</div>
                </div>
            </div>
            @endif
        </div>
        {{ $notifications }}
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection