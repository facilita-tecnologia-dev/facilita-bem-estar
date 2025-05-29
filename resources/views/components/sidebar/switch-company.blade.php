@props([
    'companies'
])

<x-form action="{{ route('user.switch-company') }}" post class="space-y-2 px-2">                
    <div class="relative">
        <select name="company_id" class="w-full bg-transparent border border-gray-400 pl-2 pr-9 py-1.5 rounded-md flex items-center gap-2 justify-start focus:bg-gray-200 hover:bg-gray-200 transition appearance-none focus:outline-none">
            @foreach ($companies as $company)
                <option value="{{ $company['value'] }}" class="w-full truncate">{{ $company['option'] }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2">
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </div>
    </div>
    <button type="submit" class="w-full bg-transparent px-2 py-1.5 rounded-md flex items-center border border-gray-400 gap-2 justify-center hover:bg-gray-200 transition">
        Logar na empresa
    </button>
</x-form>