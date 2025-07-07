@php
    $modalTambah = [
        'title' => 'Tambah Kategori',
        'footer' => '<button type="submit" class="btn btn-primary" form="addUserForm">Tambah</button>',
    ];
@endphp
<x-modal :id="'addUserModal'" :data="$modalTambah">
    <x-slot name="body">
        <form id="addUserForm" method="POST" action="{{ route('category.store') }}">
            @csrf
            <div class="row g-gs">
                <div class="col-md-12">
                    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" id="name" value="{{ old('name') }}" required
                                placeholder="Masukkan nama..">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>
