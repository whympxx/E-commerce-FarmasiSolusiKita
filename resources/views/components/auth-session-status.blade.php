@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small py-2']) }}>
        {{ $status }}
    </div>
@endif
