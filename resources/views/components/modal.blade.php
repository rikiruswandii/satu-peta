@props(['id', 'data', 'size' => '']) <!-- Tambahkan props 'size' dengan nilai default string kosong -->

<div class="modal fade" id="{{ $id }}" aria-labelledby="{{ $id }}Label" aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog {{ $size ? 'modal-' . $size : '' }}"> <!-- Tambahkan class ukuran modal jika props 'size' diberikan -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="{{ $id }}Label">{{ $data['title'] }}</h5>
                <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div class="modal-body modal-body-md">
                {{ $body }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                {!! $data['footer'] !!}
            </div>
        </div>
    </div>
</div>