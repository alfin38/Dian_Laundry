@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')

<div id="tambah_karyawan" class="main-content">
	<div class="container">
		<div class="baris">
			<div class="col mt-2">
				<div class="card">
					<div class="card-title card-flex">
						<div class="card-col">
							<h2>Update Data Karyawan</h2>	
						</div>

						<div class="card-col txt-right">
							<a href="{{ route('karyawan.index') }}" class="btn-xs bg-primary">Kembali</a>
						</div>
					</div>

					<div class="card-body">
						<form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" class="form-input">
                            @csrf
                            @method('PUT')
							<div class="form-grup">
								<label for="nama">Nama Karyawan</label>
								<input type="text" name="nama" placeholder="Nama lengkap" autocomplete="off" id="nama" value="{{ $karyawan->nama }}">
							</div>

							<div class="form-grup">
								<label for="username">Username</label>
								<input type="text" name="username" placeholder="Username" autocomplete="off" id="username" value="{{ $karyawan->username }}">
							</div>

							<div class="form-grup">
								<label for="email">Email</label>
								<input type="text" name="email" placeholder="Email" autocomplete="off" id="email" value="{{ $karyawan->email }}">
							</div>

                            <div class="form-grup">
                                <label for="level">Level</label>
                                <select name="level" id="level">
                                    <option value="karyawan">Karyawan</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

							<div class="form-grup">
								<label for="email">Password</label>
								<input type="text" name="password" placeholder="password baru" autocomplete="off" id="password">
							</div>
                                
                                <div class="form-grup">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi Password" autocomplete="new-password" id="password_confirmation" required>
                                </div>

							<div class="form-grup ">
								<button type="submit" class="mt-1" name="update">Update Data</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
    
@endsection