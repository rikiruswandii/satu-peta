<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-primary">{{ __('Sunting Artikel') }}</h3>
                            <div class="nk-block-des text-soft">
                                <p class="text-sm">Masukkan atau ubah form data
                                    <strong class="text-primary">{{ __('artikel') }}</strong> yang ingin Anda sunting.
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="{{ route('articles') }}" class="btn btn-secondary">
                                <em class="icon ni ni-chevron-left-circle-fill"></em><span>Kembali</span></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="col-12 bg-white rounded-md">
                        <form action="{{ route('articles.update', ['id' => Crypt::encrypt($data->id)]) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <div class="mb-3">
                                            <label for="title" class="form-label" for="title">Judul</label>
                                            <input type="text"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                name="title" placeholder="masukkan nama.."
                                                value="{{ old('title', $data->title) }}" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-0">
                                            <label for="content" class="form-label" for="content">Konten</label>
                                            <textarea class="summernote-basic @error('content') is-invalid @enderror" id="content" name="content" required>{{ $data->content }}</textarea>

                                            @error('content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label class="form-label" style="margin-bottom: 20px;"
                                                for="category">Kategori</label>
                                            <div class="form-control-wrap d-flex align-items-center gap-2">
                                                <!-- Flexbox untuk mengatur tata letak -->
                                                <!-- Form Select -->
                                                <select id="category"
                                                    class="form-select js-select2 @error('category_id') is-invalid @enderror"
                                                    data-search="on" name="category_id" required>
                                                    <option value="Pilih Kategori" disabled>Pilih Kategori</option>
                                                    @foreach ($categories as $c)
                                                        <option value="{{ $c->id }}"
                                                            {{ $data->category_id === $c->id ? 'selected' : '' }}>
                                                            {{ $c->name }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- Tombol Tambah Kategori -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addUserModal">
                                                    <i class="ni ni-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-0" style="margin-top: 25px;">
                                            <div class="form-group">
                                                <label class="form-label" for="file">Gambar Mini</label>
                                                <input type="file" id="file" name="file"
                                                    class="filepond @error('file') is-invalid @enderror"
                                                    accept="image/jpeg, image/png"
                                                    data-existing-file="{{ isset($data) && $data->documents->where('documentable_id', $data->id)->first() ? Storage::url($data->documents->where('documentable_id', $data->id)->first()->path) : '' }}"
                                                    required>
                                                @error('file')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
    @section('modal')
        @include('panel.partials.category-create')
    @endsection
</x-app-layout>
