<div class="form-group row align-items-center" :class="{'has-danger': errors.has('code'), 'has-success': fields.code && fields.code.valid }">
    <label for="coin" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Coin</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.coin" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('coin'), 'form-control-success': fields.coin && fields.coin.valid}" id="code" name="coin" placeholder="Coin">
        <div v-if="errors.has('coin')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('coin') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('code'), 'has-success': fields.code && fields.code.valid }">
    <label for="code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Code</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.code" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('code'), 'form-control-success': fields.code && fields.code.valid}" id="code" name="code" placeholder="Code">
        <div v-if="errors.has('code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('code') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('private_key'), 'has-success': fields.private_key && fields.private_key.valid }">
    <label for="private_key" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Private_key</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.private_key" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('private_key'), 'form-control-success': fields.private_key && fields.private_key.valid}" id="private_key" name="private_key" placeholder="Private_key">
        <div v-if="errors.has('private_key')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('private_key') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cate_id'), 'has-success': fields.cate_id && fields.cate_id.valid }">
    <label for="cate_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Category</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cate_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cate_id'), 'form-control-success': fields.cate_id && fields.cate_id.valid}" id="cate_id" name="cate_id" placeholder="Category">
        <div v-if="errors.has('cate_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cate_id') }}</div>
    </div>
</div>
