<form class="form-edit-add" role="form"
      action="@if(isset($dataTypeContent->id)){{ route('voyager.posts.update', $dataTypeContent->id) }}@else{{ route('voyager.posts.store') }}@endif"
      method="POST" enctype="multipart/form-data">


    <!-- PUT Method if we are editing -->
    @if(isset($dataTypeContent->id))
        {{ method_field("PUT") }}
    @endif
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-9">

            <!-- ### TITLE ### -->
            <div class="panel">
                @include('voyager::partials.form-errors',['errors' => $errors])
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="voyager-character"></i> {{ __('voyager.post.title') }}
                        <span class="panel-desc"> {{ __('voyager.post.title_sub') }}</span>
                    </h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>

                <div class="panel-body">
                    <input type="text" class="form-control" id="title" name="title"
                           placeholder="{{ __('voyager.generic.title') }}"
                           value="{{ $dataTypeContent->title ?? "" }}">
                </div>
            </div>

            <!-- ### CONTENT ### -->
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon wb-book"></i> {{ __('voyager.post.content') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-resize-full" data-toggle="panel-fullscreen"
                           aria-hidden="true"></a>
                    </div>
                </div>
                <textarea class="form-control richTextBox" id="richtextbody" name="body"
                          style="border:0px;">@if(isset($dataTypeContent->body)){{ $dataTypeContent->body }}@endif</textarea>
            </div><!-- .panel -->

            <!-- ### EXCERPT ### -->
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{!! __('voyager.post.excerpt') !!}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">
                    @include('voyager::multilingual.input-hidden', [
                        '_field_name'  => 'excerpt',
                        '_field_trans' => get_field_translations($dataTypeContent, 'excerpt')
                    ])
                    <textarea class="form-control"
                              name="excerpt">@if (isset($dataTypeContent->excerpt)){{ $dataTypeContent->excerpt }}@endif</textarea>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Additional Fields</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">
                    @php
                        $metaFields = $dataTypeContent->postMeta;
                        $exclude = ['_profile'];
                    @endphp
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <!-- ### DETAILS ### -->
            <div class="panel panel panel-bordered panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon wb-clipboard"></i> {{ __('voyager.post.details') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.slug') }}</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               placeholder="slug"
                               {{!! isFieldSlugAutoGenerator($dataType, $dataTypeContent, "slug") !!}}
                               value="@if(isset($dataTypeContent->slug)){{ $dataTypeContent->slug }}@endif">
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.status') }}</label>
                        <select class="form-control" name="status">
                            <option value="PUBLISHED" @if(isset($dataTypeContent->status) && $dataTypeContent->status == 'PUBLISHED'){{ 'selected="selected"' }}@endif>{{ __('voyager.post.status_published') }}</option>
                            <option value="DRAFT" @if(isset($dataTypeContent->status) && $dataTypeContent->status == 'DRAFT'){{ 'selected="selected"' }}@endif>{{ __('voyager.post.status_draft') }}</option>
                            <option value="PENDING" @if(isset($dataTypeContent->status) && $dataTypeContent->status == 'PENDING'){{ 'selected="selected"' }}@endif>{{ __('voyager.post.status_pending') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.category') }}</label>
                        <select class="form-control" name="category_id">
                            @foreach(TCG\Voyager\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" @if(isset($dataTypeContent->category_id) && $dataTypeContent->category_id == $category->id){{ 'selected="selected"' }}@endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="{{$dataTypeContent->sort_order ?? 0}}">
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('voyager.generic.featured') }}</label>
                        <input type="checkbox"
                               name="featured" @if(isset($dataTypeContent->featured) && $dataTypeContent->featured){{ 'checked="checked"' }}@endif>
                    </div>

                </div>
            </div>

            <!-- ### PROFILE ### -->
            <div class="panel panel-bordered panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon wb-clipboard"></i> Profile</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">
                    @php
                        //meta
                        $profile = $dataTypeContent->postMeta->where('meta_key','_profile')->first();
                        if($profile) {
                            //find in the media manager
                            $media = \App\Models\MediaManager::slug($profile->meta_value)->first();
                            $options = (object)['thumbnail' => $media->thumbnail_path];
                        }
                        else {
                            $options = null;
                        }
                    @endphp
                    <input class="js-input-profile" type="text" name="post_meta[{{$profile->meta_key ?? "_profile"}}]"
                           id="post_meta[{{$profile->meta_key ?? "_profile"}}]" value="{{$profile->meta_value ?? ""}}"/>
                    @include("voyager::partials.image-profile",['options' => $options])
                </div>
                <div class="panel-footer">
                    <a href="#" id="meta_media_browser" class="btn btn-default">Open Media</a>
                </div>
            </div>

            <!-- ## GALLERY ## -->
            <div class="panel panel-bordered panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon wb-clipboard"></i> Galleries</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">

                    @php
                        //meta
                        $profile = $dataTypeContent->postMeta->where('meta_key','_galleries')->first();
                        if($profile) {
                            $values = (!is_null($profile->meta_value) || !empty($profile->meta_value)) ? json_decode($profile->meta_value) : [];

                            //extract all meta
                            $vals = [];
                            if(count($values ) > 0) {
                                foreach ($values as $value) {
                                    $vals[] = $value->meta;
                                }
                            }

                            //find in the path in media manager
                            $medias = \App\Models\MediaManager::slugIn($vals)->get();

                            $options = [];
                            foreach($medias as $media) {
                                $option = (object)['thumbnail' => $media->thumbnail_path];
                                array_push($options,$option);
                            }
                        }
                        else {
                            $options = null;
                        }

                    @endphp
                    <input class="js-input-galleries" type="text"
                           name="post_meta[{{$profile->meta_key ?? "_galleries"}}]"
                           id="post_meta[{{$profile->meta_key ?? "_galleries"}}]"
                           value="{{$profile->meta_value ?? ""}}"/>

                    @include("voyager::partials.image-galleries",["options" => $options])

                </div>
                <div class="panel-footer">
                    <a href="#" id="meta_galleries_browser" class="btn btn-default">Open Media</a>
                </div>
            </div>

            <!-- ### SEO CONTENT ### -->
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon wb-search"></i> {{ __('voyager.post.seo_content') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.meta_description') }}</label>
                        @include('voyager::multilingual.input-hidden', [
                            '_field_name'  => 'meta_description',
                            '_field_trans' => get_field_translations($dataTypeContent, 'meta_description')
                        ])
                        <textarea class="form-control"
                                  name="meta_description">@if(isset($dataTypeContent->meta_description)){{ $dataTypeContent->meta_description }}@endif</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.meta_keywords') }}</label>
                        @include('voyager::multilingual.input-hidden', [
                            '_field_name'  => 'meta_keywords',
                            '_field_trans' => get_field_translations($dataTypeContent, 'meta_keywords')
                        ])
                        <textarea class="form-control"
                                  name="meta_keywords">@if(isset($dataTypeContent->meta_keywords)){{ $dataTypeContent->meta_keywords }}@endif</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('voyager.post.seo_title') }}</label>
                        @include('voyager::multilingual.input-hidden', [
                            '_field_name'  => 'seo_title',
                            '_field_trans' => get_field_translations($dataTypeContent, 'seo_title')
                        ])
                        <input type="text" class="form-control" name="seo_title" placeholder="SEO Title"
                               value="@if(isset($dataTypeContent->seo_title)){{ $dataTypeContent->seo_title }}@endif">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right">
                @if(isset($dataTypeContent->id)){{ __('voyager.post.update') }}@else <i
                        class="icon wb-plus-circle"></i> {{ __('voyager.post.new') }} @endif
            </button>
        </div>
    </div>
</form>