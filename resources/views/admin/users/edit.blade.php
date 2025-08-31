@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Edit User', 'asset' => 'User', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<form method="POST" action="{{ route('users.update', $user->id) }}" class="submit-form">
    @csrf
    @method('PUT')
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Edit User') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password (leave blank to keep current)') }}</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Update User') }}</button>
</form>
@endsection
