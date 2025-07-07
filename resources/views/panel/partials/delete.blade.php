<x-modal :id="'deleteMapModal'" :data="$modalDelete">
    <x-slot name="body">
        <form method="POST" id="deleteForm" action="{{ route($prefix . '.destroy') }}">
            @csrf
            @method('delete')
            <div class="row g-gs">
                <div class="col-md-12">
                    <div class="form-group">
                        <span>Apakah Anda yakin ingin menghapus <strong
                                id="nameAccount"></strong></span>
                    </div>
                    <input type="hidden" name="id" value="">
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>
