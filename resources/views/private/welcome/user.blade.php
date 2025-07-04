<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Bem vindo(a)!" centered />

            <div class="flex-1 w-full py-4 md:py-8 space-y-8 flex flex-col items-center mt-6">
                @if(session('company')->getActiveCampaigns()->count())
                    <div class="text-center space-y-2">
                        <h2 class="text-base md:text-2xl font-medium">Vamos responder aos testes?</h2>
                        <p class="text-sm md:text-base">Sua empresa tem campanhas de testes ativas no momento:</p>
                    </div>     
                    
                    @foreach (session('company')->getActiveCampaigns() as $activeCampaign)
                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-question"></i>
                                {{ $activeCampaign->name }}
                            </span>
                            @php
                                $psychosocialCollection = session('company')['customCollections']->firstWhere('collection_id', 1);
                                $collectionId = $activeCampaign['collection_id'] == $psychosocialCollection['id'] ? 1 : 2;

                                $user = App\Helpers\AuthGuardHelper::user();

                                if($collectionId == 1){
                                    $hasCompatibleCollection = $user->latestPsychosocialCollection;
                                } elseif($collectionId == 2){
                                    $hasCompatibleCollection = $user->latestOrganizationalClimateCollection;
                                }
                            @endphp

                            @if($hasCompatibleCollection)
                                <p class="p-2 bg-green-300/30 border border-green-300 text-green-800 rounded-md text-sm sm:text-base">
                                    Respondido
                                </p>
                            @else
                                <x-action variant="secondary" :href="route('responder-teste', App\Models\CustomCollection::firstWhere('id', $activeCampaign['collection_id']))">Responder testes</x-action>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="flex flex-col items-center gap-6">
                        <div class="space-y-2 text-center">
                            <h2 class="text-base md:text-2xl font-medium">Não há testes para responder!</h2>
                            <p class="text-sm md:text-base">Identificamos que a empresa não tem campanhas de testes ativas no momento.</p>
                        </div>
                        <x-action href="{{ route('logout') }}" onclick="return confirm('Você deseja mesmo sair?')">
                            Sair do sistema
                        </x-action>
                    </div>    
                @endif
            </div>

            <div class="w-full text-center">
                <p class="text-xs sm:text-sm">Este sistema assegura o anonimato dos usuários e realiza o tratamento de dados de forma ética e responsável, em total conformidade com a LGPD.</p>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>