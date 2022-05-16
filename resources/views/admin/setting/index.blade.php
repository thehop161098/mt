@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))

@section('body')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cấu hình hệ thống
                </div>

                <form method="POST" action="{{ route("admin/settings.store") }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach($defineOptions as $keyOption => $option)
                                    <a class="nav-link {{ $loop->first ? "active" : "" }}" id="nav-{{ $keyOption }}-tab"
                                       data-toggle="tab" href="#nav-{{ $keyOption }}"
                                       role="tab" aria-controls="nav-{{ $keyOption }}"
                                       aria-selected="{{ $loop->first ? "true" : "false" }}">{!! $option['icon'] !!} {{ $option['label'] }}</a>
                                @endforeach
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach($defineOptions as $keyOption => $option)
                                <div class="tab-pane fade {{ $loop->first ? "show active" : "" }}"
                                     id="nav-{{ $keyOption }}"
                                     role="tabpanel"
                                     aria-labelledby="nav-{{ $keyOption }}-tab">

                                    @isset($option['items'])
                                        @foreach ($option['items'] as $items)
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-3 text-capitalize">{{ $callbackSetting->_settingRespository->getAttributeName($items['name']) }}</label>
                                                <div class="col-sm-9">
                                                    @if($items['type'] == 'input')
                                                        <input type="text"
                                                               placeholder="{{ $items['placeholder'] }}"
                                                               class="form-control {{ $items['class'] }}"
                                                               name="Settings[{{ $items['name'] }}]"
                                                               value="{{ $callbackSetting->_settingRespository->getParamViewSetting($items['name']) }}"
                                                               class="form-control"
                                                            {{ $callbackSetting->_settingRespository->getHtmlOption($items['htmlOptions']) }} />
                                                        <p>{{ isset($items['note']) ? $items['note'] : "" }}</p>
                                                    @elseif($items['type'] == 'file')
                                                        <div class="custom-file">
                                                            <input type="file"
                                                                   class="custom-file-input {{ $items['class'] }}"
                                                                   id="file-{{ $items['name'] }}"
                                                                   name="Settings[{{ $items['name'] }}]"/>
                                                            <label class="custom-file-label"
                                                                   for="file-{{ $items['name'] }}">Choose file</label>
                                                        </div>
                                                    @elseif($items['type'] == 'input-prepend')
                                                        <div class="input-group mb-2 mr-sm-2">
                                                            <input type="text"
                                                                   class="form-control {{ $items['class'] }}"
                                                                   placeholder="{{ $items['placeholder'] }}"
                                                                   name="Settings[{{ $items['name'] }}]"
                                                                   value="{{ $callbackSetting->_settingRespository->getParamViewSetting($items['name']) }}"
                                                                {{ $callbackSetting->_settingRespository->getHtmlOption($items['htmlOptions']) }} />
                                                            <div id="js-coins" class="input-group-prepend">
                                                                <div class="input-group-text cursor-pointer">
                                                                    <i class="fa fa-refresh"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($items['type'] == 'radio')
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="{{ $items['name'] }}-yes"
                                                                   name="Settings[{{ $items['name'] }}]"
                                                                   value="{{ Config::get('constants.publish.yes') }}"
                                                                {{ empty($callbackSetting->_settingRespository->getParamViewSetting($items['name'])) || $callbackSetting->_settingRespository->getParamViewSetting($items['name']) == Config::get('constants.publish.yes')  ? "checked" : "" }}
                                                            />
                                                            <label class="form-check-label mb-0"
                                                                   for="{{ $items['name'] }}-yes">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="{{ $items['name'] }}-no"
                                                                   name="Settings[{{ $items['name'] }}]"
                                                                   value="{{ Config::get('constants.publish.no') }}"
                                                                {{ $callbackSetting->_settingRespository->getParamViewSetting($items['name']) == Config::get('constants.publish.no')  ? "checked" : ""  }}
                                                            />
                                                            <label class="form-check-label mb-0"
                                                                   for="{{ $items['name'] }}-no">No</label>
                                                        </div>
                                                    @else
                                                        <textarea class="form-control {{ $items['class'] }}"
                                                                  name="Settings[{{ $items['name'] }}]"
                                                                  placeholder="{{ $items['placeholder'] }}"
                                                            {{ $callbackSetting->_settingRespository->getHtmlOption($items['htmlOptions']) }}>{!! $callbackSetting->_settingRespository->getParamViewSetting($items['name']) !!}</textarea>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset

                                </div>
                            @endforeach
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
