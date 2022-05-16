@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.history-refund.actions.edit', ['name' => $historyRefund->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <history-refund-form
                :action="'{{ $historyRefund->resource_url }}'"
                :data="{{ $historyRefund->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.history-refund.actions.edit', ['name' => $historyRefund->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.history-refund.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </history-refund-form>

        </div>
    
</div>

@endsection