@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Add User', 'asset' => 'User', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<form method="POST" action="{{ route('users.store') }}" class="submit-form">
    @csrf
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Add User') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save User') }}</button>
</form>
@endsection
