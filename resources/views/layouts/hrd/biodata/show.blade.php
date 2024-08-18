@extends('main')
@section('content')

<div class="row">
    <div class="col-md-6 col-12 mb-md-0 mb-4">
      <div class="card">
        <h5 class="card-header">Biodata</h5>
        <div class="card-body">
          {{-- <p>Display content from your connected accounts on your site</p> --}}
          <!-- Connections -->
          <div class="d-flex mb-3">
            <div class="flex-shrink-4">
                @if ($imagePath)
                    <img src="{{ $imagePath }}" alt="Biodata Photo" class="img-thumbnail rounded-circle" style="width: 120px; height: 120px;">
                @else
                    <p>No Img</p>
                @endif
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="d-flex mb-2 align-items-center">
                    <h6 class="mb-0 mr-3">Nama : </h6>
                    <span class="text-lg mb-0" style="padding-left: 24px;"> {{ $biodata->users->name }}</span>
                </div>
                <div class="d-flex mb-2 align-items-center">
                    <h6 class="mb-0 mr-3">Email : </h6>
                    <span class="text-lg mb-0" style="padding-left: 24px;"> {{ $biodata->users->email }}</span>
                </div>
                <div class="d-flex mb-2 align-items-center">
                    <h6 class="mb-0 mr-3">No. KTP : </h6>
                    <span class="text-lg mb-0" style="padding-left: 24px;"> {{ $biodata->nik }}</span>
                </div>
                <div class="d-flex mb-2 align-items-center">
                    <h6 class="mb-0 mr-3">No. Telp  : </h6>
                    <span class="text-lg mb-0" style="padding-left: 24px;">{{ $biodata->users->phone }}</span>
                </div>
            </div>
          </div>
          <!-- /Connections -->
          <div class="d-flex mb-3">
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">instagram</h6>
                <a href="https://www.instagram.com/themeselection/" target="_blank">@ThemeSelection</a>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-danger">
                  <i class="bx bx-trash-alt"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-12">
      <div class="card">
        <h5 class="card-header">Social Accounts</h5>
        <div class="card-body">
          <p>Display content from social accounts on your site</p>
          <!-- Social Accounts -->
          <div class="d-flex mb-3">
            <div class="flex-shrink-0">
              <img
                src="../assets/img/icons/brands/facebook.png"
                alt="facebook"
                class="me-3"
                height="30"
              />
            </div>
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">Facebook</h6>
                <small class="text-muted">Not Connected</small>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-secondary">
                  <i class="bx bx-link-alt"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex mb-3">
            <div class="flex-shrink-0">
              <img
                src="../assets/img/icons/brands/twitter.png"
                alt="twitter"
                class="me-3"
                height="30"
              />
            </div>
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">Twitter</h6>
                <a href="https://twitter.com/Theme_Selection" target="_blank">@ThemeSelection</a>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-danger">
                  <i class="bx bx-trash-alt"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex mb-3">
            <div class="flex-shrink-0">
              <img
                src="../assets/img/icons/brands/instagram.png"
                alt="instagram"
                class="me-3"
                height="30"
              />
            </div>
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">instagram</h6>
                <a href="https://www.instagram.com/themeselection/" target="_blank">@ThemeSelection</a>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-danger">
                  <i class="bx bx-trash-alt"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex mb-3">
            <div class="flex-shrink-0">
              <img
                src="../assets/img/icons/brands/dribbble.png"
                alt="dribbble"
                class="me-3"
                height="30"
              />
            </div>
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">Dribbble</h6>
                <small class="text-muted">Not Connected</small>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-secondary">
                  <i class="bx bx-link-alt"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="flex-shrink-0">
              <img
                src="../assets/img/icons/brands/behance.png"

                alt="behance"
                class="me-3"
                height="30"
              />
            </div>
            <div class="flex-grow-1 row">
              <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                <h6 class="mb-0">Behance</h6>
                <small class="text-muted">Not Connected</small>
              </div>
              <div class="col-4 col-sm-5 text-end">
                <button type="button" class="btn btn-icon btn-outline-secondary">
                  <i class="bx bx-link-alt"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- /Social Accounts -->
        </div>
      </div>
    </div>
  </div>


        {{-- <div class="card">
            <div class="card-body">
                <h5 class="card-header">Detail Biodata</h5>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input id="nik" name="nik" type="text" value="{{ $biodata->nik }}" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" name="name" type="text" value="{{ $biodata->users->name }}" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="text" value="{{ $biodata->users->email }}" class="form-control" readonly>
                    </div>
                    <!-- Add other fields as necessary -->
                    <div class="mt-2">
                        <a href="{{ route('karyawan') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
                <!-- /Account -->
            </div>
            </div>
        </div> --}}
    </div>

@endsection
