<form action="{{ route('features.toggle.store') }}" method="post">
    @csrf
    <tr>
        <td class="w-2 border border-l-0 px-4 py-2">&nbsp;</td>
        <td class="border border-l-0 px-4 py-2">
            <input name="name" type="text" value="{{ old('name') }}" class="border {{ $errors->first('name') ? 'border-red-600' : null }} bg-gray-100 py-2 px-4 w-96 outline-none focus:ring-2 focus:ring-indigo-400 rounded" placeholder="Feature name" />
            @if($errors->first('name'))
                <p class="mt-2 text-red-600">
                    {{ $errors->first('name') }}
                </p>
            @enderror
        </td>
        <td class="border border-l-0 px-4 py-2">
            <input name="enabled" type="checkbox" checked class="m-0 p-0 border bg-gray-100 outline-none focus:ring-2 focus:ring-indigo-400 rounded" />
        </td>
        <td class="border border-l-0 border-r-0 px-4 py-2 w-5">
            <button type="submit" class="w-full text-white font-bold bg-indigo-600 py-3 rounded-md hover:bg-indigo-500 transition duration-300">Add</button>
        </td>
    </tr>
</form>
