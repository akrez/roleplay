@extends('layouts.app')

@section('header', __('UserFriendships'))

@php
    $from = [];
    $to = [];
    foreach ($user_friendships as $friendship) {
        if ($friendship['from']['id'] == $user['id']) {
            $from[] = $friendship;
        }
        if ($friendship['to']['id'] == $user['id']) {
            $to[] = $friendship;
        }
    }
@endphp

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('user_friendships.store') }}">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text">نام کاربری کاربر</span>
                                <input type="text" name="username" id="username"
                                    class="form-control @error('username') is-invalid @enderror" required>
                            </div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            ارسال درخواست دوستی
                        </button>
                    </form>
                </div>
            </div>

            <table class="table table-hover align-middle text-center m-0 mb-3">
                @include('user_friendship._friendships', [
                    'friendships' => $from,
                    'first_column_title' => 'درخواست ارسال شده به کاربر',
                    'first_column_key' => 'to',
                ])
            </table>
        </div>

        <div class="col-md-8">
            <table class="table table-hover align-middle text-center m-0 mb-3">
                @include('user_friendship._friendships', [
                    'friendships' => $to,
                    'first_column_title' => 'درخواست دریافت شده از کاربر',
                    'first_column_key' => 'from',
                ])
            </table>
        </div>
    </div>
@endsection
