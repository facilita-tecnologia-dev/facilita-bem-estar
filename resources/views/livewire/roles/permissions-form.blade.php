<label class="flex items-center gap-4 w-full rounded-md shadow-md p-4 bg-gray-100/50 has-[input[type=checkbox]:checked]:bg-sky-300/50 cursor-pointer relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
    <input type="hidden" name="{{ $userPermission->permission->key_name }}" value="0">
    <input type="checkbox" name="{{ $userPermission->permission->key_name }}" {{ $userPermission->allowed ? 'checked' : '' }} value="1" class="hidden">
    <i class="fa-solid fa-circle-check hidden has-[input[type=checkbox]:checked]:block"></i>
<label> 