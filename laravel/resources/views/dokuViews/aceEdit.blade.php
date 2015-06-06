@section('content')
@parent
@foreach ($documentContent as $Part)
<div class="box collapsed-box">
    <div  class="box-header">
        <h3>Abschnitt Name: {{$Part['name']}}</h3>
        <div id="{{$Part['id']}}_header">
            <?php echo html_entity_decode($Part['html']); ?>  
        </div>
        <!-- tools box -->
        <div class="pull-right box-tools">
            <button id="{{$Part['id']}}_collapse" data-original-title="Bearbeiten" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title=""><i class="fa fa-pencil"></i></button>
        </div><!-- /. tools -->
    </div><!-- /.box-header -->
    <div style="display: none;" class="box-body pad"> 
        <div class="pull-right box-tools">
            <button data-original-title="Speicheren" onclick="saveDoc('{{$Part['id']}}')" class="btn btn-info btn-sm" data-toggle="tooltip" title=""><i class="fa fa-save"></i></button>
        
        </div><!-- /. tools -->

        <div id="{{$Part['id']}}"  style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$Part['mardown']}}</div>
        <input type="hidden" id="{{$Part['id']}}_name" name="{{$Part['id']}}_name" value="{{$Part['name']}}"> 
        <script>
                    ace.edit("{{$Part['id']}}").setOption("wrap", 80);
                    ace.edit("{{$Part['id']}}").setHighlightActiveLine(true);
                    ace.edit("{{$Part['id']}}").setKeyboardHandler(null);
                    ace.edit("{{$Part['id']}}").setShowInvisibles(true);
                    ace.edit("{{$Part['id']}}").renderer.setShowGutter(true);
                    ace.edit("{{$Part['id']}}").setFadeFoldWidgets(false);
                    ace.edit("{{$Part['id']}}").setShowFoldWidgets(true);
                    ace.edit("{{$Part['id']}}").setReadOnly(false);
                    ace.edit("{{$Part['id']}}").getSession().setMode("ace/mode/markdown");
                    ace.edit("{{$Part['id']}}").setTheme("ace/theme/chrome");        </script>
    </div>				
</div>
@endforeach

<script>
            function saveDoc(id)
            {
                var url = "http://{{$_SERVER['SERVER_NAME']}}:8000/document/save";
                var $post = {};
                $post.docuId = {{$document->id}};
                $post.docuAccess = '{{$docuAccess}}';
                $post.partId = id;
                $post.name = $('input#' + id + '_name').val();
                var editor = ace.edit(id);
                $post.makedown = editor.getSession().getValue();
                $.ajax({
                type: "POST",
                    url: url,
                    data: $post,
                    cache: false,
                    success: function (data) {
                        if (data.overwrite){
                                location.reload();
                        }
                        else{
                        console.log(data);
                        document.getElementById(data.id+'_header').innerHTML= data.html;
                        document.getElementById(data.id+'_collapse').click();
                        }
                    }
                });
                return false;
            }
            function downloadPDF()
            {
                var url = "http://{{$_SERVER['SERVER_NAME']}}:8000/document/downloadPDF";
                var $post = {};
                $post.docuId = {{$document->id}};                  
                $.ajax({
                type: "POST",
                    url: url,
                    data: $post,
                    cache: false,
                    success: function (data) {    
                        window.open("http://{{$_SERVER['SERVER_NAME']}}:8003/"+data,'_blank');                       
                    }
                });
                return false;
            }

    $.ajaxSetup({
    headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
    });
</script>

@endsection