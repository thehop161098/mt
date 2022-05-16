<div class="form-group row align-items-center" :class="{'has-danger': errors.has('day'), 'has-success': fields.day && fields.day.valid }">
    <label for="day" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Day</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.day" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('day'), 'form-control-success': fields.day && fields.day.valid}" id="day" name="day" placeholder="Day">
        <div v-if="errors.has('day')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('day') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('amount'), 'has-success': fields.amount && fields.amount.valid }">
    <label for="amount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Amount</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.amount" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('amount'), 'form-control-success': fields.amount && fields.amount.valid}" id="amount" name="amount" placeholder="Amount">
        <div v-if="errors.has('amount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('amount') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('percent'), 'has-success': fields.percent && fields.percent.valid }">
    <label for="percent" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Percent</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.percent" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('percent'), 'form-control-success': fields.percent && fields.percent.valid}" id="percent" name="percent" placeholder="Percent">
        <div v-if="errors.has('percent')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('percent') }}</div>
    </div>
</div>


