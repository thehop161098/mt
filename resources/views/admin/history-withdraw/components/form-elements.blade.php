<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tx_hash'), 'has-success': fields.tx_hash && fields.tx_hash.valid }">
    <label for="tx_hash" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.history-withdraw.columns.tx_hash') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.tx_hash" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('tx_hash'), 'form-control-success': fields.tx_hash && fields.tx_hash.valid}" id="tx_hash" name="tx_hash" placeholder="{{ trans('admin.history-withdraw.columns.tx_hash') }}">
        <div v-if="errors.has('tx_hash')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('tx_hash') }}</div>
    </div>
</div>


