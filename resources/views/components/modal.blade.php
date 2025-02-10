@props(['id', 'data', 'size' => '', 'cancelButtonText' => 'Batal', 'showCancelButton' => true]) <!-- Tambahkan props 'showCancelButton' -->

<div class="modal fade" id="{{ $id }}" aria-labelledby="{{ $id }}Label" aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $size ? 'modal-' . $size : '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="{{ $id }}Label">{{ $data['title'] }}</h5>
                <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div class="modal-body modal-body-md">
                {{ $body }}
            </div>
            <div class="modal-footer">
                @if($showCancelButton) <!-- Cek apakah tombol cancel harus ditampilkan -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $cancelButtonText }}</button>
                @endif
                {!! $data['footer'] !!}
            </div>
        </div>
    </div>
</div>
