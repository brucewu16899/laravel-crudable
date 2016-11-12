<h2>{{ $title }}</h2>

<form action="{{ $form_url }}" method="POST"{!! $multipart !!}>
    {!! $method_field !!}
    {!! $csrf_field !!}
    @foreach($entry->fields() as $field)
        {!! $field->form() !!}
    @endforeach
    <hr>
    <a href="{{ $back_url }}" class="btn btn-default">@lang('crud::buttons.back')</a>
    <button type="submit" class="btn btn-primary">@lang('crud::buttons.save')</button>
</form>
@include('crud::fields.assets', compact($crud_js, $crud_css))