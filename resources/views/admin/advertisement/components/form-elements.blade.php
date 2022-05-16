<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.advertisement.columns.name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="{{ trans('admin.advertisement.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
     :class="{'has-danger': errors.has('image'), 'has-success': fields.image && fields.image.valid }">
    <label for="image" class="col-form-label text-md-right"
           :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.advertisement.columns.image') }}</label>
    <div class="form-image" :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        @include('brackets/admin-ui::admin.includes.media-uploader', [
            'mediaCollection' => app(App\Models\Advertisement::class)->getMediaCollection('image'),
            'media' => isset($discount) ? $discount->getThumbs200ForCollection('image') : null
        ])
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sort'), 'has-success': fields.sort && fields.sort.valid }">
    <label for="sort" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.advertisement.columns.sort') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.sort" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('sort'), 'form-control-success': fields.sort && fields.sort.valid}" id="sort" name="sort" placeholder="{{ trans('admin.advertisement.columns.sort') }}">
        <div v-if="errors.has('sort')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sort') }}</div>
    </div>
</div>

<div class="form-check row" :class="{'has-danger': errors.has('publish'), 'has-success': fields.publish && fields.publish.valid }">
    <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
        <input class="form-check-input" id="publish" type="checkbox" v-model="form.publish" v-validate="''" data-vv-name="publish"  name="publish_fake_element">
        <label class="form-check-label" for="publish">
            {{ trans('admin.advertisement.columns.publish') }}
        </label>
        <input type="hidden" name="publish" :value="form.publish">
        <div v-if="errors.has('publish')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('publish') }}</div>
    </div>
</div>


