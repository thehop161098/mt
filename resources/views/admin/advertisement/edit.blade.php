@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.advertisement.actions.edit', ['name' => $advertisement->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <advertisement-form
                :action="'{{ $advertisement->resource_url }}'"
                :data="{{ $advertisement->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.advertisement.actions.edit', ['name' => $advertisement->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.advertisement.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </advertisement-form>

        </div>
    
</div>

@endsection