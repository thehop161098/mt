@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Create')

@section('body')

    <div class="container-xl">

                <div class="card">
        
        <setting-refund-form
            :action="'{{ url('admin/setting-refunds') }}'"
            v-cloak
            inline-template>

            <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>
                
                <div class="card-header">
                    <i class="fa fa-plus"></i> Create
                </div>

                <div class="card-body">
                    @include('admin.setting-refund.components.form-elements')
                </div>
                                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="submiting">
                        <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                        {{ trans('brackets/admin-ui::admin.btn.save') }}
                    </button>
                </div>
                
            </form>

        </setting-refund-form>

        </div>

        </div>

    
@endsection