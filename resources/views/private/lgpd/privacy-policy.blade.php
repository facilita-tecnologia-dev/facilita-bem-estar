<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="LGPD"
                :breadcrumbs="[
                    'LGPD' => '',
                ]"
            />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 text-sm sm:text-base">
                <h2 class="text-base sm:text-lg font-semibold mb-2">1. Quem somos</h2>
                <p>Somos a Facilita Tecnologia, uma empresa comprometida com a proteção dos seus dados pessoais, em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 - LGPD).</p>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">2. Dados pessoais coletados</h2>
                <p>Para fins administrativos, gestão de recursos humanos e realização de testes, coletamos os seguintes dados pessoais:</p>

                <ul class="list-disc pl-4 my-2">
                    <li>Nome</li>
                    <li>CPF</li>
                    <li>Senha de acesso ao sistema</li>
                    <li>Setor</li>
                    <li>Função de trabalho</li>
                    <li>Turno</li>
                    <li>Grau de Instrução</li>
                    <li>Data de nascimento</li>
                    <li>Data de admissão</li>
                    <li>Estado Civil</li>
                    <li>Gênero</li>
                </ul>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">3. Finalidade do tratamento dos dados</h2>
                Os dados são tratados para:

                <ul class="list-disc pl-4 my-2">
                    <li>Gerenciamento de colaboradores;</li>
                    <li>Controle interno da empresa;</li>
                    <li>Realização dos testes;</li>
                    <li>Cumprimento de obrigações legais;</li>
                    <li>Análise estatística e melhoria dos processos, preservando o anonimato.</li>
                </ul>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">4. Anonimato durante os testes</h2>
                <p>Garantimos que durante a realização dos testes os dados são tratados de forma anonimizada. Ou seja, não é possível identificar ou vincular os resultados dos testes a um colaborador específico por meio de nome, CPF ou qualquer outra informação pessoal única. Dessa forma, a sua identidade é protegida durante todo o processo.</p>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">5. Bases legais para o tratamento</h2>
                <p>O tratamento dos seus dados está fundamentado nas bases legais da LGPD, incluindo:</p>

                <ul class="list-disc pl-4 my-2">
                    <li>Cumprimento de obrigação legal ou regulatória;</li>
                    <li>Execução de contrato;</li>
                    <li>Legítimo interesse da empresa para a gestão de pessoas e segurança do ambiente de trabalho.</li>
                </ul>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">6. Compartilhamento e segurança dos dados</h2>
                <p>Seus dados pessoais são acessíveis somente a colaboradores autorizados e utilizados exclusivamente para as finalidades descritas neste termo. Adotamos medidas técnicas e administrativas para proteger os dados contra acessos não autorizados, vazamentos ou qualquer uso indevido.</p>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">7. Direitos do titular dos dados</h2>
                <p>Você tem o direito de:</p>

                <ul class="list-disc pl-4 my-2">
                    <li>Confirmar a existência de tratamento dos seus dados;</li>
                    <li>Acessar seus dados pessoais;</li>
                    <li>Corrigir dados incompletos, inexatos ou desatualizados;</li>
                    <li>Solicitar a anonimização, bloqueio ou eliminação de dados desnecessários;</li>
                    <li>Solicitar a portabilidade dos seus dados;</li>
                    <li>Revogar seu consentimento a qualquer momento, quando o tratamento for baseado neste;</li>
                    <li>Solicitar a eliminação dos dados pessoais, ressalvadas as hipóteses legais de manutenção.</li>
                </ul>
                <p>Para exercer esses direitos, entre em contato conosco através do e-mail: <u>facilita.code@gmail.com</u>.</p>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">8. Tempo de armazenamento</h2>
                <p>Seus dados pessoais serão armazenados enquanto forem necessários para cumprir as finalidades descritas neste termo ou conforme exigido por legislação aplicável.</p>

                <h2 class="text-base sm:text-lg font-semibold mb-2 mt-6">9. Alterações nesta política</h2>
                <p>Podemos atualizar esta política para refletir mudanças legais ou operacionais. Sempre que isso ocorrer, você será informado.</p>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>