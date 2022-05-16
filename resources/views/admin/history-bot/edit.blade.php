@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.history-bot.actions.edit', ['name' => $historyBot->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <history-bot-form
                :action="'{{ $historyBot->resource_url }}'"
                :data="{{ $historyBot->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.history-bot.actions.edit', ['name' => $historyBot->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.history-bot.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </history-bot-form>

        </div>
    
</div>

@endsection