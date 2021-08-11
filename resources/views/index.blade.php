@extends('feature-toggle::layouts.app')

@section('content')
    @if(session('alert'))
        <x-feature-toggle-alert :type="session('alert')['type']" :message="session('alert')['message']" />
    @endif

        <div class="card col-span-2 xl:col-span-1">
            <div class="card-header">Features</div>
            <table class="table-auto w-full text-left">
                <thead>
                <tr>
                    <th class="px-4 py-2 border-r"></th>
                    <th class="px-4 py-2 border-r">name</th>
                    <th class="px-4 py-2 border-r">enabled</th>
                    <th class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody class="text-gray-600">
                @include('feature-toggle::partials.create')
                @foreach($features as $feature)
                    <tr class="hover:bg-gray-100">
                        <td class="w-2 border border-l-0 px-4 py-2 text-center text-{{$feature->enabled ? 'green' : 'red'}}-500"><i class="fas fa-circle"></i></td>
                        <td class="border border-l-0 px-4 py-2 lowercase">{{$feature->name}}</td>
                        <td class="border border-l-0 px-4 py-2">{{$feature->enabled ? 'Yes' : 'No'}}</td>
                        <td class="border border-l-0 border-r-0 px-4 py-2 w-5">
                            <div class="flex item-center">
                                <x-feature-toggle-button type="edit" :id="$feature->id" />
                                @if($feature->enabled)
                                    <x-feature-toggle-button type="disable" :id="$feature->id" />
                                @else
                                    <x-feature-toggle-button type="enable" :id="$feature->id" />
                                @endif
                                <x-feature-toggle-button type="delete" :id="$feature->id" />
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="flex justify-end pt-4 pr-4 pb-4">
                {{ $features->links() }}
            </div>
        </div>
@endsection
