@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')

@section('content')
@foreach ($Dokument as $Part)
<div class="box collapsed-box">
    <div class="box-header">
        <?php echo html_entity_decode($Part['html']); ?>       
        <!-- tools box -->
        <div class="pull-right box-tools">
            <button data-original-title="Collapse" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title=""><i class="fa fa-pencil"></i></button>
        </div><!-- /. tools -->
    </div><!-- /.box-header -->
    <div style="display: none;" class="box-body pad">      
        <div id="{{$Part['id']}}"  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$Part['mardown']}}</div>
        <script>
            ace.edit("{{$Part['id']}}").setOption("wrap", 80);
            ace.edit("{{$Part['id']}}").setHighlightActiveLine(true);
            ace.edit("{{$Part['id']}}").setKeyboardHandler(null);
            ace.edit("{{$Part['id']}}").setShowInvisibles(true);
            ace.edit("{{$Part['id']}}").renderer.setShowGutter(true);
            ace.edit("{{$Part['id']}}").setFadeFoldWidgets(false);
            ace.edit("{{$Part['id']}}").setShowFoldWidgets(false);
            ace.edit("{{$Part['id']}}").setReadOnly(false);
            ace.edit("{{$Part['id']}}").getSession().setMode("{{asset('/js/ace/mode/markdown')}}");
        </script>
    </div>				
</div>
@endforeach
@endsection