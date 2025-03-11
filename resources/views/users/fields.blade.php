<!-- Email Field -->
<div class="form-group row">
    {{ html()->label('Email', 'email')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->email('email', old('email'))->class(['form-control' => true, 'is-invalid' => $errors->has('email')])->placeholder('Email')->attribute('maxlength', 100)->required() }}
    @error('email')
        <div class="invalid-feedback">
            <h6>{{ $message }}</h6>
        </div>
    @enderror
    </div>
</div>

<!-- Name Field -->
<div class="form-group row">
    {{ html()->label('Nama', 'name')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->text('name', old('name'))->class(['form-control' => true, 'is-invalid' => $errors->has('name')])->placeholder('Nama')->attribute('maxlength', 50)->required() }}

    @error('name')
        <div class="invalid-feedback">
            <h6>{{ $message }}</h6>
        </div>
    @enderror
    </div>
</div>

<!-- Name Field -->
<div class="form-group row">
    {{ html()->label('Peran', 'role')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->select('role', ['' => 'Pilih Peran'] + $roles->toArray(), old('role', $roleName ?? ''))->class(['form-control' => true, 'is-invalid' => $errors->has('role')])->required() }}
    @error('role')
        <div class="invalid-feedback">
            <h6>{{ $message }}</h6>
        </div>
    @enderror
    </div>
</div>


@if (!isset($user))
    <!-- Password Field -->
    <div class="form-group row">
        {{ html()->label('Password', 'password')->addClass('col-sm-2 col-form-label') }}
        <div class="col-sm-10">
            {{ html()->text('password', old('password'))->type('password')->class(['form-control' => true, 'is-invalid' => $errors->has('password')])->attribute('maxlength', 50)->required() }}
        @error('password')
            <div class="invalid-feedback">
                <h6>{{ $message }}</h6>
            </div>
        @enderror
        </div>
    </div>
@endif

<!-- Foto Field
<div class="form-group row">
    {{ html()->label('Foto', 'foto')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->text('foto', old('foto'))->class(['form-control' => true, 'is-invalid' => $errors->has('email')])->placeholder('Foto')->attribute('maxlength', 100)->required() }}
    </div>
</div>
-->
