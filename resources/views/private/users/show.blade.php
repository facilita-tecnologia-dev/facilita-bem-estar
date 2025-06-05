<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title 
                :title="$user->name"
                :back="Gate::allows('user-index') ? route('user.index') : null"
                :breadcrumbs="[
                    'Lista de colaboradores' => Gate::allows('user-index') ? route('user.index') : null,
                    'Colaborador - Detalhe' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif
    
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Nome:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->name ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">CPF:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->cpf ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Idade:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->birth_date ? Carbon\Carbon::parse($user->birth_date)->age . ' anos' : 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Sexo:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->gender ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Estado Civil:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->marital_status ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Grau de Instrução:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->education_level ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Setor:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->department ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Função:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->occupation ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Turno:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->work_shift ?? 'Não cadastrado' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Data de admissão:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->admission ? Carbon\Carbon::parse($user->admission)->format('d/m/Y') : 'Não cadastrado'}}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Tempo de empresa:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->admission ? Carbon\Carbon::parse($user->admission)->diffForHumans() : 'Não cadastrado'}}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Hierarquia:</p>
                        <p class="text-sm sm:text-base text-left">{{ $user->roles[0]->display_name }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Último teste de Riscos Psicossociais realizado:</p>
                        <p class="text-sm sm:text-base text-left">{{ $latestPsychosocialCollectionDate ?? 'Nunca' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Último teste de Clima Organizacional realizado:</p>
                        <p class="text-sm sm:text-base text-left">{{ $latestOrganizationalClimateCollectionDate ?? 'Nunca' }}</p>
                    </div>
                </div>

                @if($hasTemporaryPassword)
                    <div class="md:col-span-2 bg-blue-200/50 rounded-md p-4 space-y-1">
                        <p class="text-sm sm:text-base text-gray-800">Este gestor ainda não definiu uma senha. No próximo acesso, deverá usar a senha provisória e será solicitado a criar uma nova.</p> 
                        <button class="text-sm sm:text-base break-all text-left" onclick="navigator.clipboard.writeText('{{ $user->password }}')">
                            Clique no texto sublinhado para copiar a senha provisória: 
                            <span class="text-blue-500 underline active:text-blue-700" data-tippy-content="Clique para copiar">
                                {{ $user->password }}
                            </span>
                        </button>
                    </div>
                @endif

                <div class="w-full flex flex-row flex-wrap justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        @can('user-delete')
                            <x-form action="{{ route('user.destroy', $user) }}" delete onsubmit="return confirm('Você deseja excluir o colaborador?')">
                                <x-action tag="button" type="submit" variant="danger">Excluir colaborador</x-action>
                            </x-form>
                        @endcan
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        @if($user->id === App\Helpers\AuthGuardHelper::user()->id && $user->hasRole('manager'))
                            <x-action tag="button" variant="secondary" data-role="reset-password-modal-trigger">Redefinir senha</x-action>
                        @endif
                        @can('user-permission-edit')
                            <x-action href="{{ route('user.permissions', $user) }}" variant="secondary">Gerenciar permissões</x-action>
                        @endcan
                        @can('user-department-scope-edit')
                            @if($user->hasRole('manager'))
                                <x-action href="{{ route('user.department-scope', $user) }}" variant="secondary">Gerenciar Visão de Setores</x-action>
                            @endif
                        @endcan
                        @can('user-edit')
                            <x-action href="{{ route('user.edit', $user) }}" variant="secondary">Editar</x-action>
                        @endcan
                    </div>
                </div>
            </div>

            @if($hasTemporaryPassword /*session('password-warning')*/)
                <x-modal.background data-role="password-warning-modal" class="!flex">
                    <x-modal.wrapper class="max-w-[450px]">
                        <x-modal.title>Aviso - Senha do gestor</x-modal.title>
                        
                        <p class="p-2 bg-blue-300/50 rounded-md text-blue-700 text-sm">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>
                            Este gestor ainda não definiu uma senha. No próximo acesso, deverá usar a senha provisória e será solicitado a criar uma nova.
                        </p>

                        <div class="text-sm sm:text-base break-all text-left flex flex-col items-center gap-2" >
                            Clique na senha provisória para copiá-la: 
                            <button class="text-blue-500 underline active:text-blue-700" data-tippy-content="Clique para copiar" onclick="navigator.clipboard.writeText('{{ $user->password }}')">
                                {{ $user->password }}
                            </button>
                        </div>

                        <x-action variant="danger" width="full" tag="button" type="button" onclick="hidePasswordWarningModal()">Fechar</x-action>
                    </x-modal.wrapper>
                </x-modal.background>
            @endif

            <x-modal.background data-role="reset-password-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Redefinir senha do usuário</x-modal.title>
                    
                    <div class="w-full flex flex-col gap-2">
                        <x-form action="{{ route('user.reset-password', $user) }}" put class="w-full flex flex-col gap-2">
                            <p class="text-center">Digite a senha atual:</p>
                            <x-form.input-text type="password" name="current_password" placeholder="Senha atual"/>
                            <p class="text-center">Digite e confirme a nova senha:</p>
                            <x-form.input-text type="password" name="new_password" placeholder="Nova senha" oninput="checkPasswordSteps(event)"/>
                            <x-password-requirements />
                            <x-form.input-text type="password" name="new_password_confirmation" placeholder="Confirme sua nova senha"/>
                            <x-action variant="secondary" tag="button" width="full">Redefinir senha</x-action>
                        </x-form>
                    </div>
                </x-modal.wrapper>
            </x-modal.background>
        </x-structure.main-content-container>    
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/user/show.js') }}"></script>
    <script src="{{ asset('js/reset-password.js') }}"></script>
</x-layouts.app>