<x-layouts.app>

        <div class="flex h-full justify-center">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">

                    <div class="w-full flex flex-col items-center gap-4">
                        <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                        <div class="flex flex-col gap-2 items-center">
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Escolha a empresa</h1>
                            <p class="text-base text-center text-gray-800">Escolha em qual das empresas você quer fazer login</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 max-h-64 overflow-auto px-2 py-2">
                        @foreach ($userCompanies as $company)
                            <x-form action="{{ route('auth.login.internal.with-company', $company) }}" post>
                                <button type="submit" class="block rounded-md shadow-md bg-gray-100/50 border border-gray-200 w-full px-2 py-3 text-center relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                                    {{ $company->name }}
                                </button>
                            </x-form>
                        @endforeach 
                    </div>

                    <x-action href="{{ route('logout') }}" variant="secondary">
                        Voltar
                    </x-action>
                </div>
            </div>
        </div>

</x-layouts.app>
{{-- 
<script>
    const toggleLoginFormButtons = document.querySelectorAll('[data-role="toggle-login-form"]');

    toggleLoginFormButtons.forEach(button => {
        button.addEventListener('click', function(){
            const bgElement = button.parentElement.querySelector('[data-role="toggle-bg"]');

            if(this.id == 'external'){
                bgElement.classList.replace('left-1', 'left-[calc(50%+4px)]')
                document.querySelector('#login-internal').style.display = 'none';
                document.querySelector('#login-external').style.display = 'flex';
            } else{
                bgElement.classList.replace('left-[calc(50%+4px)]', 'left-1')
                document.querySelector('#login-external').style.display = 'none';
                document.querySelector('#login-internal').style.display = 'flex';
            }
        });
    });
</script> --}}