<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Name</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="Name">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('amount'), 'has-success': fields.amount && fields.amount.valid }">
    <label for="amount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Amount</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.amount" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('amount'), 'form-control-success': fields.amount && fields.amount.valid}" id="amount" name="amount" placeholder="Amount">
        <div v-if="errors.has('amount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('amount') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('commission_f1'), 'has-success': fields.commission_f1 && fields.commission_f1.valid }">
    <label for="commission_f1" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Comission_f1</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.commission_f1" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('commission_f1'), 'form-control-success': fields.commission_f1 && fields.commission_f1.valid}" id="commission_f1" name="commission_f1" placeholder="Comission_f1">
        <div v-if="errors.has('commission_f1')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('commission_f1') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('total_f1'), 'has-success': fields.total_f1 && fields.total_f1.valid }">
    <label for="total_f1" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Total_f1</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.total_f1" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('total_f1'), 'form-control-success': fields.total_f1 && fields.total_f1.valid}" id="total_f1" name="total_f1" placeholder="Total_f1">
        <div v-if="errors.has('total_f1')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('total_f1') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('total_trade'), 'has-success': fields.total_trade && fields.total_trade.valid }">
    <label for="total_trade" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Total_trade</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.total_trade" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('total_trade'), 'form-control-success': fields.total_trade && fields.total_trade.valid}" id="total_trade" name="total_trade" placeholder="Total_trade">
        <div v-if="errors.has('total_trade')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('total_trade') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('master_ib'), 'has-success': fields.master_ib && fields.master_ib.valid }">
    <label for="master_ib" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Master_ib</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.master_ib" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('master_ib'), 'form-control-success': fields.master_ib && fields.master_ib.valid}" id="master_ib" name="master_ib" placeholder="Master_ib">
        <div v-if="errors.has('master_ib')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('master_ib') }}</div>
    </div>
</div>


