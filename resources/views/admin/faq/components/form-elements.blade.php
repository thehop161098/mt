<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">name</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="name">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('content'), 'has-success': fields.content && fields.content.valid }">
    <label for="content" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">content</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            {{-- <textarea class="form-control" v-model="form.content" v-validate="'required'" id="content" name="content"></textarea> --}}
            <wysiwyg v-model="form.content" v-validate="'required'" id="content" name="content" :config="mediaWysiwygConfig" />
        </div>
        <div v-if="errors.has('content')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('content') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sort'), 'has-success': fields.sort && fields.sort.valid }">
    <label for="sort" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">sort</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.sort" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('sort'), 'form-control-success': fields.sort && fields.sort.valid}" id="sort" name="sort" placeholder="sort">
        <div v-if="errors.has('sort')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sort') }}</div>
    </div>
</div>


