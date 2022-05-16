@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Update')

@section('body')

    <div class="container-xl">
        <div class="card">

            <wallet-form
                :action="'{{ $wallet->resource_url }}'"
                :data="{{ $wallet->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Update
                    </div>

                    <div class="card-body">
                        @include('admin.wallet.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </wallet-form>

        </div>
    
</div>

@endsection