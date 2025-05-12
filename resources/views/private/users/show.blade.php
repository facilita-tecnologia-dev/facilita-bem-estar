<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title :title="$user->name" :back="route('user.index')" />
    
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
                        <p class="font-semibold text-base sm:text-lg text-left">Último teste de Riscos Psicossociais realizado:</p>
                        <p class="text-sm sm:text-base text-left">{{ $latestPsychosocialCollectionDate ?? 'Nunca' }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Último teste de Clima Organizacional realizado:</p>
                        <p class="text-sm sm:text-base text-left">{{ $latestOrganizationalClimateCollectionDate ?? 'Nunca' }}</p>
                    </div>
                </div>

                <div class="w-full flex flex-row justify-between gap-2">
                    <x-form action="{{ route('user.destroy', $user) }}" delete onsubmit="return confirm('Você deseja excluir o colaborador?')">
                        <x-action tag="button" type="submit" variant="secondary">Excluir colaborador</x-action>
                    </x-form>
                    <x-action href="{{ route('user.edit', $user) }}" variant="secondary">Editar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>    
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>


{{-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script> --}}
{{-- 
<script>

    const userInfo = @json($user)    
    const testResults = @json($testResults)    
  

    function generateMentalCareReport(userInfo, testResults) {
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Set document properties
        doc.setProperties({
            title: 'Relatório de Saúde Mental',
            subject: 'Dados de Saúde Mental Pessoal',
            author: 'Facilita',
            creator: 'jsPDF'
        });

        // Colors based on iPhone wellness aesthetic
        const colors = {
            primary: '#007AFF', // iOS blue
            secondary: '#b9e6fa', // iOS light blue
            tertiary: '#4CD964', // iOS green
            background: '#F2F2F7', // iOS light background
            text: '#1C1C1E', // iOS dark text
            subText: '#8E8E93' // iOS gray text
        };

        const severityColors = {
            1: '#4CAF50',
            2: '#a9f5ac',
            3: '#eddd58',
            4: '#FFB74D',
            5: '#f55547',
        }

        const severityColorPointsMap = {
            'green': 1,
            'blue': 2,
            'yellow': 3,
            'orange': 4,
            'red': 5,
        }

        // Fonts
        doc.setFont('helvetica', 'normal');

        // Helper functions
        function addHeader(text, y, size = 16) {
            doc.setFontSize(size);
            doc.setTextColor(colors.text);
            doc.setFont('helvetica', 'bold');
            doc.text(text, 20, y);
            doc.setDrawColor(colors.primary);
            doc.setLineWidth(0.5);
            doc.line(20, y + 2, 190, y + 2);
            doc.setFont('helvetica', 'normal');
        }

        function addSubHeader(text, y) {
            doc.setFontSize(12);
            doc.setTextColor(colors.text);
            doc.setFont('helvetica', 'bold');
            doc.text(text, 20, y);
            doc.setFont('helvetica', 'normal');
        }

        function addText(text, y, color = colors.text, size = 10) {
            doc.setFontSize(size);
            doc.setTextColor(color);
            doc.text(text, 20, y);
        }

        function drawCircleProgress(x, y, radius, percentage, color, label, value) {
            // Background circle
            doc.setDrawColor(colors.primary);
            doc.setFillColor(colors.secondary);
            doc.circle(x, y, radius, 'F');
            
            // Progress arc
            doc.setDrawColor(color);
            doc.setFillColor(color);
            const angle = (percentage / 100) * 360;
            
            // Draw arc segments to simulate progress
            const segments = 36;
            const angleIncrement = 360 / segments;
            for (let i = 0; i < segments * (percentage / 100); i++) {
            const startAngle = i * angleIncrement - 90;
            const endAngle = (i + 1) * angleIncrement - 90;
            doc.setFillColor(color);
            const radStart = startAngle * Math.PI / 180;
            const radEnd = endAngle * Math.PI / 180;
            doc.triangle(
                x, y,
                x + radius * Math.cos(radStart), y + radius * Math.sin(radStart),
                x + radius * Math.cos(radEnd), y + radius * Math.sin(radEnd),
                'F'
            );
            }
            
            // Center white circle to create ring effect
            doc.setFillColor('white');
            doc.circle(x, y, radius * 0.7, 'F');
            
            // Add label and value
            doc.setTextColor(colors.text);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text(value, x, y + 2, { align: 'center' });
        }

        function addWeeklyChart(data, y, title) {
            addSubHeader(title, y);
            
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const maxValue = Math.max(...data);
            const barWidth = 15;
            const spacing = 5;
            const startX = 30;
            const chartHeight = 40;
            
            for (let i = 0; i < 7; i++) {
            const x = startX + i * (barWidth + spacing);
            const barHeight = (data[i] / maxValue) * chartHeight;
            
            // Draw bar
            doc.setFillColor(colors.primary);
            doc.roundedRect(x, y + 15, barWidth, barHeight, 2, 2, 'F');
            
            // Draw day text
            doc.setFontSize(8);
            doc.setTextColor(colors.subText);
            doc.text(days[i], x + barWidth/2, y + chartHeight + 20, { align: 'center' });
            
            // Draw value
            doc.setFontSize(8);
            doc.setTextColor(colors.text);
            doc.text(data[i].toString(), x + barWidth/2, y + 12, { align: 'center' });
            }
        }

        function getTotalResultSeverity(totalPoints){
            if(totalPoints >= 46){
                $recommendation = {
                    'title' : 'Saúde Mental Crítica',
                    'content' : 'Neste momento, é fundamental buscar atendimento psicológico e médico. Lembre-se de que pedir ajuda é um passo corajoso e importante para a sua recuperação.',
                }
            } else if(totalPoints >= 37){
                $recommendation = {
                    'title' : 'Saúde Mental Preocupante',
                    'content' : 'Sua saúde mental merece atenção e cuidado. Considere procurar um profissional para orientação e apoio em como lidar com os desafios que você está enfrentando.',
                }
            } else if(totalPoints >= 28){
                $recommendation = {
                    'title' : 'Saúde Mental Média',
                    'content' : 'Sua saúde mental está em um ponto onde algumas pequenas mudanças podem ter um grande impacto positivo. Avaliar sua rotina e incluir momentos de descanso pode ser um bom começo.',
                }
            } else if(totalPoints >= 20){
                $recommendation = {
                    'title' : 'Saúde Mental Boa',
                    'content' : 'Sua saúde mental está em um bom estado, e isso é algo muito positivo. Continue cuidando de si mesmo, praticando atividades que ajudam a reduzir o estresse e a manter o equilíbrio emocional.',
                }
            } else{
                $recommendation = {
                    'title' : 'Saúde Mental Excelente',
                    'content' : 'Sua saúde mental está em uma ótima fase! Parabéns! Manter essa qualidade envolve continuar cuidando de si e, se possível, apoiar outros ao seu redor, pois o apoio mútuo fortalece ainda mais.',
                }
            }

            return $recommendation
        }

        doc.setFillColor(colors.background);
        doc.rect(0, 0, 210, 297, 'F');
        
        doc.setFillColor(colors.primary);

        // Resultados

        doc.setFillColor('white');
        doc.rect(0, 0, 210, 297, 'F');

        addHeader(userInfo.name, 20, 20);
        addText(`${userInfo.age} anos - ${userInfo.occupation}`, 27, colors.subText, 12);
        
        testResults.forEach((test, index) => {
            doc.setFont('helvetica', 'normal');
            addText(`${test.test_name}`, (38 + (18 * index)), null, 12);
            doc.setFont('helvetica', 'bold')
            addText(`${test.recommendation}`, (45 + (18 * index)), severityColors[test['severity_color']], 14);
            doc.setFillColor(colors.text);
            doc.rect(20, (48 + (18 * index)), 170, 0.1, 'F')
        });
        
        
        // Footer on all pages
        const pageCount = doc.getNumberOfPages();

        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(colors.subText);
            doc.text(`Page ${i} of ${pageCount}`, 105, 285, { align: 'center' });
            doc.text('Relatório gerado por Facilita Tecnologia', 105, 290, { align: 'center' });
        }

        // Return the PDF document
        return doc;
    }

    // Generate and save the PDF
    function downloadPDF() {
        const username = userInfo.name.toLowerCase();
        const usernameToFileName = username.replace(' ', '_');
    
        const doc = generateMentalCareReport(userInfo, testResults);
        doc.save(`${usernameToFileName}_relatorio_de_bem_estar.pdf`);
    }

</script> --}}