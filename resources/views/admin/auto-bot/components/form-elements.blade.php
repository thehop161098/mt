<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.name" v-validate="'required'" id="name" name="name"></textarea>
        </div>
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('price'), 'has-success': fields.price && fields.price.valid }">
    <label for="price" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.price') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.price" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('price'), 'form-control-success': fields.price && fields.price.valid}" id="price" name="price" placeholder="{{ trans('admin.auto-bot.columns.price') }}">
        <div v-if="errors.has('price')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('price') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('image'), 'has-success': fields.image && fields.image.valid }">
    <label for="image" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Image</label>
    <div class="form-image" :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        @include('brackets/admin-ui::admin.includes.media-uploader', [
            'mediaCollection' => app(App\Models\Advertisement::class)->getMediaCollection('image'),
            'media' => isset($autoBot) ? $autoBot->getThumbs200ForCollection('image') : null
        ])
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_f1'), 'has-success': fields.commission_f1 && fields.commission_f1.valid }">
    <label for="commission_f1" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Commission F1</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_f1" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_f1'), 'form-control-success': fields.commission_f1 && fields.commission_f1.valid}" id="commission_f1" name="commission_f1" placeholder="Commission F1">
        <div v-if="errors.has('commission_f1')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_f1') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_7'), 'has-success': fields.commission_7 && fields.commission_7.valid }">
    <label for="commission_7" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.commission_7') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_7" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_7'), 'form-control-success': fields.commission_7 && fields.commission_7.valid}" id="commission_7" name="commission_7" placeholder="{{ trans('admin.auto-bot.columns.commission_7') }}">
        <div v-if="errors.has('commission_7')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_7') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_21'), 'has-success': fields.commission_21 && fields.commission_21.valid }">
    <label for="commission_21" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.commission_21') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_21" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_21'), 'form-control-success': fields.commission_21 && fields.commission_21.valid}" id="commission_21" name="commission_21" placeholder="{{ trans('admin.auto-bot.columns.commission_21') }}">
        <div v-if="errors.has('commission_21')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_21') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_30'), 'has-success': fields.commission_30 && fields.commission_30.valid }">
    <label for="commission_30" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.commission_30') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_30" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_30'), 'form-control-success': fields.commission_30 && fields.commission_30.valid}" id="commission_30" name="commission_30" placeholder="{{ trans('admin.auto-bot.columns.commission_30') }}">
        <div v-if="errors.has('commission_30')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_30') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_90'), 'has-success': fields.commission_90 && fields.commission_90.valid }">
    <label for="commission_90" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.auto-bot.columns.commission_90') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_90" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_90'), 'form-control-success': fields.commission_90 && fields.commission_90.valid}" id="commission_90" name="commission_90" placeholder="{{ trans('admin.auto-bot.columns.commission_90') }}">
        <div v-if="errors.has('commission_90')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_90') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('risk'), 'has-success': fields.risk && fields.risk.valid }">
    <label for="risk" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Risk</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.risk" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('risk'), 'form-control-success': fields.risk && fields.risk.valid}" id="risk" name="risk" placeholder="Risk">
        <div v-if="errors.has('risk')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('risk') }}</div>
    </div>
</div>


