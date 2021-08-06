<!-- Button trigger modal -->
@include('crud::fields.inc.wrapper_start')
<div @include('crud::fields.inc.attributes')>
    @php($multiple=isset($field['multiple'])?$field['multiple']:false)
    @php($imageType =isset($field['image_type'])?$field['image_type']:'featured')
    <?php
    $md=[];
    if(isset($entry)&&count($entry->getMedia($imageType))){
        $md=$entry->getMedia($imageType)->map(function($m){
            return [
                'path' => $m->getUrl(),
                'filename' => $m->filename,
                'id' => $m->id
            ];
        })->toArray();
    }


    ?>
    @if ($imageType=='gallery')
        <gallery-media-library url="{{ route('backpack.upload') }}" key="{{ $field['name'] }}" type="{{$imageType}}" media="{{ json_encode($md) }}"/>
    @else
        <media-library url="{{ route('backpack.upload') }}" key="{{ $field['name'] }}" type="{{$imageType}}" media="{{ json_encode($md) }}"/>
    @endif

</div>
@include('crud::fields.inc.wrapper_end')
<!-- Modal -->
{{--@dd($entry->getMedia('blog')->first()->getUrl())--}}


@push('crud_fields_styles')
    <style>
        .modal-backdrop {
            z-index: -1;
        }
    </style>
@endpush
