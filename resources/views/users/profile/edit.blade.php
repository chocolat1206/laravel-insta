@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <div class="card border-0 shadow">
                <div class="card-header border-0 bg-white px-5 pt-5">
                    <h3 class="text-secondary">Update Profile</h3>
                </div>
                <div class="card-body border-0 bg-white px-5">
                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                
                        <div class="row mt-2 mb-3">
                            <div class="col-4">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle w-100">
                                @else
                                    <i class="fa-solid fa-circle-user d-block text-center text-secondary icon-lg"></i>
                                @endif
                            </div>
                            <div class="col-6 pt-5">
                                <input type="file" name="avatar" class="form-control" aria-describedby="avatar-info">
                                <div class="form-text" id="avatar-info">
                                    Acceptable formats: jpeg, jpg, png, gif only <br>
                                    Maximum file size: 1048kb
                                </div>
                                @error('avatar')
                                    <p class="text-danger small">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" autofocus>
                            @error('name')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Addres</label>
                            <input type="text" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="introduction" class="form-label fw-bold">Introduction</label>
                            <textarea name="introduction" id="introduction" rows="5" class="form-control" placeholder="Describe yourself">{{ old('introduction', $user->introduction)}}</textarea>
                            @error('introduction')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                
                        <button type="submit" class="btn btn-warning px-5 mb-5">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection