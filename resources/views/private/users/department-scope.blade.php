<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title 
                title="{{ $user->name . ' - Visão de Setores'}}"
                :back="route('user.show', $user)"
                :breadcrumbs="[
                    'Lista de colaboradores' => route('user.show', $user),
                    'Colaborador - Visão de Setores' => '',
                ]"
            />
    
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form action="{{ route('user.department-scope', $user) }}" post id="user-permission-update-form" class="contents">
                    @foreach ($companyDepartments as $department)
                        <label class="flex items-center gap-4 w-full rounded-md shadow-md p-4 bg-gray-100/50 has-[input[type=checkbox]:checked]:bg-sky-300/50 cursor-pointer relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                            <input type="hidden" name="{{ $department }}" value="0" {{ $department == $user->department ? 'disabled' : '' }} >
                            <input type="checkbox" name="{{ $department }}" {{ $userDepartmentPermissions->firstWhere('department', $department)?->allowed || $department == $user->department ? 'checked' : '' }}  {{ $department == $user->department ? 'disabled' : '' }} value="1" class="peer hidden">
                            <i class="fa-solid fa-circle-check opacity-0 peer-checked:opacity-100 transition-opacity text-gray-800 "></i>
                            <div class="w-full flex items-center justify-between">
                                <p class="font-medium text-sm md:text-base text-gray-800">{{ $department }}</p>
                                @if($department == $user->department)
                                    <span class="text-xs">Setor do usuário</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </x-form>
            </div>

            <div class="w-full flex flex-row justify-end gap-2">
                <x-action tag="button" onclick="uncheckAllDepartmentScopes()" variant="secondary">Desmarcar tudo</x-action>
                <x-action tag="button" onclick="resetDepartmentScopes()" variant="secondary">Redefinir</x-action>
                <x-action tag="button" onclick="checkAllDepartmentScopes()" variant="secondary">Marcar tudo</x-action>
                <x-action tag="button" form="user-permission-update-form" variant="secondary">Salvar</x-action>
            </div>
        </x-structure.main-content-container>    
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/user/department-scopes.js') }}"></script>
</x-layouts.app>