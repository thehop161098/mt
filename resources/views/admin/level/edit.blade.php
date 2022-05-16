@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Edit')

@section('body')

    <div class="container-xl">
        <div class="card">

            <level-form
                :action="'{{ $level->resource_url }}'"
                :data="{{ $level->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> Edit
                    </div>

                    <div class="card-body">
                        @include('admin.level.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </level-form>

        </div>
    
</div>

@endsection