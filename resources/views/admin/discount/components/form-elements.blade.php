<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control"
               :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}"
               id="name" name="name" placeholder="{{ trans('admin.discount.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('image'), 'has-success': fields.image && fields.image.valid }">
    <label for="image" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.image') }}</label>
    <div class="form-image" :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        @include('brackets/admin-ui::admin.includes.media-uploader', [
            'mediaCollection' => app(App\Models\Discount::class)->getMediaCollection('image'),
            'media' => isset($discount) ? $discount->getThumbs200ForCollection('image') : null
        ])
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('date_show_image'), 'has-success': fields.date_show_image && fields.date_show_image.valid }">
    <label for="date_show_image" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.date_show_image') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <datetime @input="validate($event)" v-model="form.date_show_image" v-validate="'required'"
                  :config="datePickerConfig" class="flatpickr"
                  :class="{'form-control-danger': errors.has('date_show_image'), 'form-control-success': fields.date_show_image && fields.date_show_image.valid}"
                  name="date_show_image"
                  placeholder="{{ trans('admin.discount.columns.date_show_image') }}"></datetime>
        <div v-if="errors.has('date_show_image')" class="form-control-feedback form-text" v-cloak>@{{
            errors.first('date_show_image') }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('deposit'), 'has-success': fields.deposit && fields.deposit.valid }">
    <label for="deposit" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.deposit') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.deposit" v-validate="'required|decimal'" @input="validate($event)"
               class="form-control"
               :class="{'form-control-danger': errors.has('deposit'), 'form-control-success': fields.deposit && fields.deposit.valid}"
               id="deposit" name="deposit" placeholder="{{ trans('admin.discount.columns.deposit') }}">
        <div v-if="errors.has('deposit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('deposit')
            }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('discount'), 'has-success': fields.discount && fields.discount.valid }">
    <label for="discount" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.discount') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.discount" v-validate="'required|decimal'" @input="validate($event)"
               class="form-control"
               :class="{'form-control-danger': errors.has('discount'), 'form-control-success': fields.discount && fields.discount.valid}"
               id="discount" name="discount" placeholder="{{ trans('admin.discount.columns.discount') }}">
        <div v-if="errors.has('discount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('discount')
            }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('from_date'), 'has-success': fields.from_date && fields.from_date.valid }">
    <label for="from_date" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.from_date') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <datetime @input="validate($event)" v-model="form.from_date" v-validate="'required'"
                  :config="datePickerConfig" class="flatpickr"
                  :class="{'form-control-danger': errors.has('from_date'), 'form-control-success': fields.from_date && fields.from_date.valid}"
                  name="date_show_image"
                  placeholder="{{ trans('admin.discount.columns.date_show_image') }}"></datetime>
        <div v-if="errors.has('from_date')" class="form-control-feedback form-text" v-cloak>@{{
            errors.first('from_date') }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('to_date'), 'has-success': fields.to_date && fields.to_date.valid }">
    <label for="to_date" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.discount.columns.to_date') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <datetime @input="validate($event)" v-model="form.to_date" v-validate="'required'"
                  :config="datePickerConfig" class="flatpickr"
                  :class="{'form-control-danger': errors.has('to_date'), 'form-control-success': fields.to_date && fields.to_date.valid}"
                  name="date_show_image"
                  placeholder="{{ trans('admin.discount.columns.date_show_image') }}"></datetime>
        <div v-if="errors.has('to_date')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('to_date')
            }}
        </div>
    </div>
</div>


