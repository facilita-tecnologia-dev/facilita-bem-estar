<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title 
                title="{{ $user->name . ' - Permissões'}}"
                :back="route('user.show', $user)"
                :breadcrumbs="[
                    'Lista de colaboradores' => route('user.show', $user),
                    'Colaborador - Permissões' => '',
                ]"
            />
    
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form action="{{ route('user.permissions', $user) }}" post id="user-permission-update-form" class="contents">
                    @foreach ($compiledPermissions as $userPermission)
                        <label class="flex items-center gap-4 w-full rounded-md shadow-md p-4 bg-gray-100/50 has-[input[type=checkbox]:checked]:bg-sky-300/50 cursor-pointer relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                            <input type="hidden" name="{{ $userPermission->permission->key_name }}" value="0">
                            <input type="checkbox" name="{{ $userPermission->permission->key_name }}" {{ $userPermission->allowed ? 'checked' : '' }} value="1" class="peer hidden">
                            <i class="fa-solid fa-circle-check opacity-0 peer-checked:opacity-100 transition-opacity text-gray-800"></i>
                            <div>
                                <p class="font-medium text-sm md:text-base text-gray-800">{{ $userPermission->permission->name }}</p>
                                <span class="text-xs md:text-sm text-gray-800">{{ $userPermission->permission->description }}</span>
                            </div>
                        </label>
                    @endforeach
                </x-form>
            </div>

            <div class="w-full flex flex-row flex-wrap justify-end gap-2">
                <x-action tag="button" onclick="uncheckAllPermissions()" variant="secondary">Desmarcar tudo</x-action>
                <x-action tag="button" onclick="resetPermissions()" variant="secondary">Redefinir</x-action>
                <x-action tag="button" onclick="checkAllPermissions()" variant="secondary">Marcar tudo</x-action>
                <x-action tag="button" form="user-permission-update-form" variant="secondary">Salvar</x-action>
            </div>
        </x-structure.main-content-container>    
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/user/permissions.js') }}"></script>
</x-layouts.app>