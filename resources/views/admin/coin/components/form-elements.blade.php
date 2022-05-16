<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Name</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="Name">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image'), 'has-success': fields.image && fields.image.valid }">
    <label for="image" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Image</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.image" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('image'), 'form-control-success': fields.image && fields.image.valid}" id="image" name="image" placeholder="Image">
        <div v-if="errors.has('image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('alias'), 'has-success': fields.alias && fields.alias.valid }">
    <label for="alias" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Alias</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.alias" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('alias'), 'form-control-success': fields.alias && fields.alias.valid}" id="alias" name="alias" placeholder="Alias">
        <div v-if="errors.has('alias')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('alias') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('range'), 'has-success': fields.range && fields.range.valid }">
    <label for="range" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Range</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.range" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('range'), 'form-control-success': fields.range && fields.range.valid}" id="range" name="range" placeholder="Range">
        <div v-if="errors.has('range')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('range') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('min'), 'has-success': fields.min && fields.min.valid }">
    <label for="min" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Min</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.min" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('min'), 'form-control-success': fields.min && fields.min.valid}" id="min" name="min" placeholder="Min">
        <div v-if="errors.has('min')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('min') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('max'), 'has-success': fields.max && fields.max.valid }">
    <label for="max" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Max</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.max" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('max'), 'form-control-success': fields.max && fields.max.valid}" id="max" name="max" placeholder="Max">
        <div v-if="errors.has('max')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('max') }}</div>
    </div>
</div>

<div class="form-check row align-items-center" :class="{'has-danger': errors.has('is_gold'), 'has-success': fields.is_gold && fields.is_gold.valid }">
    <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
        <input class="form-check-input" id="checkbox" type="checkbox" v-model="form.is_gold" data-vv-name="is_gold"  name="is_gold_fake_element">
        <label class="form-check-label" for="checkbox">
            Gold Coin
        </label>
        <input type="hidden" name="is_gold" v-model="form.is_gold">
        <div v-if="errors.has('is_gold')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('is_gold') }}</div>
    </div>
</div>

<div class="form-check row align-items-center" :class="{'has-danger': errors.has('publish'), 'has-success': fields.publish && fields.publish.valid }">
    <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
        <input class="form-check-input" id="checkboxPublish" type="checkbox" v-model="form.publish" data-vv-name="publish"  name="publish_fake_element">
        <label class="form-check-label" for="checkboxPublish">
            Publish
        </label>
        <input type="hidden" name="publish" v-model="form.publish">
        <div v-if="errors.has('publish')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('publish') }}</div>
    </div>
</div>



