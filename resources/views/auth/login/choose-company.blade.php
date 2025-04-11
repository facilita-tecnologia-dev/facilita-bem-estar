<x-layouts.app>

        <div class="flex h-full justify-end">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">

                    <div class="flex flex-col gap-4 items-center">
                        <h1 class="text-4xl md:text-5xl font-semibold text-center text-gray-800">Escolha a empresa</h1>
                        <x-text-content>Escolha em qual das empresas a seguir você deseja fazer login</x-text-content>
                    </div>

                    <x-form action="{{ route('auth.login.choose-company') }}" class="w-full flex flex-col gap-4 items-center h-[220px] overflow-y-auto p-1" post>
                        {{-- <x-form.input-text name="cpf" placeholder="CPF apenas números" /> --}}
                        {{-- <x-form.input-text type="password" name="password" placeholder="Senha" /> --}}
                        <input type="hidden" id="company" name="company_id" value="">
                        
                        @foreach (session('user')->companies as $company)
                            <button data-role="choose-company-button" data-value="{{ $company->id }}" type="submit" class="w-full p-4 bg-gray-100 shadow-sm rounded-md border border-gray-800/20">{{ $company->name }}</button>
                        @endforeach
                        {{-- <x-action tag="button">Fazer login</x-action> --}}
                    </x-form>

                    <x-action href="{{ route('presentation') }}" variant="simple">Voltar para a Home</x-action>
                </div>
            </div>
        </div>

</x-layouts.app>

<script>
    const chooseButtons = document.querySelectorAll('[data-role="choose-company-button"]');
    const inputCompany = document.querySelector('#company');

    chooseButtons.forEach(button => {
        button.addEventListener('click', function(){
            inputCompany.setAttribute('value', this.dataset.value);
        });
    });
    console.log(chooseButtons);
</script>