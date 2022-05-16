<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.history-bot.columns.user_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.user_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_id'), 'form-control-success': fields.user_id && fields.user_id.valid}" id="user_id" name="user_id" placeholder="{{ trans('admin.history-bot.columns.user_id') }}">
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('bot_id'), 'has-success': fields.bot_id && fields.bot_id.valid }">
    <label for="bot_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.history-bot.columns.bot_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.bot_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('bot_id'), 'form-control-success': fields.bot_id && fields.bot_id.valid}" id="bot_id" name="bot_id" placeholder="{{ trans('admin.history-bot.columns.bot_id') }}">
        <div v-if="errors.has('bot_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('bot_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('time'), 'has-success': fields.time && fields.time.valid }">
    <label for="time" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.history-bot.columns.time') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.time" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('time'), 'form-control-success': fields.time && fields.time.valid}" id="time" name="time" placeholder="{{ trans('admin.history-bot.columns.time') }}">
        <div v-if="errors.has('time')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('time') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('time_expired'), 'has-success': fields.time_expired && fields.time_expired.valid }">
    <label for="time_expired" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.history-bot.columns.time_expired') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.time_expired" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('time_expired'), 'form-control-success': fields.time_expired && fields.time_expired.valid}" id="time_expired" name="time_expired" placeholder="{{ trans('admin.history-bot.columns.time_expired') }}">
        <div v-if="errors.has('time_expired')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('time_expired') }}</div>
    </div>
</div>

<div class="form-check row" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
        <input class="form-check-input" id="status" type="checkbox" v-model="form.status" v-validate="''" data-vv-name="status"  name="status_fake_element">
        <label class="form-check-label" for="status">
            {{ trans('admin.history-bot.columns.status') }}
        </label>
        <input type="hidden" name="status" :value="form.status">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


