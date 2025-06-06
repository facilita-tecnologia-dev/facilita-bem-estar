const addControlActionModal = document.querySelector('[data-role="add-control-action-modal"]');

document.addEventListener('DOMContentLoaded', function () {
    if(addControlActionModal){
        body.addEventListener("click", function (event) {
            if (event.target === addControlActionModal) {
                hideAddControlActionModal(addControlActionModal);
            }
        });
    }
});

function showAddControlActionModal(){
    addControlActionModal.classList.replace("hidden", "flex");
}

function hideAddControlActionModal(){
    addControlActionModal.classList.replace("flex", "hidden");
}


// function addControlAction(event){
//     const parent = event.target.parentElement;
//     const content = parent.querySelector('input[type="text"]').value;
//     const riskName = parent.querySelector('select').value;
    
//     const riskContainer = document.querySelector(`[data-risk-name="${riskName}"]`)

//     const nodeToClone = riskContainer.querySelector('[data-role="control-action-line"]');
//     const clone = nodeToClone.cloneNode(true);

//     riskContainer.appendChild(clone);
//     `
//     <div data-role="control-action-line" class="flex gap-2">
//         <label class="flex items-center gap-4 w-full rounded-md shadow-md p-4 border border-gray-300 bg-gray-100/50 opacity-70 has-[input[type=checkbox]:checked]:bg-sky-300/50 has-[input[type=checkbox]:checked]:opacity-100 cursor-pointer relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
//             <input type="hidden" name="control_actions[]" value="0">
//             <input 
//                 type="checkbox" 
//                 name="control_actions[]" 
//                 {{ $isAllowed ? 'checked' : '' }} 
//                 value="1" 
//                 class="peer hidden"
//             >
//             <i class="fa-solid fa-circle-check opacity-0 peer-checked:opacity-100 transition-opacity text-gray-800"></i>
//             <div class="flex-1">
//                 <p class="font-medium text-sm md:text-base text-gray-800">{{ $controlAction->content }}</p>
//             </div>
//             <span class="text-xs">{{ $isDefault ? 'Padrão' : 'Personalizado'}}</span>
//         </label>

//         @if(!$isDefault)
//             <x-action tag="button" type="button" variant="danger" onclick="deleteControlAction(event)">
//                 <i class="fa-solid fa-trash"></i>
//             </x-action>
//         @endif
//     </div>
//     `
//     console.log(riskContainer);


// }

function deleteControlAction(){
    console.log('deletar');
}
