<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.wheel.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control"
               :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}"
               id="name" name="name" placeholder="{{ trans('admin.wheel.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('prize'), 'has-success': fields.prize && fields.prize.valid }">
    <label for="prize" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.wheel.columns.prize') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.prize" v-validate="'required'" @input="validate($event)"
               class="form-control"
               :class="{'form-control-danger': errors.has('prize'), 'form-control-success': fields.prize && fields.prize.valid}"
               id="prize" name="prize" placeholder="{{ trans('admin.wheel.columns.prize') }}" step="any">
        <div v-if="errors.has('prize')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('prize') }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('sort'), 'has-success': fields.sort && fields.sort.valid }">
    <label for="sort" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.wheel.columns.sort') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.sort" v-validate="'required|integer'" @input="validate($event)"
               class="form-control"
               :class="{'form-control-danger': errors.has('sort'), 'form-control-success': fields.sort && fields.sort.valid}"
               id="sort" name="sort" placeholder="{{ trans('admin.wheel.columns.sort') }}">
        <div v-if="errors.has('sort')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sort') }}</div>
    </div>
</div>


