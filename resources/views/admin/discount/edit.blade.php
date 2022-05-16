@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.discount.actions.edit', ['name' => $discount->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <discount-form
                :action="'{{ $discount->resource_url }}'"
                :data="{{ $discount->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.discount.actions.edit', ['name' => $discount->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.discount.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </discount-form>

        </div>
    
</div>

@endsection