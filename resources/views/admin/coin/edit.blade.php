@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.coin.actions.edit', ['name' => $coin->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <coin-form
                :action="'{{ $coin->resource_url }}'"
                :data="{{ $coin->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Edit
                    </div>

                    <div class="card-body">
                        @include('admin.coin.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </coin-form>

        </div>
    
</div>

@endsection