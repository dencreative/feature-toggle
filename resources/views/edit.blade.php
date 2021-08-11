@extends('feature-toggle::layouts.app')

@section('content')
    <form action="{{ route('features.toggle.update', [$feature->id]) }}" method="post">
        @csrf
        <div class="card col-span-2 xl:col-span-1 mb-4">
            <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b bg-{{$feature->enabled ? 'green' : 'red'}}-200">
                <div class="flex items-center">
                    <h1 class="lowercase">{{ $feature->name }}</h1>
                </div>
                <div>
                    <div class="grid grid-flow-col auto-cols-max">
                        <button type="submit" class="btn-bs-primary mr-6 lg:mr-0 lg:mb-6">
                            <i class="far fa-save"></i>
                            Update
                        </button>
                        @if($feature->enabled)
                            <a href="{{ route('features.toggle.disable', [$feature->id]) }}" class="btn-danger mr-6 lg:mr-0 lg:mb-6">
                                <i class="far fa-times-circle"></i>
                                Disable
                            </a>
                        @else
                            <a href="{{ route('features.toggle.enable', [$feature->id]) }}" class="btn-bs-success mr-6 lg:mr-0 lg:mb-6">
                                <i class="far fa-check-square"></i>
                                Enable
                            </a>
                        @endif
                        <a href="{{ route('features.toggle.delete', [$feature->id]) }}" class="btn-bs-secondary mr-6 lg:mr-0 lg:mb-6">
                            <i class="far fa-trash-alt"></i>
                            Delete
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="card col-span-1 xl:col-span-1">
            <div class="card-header">
                Roles
            </div>

            @foreach($linkedRoles as $role)
                <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
                    <div class="flex items-center">
                        <h1>
                            <input name="roles[]" value="{{ $role['role']->id }}" type="checkbox" {{ $role['linked'] ? 'checked' : null }} class="m-0 p-0 border bg-gray-100 outline-none focus:ring-2 focus:ring-indigo-400 rounded" />
                            {{ $role['role'][config('features.roles.column')] }}
                        </h1>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection
