<!-- Name Field -->
<div class="form-group row">
    {{ html()->label('Nama', 'Nama')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->text('name', old('name'))
            ->class('form-control')
            ->placeholder('Nama')
            ->attribute('maxlength', 125)
            ->required() }}
    </div>
</div>


<!-- Guard Name Field -->
<div class="form-group row">
    {{ html()->label('Nama Guard', 'Nama Guard')->addClass('col-sm-2 col-form-label') }}
    <div class="col-sm-10">
        {{ html()->text('guard_name', old('guard_name'))
            ->class('form-control')
            ->placeholder('Nama Guard')
            ->attribute('maxlength', 125)
            ->required() }}
</div>
