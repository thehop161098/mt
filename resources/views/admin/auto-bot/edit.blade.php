@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.auto-bot.actions.edit', ['name' => $autoBot->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <auto-bot-form
                :action="'{{ $autoBot->resource_url }}'"
                :data="{{ $autoBot->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.auto-bot.actions.edit', ['name' => $autoBot->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.auto-bot.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </auto-bot-form>

        </div>
    
</div>

@endsection